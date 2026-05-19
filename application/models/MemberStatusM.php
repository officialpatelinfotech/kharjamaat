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
            ->select('ITS_ID, HOF_ID, in_its_csv')
            ->from('user')
            ->where('ITS_ID', $its_id)
            ->get()->row_array();

        if (empty($row)) return false;

        $hof_id = (int)($row['HOF_ID'] ?: $its_id);
        $sabeelYear    = $this->_current_sabeel_year();
        $sabeelHofIds  = $this->_hof_ids_with_sabeel($sabeelYear);

        $its_exists    = (bool)$row['in_its_csv'];
        $sabeel_exists = isset($sabeelHofIds[$hof_id]);

        if ($its_exists && $sabeel_exists)   $match = self::MATCH_BOTH_KHAR;
        elseif ($its_exists)                 $match = self::MATCH_ITS_KHAR;
        elseif ($sabeel_exists)              $match = self::MATCH_SABEEL_KHAR;
        else                                 $match = self::MATCH_BOTH_NOT_KHAR;

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
    public function recalculate_all(array $importedIds = []): array
    {
        $updated = 0;
        $errors  = 0;

        // If importedIds is provided, update the in_its_csv column in user table first!
        if (!empty($importedIds)) {
            $cleanIds = array_values(array_filter(array_map('trim', $importedIds), function ($v) {
                return $v !== '';
            }));
            if (!empty($cleanIds)) {
                // Set in_its_csv = 1 for imported IDs
                $this->db->where_in('ITS_ID', $cleanIds);
                $this->db->update('user', ['in_its_csv' => 1]);

                // Set in_its_csv = 0 for IDs not in imported list
                $this->db->where_not_in('ITS_ID', $cleanIds);
                $this->db->update('user', ['in_its_csv' => 0]);
            }
        }

        // Pre-build a set of HOF_IDs that have Sabeel (family-level check)
        $sabeelYear    = $this->_current_sabeel_year();
        $sabeelHofIds  = $this->_hof_ids_with_sabeel($sabeelYear);

        // Fetch all members with their HOF_ID and in_its_csv status
        $rows = $this->db
            ->select('ITS_ID, HOF_ID, in_its_csv')
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

            // ITS presence is determined by whether they are in the ITS CSV
            $its_exists    = (bool)$row['in_its_csv'];
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
     * Auto-computes activity_status = 'inactive' if a triggering deeni/health/residential
     * status is set. The admin-submitted activity_status is used ONLY when no auto-trigger fires.
     * Does NOT touch its_sabeel_match or Member_Type.
     */
    public function update_living_status(int $its_id, array $fields): bool
    {
        $allowed = ['deeni_status', 'residential_status', 'health_status', 'activity_status'];
        $data = [];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $fields)) {
                $val = $fields[$col];
                // Treat null or empty string as NULL (clear the field)
                $data[$col] = ($val === null || trim((string)$val) === '') ? null : trim((string)$val);
            }
        }
        if (empty($data)) return false;

        // Auto-inactive engine: check if any triggering status should force Inactive
        $deeni       = (string)($data['deeni_status']       ?? '');
        $health      = (string)($data['health_status']      ?? '');
        $residential = (string)($data['residential_status'] ?? '');

        $autoInactive = self::compute_auto_inactive($deeni, $health, $residential);
        if ($autoInactive !== null) {
            // Force inactive regardless of what the admin submitted
            $data['activity_status'] = $autoInactive;
        }
        // If no auto-trigger: the admin-provided activity_status (or null) is used as-is.

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

        // Fetch all members of this family to check their individual in_its_csv status
        $members = $this->db
            ->select('ITS_ID, in_its_csv')
            ->from('user')
            ->where('HOF_ID', $hof_id)
            ->or_where('ITS_ID', $hof_id)
            ->get()->result_array();

        $success = true;
        foreach ($members as $m) {
            $its_id = (int)$m['ITS_ID'];
            $its_exists = (bool)$m['in_its_csv'];

            if ($its_exists && $sabeel_exists)   $match = self::MATCH_BOTH_KHAR;
            elseif ($its_exists)                 $match = self::MATCH_ITS_KHAR;
            elseif ($sabeel_exists)              $match = self::MATCH_SABEEL_KHAR;
            else                                 $match = self::MATCH_BOTH_NOT_KHAR;

            $ok = $this->db->update('user', [
                'its_sabeel_match' => $match,
            ], ['ITS_ID' => $its_id]);

            if (!$ok) $success = false;
        }

        return $success;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Static option lists (used by views to build dropdowns)
    // ─────────────────────────────────────────────────────────────────────────

    public static function deeni_status_options(): array
    {
        return [
            ''                                                                 => '— None —',
            'Normal'                                                           => 'Normal',
            'Deen Badli Lidu che'                                              => 'Deen Badli Lidu che',
            'Married Outside'                                                  => 'Married Outside',
            'Misaq Not Given'                                                  => 'Not given Misaq to Syedna Mufaddal Saifuddin AQA tus after Takht Nashini',
            'Mustajeeb'                                                        => 'Mustajeeb',
            'No Ashara / LQ'                                                   => 'No Ashara / LQ attended for past 3 years',
            'No Vajebaat / Sabeel'                                             => 'Not paid Sila Fitra / Vajeebaat / Sabeel for at least 3 years',
            'Zero Days Scanned in Ashara Mubaraka'                             => 'Zero Days Scanned in Ashara Mubaraka',
        ];
    }

    public static function residential_status_options(): array
    {
        return [
            ''                                            => '— None —',
            'Residing in Local Jamaat'                    => 'Residing in Local Jamaat',
            'Moved for Job'                               => 'Moved for Job',
            'Moved for Studies'                           => 'Moved for Studies',
            'Moved after Marriage'                        => 'Moved after Marriage',
            'Permanently moved but ITS not Transferred'   => 'Permanently moved but ITS not Transferred',
            'Permanently Moved and ITS also Transferred'  => 'Permanently Moved and ITS also Transferred',
            'Unknown or Not Traceable'                    => 'Unknown or Not Traceable',
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
            'Elderly / Needs Care' => 'Elderly / Needs Care',
            'Wafaat'               => 'Wafaat',
        ];
    }

    public static function activity_status_options(): array
    {
        return [
            ''          => '— None —',
            'active'    => 'Active',
            'inactive'  => 'Inactive',
        ];
    }

    public static function match_status_label(string $val): string
    {
        $map = [
            self::MATCH_BOTH_KHAR     => 'ITS & Sabeel both in Khar',
            self::MATCH_ITS_KHAR      => 'ITS in Khar, Sabeel not in Khar',
            self::MATCH_SABEEL_KHAR   => 'Sabeel in Khar, ITS not in Khar',
            self::MATCH_BOTH_NOT_KHAR => 'Sabeel & ITS both not in Khar',
        ];
        return $map[$val] ?? '—';
    }

    /**
     * Compute whether a member should be auto-marked Inactive based on their
     * deeni_status, health_status, and residential_status.
     *
     * Returns 'inactive' if any triggering status is set, null otherwise
     * (null = do not override manually-set active/temporary status).
     */
    public static function compute_auto_inactive(string $deeni = '', string $health = '', string $residential = ''): ?string
    {
        // Deeni statuses that trigger Inactive
        $inactiveDeeni = [
            'Deen Badli Lidu che',
            'Married Outside',
            'Misaq Not Given',
            'Mustajeeb',
        ];
        // Health statuses that trigger Inactive
        $inactiveHealth = [
            'Lazimul Firash',
            'Wafaat',
        ];
        // Residential statuses that trigger Inactive
        $inactiveResidential = [
            'Permanently moved but ITS not Transferred',
            'Permanently Moved and ITS also Transferred',
            'Unknown or Not Traceable',
        ];

        if (in_array($deeni, $inactiveDeeni, true)) return self::ACTIVITY_INACTIVE;
        if (in_array($health, $inactiveHealth, true)) return self::ACTIVITY_INACTIVE;
        if (in_array($residential, $inactiveResidential, true)) return self::ACTIVITY_INACTIVE;

        return null; // No auto-inactive trigger
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
