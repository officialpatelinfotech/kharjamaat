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
        $CI =& get_instance();
        if (!$CI->db->table_exists('status_options')) {
            return [
                ''                                                                 => '— None —',
                'Normal'                                                           => 'Normal (Active)',
                'Deen Badli Lidu che'                                              => 'Deen Badli Lidu che (Inactive)',
                'Married Outside'                                                  => 'Married Outside (Inactive)',
                'Misaq Not Given'                                                  => 'Not given Misaq to Syedna Mufaddal Saifuddin AQA tus after Takht Nashini (Inactive)',
                'Mustajeeb'                                                        => 'Mustajeeb (Inactive)',
                'No Ashara / LQ'                                                   => 'No Ashara / LQ attended for past 3 years (Inactive)',
                'No Vajebaat / Sabeel'                                             => 'Not paid Sila Fitra / Vajeebaat / Sabeel for last 3 years (Inactive)',
                'Zero Days Scanned in Ashara Mubaraka'                             => 'Zero Days Scanned in Ashara Mubaraka (Inactive)',
            ];
        }
        $rows = $CI->db->where('type', 'deeni')->order_by('id', 'ASC')->get('status_options')->result_array();
        $options = ['' => '— None —'];
        foreach ($rows as $row) {
            $options[$row['status_key']] = $row['status_label'];
        }
        return $options;
    }

    public static function residential_status_options(): array
    {
        $CI =& get_instance();
        if (!$CI->db->table_exists('status_options')) {
            return [
                ''                                            => '— None —',
                'Residing in Khar'                            => 'Residing in Khar (Active)',
                'Madresa in Khar'                             => 'Madresa in Khar (Active)',
                'FMB Thaali in Khar'                          => 'FMB Thaali in Khar (Active)',
                'Moved for Job'                               => 'Moved for Job (Inactive)',
                'Moved for Studies'                           => 'Moved for Studies (Inactive)',
                'Moved after Marriage'                        => 'Permanently moved after Marriage (Inactive)',
                'Permanently Migrated'                        => 'Permanently Migrated (Inactive)',
                'Unknown or Not Traceable'                    => 'Unknown or Not Traceable (Inactive)',
            ];
        }
        $rows = $CI->db->where('type', 'residential')->order_by('id', 'ASC')->get('status_options')->result_array();
        $options = ['' => '— None —'];
        foreach ($rows as $row) {
            $options[$row['status_key']] = $row['status_label'];
        }
        return $options;
    }

    public static function health_status_options(): array
    {
        $CI =& get_instance();
        if (!$CI->db->table_exists('status_options')) {
            return [
                ''                     => '— None —',
                'Healthy'              => 'Fit & Healthy (Active)',
                'Medically Unfit'      => 'Handicapped Medically Unfit (Active)',
                'Hospitalised'         => 'Major Disease Patient (Active)',
                'Lazimul Firash'       => 'Lazimul Firash / Bedridden (Active)',
                'Wafaat'               => 'Wafaat (Inactive)',
            ];
        }
        $rows = $CI->db->where('type', 'health')->order_by('id', 'ASC')->get('status_options')->result_array();
        $options = ['' => '— None —'];
        foreach ($rows as $row) {
            $options[$row['status_key']] = $row['status_label'];
        }
        return $options;
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
     * Returns 'inactive' if any triggering status is set, null otherwise.
     */
    public static function compute_auto_inactive(string $deeni = '', string $health = '', string $residential = ''): ?string
    {
        $CI =& get_instance();
        if (!$CI->db->table_exists('status_options')) {
            $inactiveDeeni = [
                'Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb',
                'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka',
            ];
            $inactiveHealth = ['Wafaat'];
            $inactiveResidential = [
                'Moved for Job', 'Moved for Studies', 'Moved after Marriage',
                'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred',
                'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable',
            ];

            if (in_array($deeni, $inactiveDeeni, true)) return self::ACTIVITY_INACTIVE;
            if (in_array($health, $inactiveHealth, true)) return self::ACTIVITY_INACTIVE;
            if (in_array($residential, $inactiveResidential, true)) return self::ACTIVITY_INACTIVE;
            return null;
        }

        if ($deeni !== '') {
            $check = $CI->db->where([
                'type' => 'deeni',
                'status_key' => $deeni,
                'is_inactive_trigger' => 1
            ])->get('status_options')->num_rows();
            if ($check > 0) return self::ACTIVITY_INACTIVE;
        }
        if ($health !== '') {
            $check = $CI->db->where([
                'type' => 'health',
                'status_key' => $health,
                'is_inactive_trigger' => 1
            ])->get('status_options')->num_rows();
            if ($check > 0) return self::ACTIVITY_INACTIVE;
        }
        if ($residential !== '') {
            $check = $CI->db->where([
                'type' => 'residential',
                'status_key' => $residential,
                'is_inactive_trigger' => 1
            ])->get('status_options')->num_rows();
            if ($check > 0) return self::ACTIVITY_INACTIVE;
        }

        return null;
    }

    /**
     * Check if a status option triggers the inactive state.
     */
    public static function is_inactive_trigger(string $type, ?string $key): bool
    {
        if (empty($key)) return false;
        $CI =& get_instance();
        if (!$CI->db->table_exists('status_options')) {
            $inactiveDeeni = [
                'Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb',
                'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka'
            ];
            $inactiveHealth = ['Wafaat'];
            $inactiveResidential = [
                'Moved for Job', 'Moved for Studies', 'Moved after Marriage',
                'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred',
                'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable'
            ];
            if ($type === 'deeni') return in_array($key, $inactiveDeeni, true);
            if ($type === 'health') return in_array($key, $inactiveHealth, true);
            if ($type === 'residential') return in_array($key, $inactiveResidential, true);
            return false;
        }
        $row = $CI->db->where([
            'type' => $type,
            'status_key' => $key,
            'is_inactive_trigger' => 1
        ])->get('status_options')->row_array();
        return !empty($row);
    }

    /**
     * Get list of keys that trigger inactive status for a given type.
     */
    public static function get_inactive_trigger_keys(string $type): array
    {
        $CI =& get_instance();
        if (!$CI->db->table_exists('status_options')) {
            $inactiveDeeni = [
                'Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb',
                'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka'
            ];
            $inactiveHealth = ['Wafaat'];
            $inactiveResidential = [
                'Moved for Job', 'Moved for Studies', 'Moved after Marriage',
                'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred',
                'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable'
            ];
            if ($type === 'deeni') return $inactiveDeeni;
            if ($type === 'health') return $inactiveHealth;
            if ($type === 'residential') return $inactiveResidential;
            return [];
        }
        $rows = $CI->db->select('status_key')
            ->where(['type' => $type, 'is_inactive_trigger' => 1])
            ->get('status_options')->result_array();
        return array_column($rows, 'status_key');
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
