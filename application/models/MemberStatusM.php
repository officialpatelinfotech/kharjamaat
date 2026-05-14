<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MemberStatusM
 *
 * Auto-calculated fields (run on CSV import or manual trigger):
 *   - its_sabeel_match  — family-level: if ANY member of the HOF family has
 *                         a Sabeel record, the whole family is "Sabeel in Khar"
 *   - Member_Type       — derived from its_sabeel_match
 *
 * Manual fields (saved via update_living_status / updatemember):
 *   - deeni_status, residential_status, health_status, activity_status
 *   (activity_status is fully admin-controlled; no auto-trigger)
 */
class MemberStatusM extends CI_Model
{
    // ── ITS–Sabeel match enum values ──────────────────────────────────────────
    const MATCH_BOTH_KHAR       = 'its_sabeel_both_khar';
    const MATCH_ITS_KHAR        = 'its_khar_sabeel_out';
    const MATCH_SABEEL_KHAR     = 'sabeel_khar_its_out';
    const MATCH_BOTH_NOT_KHAR   = 'both_not_khar';

    // ── Member Type values ────────────────────────────────────────────────────
    const TYPE_PERMANENT  = 'Permanent';
    const TYPE_TEMPORARY  = 'Temporary';

    // ── Activity Status values ────────────────────────────────────────────────
    const ACTIVITY_ACTIVE    = 'active';
    const ACTIVITY_INACTIVE  = 'inactive';
    const ACTIVITY_TEMPORARY = 'temporary';

    // ─────────────────────────────────────────────────────────────────────────
    // Public API
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Recalculate ITS–Sabeel match and Member_Type for ONE member by ITS_ID.
     * activity_status is NOT touched — it is manual.
     */
    public function recalculate_one(int $its_id): bool
    {
        $row = $this->db
            ->select('ITS_ID, HOF_ID')
            ->from('user')
            ->where('ITS_ID', $its_id)
            ->get()->row_array();

        if (empty($row)) return false;

        $match = $this->_compute_match_for_family((int)($row['HOF_ID'] ?: $its_id));

        return (bool) $this->db->update('user', [
            'its_sabeel_match' => $match,
        ], ['ITS_ID' => $its_id]);
    }

    /**
     * Recalculate ITS–Sabeel match and Member_Type for ALL members.
     * Called after every ITS CSV import.
     *
     * Family logic: Sabeel is checked at the HOF level. If the HOF (or any
     * member sharing the same HOF_ID) has a Sabeel record for the current year,
     * ALL members of that family are treated as "Sabeel in Khar".
     *
     * Returns ['updated' => N, 'errors' => N, 'match_distribution' => [...]]
     */
    public function recalculate_all(): array
    {
        $updated = 0;
        $errors  = 0;

        // Pre-build a set of HOF_IDs that have Sabeel (family-level check)
        $sabeelYear    = $this->_current_sabeel_year();
        $sabeelHofIds  = $this->_hof_ids_with_sabeel($sabeelYear);

        // Fetch all members with their HOF_ID
        $rows = $this->db
            ->select('ITS_ID, HOF_ID')
            ->from('user')
            ->get()->result_array();

        $distribution = [
            self::MATCH_BOTH_KHAR     => 0,
            self::MATCH_ITS_KHAR      => 0,
            self::MATCH_SABEEL_KHAR   => 0,
            self::MATCH_BOTH_NOT_KHAR => 0,
        ];

        foreach ($rows as $row) {
            $its_id  = (int)$row['ITS_ID'];
            $hof_id  = (int)($row['HOF_ID'] ?: $its_id);

            // ITS always present (row exists in user table)
            $its_exists    = true;
            $sabeel_exists = isset($sabeelHofIds[$hof_id]);

            if ($its_exists && $sabeel_exists)   $match = self::MATCH_BOTH_KHAR;
            elseif ($its_exists)                 $match = self::MATCH_ITS_KHAR;
            elseif ($sabeel_exists)              $match = self::MATCH_SABEEL_KHAR;
            else                                 $match = self::MATCH_BOTH_NOT_KHAR;

            $distribution[$match]++;

            $ok = $this->db->update('user', [
                'its_sabeel_match' => $match,
            ], ['ITS_ID' => $its_id]);

            $ok ? $updated++ : $errors++;
        }

        return [
            'updated'            => $updated,
            'errors'             => $errors,
            'match_distribution' => $distribution,
        ];
    }

    /**
     * Save manual living-status fields for one member.
     * activity_status is included here as it is fully manual.
     * Does NOT touch its_sabeel_match or Member_Type.
     */
    public function update_living_status(int $its_id, array $fields): bool
    {
        $allowed = ['deeni_status', 'residential_status', 'health_status', 'activity_status', 'Member_Type'];
        $data = [];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $fields)) {
                $val = $fields[$col];
                // Treat null or empty string as NULL (clear the field)
                $data[$col] = ($val === null || trim((string)$val) === '') ? null : trim((string)$val);
            }
        }
        if (empty($data)) return false;
        return (bool) $this->db->update('user', $data, ['ITS_ID' => $its_id]);
    }

    /**
     * Recalculate ITS–Sabeel match for a specific HOF family.
     * Called when a Sabeel record is added/removed for a member.
     */
    public function recalculate_family(int $hof_id): bool
    {
        $sabeelYear   = $this->_current_sabeel_year();
        $sabeelHofIds = $this->_hof_ids_with_sabeel($sabeelYear);
        $sabeel_exists = isset($sabeelHofIds[$hof_id]);
        $match         = $sabeel_exists ? self::MATCH_BOTH_KHAR : self::MATCH_ITS_KHAR;

        // Update all members of this family
        return (bool) $this->db->update('user', [
            'its_sabeel_match' => $match,
        ], ['HOF_ID' => $hof_id]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Static option lists (used by views to build dropdowns)
    // ─────────────────────────────────────────────────────────────────────────

    public static function deeni_status_options(): array
    {
        return [
            ''                                     => '— None —',
            'Address / Contact not traceable'      => 'Address / Contact not traceable',
            'Ashura Attended but Not Scanned'      => 'Ashura Attended but Not Scanned',
            'Deen Badli Lidu che'                  => 'Deen Badli Lidu che',
            'Lazimul Firash / Medically unfit'     => 'Lazimul Firash / Medically unfit',
            'Married Outside'                      => 'Married Outside',
            'Misaq Not Given'                      => 'Misaq Not Given — Not given Misaq to Syedna Mufaddal Saifuddin Aqa tus after Takht Nashini',
            'Moved but not taken transfer'         => 'Moved but not taken transfer',
            'Mustajeeb'                            => 'Mustajeeb',
            'No Ashara / LQ'                       => 'No Ashara / LQ — Paying Vajebaat Sabeel but not attending Ashara Mubaraka and Lailatul Qadr',
            'No Vajebaat / Sabeel'                 => 'No Vajebaat / Sabeel — Have not paid Sila Fitra / Vajebaat Sabeel for at least 3 years',
            'Unapproachable / Prefers not to meet' => 'Unapproachable / Prefers not to meet',
            'Wafaat'                               => 'Wafaat',
            'Zero Days Scanned in Ashara Mubaraka' => 'Zero Days Scanned in Ashara Mubaraka',
        ];
    }

    public static function residential_status_options(): array
    {
        return [
            ''                             => '— None —',
            'Residing in Khar'             => 'Residing in Khar',
            'Moved for Job'                => 'Moved for Job',
            'Moved for Studies'            => 'Moved for Studies',
            'Moved but not taken transfer' => 'Moved but not taken transfer',
            'Shifted Permanently'          => 'Shifted Permanently',
            'Abroad'                       => 'Abroad',
            'Unknown'                      => 'Unknown / Not traceable',
        ];
    }

    public static function health_status_options(): array
    {
        return [
            ''                     => '— None —',
            'Healthy'              => 'Healthy',
            'Lazimul Firash'       => 'Lazimul Firash / Bedridden',
            'Medically Unfit'      => 'Medically Unfit',
            'Hospitalised'         => 'Hospitalised',
            'Elderly / Needs care' => 'Elderly / Needs care',
        ];
    }

    public static function activity_status_options(): array
    {
        return [
            ''          => '— None —',
            'active'    => 'Active',
            'inactive'  => 'Inactive',
            'temporary' => 'Temporary',
        ];
    }

    public static function match_status_label(string $val): string
    {
        $map = [
            self::MATCH_BOTH_KHAR     => 'ITS & Sabeel both in Khar',
            self::MATCH_ITS_KHAR      => 'ITS in Khar, Sabeel outside Khar',
            self::MATCH_SABEEL_KHAR   => 'Sabeel in Khar, ITS outside Khar',
            self::MATCH_BOTH_NOT_KHAR => 'ITS & Sabeel both not in Khar',
        ];
        return $map[$val] ?? '—';
    }

    public static function match_status_badge_class(string $val): string
    {
        $map = [
            self::MATCH_BOTH_KHAR     => 'success',
            self::MATCH_ITS_KHAR      => 'warning',
            self::MATCH_SABEEL_KHAR   => 'info',
            self::MATCH_BOTH_NOT_KHAR => 'secondary',
        ];
        return $map[$val] ?? 'light';
    }

    public static function activity_badge_class(string $val): string
    {
        $map = [
            'active'    => 'success',
            'inactive'  => 'danger',
            'temporary' => 'warning',
        ];
        return $map[$val] ?? 'secondary';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Compute ITS–Sabeel match for a single HOF family.
     * Used by recalculate_one() and recalculate_family().
     */
    private function _compute_match_for_family(int $hof_id): string
    {
        $year          = $this->_current_sabeel_year();
        $sabeelHofIds  = $this->_hof_ids_with_sabeel($year);
        $sabeel_exists = isset($sabeelHofIds[$hof_id]);

        // ITS presence: member exists in user table (always true when called)
        return $sabeel_exists ? self::MATCH_BOTH_KHAR : self::MATCH_ITS_KHAR;
    }

    /**
     * Returns a hash-map of HOF_IDs that have at least one Sabeel record
     * for the given year. Keys are HOF_IDs (as ints).
     *
     * Sabeel is checked against the HOF_ID column of the user table joined
     * to sabeel_takhmeen. If user_id in sabeel_takhmeen matches any ITS_ID
     * in a family, that family's HOF_ID is included.
     */
    private function _hof_ids_with_sabeel(string $year): array
    {
        // Get all ITS_IDs that have a Sabeel record for the year
        $sabeelRows = $this->db->query(
            "SELECT DISTINCT user_id FROM sabeel_takhmeen WHERE year = ?",
            [$year]
        )->result_array();

        if (empty($sabeelRows)) return [];

        $sabeelIts = array_column($sabeelRows, 'user_id');

        // Map each of those ITS_IDs to their HOF_ID (or themselves if HOF)
        $placeholders = implode(',', array_fill(0, count($sabeelIts), '?'));
        $userRows = $this->db->query(
            "SELECT ITS_ID, HOF_ID FROM `user` WHERE ITS_ID IN ({$placeholders})",
            $sabeelIts
        )->result_array();

        $hofIds = [];
        foreach ($userRows as $r) {
            $hof = (int)($r['HOF_ID'] ?: $r['ITS_ID']);
            $hofIds[$hof] = true;
        }
        return $hofIds;
    }

    /**
     * Returns the current Sabeel year (highest year in sabeel_takhmeen).
     */
    private function _current_sabeel_year(): string
    {
        static $cached = null;
        if ($cached !== null) return $cached;

        $row = $this->db->query(
            "SELECT MAX(year) AS yr FROM sabeel_takhmeen"
        )->row_array();

        $cached = (!empty($row['yr'])) ? (string)$row['yr'] : (string)date('Y');
        return $cached;
    }
}
