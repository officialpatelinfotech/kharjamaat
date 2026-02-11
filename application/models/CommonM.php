<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class CommonM extends CI_Model
{

  public function get_sabeel_user_details($year = null, $grade = null, $name_filter = null, $amount_zero = null)
  {
    // Returns per-user summary: user_id, full_name, total_takhmeen, total_paid, outstanding
    $params = [];
    $year_filter_clause = '';
    $year_filter_clause_join = '';
    if (!empty($year)) {
      // compute takhmeen_year format used in st.year (e.g. 1446-47)
      $takhmeen_year = $year . '-' . substr($year + 1, -2);
      $year_filter_clause = ' WHERE st.year = ? ';
      $year_filter_clause_join = ' AND st.year = ? ';
      // Will bind twice: once in base UNION, once in main JOIN
      $params[] = $takhmeen_year;
      $params[] = $takhmeen_year;
    }

    // Build base set of user_ids from both sources; do not include names here to avoid UNION duplicates
    $base_users_sql = "SELECT u.ITS_ID AS user_id
      FROM user u
      WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.Sector IS NOT NULL";

    $base_st_sql = "SELECT DISTINCT st.user_id AS user_id
      FROM sabeel_takhmeen st" . $year_filter_clause;

    $sql = "SELECT
        b.user_id,
        MAX(COALESCE(u.Full_Name, CAST(b.user_id AS CHAR))) AS full_name,
        COALESCE(SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)), 0) AS total_takhmeen,
        COALESCE(paid.total_paid, 0) AS total_paid,
        COALESCE(SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)), 0) - COALESCE(paid.total_paid, 0) AS outstanding
      FROM (
        SELECT DISTINCT user_id FROM ( $base_users_sql UNION $base_st_sql ) s
      ) b
      LEFT JOIN user u ON u.ITS_ID = b.user_id
      LEFT JOIN sabeel_takhmeen st ON st.user_id = b.user_id" . $year_filter_clause_join . "
      LEFT JOIN sabeel_takhmeen_grade est ON est.id = st.establishment_grade
      LEFT JOIN sabeel_takhmeen_grade res ON res.id = st.residential_grade
      LEFT JOIN (
        SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments GROUP BY user_id
      ) paid ON paid.user_id = b.user_id
      GROUP BY b.user_id
    ";

    // Optional HAVING filters
    $having = [];
    if (!empty($name_filter)) {
      $having[] = "LOWER(MAX(COALESCE(u.Full_Name, CAST(b.user_id AS CHAR)))) LIKE ?";
      $params[] = '%' . strtolower(trim($name_filter)) . '%';
    }
    if (!empty($amount_zero)) {
      // total_takhmeen alias equals 0
      $having[] = "COALESCE(SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)), 0) = 0";
    }
    if (!empty($having)) {
      $sql .= ' HAVING ' . implode(' AND ', $having) . ' ';
    }

    $sql .= "
      ORDER BY full_name ASC";

    $rows = $this->db->query($sql, $params)->result_array();

    // If a specific hijri start year is requested, compute per-user paid/due
    // for that year by allocating payments FIFO to oldest years first.
    if (!empty($year) && !empty($rows)) {
      $takhmeen_year = $year . '-' . substr($year + 1, -2);
      // Build user id list for querying per-user year totals and payments
      $user_ids = array_map(function ($r) {
        return $r['user_id'];
      }, $rows);
      if (!empty($user_ids)) {
        $in_ids = implode(',', array_map(function ($id) {
          return $this->db->escape($id);
        }, $user_ids));

        // Per-user per-year takhmeen totals (only for these users)
        $per_user_years = $this->db->query(
          "SELECT st.user_id, st.year, SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)) AS total, CAST(SUBSTRING_INDEX(st.year, '-', 1) AS UNSIGNED) AS yr_start
             FROM sabeel_takhmeen st
             LEFT JOIN sabeel_takhmeen_grade est ON est.id = st.establishment_grade
             LEFT JOIN sabeel_takhmeen_grade res ON res.id = st.residential_grade
            WHERE st.user_id IN ($in_ids)
            GROUP BY st.user_id, st.year
            ORDER BY st.user_id ASC, yr_start ASC"
        )->result_array();

        // Total paid per user (all years) but limited to these users
        $paid_by_user = $this->db->query(
          "SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments WHERE user_id IN ($in_ids) GROUP BY user_id"
        )->result_array();

        $paid_map = [];
        foreach ($paid_by_user as $r) {
          $paid_map[$r['user_id']] = (float) ($r['total_paid'] ?? 0);
        }

        // Build map user => years[]
        $user_years = [];
        foreach ($per_user_years as $r) {
          $uid = $r['user_id'];
          if (!isset($user_years[$uid]))
            $user_years[$uid] = [];
          $user_years[$uid][] = [
            'year' => $r['year'],
            'yr_start' => (int) $r['yr_start'],
            'total' => (float) ($r['total'] ?? 0),
          ];
        }

        // Allocate payments per user across years (oldest first) and capture
        // allocated paid and due for the requested year.
        $alloc_map = []; // user_id => ['total'=>..., 'paid'=>..., 'due'=>...]
        foreach ($user_ids as $uid) {
          $rows_for_user = $user_years[$uid] ?? [];
          usort($rows_for_user, function ($a, $b) {
            return $a['yr_start'] <=> $b['yr_start'];
          });
          $remain = $paid_map[$uid] ?? 0.0;
          $alloc_map[$uid] = ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
          foreach ($rows_for_user as $r) {
            $alloc = min($r['total'], $remain);
            $due = max(0.0, $r['total'] - $alloc);
            if ($r['year'] === $takhmeen_year) {
              $alloc_map[$uid] = ['total' => $r['total'], 'paid' => $alloc, 'due' => $due];
              break; // once we've recorded the target year allocation, we can stop
            }
            $remain -= $alloc;
            if ($remain <= 0) {
              $remain = 0;
            }
          }
          // Ensure entry exists even if user had no rows for selected year
          if (!isset($alloc_map[$uid])) {
            $alloc_map[$uid] = ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
          }
        }

        // Update returned rows to reflect year-scoped paid/due
        foreach ($rows as &$r) {
          $uid = $r['user_id'];
          if (isset($alloc_map[$uid])) {
            $r['total_paid'] = $alloc_map[$uid]['paid'];
            $r['outstanding'] = $alloc_map[$uid]['due'];
          } else {
            $r['total_paid'] = 0.0;
            $r['outstanding'] = (float) ($r['total_takhmeen'] ?? 0) - 0.0;
          }
        }
        unset($r);
      }
    }

    return $rows;
  }

  /**
   * Return distinct starting hijri years present in the `sabeel_takhmeen` table.
   * Each row in the table stores a `year` like '1446-47' — this returns [1446, 1445, ...]
   */
  public function get_sabeel_distinct_years()
  {
    $sql = "SELECT DISTINCT CAST(SUBSTRING_INDEX(`year`, '-', 1) AS UNSIGNED) AS start_year FROM sabeel_takhmeen WHERE `year` IS NOT NULL ORDER BY start_year DESC";
    $rows = $this->db->query($sql)->result_array();
    $years = [];
    foreach ($rows as $r) {
      if (isset($r['start_year']) && $r['start_year'] !== null) {
        $years[] = (int) $r['start_year'];
      }
    }
    return $years;
  }

  public function get_sabeel_user_records($user_id, $year = null)
  {
    $this->db->select('st.*, est.grade as est_grade, est.amount as est_amount, res.grade as res_grade, res.yearly_amount as res_amount');
    $this->db->from('sabeel_takhmeen st');
    $this->db->join('sabeel_takhmeen_grade est', 'est.id = st.establishment_grade', 'left');
    $this->db->join('sabeel_takhmeen_grade res', 'res.id = st.residential_grade', 'left');
    $this->db->where('st.user_id', $user_id);
    if (!empty($year)) {
      $takhmeen_year = $year . '-' . substr($year + 1, -2);
      $this->db->where('st.year', $takhmeen_year);
    }
    $this->db->order_by('st.id', 'ASC');
    return $this->db->get()->result_array();
  }

  public function get_sabeel_payments_by_user($user_id)
  {
    $this->db->select('*');
    $this->db->from('sabeel_takhmeen_payments');
    $this->db->where('user_id', $user_id);
    $this->db->order_by('payment_date', 'ASC');
    return $this->db->get()->result_array();
  }

  // ===================== FMB (Thaali) Takhmeen - Summary & Details =====================
  public function get_fmb_takhmeen_reports($year = null)
  {
    $summary = [];

    // Total FMB Takhmeen amount (all years)
    $row = $this->db->select('SUM(total_amount) AS total')
      ->from('fmb_takhmeen')
      ->get()->row_array();
    $summary['total_fmb_takhmeen_amount'] = (float) ($row['total'] ?? 0);

    // Total Outstanding FMB amount (aggregate per user, clamp >= 0)
    $due_sql = "SELECT SUM(GREATEST(user_due, 0)) AS total_due FROM (
      SELECT ft.user_id,
        SUM(ft.total_amount) AS total_takhmeen,
        COALESCE(paid.total_paid,0) AS total_paid,
        SUM(ft.total_amount) - COALESCE(paid.total_paid,0) AS user_due
      FROM fmb_takhmeen ft
      LEFT JOIN (
        SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments GROUP BY user_id
      ) paid ON paid.user_id = ft.user_id
      GROUP BY ft.user_id
    ) AS user_dues";
    $row = $this->db->query($due_sql)->row_array();
    $summary['total_outstanding_fmb_amount'] = (float) ($row['total_due'] ?? 0);

    // Optional: per-year filtered totals (if year provided)
    if (!empty($year)) {
      $takhmeen_year = $year . '-' . substr($year + 1, -2);
      $yr_row = $this->db->select('SUM(total_amount) AS total')
        ->from('fmb_takhmeen')
        ->where('year', $takhmeen_year)
        ->get()->row_array();
      $summary['year_total_fmb_takhmeen_amount'] = (float) ($yr_row['total'] ?? 0);

      // Sector-wise breakdown (include all sectors; zero when no takhmeen in selected year)
      $sector_list_sql = "SELECT DISTINCT u.Sector AS sector
        FROM user u
        WHERE u.Sector IS NOT NULL AND TRIM(u.Sector) <> ''";

      $agg_sql = "SELECT 
          u.Sector AS sector,
          SUM(COALESCE(ft.total_amount,0)) AS sector_total,
          COUNT(DISTINCT ft.user_id) AS member_count
        FROM fmb_takhmeen ft
        LEFT JOIN user u ON u.ITS_ID = ft.user_id
        WHERE ft.year = ?
        GROUP BY u.Sector";

      $sql = "SELECT s.sector,
                COALESCE(a.sector_total, 0) AS sector_total,
                COALESCE(a.member_count, 0) AS member_count
              FROM (" . $sector_list_sql . ") s
              LEFT JOIN (" . $agg_sql . ") a ON a.sector = s.sector
              ORDER BY s.sector ASC";

      $summary['sector_breakdown'] = $this->db->query($sql, [$takhmeen_year])->result_array();

      // FIFO allocation of payments to oldest years first to compute current-year paid and outstanding
      // 1) Per-user per-year takhmeen totals with a sortable numeric year
      $per_user_years = $this->db->query(
        "SELECT ft.user_id, ft.year, SUM(ft.total_amount) AS total,
                CAST(SUBSTRING_INDEX(ft.year, '-', 1) AS UNSIGNED) AS yr_start
           FROM fmb_takhmeen ft
          GROUP BY ft.user_id, ft.year
          ORDER BY ft.user_id ASC, yr_start ASC"
      )->result_array();

      // 2) Total paid per user (all years)
      $paid_by_user = $this->db->query(
        "SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments GROUP BY user_id"
      )->result_array();

      $paid_map = [];
      foreach ($paid_by_user as $r) {
        $paid_map[$r['user_id']] = (float) ($r['total_paid'] ?? 0);
      }

      // Build map user => [years...]
      $user_years = [];
      foreach ($per_user_years as $r) {
        $uid = $r['user_id'];
        if (!isset($user_years[$uid]))
          $user_years[$uid] = [];
        $user_years[$uid][] = [
          'year' => $r['year'],
          'yr_start' => (int) $r['yr_start'],
          'total' => (float) ($r['total'] ?? 0),
        ];
      }

      // Allocate per user and record per-user paid/due for the selected year
      $year_paid_total = 0.0;
      $year_outstanding_total = 0.0;
      $user_allocations = []; // user_id => ['total'=>..., 'paid'=>..., 'due'=>...]
      foreach ($user_years as $uid => $rows) {
        // Ensure ascending order by start year
        usort($rows, function ($a, $b) {
          return $a['yr_start'] <=> $b['yr_start'];
        });
        $remain = $paid_map[$uid] ?? 0.0;
        foreach ($rows as $row) {
          $alloc = min($row['total'], $remain);
          $due = max(0.0, $row['total'] - $alloc);
          if ($row['year'] === $takhmeen_year) {
            $year_paid_total += $alloc;
            $year_outstanding_total += $due;
            // record per-user values for this year
            $user_allocations[$uid] = [
              'total' => $row['total'],
              'paid' => $alloc,
              'due' => $due
            ];
            // no break: continue to reduce $remain for completeness
          }
          $remain -= $alloc;
          if ($remain <= 0) {
            // paid exhausted
          }
        }
        // If user had no row for selected year, ensure allocation entry exists
        if (!isset($user_allocations[$uid])) {
          $user_allocations[$uid] = ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
        }
      }

      $summary['year_total_paid_fmb_amount'] = $year_paid_total;
      $summary['year_outstanding_fmb_amount'] = $year_outstanding_total;

      // Build sector-wise aggregates (include sectors even if totals are zero)
      $user_ids = array_keys($user_years);
      $sector_map = [];
      if (!empty($user_ids)) {
        $in_ids = implode(',', array_map(function ($id) {
          return $this->db->escape($id);
        }, $user_ids));
        $users_sectors = $this->db->query("SELECT ITS_ID AS user_id, COALESCE(Sector, 'Unknown') AS sector FROM user WHERE ITS_ID IN ($in_ids)")->result_array();
        $user_sector_map = [];
        foreach ($users_sectors as $urow) {
          $user_sector_map[$urow['user_id']] = $urow['sector'];
        }

        $unknown_sector_due = 0.0;
        foreach ($user_allocations as $uid => $alloc) {
          $sector = isset($user_sector_map[$uid]) ? trim((string) $user_sector_map[$uid]) : '';
          if ($sector === '' || strcasecmp($sector, 'unknown') === 0) {
            // Accumulate unknown sector due (we will exclude these from sector table)
            $unknown_sector_due += (float) $alloc['due'];
            continue;
          }
          if (!isset($sector_map[$sector])) {
            $sector_map[$sector] = ['sector' => $sector, 'sector_total' => 0.0, 'sector_paid' => 0.0, 'sector_due' => 0.0, 'member_count' => 0];
          }
          $sector_map[$sector]['sector_total'] += (float) $alloc['total'];
          $sector_map[$sector]['sector_paid'] += (float) $alloc['paid'];
          $sector_map[$sector]['sector_due'] += (float) $alloc['due'];
          $sector_map[$sector]['member_count'] += 1;
        }

        // expose outstanding for known sectors (exclude unknown sector due)
        $summary['year_outstanding_known_fmb_amount'] = max(0.0, $year_outstanding_total - $unknown_sector_due);
      }

      // Ensure all sectors from user list are present (order by sector name)
      $summary['sector_breakdown'] = array_values($sector_map);
    }

    return $summary;
  }

  /**
   * Get next upcoming miqaat (or specific miqaat_id) RSVP stats.
   * Returns array: next_miqaat, total_hof, rsvp_count, not_rsvp_count, rsvp_list, not_rsvp_list
   */
  public function get_next_miqaat_rsvp_stats($miqaat_id = null)
  {
    // Find target miqaat: if $miqaat_id provided try to load it, otherwise pick next upcoming active miqaat
    if (!empty($miqaat_id)) {
      // Only consider miqaats that have been approved by Janab (raza.`Janab-status` = 1)
      $mi = $this->db->select('m.*, r.id AS raza_id, r.`Janab-status` AS janab_status')
        ->from('miqaat m')
        ->join('raza r', 'r.miqaat_id = m.id AND r.`Janab-status` = 1', 'inner')
        ->where('m.id', $miqaat_id)
        ->get()->row_array();
    } else {
      // Next upcoming miqaat that has janab approval
      $mi = $this->db->select('m.*, r.id AS raza_id, r.`Janab-status` AS janab_status')
        ->from('miqaat m')
        ->join('raza r', 'r.miqaat_id = m.id AND r.`Janab-status` = 1', 'inner')
        ->where('m.date >=', date('Y-m-d'))
        ->where('m.status', 1)
        ->order_by('m.date', 'ASC')
        ->order_by('m.id', 'ASC')
        ->limit(1)
        ->get()->row_array();
    }

    if (empty($mi)) {
      return [
        'next_miqaat' => null,
        'total_hof' => 0,
        'rsvp_count' => 0,
        'not_rsvp_count' => 0,
        'rsvp_list' => [],
        'not_rsvp_list' => []
      ];
    }

    $miqaat_pk = isset($mi['id']) ? $mi['id'] : null;

    // Get distinct RSVP hof_ids for this miqaat
    // Use distinct() to avoid the query builder escaping DISTINCT as a column
    $rsvp_rows = $this->db->distinct()->select('hof_id')->from('general_rsvp')->where('miqaat_id', $miqaat_pk)->get()->result_array();
    $rsvp_ids = [];
    foreach ($rsvp_rows as $r) {
      if (!empty($r['hof_id']))
        $rsvp_ids[] = $r['hof_id'];
    }

    // Total HOFs (active)
    $total_hof_row = $this->db->select('COUNT(*) AS total')->from('user')->where("HOF_FM_TYPE = 'HOF'")->where('Inactive_Status IS NULL', null, false)->where("Sector IS NOT NULL", null, false)->where("Sub_Sector IS NOT NULL", null, false)->get()->row_array();
    $total_hof = isset($total_hof_row['total']) ? (int) $total_hof_row['total'] : 0;

    $rsvp_count = count($rsvp_ids);
    $not_rsvp_count = max(0, $total_hof - $rsvp_count);

    // Fetch RSVP list details
    $rsvp_list = [];
    if (!empty($rsvp_ids)) {
      $in = implode(',', array_map(function ($v) {
        return $this->db->escape($v);
      }, $rsvp_ids));
      // Exclude users who do not have Sector and Sub_Sector set
      $sql = "SELECT ITS_ID AS ITS_ID, Full_Name, Sector, Sub_Sector, COALESCE(Registered_Family_Mobile, Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE ITS_ID IN ($in) AND COALESCE(Sector,'') <> '' AND COALESCE(Sub_Sector,'') <> '' ORDER BY Sector ASC, Sub_Sector ASC, Full_Name ASC";
      $rsvp_list = $this->db->query($sql)->result_array();
    }

    // Also fetch member-level RSVP records (individual users who submitted RSVP entries)
    $rsvp_member_list = [];
    try {
      $sqlm = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile FROM user u JOIN general_rsvp gr ON gr.user_id = u.ITS_ID WHERE gr.miqaat_id = ? AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> '' ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
      $rsvp_member_list = $this->db->query($sqlm, [$miqaat_pk])->result_array();
    } catch (Exception $e) {
      $rsvp_member_list = [];
    }

    // Member lists by gender/children for direct modal display
    $rsvp_male_member_list = [];
    $rsvp_female_member_list = [];
    $rsvp_children_member_list = [];
    try {
      $sql_male = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                   FROM user u JOIN general_rsvp gr ON gr.user_id = u.ITS_ID
                   WHERE gr.miqaat_id = ? AND LOWER(COALESCE(u.Gender,'')) = 'male' AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''
                   ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
      $rsvp_male_member_list = $this->db->query($sql_male, [$miqaat_pk])->result_array();

      $sql_female = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                    FROM user u JOIN general_rsvp gr ON gr.user_id = u.ITS_ID
                    WHERE gr.miqaat_id = ? AND LOWER(COALESCE(u.Gender,'')) = 'female' AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''
                    ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
      $rsvp_female_member_list = $this->db->query($sql_female, [$miqaat_pk])->result_array();

      $sql_children = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                       FROM user u JOIN general_rsvp gr ON gr.user_id = u.ITS_ID
                       WHERE gr.miqaat_id = ? AND (u.Age IS NOT NULL AND u.Age <= 15) AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''
                       ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
      $rsvp_children_member_list = $this->db->query($sql_children, [$miqaat_pk])->result_array();
    } catch (Exception $e) {
      $rsvp_male_member_list = [];
      $rsvp_female_member_list = [];
      $rsvp_children_member_list = [];
    }

    // Build not-RSVP member list by selecting users with no RSVP record (rsvp_status=0)
    // but whose HOF has at least one RSVP (hof_id in $rsvp_ids). This mirrors the counts logic.
    $not_rsvp_member_list = [];
    try {
      if (!empty($rsvp_ids)) {
        // prepare hof id list as integers
        $in_hofs = implode(',', array_map(function ($v) {
          return (int)$v;
        }, $rsvp_ids));
        // select users left-joined with general_rsvp for this miqaat and pick those with no rsvp (rsvp.id IS NULL)
        $sql_nr = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, u.HOF_ID, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                   FROM `user` u
                   LEFT JOIN general_rsvp gr ON gr.user_id = u.ITS_ID AND gr.miqaat_id = ?
                   WHERE u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''
                     AND gr.id IS NULL
                     AND u.HOF_ID IN ({$in_hofs})
                   ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
        $not_rsvp_member_list = $this->db->query($sql_nr, [$miqaat_pk])->result_array();
      }
    } catch (Exception $e) {
      $not_rsvp_member_list = [];
    }

    // Fetch Not-RSVP list (active HOFs not in RSVP list)
    $not_rsvp_list = [];
    $where_active = "HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL";
    if (!empty($rsvp_ids)) {
      $in = implode(',', array_map(function ($v) {
        return $this->db->escape($v);
      }, $rsvp_ids));
      // Exclude users without sector/sub sector
      $sql2 = "SELECT ITS_ID AS ITS_ID, Full_Name, Sector, Sub_Sector, COALESCE(Registered_Family_Mobile, Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE {$where_active} AND COALESCE(Sector,'') <> '' AND COALESCE(Sub_Sector,'') <> '' AND ITS_ID NOT IN ($in) ORDER BY Sector ASC, Sub_Sector ASC, Full_Name ASC";
      $not_rsvp_list = $this->db->query($sql2)->result_array();
    } else {
      // No RSVP ids: return all active HOFs but exclude those without sector/sub sector
      $sql2 = "SELECT ITS_ID AS ITS_ID, Full_Name, Sector, Sub_Sector, COALESCE(Registered_Family_Mobile, Mobile, Mobile, WhatsApp_No, '') AS mobile FROM user WHERE {$where_active} AND COALESCE(Sector,'') <> '' AND COALESCE(Sub_Sector,'') <> '' ORDER BY Sector ASC, Sub_Sector ASC, Full_Name ASC";
      $not_rsvp_list = $this->db->query($sql2)->result_array();
    }

    // Build not-submitted member list: individual users whose HOF has NO RSVP for this miqaat
    $not_submitted_member_list = [];
    try {
      // base active filter
      $base_active = "u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''";
      if (!empty($rsvp_ids)) {
        $in_hofs = implode(',', array_map(function ($v) {
          return (int)$v;
        }, $rsvp_ids));
        $sql_ns = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, u.HOF_ID, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                   FROM `user` u
                   WHERE {$base_active} AND u.HOF_ID NOT IN ({$in_hofs})
                   ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
        $not_submitted_member_list = $this->db->query($sql_ns)->result_array();
      } else {
        // no hof RSVPs: everyone is in not_submitted
        $sql_ns = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, u.HOF_ID, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                   FROM `user` u
                   WHERE {$base_active}
                   ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
        $not_submitted_member_list = $this->db->query($sql_ns)->result_array();
      }
    } catch (Exception $e) {
      $not_submitted_member_list = [];
    }

    // Aggregate guest RSVP counts for this miqaat (from general_guest_rsvp)
    $guest_summary = ['gents' => 0, 'ladies' => 0, 'children' => 0, 'total' => 0];
    try {
      if (!empty($miqaat_pk)) {
        $g = $this->db->query("SELECT COALESCE(SUM(gents),0) AS gents, COALESCE(SUM(ladies),0) AS ladies, COALESCE(SUM(children),0) AS children FROM general_guest_rsvp WHERE miqaat_id = ?", [$miqaat_pk])->row_array();
        if ($g) {
          $guest_summary['gents'] = isset($g['gents']) ? (int)$g['gents'] : 0;
          $guest_summary['ladies'] = isset($g['ladies']) ? (int)$g['ladies'] : 0;
          $guest_summary['children'] = isset($g['children']) ? (int)$g['children'] : 0;
          $guest_summary['total'] = $guest_summary['gents'] + $guest_summary['ladies'] + $guest_summary['children'];
        }
      }
    } catch (Exception $e) {
      // ignore and keep zeros
    }

    // Aggregate member RSVP counts (members who RSVP'd) by gender/age groups
    $member_summary = ['gents' => 0, 'ladies' => 0, 'children' => 0, 'total' => 0];
    try {
      if (!empty($miqaat_pk)) {
        $sql_ms = "SELECT 
            COALESCE(SUM(CASE WHEN (u.Age IS NOT NULL AND u.Age <= 15) THEN 1 ELSE 0 END),0) AS children,
            COALESCE(SUM(CASE WHEN (u.Age IS NULL OR u.Age > 15) AND LOWER(COALESCE(u.Gender,'')) = 'male' THEN 1 ELSE 0 END),0) AS gents,
            COALESCE(SUM(CASE WHEN (u.Age IS NULL OR u.Age > 15) AND LOWER(COALESCE(u.Gender,'')) = 'female' THEN 1 ELSE 0 END),0) AS ladies,
            COALESCE(COUNT(DISTINCT gr.user_id),0) AS total
          FROM general_rsvp gr
          JOIN `user` u ON u.ITS_ID = gr.user_id
          WHERE gr.miqaat_id = ? AND u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''";
        $mrow = $this->db->query($sql_ms, [$miqaat_pk])->row_array();
        if ($mrow) {
          $member_summary['gents'] = isset($mrow['gents']) ? (int)$mrow['gents'] : 0;
          $member_summary['ladies'] = isset($mrow['ladies']) ? (int)$mrow['ladies'] : 0;
          $member_summary['children'] = isset($mrow['children']) ? (int)$mrow['children'] : 0;
          $member_summary['total'] = isset($mrow['total']) ? (int)$mrow['total'] : ($member_summary['gents'] + $member_summary['ladies'] + $member_summary['children']);
        }
      }
    } catch (Exception $e) {
      // ignore and keep zeros
    }

    // Combined totals (members + guests)
    $combined_summary = [
      'gents' => $member_summary['gents'] + $guest_summary['gents'],
      'ladies' => $member_summary['ladies'] + $guest_summary['ladies'],
      'children' => $member_summary['children'] + $guest_summary['children'],
      'total' => ($member_summary['total'] + $guest_summary['total'])
    ];

    // For completeness, prepare an empty not-rsvp member list (individual users who did NOT RSVP)
    // NOTE: do not overwrite the computed $not_rsvp_member_list here — return the member-level list computed above

    return [
      'next_miqaat' => $mi,
      'total_hof' => $total_hof,
      'rsvp_count' => $rsvp_count,
      'not_rsvp_count' => $not_rsvp_count,
      'rsvp_list' => $rsvp_list,
      'not_rsvp_list' => $not_rsvp_list,
      'rsvp_member_list' => $rsvp_member_list,
      'rsvp_male_member_list' => $rsvp_male_member_list,
      'rsvp_female_member_list' => $rsvp_female_member_list,
      'rsvp_children_member_list' => $rsvp_children_member_list,
      'not_rsvp_member_list' => $not_rsvp_member_list,
      'not_submitted_member_list' => $not_submitted_member_list,
      'guest_summary' => $guest_summary,
      'member_summary' => $member_summary,
      'combined_summary' => $combined_summary
    ];
  }

  public function get_fmb_user_details($year = null, $name_filter = null, $amount_zero = null)
  {
    $params = [];
    $year_filter_clause = '';
    $year_filter_clause_join = '';
    if (!empty($year)) {
      $takhmeen_year = $year . '-' . substr($year + 1, -2);
      $year_filter_clause = ' WHERE ft.year = ? ';
      $year_filter_clause_join = ' AND ft.year = ? ';
      // Will bind twice: once in base UNION, once in main JOIN
      $params[] = $takhmeen_year;
      $params[] = $takhmeen_year;
    }

    // Build base set of user_ids from both sources; do not include names here to avoid UNION duplicates
    $base_users_sql = "SELECT u.ITS_ID AS user_id
      FROM user u
      WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.Sector IS NOT NULL";

    $base_ft_sql = "SELECT DISTINCT ft.user_id AS user_id
      FROM fmb_takhmeen ft" . $year_filter_clause;

    $sql = "SELECT 
        b.user_id,
        MAX(COALESCE(u.Full_Name, CAST(b.user_id AS CHAR))) AS full_name,
        COALESCE(SUM(ft.total_amount), 0) AS total_takhmeen,
        COALESCE(paid.total_paid, 0) AS total_paid,
        COALESCE(SUM(ft.total_amount), 0) - COALESCE(paid.total_paid, 0) AS outstanding
      FROM (
        SELECT DISTINCT user_id FROM ( $base_users_sql UNION $base_ft_sql ) s
      ) b
  LEFT JOIN user u ON u.ITS_ID = b.user_id
      LEFT JOIN fmb_takhmeen ft ON ft.user_id = b.user_id" . $year_filter_clause_join . "
      LEFT JOIN (
        SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments GROUP BY user_id
      ) paid ON paid.user_id = b.user_id
      GROUP BY b.user_id
    ";

    // Optional HAVING filters
    $having = [];
    if (!empty($name_filter)) {
      $having[] = "LOWER(MAX(COALESCE(u.Full_Name, CAST(b.user_id AS CHAR)))) LIKE ?";
      $params[] = '%' . strtolower(trim($name_filter)) . '%';
    }
    if (!empty($amount_zero)) {
      $having[] = "COALESCE(SUM(ft.total_amount), 0) = 0";
    }
    if (!empty($having)) {
      $sql .= ' HAVING ' . implode(' AND ', $having) . ' ';
    }

    $sql .= " ORDER BY full_name ASC";

    $rows = $this->db->query($sql, $params)->result_array();

    // If a specific hijri start year is requested, compute per-user paid/due
    // for that year by allocating payments FIFO to oldest years first.
    if (!empty($year) && !empty($rows)) {
      $takhmeen_year = $year . '-' . substr($year + 1, -2);
      // Build user id list for querying per-user year totals and payments
      $user_ids = array_map(function ($r) {
        return $r['user_id'];
      }, $rows);
      $in_ids = implode(',', array_map(function ($id) {
        return $this->db->escape($id);
      }, $user_ids));

      // Per-user per-year takhmeen totals (only for these users)
      $per_user_years = $this->db->query(
        "SELECT ft.user_id, ft.year, SUM(ft.total_amount) AS total, CAST(SUBSTRING_INDEX(ft.year, '-', 1) AS UNSIGNED) AS yr_start
           FROM fmb_takhmeen ft
          WHERE ft.user_id IN ($in_ids)
          GROUP BY ft.user_id, ft.year
          ORDER BY ft.user_id ASC, yr_start ASC"
      )->result_array();

      // Total paid per user (all years) but limited to these users
      $paid_by_user = $this->db->query(
        "SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments WHERE user_id IN ($in_ids) GROUP BY user_id"
      )->result_array();

      $paid_map = [];
      foreach ($paid_by_user as $r) {
        $paid_map[$r['user_id']] = (float) ($r['total_paid'] ?? 0);
      }

      // Build map user => years[]
      $user_years = [];
      foreach ($per_user_years as $r) {
        $uid = $r['user_id'];
        if (!isset($user_years[$uid]))
          $user_years[$uid] = [];
        $user_years[$uid][] = [
          'year' => $r['year'],
          'yr_start' => (int) $r['yr_start'],
          'total' => (float) ($r['total'] ?? 0),
        ];
      }

      // Allocate payments per user across years (oldest first) and capture
      // allocated paid and due for the requested year.
      $alloc_map = []; // user_id => ['total'=>..., 'paid'=>..., 'due'=>...]
      foreach ($user_ids as $uid) {
        $rows_for_user = $user_years[$uid] ?? [];
        usort($rows_for_user, function ($a, $b) {
          return $a['yr_start'] <=> $b['yr_start'];
        });
        $remain = $paid_map[$uid] ?? 0.0;
        $alloc_map[$uid] = ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
        foreach ($rows_for_user as $r) {
          $alloc = min($r['total'], $remain);
          $due = max(0.0, $r['total'] - $alloc);
          if ($r['year'] === $takhmeen_year) {
            $alloc_map[$uid] = ['total' => $r['total'], 'paid' => $alloc, 'due' => $due];
            break; // once we've recorded the target year allocation, we can stop
          }
          $remain -= $alloc;
          if ($remain <= 0) {
            // paid exhausted for older years
            $remain = 0;
          }
        }
        // Ensure entry exists even if user had no rows for selected year
        if (!isset($alloc_map[$uid])) {
          $alloc_map[$uid] = ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
        }
      }

      // Update returned rows to reflect year-scoped paid/due (and keep total_takhmeen for the year)
      foreach ($rows as &$r) {
        $uid = $r['user_id'];
        if (isset($alloc_map[$uid])) {
          $r['total_paid'] = $alloc_map[$uid]['paid'];
          $r['outstanding'] = $alloc_map[$uid]['due'];
        } else {
          $r['total_paid'] = 0.0;
          $r['outstanding'] = (float) ($r['total_takhmeen'] ?? 0) - 0.0;
        }
      }
      unset($r);
    }

    return $rows;
  }

  public function get_fmb_user_records($user_id, $year = null)
  {
    $this->db->select('ft.*');
    $this->db->from('fmb_takhmeen ft');
    $this->db->where('ft.user_id', $user_id);
    if (!empty($year)) {
      $takhmeen_year = $year . '-' . substr($year + 1, -2);
      $this->db->where('ft.year', $takhmeen_year);
    }
    $this->db->order_by('ft.year', 'DESC');
    return $this->db->get()->result_array();
  }

  public function get_fmb_payments_by_user($user_id)
  {
    return $this->db->from('fmb_takhmeen_payments')
      ->where('user_id', $user_id)
      ->order_by('payment_date', 'ASC')
      ->get()->result_array();
  }

  // Sector details with FIFO-paid allocation for a selected Hijri year
  public function get_fmb_sector_year_details($year, $sector)
  {
    $details = [
      'sector' => $sector,
      'year' => $year,
      'takhmeen_year' => $year . '-' . substr($year + 1, -2),
      'rows' => [],
      'totals' => ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0],
    ];

    if (empty($year) || empty($sector)) {
      return $details;
    }

    $takhmeen_year = $details['takhmeen_year'];

    // Candidate users in sector
    $users = $this->db->select('u.ITS_ID AS user_id, u.Full_Name AS name')
      ->from('user u')
      ->where('u.Sector', $sector)
      ->where("(u.Inactive_Status IS NULL OR u.Inactive_Status = '')", null, false)
      ->where('u.HOF_FM_TYPE', 'HOF')
      ->get()->result_array();

    if (empty($users))
      return $details;

    $user_ids = array_column($users, 'user_id');
    if (empty($user_ids))
      return $details;

    // Assigned Thaali Days per user for this FY (stored as composite FY in assignment.year like 1447-48)
    $assigned_map = [];
    if ($this->db->table_exists('fmb_thaali_day_assignment')) {
      $aRows = $this->db->select('user_id, COUNT(DISTINCT menu_id) AS cnt', false)
        ->from('fmb_thaali_day_assignment')
        ->where('year', $takhmeen_year)
        ->where_in('user_id', $user_ids)
        ->group_by('user_id')
        ->get()->result_array();
      foreach ($aRows as $ar) {
        $uid = isset($ar['user_id']) ? (string)$ar['user_id'] : '';
        if ($uid !== '') {
          $assigned_map[$uid] = (int)($ar['cnt'] ?? 0);
        }
      }
    }

    // Per-user per-year totals for these users only
    $in_ids = implode(',', array_map(function ($id) {
      return $this->db->escape($id);
    }, $user_ids));
    $per_user_years = $this->db->query(
      "SELECT ft.user_id, ft.year, SUM(ft.total_amount) AS total,
              CAST(SUBSTRING_INDEX(ft.year, '-', 1) AS UNSIGNED) AS yr_start
         FROM fmb_takhmeen ft
        WHERE ft.user_id IN ($in_ids)
        GROUP BY ft.user_id, ft.year
        ORDER BY ft.user_id ASC, yr_start ASC"
    )->result_array();

    // Total paid per user (all years)
    $paid_by_user = $this->db->query(
      "SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments
        WHERE user_id IN ($in_ids)
        GROUP BY user_id"
    )->result_array();

    $paid_map = [];
    foreach ($paid_by_user as $r) {
      $paid_map[$r['user_id']] = (float) ($r['total_paid'] ?? 0);
    }

    // Organize per user
    $per_by_user = [];
    foreach ($per_user_years as $r) {
      $uid = $r['user_id'];
      if (!isset($per_by_user[$uid]))
        $per_by_user[$uid] = [];
      $per_by_user[$uid][] = [
        'year' => $r['year'],
        'yr_start' => (int) $r['yr_start'],
        'total' => (float) ($r['total'] ?? 0),
      ];
    }

    // Index of user names
    $name_map = [];
    foreach ($users as $u) {
      $name_map[$u['user_id']] = $u['name'];
    }

    $rows = [];
    $agg_total = 0.0;
    $agg_paid = 0.0;
    $agg_due = 0.0;
    foreach ($user_ids as $uid) {
      $rows_for = isset($per_by_user[$uid]) ? $per_by_user[$uid] : [];
      $assignedDays = isset($assigned_map[(string)$uid]) ? (int)$assigned_map[(string)$uid] : 0;
      if (empty($rows_for)) {
        // No takhmeen any year; still show if they have assigned thaali days for this FY
        if ($assignedDays > 0) {
          $rows[] = [
            'user_id' => $uid,
            'name' => $name_map[$uid] ?? (string) $uid,
            'total' => 0.0,
            'paid' => 0.0,
            'due' => 0.0,
            'assigned_thaali_days' => $assignedDays,
          ];
        }
        continue;
      }
      usort($rows_for, function ($a, $b) {
        return $a['yr_start'] <=> $b['yr_start'];
      });
      $remain = $paid_map[$uid] ?? 0.0;
      $yr_total = 0.0;
      $yr_paid = 0.0;
      $yr_due = 0.0;
      foreach ($rows_for as $row) {
        $alloc = min($row['total'], $remain);
        $due = max(0.0, $row['total'] - $alloc);
        if ($row['year'] === $takhmeen_year) {
          $yr_total = $row['total'];
          $yr_paid = $alloc;
          $yr_due = $due;
        }
        $remain -= $alloc;
      }
      if ($yr_total > 0 || $assignedDays > 0) {
        $rows[] = [
          'user_id' => $uid,
          'name' => $name_map[$uid] ?? (string) $uid,
          'total' => $yr_total,
          'paid' => $yr_paid,
          'due' => $yr_due,
          'assigned_thaali_days' => $assignedDays,
        ];
        $agg_total += $yr_total;
        $agg_paid += $yr_paid;
        $agg_due += $yr_due;
      }
    }

    // Sort by due desc then name
    usort($rows, function ($a, $b) {
      if ($b['due'] == $a['due'])
        return strcasecmp($a['name'], $b['name']);
      return $b['due'] <=> $a['due'];
    });

    $details['rows'] = $rows;
    $details['totals'] = ['total' => $agg_total, 'paid' => $agg_paid, 'due' => $agg_due];
    return $details;
  }

  // ===================== FMB General Contributions =====================
  public function get_fmb_general_contributions($filters = [])
  {
    $this->db->select('gc.*, u.Full_Name AS user_name')
      ->from('fmb_general_contribution gc')
      ->join('user u', 'u.ITS_ID = gc.user_id', 'left');

    if (!empty($filters['contri_year'])) {
      $this->db->where('gc.contri_year', $filters['contri_year']);
    }
    if (!empty($filters['user_id'])) {
      $this->db->where('gc.user_id', $filters['user_id']);
    }
    if (!empty($filters['fmb_type'])) {
      $this->db->where('gc.fmb_type', $filters['fmb_type']);
    }
    if (!empty($filters['contri_type'])) {
      $this->db->where('gc.contri_type', $filters['contri_type']);
    }
    if (isset($filters['payment_status']) && $filters['payment_status'] !== '') {
      $this->db->where('gc.payment_status', $filters['payment_status']);
    }

    $this->db->order_by('gc.created_at', 'DESC');
    return $this->db->get()->result_array();
  }

  public function get_distinct_fmb_contribution_years()
  {
    $rows = $this->db->distinct()
      ->select('contri_year')
      ->from('fmb_general_contribution')
      ->order_by('contri_year', 'DESC')
      ->get()->result_array();
    return array_map(function ($r) {
      return $r['contri_year'];
    }, $rows);
  }

  public function get_distinct_fmb_contribution_types($filters = [])
  {
    $this->db->distinct();
    $this->db->select('contri_type');
    $this->db->from('fmb_general_contribution');
    if (!empty($filters['contri_year'])) {
      $this->db->where('contri_year', $filters['contri_year']);
    }
    if (!empty($filters['fmb_type'])) {
      $this->db->where('fmb_type', $filters['fmb_type']);
    }
    $this->db->order_by('contri_type', 'ASC');
    $rows = $this->db->get()->result_array();
    return array_map(function ($r) {
      return $r['contri_type'];
    }, $rows);
  }

  public function get_fmb_gc_payments_summary_by_contribution_ids($contributionIds = [])
  {
    if (empty($contributionIds))
      return [];
    $this->db->select('fmbgc_id, SUM(amount) AS total_paid, COUNT(*) AS payment_count')
      ->from('fmb_general_contribution_payments')
      ->where_in('fmbgc_id', $contributionIds)
      ->group_by('fmbgc_id');
    $rows = $this->db->get()->result_array();
    $map = [];
    foreach ($rows as $r) {
      $map[$r['fmbgc_id']] = [
        'total_paid' => (float) ($r['total_paid'] ?? 0),
        'payment_count' => (int) ($r['payment_count'] ?? 0),
      ];
    }
    return $map;
  }

  public function get_fmb_gc_payments_by_contribution_ids($contributionIds = [])
  {
    if (empty($contributionIds))
      return [];
    $this->db->select('id, created_at, user_id, fmbgc_id, amount, payment_method, payment_date, remarks')
      ->from('fmb_general_contribution_payments')
      ->where_in('fmbgc_id', $contributionIds)
      ->order_by('payment_date', 'ASC');
    $rows = $this->db->get()->result_array();
    $map = [];
    foreach ($rows as $r) {
      $fid = $r['fmbgc_id'];
      if (!isset($map[$fid]))
        $map[$fid] = [];
      $map[$fid][] = $r;
    }
    return $map;
  }

  /**
   * Get total counts (and optional sums) per contribution type for the selected filters.
   * Respects contri_year, fmb_type, payment_status, user_id, but intentionally ignores contri_type
   * to produce the full distribution across all types.
   * Returns array of [ 'contri_type' => string, 'count' => int, 'total_amount' => float ]
   */
  public function get_fmb_gc_contri_type_counts($filters = [])
  {
    $this->db->select('gc.contri_type, COUNT(*) AS cnt, SUM(gc.amount) AS total_amount')
      ->from('fmb_general_contribution gc');

    if (!empty($filters['contri_year'])) {
      $this->db->where('gc.contri_year', $filters['contri_year']);
    }
    if (!empty($filters['user_id'])) {
      $this->db->where('gc.user_id', $filters['user_id']);
    }
    if (!empty($filters['fmb_type'])) {
      $this->db->where('gc.fmb_type', $filters['fmb_type']);
    }
    if (isset($filters['payment_status']) && $filters['payment_status'] !== '' && $filters['payment_status'] !== null) {
      $this->db->where('gc.payment_status', $filters['payment_status']);
    }
    // NOTE: Intentionally do NOT apply contri_type filter here

    $this->db->group_by('gc.contri_type');
    $this->db->order_by('cnt', 'DESC');
    $rows = $this->db->get()->result_array();
    // Normalize keys/types
    return array_map(function ($r) {
      return [
        'contri_type' => (string) ($r['contri_type'] ?? ''),
        'count' => (int) ($r['cnt'] ?? 0),
        'total_amount' => (float) ($r['total_amount'] ?? 0),
      ];
    }, $rows);
  }
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  // Get hijri date for a given gregorian date
  public function get_hijri_date_by_greg_date($greg_date)
  {
    $this->db->select('hijri_date, hijri_month_id');
    $this->db->from('hijri_calendar');
    $this->db->where('greg_date', $greg_date);
    $query = $this->db->get();
    $row = $query->row_array();
    if ($row && isset($row['hijri_month_id'])) {
      $this->db->select('hijri_month');
      $this->db->from('hijri_month');
      $this->db->where('id', $row['hijri_month_id']);
      $month_query = $this->db->get();
      $month_row = $month_query->row_array();
      $row['hijri_month_name'] = isset($month_row['hijri_month']) ? $month_row['hijri_month'] : '';
    } else {
      $row['hijri_month_name'] = '';
    }
    return $row;
  }

  public function get_payment_details($payment_id, $table)
  {
    if ($table == 'corpus_fund_payment' || $table == 'ekram_fund_payment') {
      $this->db->select('payments.amount_paid as amount, payments.paid_at as payment_date, user.Full_Name, user.Address');
      $this->db->from($table . ' as payments');
      $this->db->join('user', 'user.ITS_ID = payments.hof_id', 'left');
    } else {
      $this->db->select('payments.*, user.Full_Name, user.Address');
      $this->db->from($table . ' as payments');
      $this->db->join('user', 'user.ITS_ID = payments.user_id', 'left');
    }
    $this->db->where('payments.id', $payment_id);
    $query = $this->db->get();

    return $query->row_array();
  }

  public function get_miqaat_by_id_and_date($id, $date)
  {
    $this->db->select('*');
    $this->db->from('miqaat');
    $this->db->where('id', $id);
    $this->db->where('date', $date);
    $query = $this->db->get();
    return $query->row_array();
  }

  /**
    $this->db->select('COALESCE(est.grade, "Unknown") AS grade,
   */
  public function get_miqaat_by_date($date)
  {
    $this->db->select('*');
    $this->db->from('miqaat');
    $this->db->where('date', $date);
    $query = $this->db->get();
    return $query->row_array();
  }

  /**
   * Update a miqaat by date
   */
  public function update_miqaat($date, $data)
  {
    $this->db->where('date', $date);
    return $this->db->update('miqaat', $data);
  }

  public function get_full_calendar($hijri_month_id = null, $sort_type = 'asc')
  {
    // Get hijri year and month filter
    $today = date('Y-m-d');
    $CI = &get_instance();
    $CI->load->model('HijriCalendar');
    $hijri_today = $CI->HijriCalendar->get_hijri_date($today);
    $hijri_year = explode('-', $hijri_today['hijri_date'])[2];

    // Filter by month/year if provided
    $CI->db->select('greg_date, hijri_date, hijri_month_id');
    $CI->db->from('hijri_calendar');
    if ($hijri_month_id) {
      if ($hijri_month_id == -3) {
        // Last year
        $filter_year = $hijri_year - 1;
        $CI->db->like('hijri_date', "-$filter_year");
      } elseif ($hijri_month_id == -2) {
        // Next year
        $filter_year = $hijri_year + 1;
        $CI->db->like('hijri_date', "-$filter_year");
      } elseif ($hijri_month_id == -1) {
        // This year
        $CI->db->like('hijri_date', "-$hijri_year");
      } else {
        // Specific month
        $CI->db->where('hijri_month_id', $hijri_month_id);
        $CI->db->like('hijri_date', "-$hijri_year");
      }
    } else {
      $CI->db->like('hijri_date', "-$hijri_year");
    }
    $CI->db->order_by('greg_date', $sort_type == 'desc' ? 'DESC' : 'ASC');
    $rows = $CI->db->get()->result_array();
    $dates = [];
    $greg_to_hijri = [];
    foreach ($rows as $row) {
      $greg_to_hijri[$row['greg_date']] = $row['hijri_date'];
      $dates[] = $row['greg_date'];
    }

    // Fetch all miqaat, menu, and assignments for the hijri year
    $this->db->select('m.id as miqaat_id, m.date as miqaat_date, m.name as miqaat_name, m.type as miqaat_type, m.assigned_to, u.Full_Name as contact');
    $this->db->from('miqaat m');
    $this->db->join('user u', 'u.ITS_ID = m.assigned_to', 'left');
    if (!empty($dates)) {
      $this->db->where_in('m.date', $dates);
    }
    $miqaats = $this->db->get()->result_array();
    $miqaat_map = [];
    foreach ($miqaats as $m) {
      $miqaat_map[$m['miqaat_date']] = $m;
    }

    $this->db->select('menu.date as menu_date, menu.id as menu_id');
    $this->db->from('menu');
    if (!empty($dates)) {
      $this->db->where_in('menu.date', $dates);
    }
    $menus = $this->db->get()->result_array();
    $menu_map = [];
    foreach ($menus as $menu) {
      $menu_map[$menu['menu_date']] = $menu['menu_id'];
    }

    // Fetch all menu items for the hijri year
    $this->db->select('menu_items_map.menu_id, menu_item.name as menu_item_name');
    $this->db->from('menu_items_map');
    $this->db->join('menu_item', 'menu_item.id = menu_items_map.item_id');
    $items = $this->db->get()->result_array();
    $items_map = [];
    foreach ($items as $item) {
      $items_map[$item['menu_id']][] = $item['menu_item_name'];
    }

    // Build calendar array for each date, flattening multiple miqaats/menu items
    $calendar = [];
    $CI->load->model('HijriCalendar');
    $month_name_cache = [];
    foreach ($dates as $date) {
      $hijri_date = isset($greg_to_hijri[$date]) ? $greg_to_hijri[$date] : '';
      $hijri_parts = explode('-', $hijri_date);
      $hijri_month = isset($hijri_parts[1]) ? $hijri_parts[1] : '';
      $hijri_month_name = '';
      if ($hijri_month) {
        if (!isset($month_name_cache[$hijri_month])) {
          $month_info = $CI->HijriCalendar->hijri_month_name($hijri_month);
          $month_name_cache[$hijri_month] = isset($month_info['hijri_month']) ? $month_info['hijri_month'] : '';
        }
        $hijri_month_name = $month_name_cache[$hijri_month];
      }
      // Get all menu entries for this date
      $menus_for_date = array_filter($menus, function ($menu) use ($date) {
        return $menu['menu_date'] == $date;
      });
      // Get all miqaats for this date
      $miqaats_for_date = array_filter($miqaats, function ($m) use ($date) {
        return $m['miqaat_date'] == $date;
      });

      // Add a row for each miqaat
      foreach ($miqaats_for_date as $miqaat) {
        $miqaat_id = isset($miqaat['miqaat_id']) ? $miqaat['miqaat_id'] : '';
        $assignments = [];
        if ($miqaat_id) {
          $miqaat_details = $this->get_miqaat_by_id($miqaat_id);
          $assignments = isset($miqaat_details['assignments']) ? $miqaat_details['assignments'] : [];
        }
        $calendar[] = [
          'greg_date' => $date,
          'hijri_date' => $hijri_date,
          'hijri_month_name' => $hijri_month_name,
          'day_type' => '',
          'miqaat_id' => $miqaat_id,
          'miqaat_name' => isset($miqaat['miqaat_name']) ? $miqaat['miqaat_name'] : '',
          'miqaat_type' => isset($miqaat['miqaat_type']) ? $miqaat['miqaat_type'] : '',
          'assigned_to' => isset($miqaat['assigned_to']) ? $miqaat['assigned_to'] : '',
          'menu_items' => [],
          'contact' => isset($miqaat['contact']) ? $miqaat['contact'] : '',
          'assignments' => $assignments,
        ];
      }

      // Add a row for each menu (thaali) for the date, always in a separate row
      foreach ($menus_for_date as $menu) {
        $menu_items = isset($items_map[$menu['menu_id']]) ? $items_map[$menu['menu_id']] : [];
        $calendar[] = [
          'greg_date' => $date,
          'hijri_date' => $hijri_date,
          'hijri_month_name' => $hijri_month_name,
          'day_type' => '',
          'menu_id' => isset($menu['menu_id']) ? $menu['menu_id'] : '',
          'miqaat_name' => 'Thaali Menu',
          'miqaat_type' => 'Thaali',
          'assigned_to' => '',
          'menu_items' => $menu_items,
          'contact' => '',
        ];
      }

      // If no miqaat and no menu, still add a blank row for the date
      if (empty($miqaats_for_date) && empty($menus_for_date)) {
        $calendar[] = [
          'greg_date' => $date,
          'hijri_date' => $hijri_date,
          'hijri_month_name' => $hijri_month_name,
          'day_type' => '',
          'miqaat_name' => '',
          'miqaat_type' => '',
          'assigned_to' => '',
          'menu_items' => [],
          'contact' => '',
        ];
      }
    }
    return $calendar;
  }

  function update_day($date, $day_type)
  {
    $date = date("Y-m-d", strtotime($date));
    $result = $this->db->insert("fmb_calendar_days", ["date" => $date, "day_type" => $day_type]);
    if ($result) {
      return true;
    }
    return false;
  }

  public function get_menu()
  {
    $currentYear = date('Y');
    $currentMonth = date('m');

    $this->db->from('menu');
    $this->db->where('YEAR(date)', $currentYear);
    $this->db->where('MONTH(date)', $currentMonth);
    $this->db->order_by('date', 'ASC');

    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_menu_dates()
  {
    $this->db->select("date");
    $this->db->from('menu');
    $this->db->order_by('date', 'ASC');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_month_wise_menu($data = [])
  {
    $startOfMonth = "";
    $endOfMonth = "";

    $today = date('Y-m-d');

    $this->db->select('hijri_date, hijri_month_id');
    $this->db->from('hijri_calendar');
    $this->db->where('greg_date', $today);
    $query = $this->db->get();
    $row = $query->row();

    if (!$row) {
      return [];
    }

    if (isset($data) && !empty($data)) {
      $hijri_month_id = $data["hijri_month_id"];
      $hijri_year = $data["hijri_year"];
    } else {
      $hijri_month_id = $row->hijri_month_id;
      $hijri_year = explode("-", $row->hijri_date)[2];
    }

    $this->db->from('hijri_calendar');
    if ($hijri_month_id > 0) {
      $this->db->where('hijri_month_id', $hijri_month_id);
      $this->db->like('hijri_date', $hijri_year);
    } else {
      $this->db->like('hijri_date', $hijri_year);
    }
    $this->db->order_by('greg_date', 'ASC');
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $month_data = $result->result_array();
    } else {
      return false;
    }

    // For full year, use first and last day of year
    $first_hijri_day = $month_data[0]["greg_date"];
    $last_hijri_day = $month_data[count($month_data) - 1]["greg_date"];

    $startOfMonth = date('Y-m-d', strtotime($first_hijri_day));
    $endOfMonth = date('Y-m-d', strtotime($last_hijri_day));

    // Get all menu entries for the month
    $sql = "SELECT 
                m.*, 
                mim.item_id, 
                mi.name AS item_name
            FROM menu m 
            LEFT JOIN menu_items_map mim ON mim.menu_id = m.id 
            LEFT JOIN menu_item mi ON mi.id = mim.item_id 
            WHERE m.date BETWEEN ? AND ? 
            ORDER BY m.date ASC";

    $query = $this->db->query($sql, array($startOfMonth, $endOfMonth));
    $results = $query->result_array();

    // Map date (Y-m-d) => assigned member info (if assignment table exists).
    // This supports showing pre-assignments even when a menu row doesn't exist.
    $assigned_by_date = [];
    if ($this->db->table_exists('fmb_thaali_day_assignment')) {
      $this->db->select("DATE(a.menu_date) AS d, a.user_id AS assigned_user_id, u.Full_Name", false);
      $this->db->from('fmb_thaali_day_assignment a');
      $this->db->join('user u', 'u.ITS_ID = a.user_id', 'left');
      $this->db->where('a.menu_date >=', $startOfMonth . ' 00:00:00');
      $this->db->where('a.menu_date <=', $endOfMonth . ' 23:59:59');
      foreach ($this->db->get()->result_array() as $ar) {
        $d = isset($ar['d']) ? (string)$ar['d'] : '';
        if ($d !== '') {
          $assigned_by_date[$d] = [
            'name' => isset($ar['Full_Name']) ? (string)$ar['Full_Name'] : '',
            'its'  => isset($ar['assigned_user_id']) ? (string)$ar['assigned_user_id'] : '',
          ];
        }
      }
    }

    // Group menu items by date
    $menu_by_date = [];
    foreach ($results as $row) {
      $date = date('Y-m-d', strtotime($row['date']));
      if (!isset($menu_by_date[$date])) {
        $assignedName = '';
        $assignedIts = '';
        if (isset($assigned_by_date[$date])) {
          $assignedName = isset($assigned_by_date[$date]['name']) ? (string)$assigned_by_date[$date]['name'] : '';
          $assignedIts = isset($assigned_by_date[$date]['its']) ? (string)$assigned_by_date[$date]['its'] : '';
        }
        $menu_by_date[$date] = [
          'id' => $row['id'],
          'date' => $date,
          'items' => [],
          'assigned_to' => $assignedName,
          'assigned_to_its' => $assignedIts,
        ];
      }
      if (!empty($row['item_id'])) {
        $menu_by_date[$date]['items'][] = $row['item_name'];
      }
    }

    // Loop through each day in the month
    $output = [];
    $period = new DatePeriod(
      new DateTime($startOfMonth),
      new DateInterval('P1D'),
      (new DateTime($endOfMonth))->modify('+1 day')
    );

    foreach ($period as $dt) {
      $date_str = $dt->format('Y-m-d');
      if (isset($menu_by_date[$date_str])) {
        $output[] = $menu_by_date[$date_str];
      } else {
        $assignedName = '';
        $assignedIts = '';
        if (isset($assigned_by_date[$date_str])) {
          $assignedName = isset($assigned_by_date[$date_str]['name']) ? (string)$assigned_by_date[$date_str]['name'] : '';
          $assignedIts = isset($assigned_by_date[$date_str]['its']) ? (string)$assigned_by_date[$date_str]['its'] : '';
        }
        $output[] = [
          'id' => null,
          'date' => $date_str,
          'items' => [],
          'assigned_to' => $assignedName,
          'assigned_to_its' => $assignedIts,
        ];
      }
    }
    return $output;
  }

  public function get_menu_by_id($id)
  {
    $this->db->select('*');
    $this->db->from('menu');
    $this->db->where('id', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return $query->row_array();
    } else {
      return null;
    }
  }

  public function get_menu_items_by_menu_id($menu_id)
  {
    $this->db->select('mi.*');
    $this->db->from('menu_items_map mim');
    $this->db->join('menu_item mi', 'mim.item_id = mi.id');
    $this->db->where('mim.menu_id', $menu_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  function search_items($keyword)
  {
    $this->db->like('name', $keyword);
    $query = $this->db->get('menu_item');
    return $query->result_array();
  }

  public function validate_menu_date($menu_date)
  {
    $this->db->select('id');
    $this->db->from('menu');
    $this->db->where('date', date('Y-m-d H:i:s', strtotime($menu_date)));
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return false;
    } else {
      return true;
    }
  }

  public function delete_menu($id)
  {
    // Remove related menu items first
    $this->db->where('menu_id', $id);
    $this->db->delete('menu_items_map');
    // Remove day assignment (if any)
    if ($this->db->table_exists('fmb_thaali_day_assignment')) {
      $this->db->where('menu_id', $id);
      $this->db->delete('fmb_thaali_day_assignment');
    }
    // Now delete the menu itself
    $this->db->where('id', $id);
    $this->db->delete('menu');
    return $this->db->affected_rows() > 0;
  }

  public function insert_menu($menu_date)
  {
    $data = [
      "date" => date('Y-m-d H:i:s', strtotime($menu_date)),
      'created_at' => date('Y-m-d H:i:s'),
    ];
    $this->db->insert('menu', $data);
    return $this->db->insert_id();
  }

  public function update_menu($menu_id, $menu_date)
  {
    $data = [
      "date" => date('Y-m-d H:i:s', strtotime($menu_date))
    ];
    $this->db->where('id', $menu_id);
    $this->db->update('menu', $data);
    return $this->db->affected_rows() > 0;
  }

  function filter_menu($data)
  {
    $this->db->select('*');
    $this->db->from('menu');

    $start_date = !empty($data['start_date']) ? date('Y-m-d', strtotime($data['start_date'])) : null;
    $end_date = !empty($data['end_date']) ? date('Y-m-d', strtotime($data['end_date'])) : null;

    if (!empty($data['start_date']) && !empty($data['end_date'])) {
      $this->db->where('date >=', $start_date);
      $this->db->where('date <=', $end_date);
    }

    if (!empty($data['sort_type'])) {
      if ($data['sort_type'] === 'asc') {
        $this->db->order_by('date', 'ASC');
      } else {
        $this->db->order_by('date', 'DESC');
      }
    }

    $query = $this->db->get();
    return $query->result_array();
  }

  public function remove_items_from_menu($menu_id)
  {
    $this->db->where('menu_id', $menu_id);
    $this->db->delete('menu_items_map');
    return $this->db->affected_rows();
  }

  public function add_items_to_menu($menu_id, $item_ids = [])
  {
    $insert_data = [];
    foreach ($item_ids as $item_id) {
      $insert_data[] = [
        'menu_id' => $menu_id,
        'item_id' => $item_id
      ];
    }

    return $this->db->insert_batch('menu_items_map', $insert_data);
  }

  public function upsert_day_assignment_by_date($menu_date, $user_id, $year, $menu_id = null)
  {
    if (!$this->db->table_exists('fmb_thaali_day_assignment')) {
      return false;
    }

    $iso = $menu_date ? date('Y-m-d', strtotime($menu_date)) : null;
    if (!$iso) return false;
    $menu_date_dt = $iso . ' 00:00:00';

    $year = (string)$year;
    $user_id = (int)$user_id;

    if ($user_id <= 0) {
      $this->db->where('year', $year);
      $this->db->where('menu_date', $menu_date_dt);
      $this->db->delete('fmb_thaali_day_assignment');
      return true;
    }

    $this->db->from('fmb_thaali_day_assignment');
    $this->db->where('year', $year);
    $this->db->where('menu_date', $menu_date_dt);
    $existing = $this->db->get()->row_array();

    $data = [
      'menu_date' => $menu_date_dt,
      'user_id' => $user_id,
      'year' => $year,
    ];
    if ($menu_id !== null && $menu_id !== '') {
      $mid = (int)$menu_id;
      if ($mid > 0) $data['menu_id'] = $mid;
    }

    if ($existing) {
      $this->db->where('id', (int)$existing['id']);
      return $this->db->update('fmb_thaali_day_assignment', $data);
    }

    if (!array_key_exists('menu_id', $data)) {
      $data['menu_id'] = null;
    }
    return $this->db->insert('fmb_thaali_day_assignment', $data);
  }

  public function get_menu_assignment($menu_id)
  {
    $this->db->select('a.user_id, u.Full_Name');
    $this->db->from('fmb_thaali_day_assignment a');
    $this->db->join('user u', 'u.ITS_ID = a.user_id', 'left');
    $this->db->where('a.menu_id', (int)$menu_id);
    $row = $this->db->get()->row_array();
    return $row ?: null;
  }

  public function upsert_menu_assignment($menu_id, $menu_date, $user_id, $year)
  {
    if (!$menu_id) return false;

    // Empty user => delete assignment
    if (!$user_id) {
      $this->db->where('menu_id', (int)$menu_id);
      $this->db->delete('fmb_thaali_day_assignment');
      return true;
    }

    $iso = $menu_date ? date('Y-m-d', strtotime($menu_date)) : null;
    $menu_date_dt = $iso ? ($iso . ' 00:00:00') : date('Y-m-d 00:00:00');

    $data = [
      'menu_id' => (int)$menu_id,
      'menu_date' => $menu_date_dt,
      'user_id' => (int)$user_id,
      'year' => (string)$year,
    ];

    // Merge any pre-assignment created by date/year (menu_id NULL)
    if ($iso) {
      $this->db->from('fmb_thaali_day_assignment');
      $this->db->where('year', (string)$year);
      $this->db->where('menu_date', $menu_date_dt);
      $byDate = $this->db->get()->row_array();
      if ($byDate) {
        $this->db->where('id', (int)$byDate['id']);
        return $this->db->update('fmb_thaali_day_assignment', $data);
      }
    }

    $this->db->from('fmb_thaali_day_assignment');
    $this->db->where('menu_id', (int)$menu_id);
    $existing = $this->db->get()->row_array();

    if ($existing) {
      $this->db->where('menu_id', (int)$menu_id);
      return $this->db->update('fmb_thaali_day_assignment', $data);
    }
    return $this->db->insert('fmb_thaali_day_assignment', $data);
  }

  public function delete_menu_assignment_by_menu_id($menu_id)
  {
    $this->db->where('menu_id', (int)$menu_id);
    $this->db->delete('fmb_thaali_day_assignment');
    return true;
  }

  public function count_assignments_for_user_year($user_id, $year)
  {
    $this->db->select('COUNT(1) AS cnt', false);
    $this->db->from('fmb_thaali_day_assignment');
    $this->db->where('user_id', (int)$user_id);
    $this->db->where('year', (string)$year);
    $row = $this->db->get()->row_array();
    return $row && isset($row['cnt']) ? (int)$row['cnt'] : 0;
  }

  // public function duplicate_last_month_menu()
  // {
  //   $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
  //   $currentMonth = date('Y-m');
  //   // Fetch last month's menu
  //   $this->db->select('*');
  //   $this->db->from('menu');
  //   $this->db->order_by('id', 'ASC');  // Assuming 'id' is the primary key
  //   $this->db->limit(31);
  //   $query = $this->db->get();
  //   $menus = $query->result_array();

  //   if (empty($menus)) {
  //     return false;
  //   }

  //   for ($day = 1; $day <= $daysInMonth; $day++) {
  //     $newDate = $currentMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);

  //     if (date('w', strtotime($newDate)) == 0) {
  //       continue;
  //     }

  //     if (isset($menus[$day])) :
  //       $menu = $menus[$day];

  //       $last_month_menu_id = $menu['id'];
  //       unset($menu['id']);
  //       $menu['date'] = $newDate;

  //       $this->db->insert('menu', $menu);
  //       $newMenuId = $this->db->insert_id();

  //       // Get menu items for this menu
  //       $items = $this->db
  //         ->where('menu_id', $last_month_menu_id)
  //         ->get('menu_items_map')
  //         ->result_array();

  //       foreach ($items as $item) {
  //         $newItem = $item;
  //         unset($newItem['id']);
  //         $newItem['menu_id'] = $newMenuId;

  //         // Insert new menu item
  //         $this->db->insert('menu_items_map', $newItem);
  //       }
  //     endif;
  //   }

  //   return true;
  // }

  function get_menu_items()
  {
    $sql = "SELECT * FROM `menu_item` WHERE status = 1 ORDER BY `id` DESC";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  function get_item_types()
  {
    $sql = "SELECT * FROM `item_type` ORDER BY `type_name`";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  function insert_item_type($data)
  {
    if (!empty($data)) {
      $this->db->insert('item_type', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function insert_menu_item($data)
  {
    if (!empty($data)) {
      $this->db->insert('menu_item', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function edit_menu_item($data)
  {
    if (!empty($data)) {
      $id = $data['id'];

      $updateData = array(
        'name' => $data['name'],
        'type' => $data['type'],
      );
      $this->db->where('id', $id);
      $this->db->update('menu_item', $updateData);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function delete_menu_item($id)
  {
    if (!empty($id)) {
      $this->db->where('id', $id);
      $this->db->delete('menu_item');
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function filter_menu_item($data)
  {
    $this->db->select('*');
    $this->db->from('menu_item');
    $this->db->where('status', 1);

    if (!empty($data['filter_type'])) {
      $this->db->where('type', $data['filter_type']);
    }

    if (!empty($data['search_item'])) {
      $this->db->like('name', $data['search_item']);
    }

    if (!empty($data['sort_type'])) {
      if ($data['sort_type'] === 'asc') {
        $this->db->order_by('name', 'ASC');
      } else {
        $this->db->order_by('name', 'DESC');
      }
    }

    $query = $this->db->get();
    return $query->result_array();
  }

  // Miqaat management

  /**
   * Get all hijri dates for the current hijri month and all miqaat assignments for those dates
   * Accepts filter_data array with hijri_month_id, hijri_year, sort_type
   */
  public function get_miqaats_with_assignments($filter_data = [])
  {
    // Step 1: Get hijri month id and year for the given filter or today
    $hijri_month_id = isset($filter_data['hijri_month_id']) ? $filter_data['hijri_month_id'] : null;
    $hijri_year = isset($filter_data['hijri_year']) ? $filter_data['hijri_year'] : null;
    $sort_type = isset($filter_data['sort_type']) ? $filter_data['sort_type'] : 'asc';

    if (!$hijri_month_id || $hijri_month_id == -1) {
      $today = date('Y-m-d');
      $this->db->select('hijri_month_id, hijri_date');
      $this->db->from('hijri_calendar');
      $this->db->where('greg_date', $today);
      $row = $this->db->get()->row();
      if (!$row)
        return [];
      $hijri_year = explode("-", $row->hijri_date)[2];
      $hijri_month_id = -1;
    }

    // Step 2: Get all hijri dates for this month or year
    $this->db->select('greg_date, hijri_date');
    $this->db->from('hijri_calendar');
    if ($hijri_month_id > 0) {
      $this->db->where('hijri_month_id', $hijri_month_id);
      $this->db->like('hijri_date', $hijri_year);
    } else {
      $this->db->like('hijri_date', $hijri_year);
    }
    $this->db->order_by('greg_date', $sort_type == 'desc' ? 'DESC' : 'ASC');
    $hijri_dates = $this->db->get()->result_array();

    // Step 3: Get all miqaat assignments for these dates
    $greg_dates = array_column($hijri_dates, 'greg_date');
    if (empty($greg_dates))
      return [];
    $this->db->select('
        m.id,
        m.name as name,
        m.type,
        m.date,
        m.assigned_to as assigned_to,
        m.status as status,
        a.assign_type,
        a.group_name,
        a.group_leader_id,
        leader.full_name as group_leader_name,
        member.ITS_ID as member_id,
        member.full_name as member_name
    ');
    $this->db->from('miqaat m');
    $this->db->join('miqaat_assignments a', 'm.id = a.miqaat_id', 'left');
    $this->db->join('user leader', 'leader.ITS_ID = a.group_leader_id', 'left');
    $this->db->join('user member', 'member.ITS_ID = a.member_id', 'left');
    $this->db->where_in('m.date', $greg_dates);
    // Optional filter by miqaat type
    if (!empty($filter_data['miqaat_type'])) {
      $this->db->where('m.type', $filter_data['miqaat_type']);
    }
    $this->db->order_by('m.date', $sort_type == 'desc' ? 'DESC' : 'ASC');
    $query = $this->db->get();
    $rows = $query->result_array();

    // Step 4: Restructure into nested array, indexed by greg_date
    $miqaats_by_date = [];
    foreach ($hijri_dates as $hd) {
      $miqaats_by_date[$hd['greg_date']] = [
        'date' => $hd['greg_date'],
        'hijri_date' => $hd['hijri_date'],
        'miqaats' => []
      ];
    }
    foreach ($rows as $row) {
      $date = $row['date'];
      if (!isset($miqaats_by_date[$date]))
        continue;
      $mid = $row['id'];
      if (!isset($miqaats_by_date[$date]['miqaats'][$mid])) {
        $miqaats_by_date[$date]['miqaats'][$mid] = [
          'id' => $row['id'],
          'name' => $row['name'],
          'type' => $row['type'],
          'date' => $row['date'],
          'status' => $row['status'],
          'assigned_to' => $row['assigned_to'],
          'assignments' => []
        ];
      }
      if ($row['assign_type'] === "Group") {
        $gid = $row['group_name'] . '_' . $row['group_leader_id'];
        if (!isset($miqaats_by_date[$date]['miqaats'][$mid]['assignments'][$gid])) {
          $miqaats_by_date[$date]['miqaats'][$mid]['assignments'][$gid] = [
            'assign_type' => $row['assign_type'],
            'group_name' => $row['group_name'],
            'group_leader_id' => $row['group_leader_id'],
            'group_leader_name' => $row['group_leader_name'],
            'members' => []
          ];
        }
        if ($row['member_id']) {
          $miqaats_by_date[$date]['miqaats'][$mid]['assignments'][$gid]['members'][] = [
            'id' => $row['member_id'],
            'name' => $row['member_name']
          ];
        }
      } elseif ($row['assign_type'] === "Individual" && $row['member_id']) {
        $miqaats_by_date[$date]['miqaats'][$mid]['assignments'][] = [
          'assign_type' => 'Individual',
          'member_id' => $row['member_id'],
          'member_name' => $row['member_name']
        ];
      }
    }
    // Step 5: Return as array of days, each with hijri_date and miqaats
    return array_values($miqaats_by_date);
  }

  /**
   * Get distinct miqaat types for filter dropdown
   */
  public function get_distinct_miqaat_types()
  {
    $this->db->distinct();
    $this->db->select('type');
    $this->db->from('miqaat');
    // Raw conditions to avoid unwanted escaping by the builder
    $this->db->where('type IS NOT NULL', null, false);
    $this->db->where("TRIM(type) <> ''", null, false);
    $this->db->order_by('type', 'ASC');
    $rows = $this->db->get()->result_array();
    return array_map(function ($r) {
      return $r['type'];
    }, $rows);
  }


  function insert_miqaat($data)
  {
    $this->db->insert('miqaat', $data);
    return $this->db->insert_id();
  }

  public function insert_assignment($data)
  {
    return $this->db->insert('miqaat_assignments', $data);
  }

  public function delete_fala_ni_niyaz_by_user_id($user_id, $miqaat_type, $hijri_year)
  {
    $this->db->where('user_id', $user_id);
    $this->db->where('miqaat_type', $miqaat_type);
    $this->db->where('year', $hijri_year);
    $this->db->delete('miqaat_invoice');
    return $this->db->affected_rows() > 0;
  }

  public function insert_raza($data)
  {
    $this->db->insert('raza', $data);
    return $this->db->insert_id();
  }

  public function delete_raza_by_miqaat_and_user($miqaat_id)
  {
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->delete('raza');
    return $this->db->affected_rows() > 0;
  }

  public function get_umoor_fmb_users()
  {
    return $this->db->where('role_id', '1')->get('user_roles')->result();
  }

  public function get_miqaat_by_id($miqaat_id)
  {
    $this->db->select('*');
    $this->db->from('miqaat');
    $this->db->where('id', $miqaat_id);
    $query = $this->db->get();
    $miqaat = $query->row_array();

    if ($miqaat) {
      $this->db->select('a.*, u.full_name as member_name, u.mobile as member_mobile, gl.full_name as group_leader_name, gl.mobile as group_leader_mobile');
      $this->db->from('miqaat_assignments a');
      $this->db->join('user u', 'a.member_id = u.ITS_ID', 'left');
      $this->db->join('user gl', 'a.group_leader_id = gl.ITS_ID', 'left');
      $this->db->where('a.miqaat_id', $miqaat_id);
      $assignments_raw = $this->db->get()->result_array();

      $assignments = [];
      $grouped = [];
      foreach ($assignments_raw as $assignment) {
        if ($assignment['assign_type'] === 'Group') {
          $group_key = $assignment['group_name'] . '_' . $assignment['group_leader_id'];
          if (!isset($grouped[$group_key])) {
            $grouped[$group_key] = [
              'assign_type' => 'Group',
              'group_name' => $assignment['group_name'],
              'group_leader_id' => $assignment['group_leader_id'],
              'group_leader_name' => $assignment['group_leader_name'],
              'group_leader_mobile' => $assignment['group_leader_mobile'],
              'members' => []
            ];
          }
          // Add member_id, member_name, member_mobile for each row
          if (!empty($assignment['member_id'])) {
            $grouped[$group_key]['members'][] = [
              'id' => $assignment['member_id'],
              'name' => $assignment['member_name'],
              'mobile' => $assignment['member_mobile']
            ];
          }
        } elseif ($assignment['assign_type'] === 'Individual') {
          $assignments[] = [
            'assign_type' => 'Individual',
            'member_id' => $assignment['member_id'],
            'member_name' => $assignment['member_name'],
            'member_mobile' => $assignment['member_mobile']
          ];
        }
      }
      // Add grouped assignments to final array
      foreach ($grouped as $g) {
        $assignments[] = $g;
      }
      $miqaat['assignments'] = $assignments;
    }
    return $miqaat;
  }

  public function get_hijri_from_gregorian($gregorian_date)
  {
    // Query hijri_calendar table for matching gregorian date
    $this->db->select('hijri_date');
    $this->db->from('hijri_calendar');
    $this->db->where('greg_date', $gregorian_date);
    $query = $this->db->get();
    if ($row = $query->row()) {
      return $row->hijri_date;
    }
    return '';
  }

  /**
   * Get counts and date lists for Miqaat days, Thaali days, and Holidays for a given Hijri year.
   * - Miqaat day: any gregorian date that has at least one miqaat entry
   * - Thaali day: any gregorian date that has at least one menu entry
   * - Holiday: a date in hijri_calendar for the year that has neither miqaat nor menu
   * Returns: [ 'hijri_year' => Y, 'miqaat_days' => int, 'thaali_days' => int, 'holiday_days' => int,
   *            'miqaat_dates' => [Y-m-d...], 'thaali_dates' => [Y-m-d...], 'holiday_dates' => [Y-m-d...] ]
   */
  public function get_year_calendar_daytypes($hijri_year = null)
  {
    // Resolve Hijri year from today if not provided
    if (empty($hijri_year)) {
      $today = date('Y-m-d');
      $CI = &get_instance();
      $CI->load->model('HijriCalendar');
      $h = $CI->HijriCalendar->get_hijri_date($today);
      if ($h && !empty($h['hijri_date'])) {
        $hijri_year = explode('-', $h['hijri_date'])[2];
      }
    }

    // Fetch all gregorian dates for this Hijri year
    $this->db->select('greg_date');
    $this->db->from('hijri_calendar');
    if (!empty($hijri_year)) {
      $this->db->like('hijri_date', '-' . $hijri_year);
    }
    $this->db->order_by('greg_date', 'ASC');
    $rows = $this->db->get()->result_array();
    if (empty($rows)) {
      return [
        'hijri_year' => $hijri_year,
        'miqaat_days' => 0,
        'thaali_days' => 0,
        'holiday_days' => 0,
        'miqaat_dates' => [],
        'thaali_dates' => [],
        'holiday_dates' => [],
      ];
    }

    $all_dates = array_map(function ($r) {
      return $r['greg_date'];
    }, $rows);

    $minDate = min($all_dates);
    $maxDate = max($all_dates);

    // Distinct miqaat dates (miqaat.date stored as Y-m-d)
    $this->db->select('DISTINCT(date) AS d', false)
      ->from('miqaat');
    // limit to year range if possible
    $this->db->where('date >=', $minDate);
    $this->db->where('date <=', $maxDate);
    $miqaat_dates = array_map(function ($r) {
      return $r['d'];
    }, $this->db->get()->result_array());

    // Distinct thaali dates (menu.date may include time; group by DATE(date))
    $this->db->select('DISTINCT(DATE(date)) AS d', false)
      ->from('menu');
    $this->db->where('date >=', $minDate . ' 00:00:00');
    $this->db->where('date <=', $maxDate . ' 23:59:59');
    $thaali_dates = array_map(function ($r) {
      return $r['d'];
    }, $this->db->get()->result_array());

    // Compute holiday dates = all_dates - union(miqaat_dates, thaali_dates)
    $has_activity = array_flip(array_unique(array_merge($miqaat_dates, $thaali_dates)));
    $holiday_dates = array_values(array_filter($all_dates, function ($d) use ($has_activity) {
      return !isset($has_activity[$d]);
    }));

    return [
      'hijri_year' => $hijri_year,
      'miqaat_days' => count(array_unique($miqaat_dates)),
      'thaali_days' => count(array_unique($thaali_dates)),
      'holiday_days' => count($holiday_dates),
      'miqaat_dates' => array_values(array_unique($miqaat_dates)),
      'thaali_dates' => array_values(array_unique($thaali_dates)),
      'holiday_dates' => $holiday_dates,
    ];
  }
  // Miqaat management

  /**
   * Update a miqaat by id
   */
  public function update_miqaat_by_id($miqaat_id, $data)
  {
    $this->db->where('id', $miqaat_id);
    if (isset($data['status'])) {
      $data['status'] = (int) $data['status'];
    }
    return $this->db->update('miqaat', $data);
  }

  public function delete_raza_by_miqaat_id($miqaat_id, $user_ids)
  {
    if (!empty($user_ids)) {
      $this->db->where('miqaat_id', $miqaat_id);
      $this->db->where_not_in('user_id', $user_ids);
      $this->db->delete('raza');
      return $this->db->affected_rows();
    }
  }

  /**
   * Remove all assignments for a miqaat
   */
  public function remove_assignments_from_miqaat($miqaat_id)
  {
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->delete('miqaat_assignments');
    return $this->db->affected_rows();
  }

  /**
   * Delete a miqaat and its assignments
   */
  public function delete_miqaat($miqaat_id)
  {

    $this->db->where("miqaat_id", $miqaat_id);
    $this->db->from("raza");
    $result = $this->db->get()->result_array();

    $this->db->where("miqaat_id", $miqaat_id);
    $this->db->delete("raza");

    // Delete assignments first
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->delete('miqaat_assignments');
    // Delete miqaat
    $this->db->where('id', $miqaat_id);
    $this->db->delete('miqaat');

    return $this->db->affected_rows();
  }

  public function set_miqaat_status($miqaat_id, $status)
  {
    $this->db->where('id', $miqaat_id);
    return $this->db->update('miqaat', ['status' => $status]);
  }

  public function set_raza_status_by_miqaat($miqaat_id, $status)
  {
    $this->db->where('miqaat_id', $miqaat_id);
    return $this->db->update('raza', ['status' => $status]);
  }

  public function get_miqaat_invoice_status($miqaat_id)
  {
    $this->db->from('miqaat_invoice');
    $this->db->where('miqaat_id', $miqaat_id);
    $query = $this->db->get()->result_array();
    if (!empty($query)) {
      return count($query);
    }
    return null;
  }

  public function get_miqaats()
  {
    $this->db->select('m.id as miqaat_id, m.name as miqaat_name, m.type as miqaat_type, m.date as miqaat_date');
    $this->db->from('miqaat m');
    $this->db->order_by('m.date', 'ASC');
    $miqaats = $this->db->get()->result_array();
    // Add hijri_date for each miqaat
    foreach ($miqaats as &$miqaat) {
      $hijri = $this->get_hijri_date_by_greg_date($miqaat['miqaat_date']);
      $miqaat['hijri_date'] = isset($hijri['hijri_date']) ? $hijri['hijri_date'] : '';
      $miqaat['hijri_month_name'] = isset($hijri['hijri_month_name']) ? $hijri['hijri_month_name'] : '';
    }
    unset($miqaat);
    return $miqaats;
  }

  public function get_attendance_by_miqaat($miqaat_id)
  {
    $this->db->from('miqaat_attendance');
    $this->db->where('miqaat_id', $miqaat_id);
    return $this->db->count_all_results();
  }

  /**
   * Get all members and join their attendance for a given miqaat
   */
  public function get_members_with_attendance($miqaat_id)
  {
    $this->db->select('u.ITS_ID, u.full_name, u.mobile, u.sector, u.sub_sector, a.comment');
    $this->db->from('user u');
    $this->db->join('miqaat_attendance a', 'u.ITS_ID = a.user_id AND a.miqaat_id = ' . (int) $miqaat_id, 'left');
    $this->db->order_by('u.sector, u.Sub_Sector, u.full_name', 'ASC');
    return $this->db->get()->result_array();
  }

  public function get_rsvp_by_miqaat($miqaat_id)
  {
    $this->db->from('general_rsvp');
    $this->db->where('miqaat_id', $miqaat_id);
    return $this->db->count_all_results();
  }

  public function get_members_with_rsvp($miqaat_id)
  {
    $this->db->select('u.ITS_ID, u.full_name, u.mobile, u.sector, u.sub_sector, IF(rsvp.id IS NOT NULL, 1, 0) as rsvp_status');
    $this->db->from('user u');
    $this->db->join('general_rsvp rsvp', 'u.ITS_ID = rsvp.user_id AND rsvp.miqaat_id = ' . (int) $miqaat_id, 'left');
    $this->db->order_by('u.sector, u.Sub_Sector, u.full_name', 'ASC');
    return $this->db->get()->result_array();
  }

  public function generate_miqaat_id($year)
  {

    $this->db->select('miqaat_id');
    $this->db->like('miqaat_id', $year . '-', 'after');
    $this->db->order_by('id', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('miqaat');

    if ($query->num_rows() > 0) {
      $last_miqaat = $query->row()->miqaat_id;
      $parts = explode('-', $last_miqaat);
      $next_number = intval($parts[1]) + 1;
    } else {
      $next_number = 1;
    }

    return $year . '-' . $next_number;
  }

  // Delivery Person Management
  public function get_hof_count()
  {
    return $this->db->query("SELECT count(*) as hof_count FROM user u WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.Sector IS NOT NULL")->result_array()[0];
  }

  public function getsignupcount($greg_date)
  {
    $date_esc = $this->db->escape($greg_date);

    // Families (HOF IDs) that have at least one member wanting thali for the date
    // We group by HOF_ID so a family signs up only once regardless of how many members
    $families_with_signup = "
      SELECT u.HOF_ID AS hof_id
      FROM fmb_weekly_signup fs
      JOIN user u ON u.ITS_ID = fs.user_id
      WHERE fs.signup_date = $date_esc
        AND fs.want_thali = 1
      GROUP BY u.HOF_ID
    ";

    // Resolve effective delivery person for the HOF user (not the member who signed up):
    // preference: active override on date > active mapping (latest by id)
    $effective_dp_per_hof = "
      SELECT 
        u.ITS_ID AS hof_id,
        COALESCE(
          (
            SELECT do.dp_id FROM delivery_overrides do
            WHERE do.user_id = u.ITS_ID
              AND do.start_date <= $date_esc
              AND do.end_date >= $date_esc
            ORDER BY do.id DESC LIMIT 1
          ),
          (
            SELECT dpm.dp_id FROM delivery_person_mapping dpm
            WHERE dpm.user_id = u.ITS_ID
              AND dpm.start_date <= $date_esc
              AND (dpm.end_date >= $date_esc OR dpm.end_date IS NULL)
            ORDER BY dpm.id DESC LIMIT 1
          )
        ) AS effective_dp_id
      FROM user u
      WHERE u.HOF_FM_TYPE = 'HOF'
    ";

    // Main aggregation: count families and distinct delivery persons (based on HOF's assignment)
    $sql = "
      SELECT 
        COUNT(*) AS hof_signup_count,
        COUNT(DISTINCT edp.effective_dp_id) AS delivery_person_count
      FROM ($families_with_signup) f
      JOIN user hof ON hof.ITS_ID = f.hof_id
      LEFT JOIN ($effective_dp_per_hof) edp ON edp.hof_id = f.hof_id
      WHERE hof.Inactive_Status IS NULL
        AND hof.Sector IS NOT NULL
    ";

    return $this->db->query($sql)->row_array();
  }

  /**
   * Sector-wise HOF signup count for a given gregorian date.
   * Returns array of ['Sector' => string, 'hof_signup_count' => int]
   */
  public function getsignupcount_by_sector($greg_date)
  {
    $date_esc = $this->db->escape($greg_date);

    $families_with_signup = "
      SELECT u.HOF_ID AS hof_id
      FROM fmb_weekly_signup fs
      JOIN user u ON u.ITS_ID = fs.user_id
      WHERE fs.signup_date = $date_esc
        AND fs.want_thali = 1
      GROUP BY u.HOF_ID
    ";

    $sql = "
      SELECT 
        hof.Sector AS Sector,
        COUNT(*) AS hof_signup_count
      FROM ($families_with_signup) f
      JOIN user hof ON hof.ITS_ID = f.hof_id
      WHERE hof.Inactive_Status IS NULL
        AND hof.Sector IS NOT NULL
      GROUP BY hof.Sector
      ORDER BY hof_signup_count DESC, hof.Sector ASC
    ";

    return $this->db->query($sql)->result_array();
  }

  public function get_all_dp()
  {
    return $this->db->query("SELECT * FROM delivery_person WHERE status = 1 ORDER BY created_at DESC")->result_array();
  }

  public function getsignupforaday($data)
  {
    $date = isset($data['date']) ? $data['date'] : date('Y-m-d');
    $escDate = $this->db->escape($date);

    // 1) Latest signup per HOF with want_thali = 1
    $latest_per_hof_sql = "
        SELECT MAX(fs.id) AS latest_id, u.HOF_ID
        FROM fmb_weekly_signup fs
        JOIN user u ON u.ITS_ID = fs.user_id
        WHERE fs.signup_date = {$escDate}
          AND fs.want_thali = 1
        GROUP BY u.HOF_ID
    ";

    // 2) Join back for details
    $latest_signup_sql = "
        SELECT fs.*, u.HOF_ID, u.Full_Name AS signup_member_name
        FROM fmb_weekly_signup fs
        JOIN user u ON u.ITS_ID = fs.user_id
        JOIN ({$latest_per_hof_sql}) li ON li.latest_id = fs.id
    ";

    // Latest override
    $latest_override_sql = "
        SELECT do1.*
        FROM delivery_overrides do1
        WHERE do1.start_date <= {$escDate}
          AND do1.end_date >= {$escDate}
          AND do1.id = (
              SELECT MAX(do2.id)
              FROM delivery_overrides do2
              WHERE do2.user_id = do1.user_id
                AND do2.start_date <= {$escDate}
                AND do2.end_date >= {$escDate}
          )
    ";

    // Latest mapping
    $latest_mapping_sql = "
        SELECT dpm1.*
        FROM delivery_person_mapping dpm1
        WHERE dpm1.start_date <= {$escDate}
          AND (dpm1.end_date >= {$escDate} OR dpm1.end_date IS NULL)
          AND dpm1.id = (
              SELECT MAX(dpm2.id)
              FROM delivery_person_mapping dpm2
              WHERE dpm2.user_id = dpm1.user_id
                AND dpm2.start_date <= {$escDate}
                AND (dpm2.end_date >= {$escDate} OR dpm2.end_date IS NULL)
          )
    ";

    // Main query
    $this->db->select("hof.ITS_ID AS hof_its_id,
        hof.Full_Name AS hof_name,
        hof.Sector AS sector,
        hof.Sub_Sector AS sub_sector,
        latest.user_id AS signup_user_id,
        latest.signup_member_name,
        latest.thali_size,
        latest.want_thali,
        dp_mapping.name AS delivery_person_name,
        dp_override.name AS sub_delivery_person_name,
        latest.signup_date", false);

    $this->db->from('user hof');
    $this->db->where('hof.HOF_FM_TYPE', 'HOF');
    $this->db->where('hof.Sector IS NOT NULL', null, false);
    $this->db->where('hof.Inactive_Status IS NULL', null, false);

    $this->db->join("({$latest_signup_sql}) latest", "latest.HOF_ID = hof.HOF_ID", 'left');
    $this->db->join("({$latest_override_sql}) do", "hof.ITS_ID = do.user_id", 'left');
    $this->db->join("({$latest_mapping_sql}) dpm", "hof.ITS_ID = dpm.user_id", 'left');

    $this->db->join('delivery_person dp_override', 'dp_override.id = do.dp_id', 'left');
    $this->db->join('delivery_person dp_mapping', 'dp_mapping.id = dpm.dp_id', 'left');

    if (isset($data["thali_taken"])) {
      if ($data["thali_taken"] == 1) {
        $this->db->where('latest.want_thali', 1);
      } elseif ($data["thali_taken"] == 0) {
        $this->db->where('latest.user_id IS NULL', null, false);
      }
    }

    if (!empty($data["reg_dp_id"]) || !empty($data["sub_dp_id"])) {
      $this->db->group_start()
        ->where('dpm.dp_id', $data["reg_dp_id"])
        ->or_where('do.dp_id', $data["sub_dp_id"])
        ->group_end();
    }

    // Optional: filter by Sector / Sub_Sector
    if (!empty($data['sector'])) {
      $this->db->where('hof.Sector', $data['sector']);
    }
    if (!empty($data['sub_sector'])) {
      $this->db->where('hof.Sub_Sector', $data['sub_sector']);
    }

    $this->db->order_by('hof.Sector, hof.Sub_Sector, hof.Full_Name');

    return $this->db->get()->result_array();
  }

  public function get_all_users()
  {
    // return $this->db->query("SELECT * FROM user WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND Sector IS NOT NULL ORDER BY Sector, Sub_Sector, First_Name ASC")->result_array();
    // return $this->db->query("SELECT 
    //   u.ITS_ID,
    //   u.HOF_FM_TYPE,
    //   u.Inactive_Status,
    //   u.Sector,
    //   u.Sub_Sector,
    //   u.First_Name,
    //   u.Surname,
    //   MAX(dpm.dp_id) AS dp_id
    // FROM user u
    // LEFT JOIN delivery_person_mapping dpm
    //   ON u.ITS_ID = dpm.user_id
    // WHERE u.HOF_FM_TYPE = 'HOF'
    //   AND u.Inactive_Status IS NULL
    //   AND u.Sector IS NOT NULL
    //   AND dpm.end_date IS NULL
    // GROUP BY u.ITS_ID, u.HOF_FM_TYPE, u.Inactive_Status, u.Sector, u.Sub_Sector, u.First_Name, u.Surname
    // ORDER BY u.Sector, u.Sub_Sector, u.First_Name")->result_array();
    return $this->db->query("
    SELECT 
      u.ITS_ID,
      u.HOF_FM_TYPE,
      u.Inactive_Status,
      u.Sector,
      u.Sub_Sector,
      u.First_Name,
      u.Full_Name,
      u.Surname,
      MAX(dpm.dp_id) AS dp_id,
      dp.name AS delivery_person_name
    FROM user u
    LEFT JOIN delivery_person_mapping dpm
      ON u.ITS_ID = dpm.user_id
    LEFT JOIN delivery_person dp
      ON dp.id = dpm.dp_id
    WHERE u.HOF_FM_TYPE = 'HOF'
      AND u.Inactive_Status IS NULL
      AND u.Sector IS NOT NULL
      AND dpm.end_date IS NULL
    GROUP BY 
      u.ITS_ID,
      u.HOF_FM_TYPE, 
      u.Inactive_Status, 
      u.Sector, 
      u.Sub_Sector, 
      u.First_Name, 
      u.Full_Name, 
      u.Surname,
      dp.name
    ORDER BY 
      u.Sector, 
      u.Sub_Sector, 
      u.First_Name
    ")->result_array();
  }

  public function substitutedeliveryperson($data)
  {
    $start_date = $data["start_date"];
    $end_date = $data["end_date"];
    $user_id = $data["user_id"];

    $this->db->where("user_id", $user_id);
    $this->db->where("start_date <=", $end_date);
    $this->db->where("end_date >=", $start_date);
    $this->db->delete("delivery_overrides");

    return $this->db->insert("delivery_overrides", $data);
  }

  public function get_current_substitutions($filters = [])
  {
    $today = date("Y-m-d");

    $this->db->select('
        do.*, 
        u.Full_Name as user_name, 
        dp.name as dp_name, 
        dp.phone as dp_phone, 
    ');
    $this->db->from('delivery_overrides do');
    $this->db->join('user u', 'do.user_id = u.ITS_ID', 'left');
    $this->db->join('delivery_person dp', 'do.dp_id = dp.id', 'left');
    // Active-only by default (today) unless filter overrides OR an explicit active_on date is supplied
    if (!empty($filters['active_on'])) {
      $this->db->where('do.start_date <=', $filters['active_on']);
      $this->db->where('do.end_date >=', $filters['active_on']);
    } else if (!isset($filters['show_all']) || !$filters['show_all']) {
      $this->db->where('do.start_date <=', $today);
      $this->db->where('do.end_date >=', $today);
    }

    // Optional filters
    if (!empty($filters['user_id'])) {
      $this->db->where('do.user_id', $filters['user_id']);
    }
    if (!empty($filters['dp_id'])) {
      $this->db->where('do.dp_id', $filters['dp_id']);
    }
    // Overlapping date range logic only applied when no single active_on date provided
    if (empty($filters['active_on'])) {
      if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $this->db->where('do.start_date <=', $filters['end_date']);
        $this->db->where('do.end_date >=', $filters['start_date']);
      } else if (!empty($filters['start_date'])) {
        $this->db->where('do.end_date >=', $filters['start_date']);
      } else if (!empty($filters['end_date'])) {
        $this->db->where('do.start_date <=', $filters['end_date']);
      }
    }
    if (!empty($filters['member_name'])) {
      $this->db->like('u.Full_Name', $filters['member_name']);
    }
    if (!empty($filters['dp_name'])) {
      $this->db->like('dp.name', $filters['dp_name']);
    }

    $this->db->order_by('do.start_date', 'ASC');
    return $this->db->get()->result_array();
  }

  /**
   * Update a delivery override (substitution) record's delivery person.
   * Only allows changing dp_id to keep history (dates remain immutable here).
   * @param int $id override row id
   * @param int $dp_id new delivery person id
   * @return bool
   */
  public function update_delivery_override($id, $dp_id)
  {
    // Return a structured array with diagnostics to help frontend determine outcome.
    if (empty($id) || empty($dp_id)) {
      return [
        'success' => false,
        'error' => 'Missing id or dp_id',
      ];
    }
    $existing = $this->db->get_where('delivery_overrides', ['id' => $id])->row_array();
    if (!$existing) {
      return [
        'success' => false,
        'error' => 'Record not found',
      ];
    }
    if ((int) $existing['dp_id'] === (int) $dp_id) {
      return [
        'success' => true,
        'unchanged' => true,
        'old_dp_id' => (int) $existing['dp_id'],
        'new_dp_id' => (int) $existing['dp_id']
      ];
    }
    $this->db->where('id', $id);
    $ok = $this->db->update('delivery_overrides', [
      'dp_id' => $dp_id,
    ]);
    $affected = $this->db->affected_rows();
    $resp = [
      'success' => $ok && $affected > 0,
      'affected_rows' => $affected,
      'old_dp_id' => (int) $existing['dp_id'],
      'new_dp_id' => (int) $dp_id,
    ];
    if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
      $resp['last_query'] = $this->db->last_query();
    }
    if (!$resp['success']) {
      $resp['error'] = $ok ? 'No rows changed' : 'Update failed';
    }
    return $resp;
  }

  /**
   * Delete a delivery override by id
   * @param int $id
   * @return bool
   */
  public function delete_delivery_override($id)
  {
    if (empty($id))
      return false;
    $this->db->where('id', $id);
    $this->db->delete('delivery_overrides');
    return $this->db->affected_rows() > 0;
  }

  public function updatedpmapping($data)
  {
    if (empty($data["user_id"])) {
      return false;
    }
    $this->db->where("user_id", $data["user_id"]);
    $this->db->update("delivery_person_mapping", ["end_date" => date("Y-m-d")]);
    return $this->db->insert("delivery_person_mapping", $data);
  }

  public function adddeliveryperson($data)
  {
    return $this->db->insert("delivery_person", $data);
  }

  public function updatedeliveryperson($data)
  {
    $name = $data["name"];
    $phone = $data["phone"];
    $status = $data["status"];

    $update_data = array(
      "name" => $name,
      "phone" => $phone,
      "status" => $status,
    );
    $this->db->where("id", $data["dp_id"]);
    return $this->db->update("delivery_person", $update_data);
  }

  // public function getsignupforaday($data)
  // {
  //   // Subquery for latest delivery override
  //   $latest_override_sql = "
  //       SELECT do1.*
  //       FROM delivery_overrides do1
  //       WHERE do1.start_date <= '" . $data["date"] . "'
  //         AND do1.end_date >= '" . $data["date"] . "'
  //         AND do1.id = (
  //             SELECT MAX(do2.id)
  //             FROM delivery_overrides do2
  //             WHERE do2.user_id = do1.user_id
  //               AND do2.start_date <= '" . $data["date"] . "'
  //               AND do2.end_date >= '" . $data["date"] . "'
  //         )
  //   ";

  //   // Subquery for latest delivery mapping
  //   $latest_mapping_sql = "
  //       SELECT dpm1.*
  //       FROM delivery_person_mapping dpm1
  //       WHERE dpm1.start_date <= '" . $data["date"] . "'
  //         AND (dpm1.end_date >= '" . $data["date"] . "' OR dpm1.end_date IS NULL)
  //         AND dpm1.id = (
  //             SELECT MAX(dpm2.id)
  //             FROM delivery_person_mapping dpm2
  //             WHERE dpm2.user_id = dpm1.user_id
  //               AND dpm2.start_date <= '" . $data["date"] . "'
  //               AND (dpm2.end_date >= '" . $data["date"] . "' OR dpm2.end_date IS NULL)
  //         )
  //   ";

  //   $this->db->select("
  //       u.ITS_ID,
  //       u.Full_Name,
  //       fmbws.thali_size,
  //       fmbws.want_thali,
  //       dp.name AS delivery_person_name,
  //       do.name AS substitute_delivery_person
  //   ", false);

  //   $this->db->from('user u');
  //   $this->db->where('u.Sector IS NOT NULL', null, false);
  //   $this->db->where('u.Inactive_Status IS NULL', null, false);

  //   // Thali signup
  //   $this->db->join(
  //     'fmb_weekly_signup fmbws',
  //     "u.ITS_ID = fmbws.user_id AND fmbws.signup_date = '" . $data["date"] . "'",
  //     'left'
  //   );

  //   // Latest override
  //   $this->db->join("($latest_override_sql) do", "u.ITS_ID = do.user_id", 'left');

  //   // Latest mapping
  //   $this->db->join("($latest_mapping_sql) dpm", "u.ITS_ID = dpm.user_id", 'left');

  //   // Delivery person name (from override if present, else mapping)
  //   // $this->db->join(
  //   //   'delivery_person dp',
  //   //   "dp.id = COALESCE(do.dp_id, dpm.dp_id)",
  //   //   'left'
  //   // );
  //   // if (!empty($data["dp_id"])) {
  //   //   $this->db->where('dp.id', $data["dp_id"]);
  //   // }

  //   $this->db->join(
  //     'delivery_person dp',
  //     'dp.id = do.dp_id',
  //     'left'
  //   );

  //   // Default Delivery Person
  //   $this->db->join(
  //     'delivery_person dp1',
  //     'dp1.id = dpm.dp_id',
  //     'left'
  //   );

  //   if (!empty($data["dp_id"])) {
  //     $this->db->group_start()
  //       ->where('dpm.dp_id', $data["dp_id"])
  //       ->or_where('do.dp_id', $data["dp_id"])
  //       ->group_end();
  //   }

  //   $this->db->where('u.HOF_FM_TYPE', 'HOF');
  //   if (isset($data["thali_taken"])) {
  //     if ($data["thali_taken"] == 1) {
  //       $this->db->where('fmbws.want_thali', '1');
  //     } else if ($data["thali_taken"] == 0) {
  //       $this->db->group_start()
  //         ->where('fmbws.want_thali !=', '1')
  //         ->or_where('fmbws.want_thali IS NULL', null, false)
  //         ->group_end();
  //     }
  //   }
  //   $this->db->order_by('u.Sector, u.Sub_Sector, u.Full_Name');

  //   return $this->db->get()->result_array();
  // }

  /* ================= HOF Aggregated Signup Methods (New) ================= */
  /**
   * Count of families (HOFs) who have at least one member wanting thali on a date
   * plus distinct delivery persons covering those families (based on HOF mapping/override)
   * Returns: [ 'hof_signup_count' => int, 'delivery_person_count' => int ]
   */
  public function getsignupcount_aggregated($greg_date)
  {
    if (empty($greg_date))
      return ['hof_signup_count' => 0, 'delivery_person_count' => 0];
    $date_esc = $this->db->escape($greg_date);
    $families_with_signup = "SELECT u.HOF_ID AS hof_id FROM fmb_weekly_signup fs JOIN user u ON u.ITS_ID = fs.user_id WHERE fs.signup_date = $date_esc AND fs.want_thali = 1 GROUP BY u.HOF_ID";
    $effective_dp_per_hof = "SELECT u.ITS_ID AS hof_id, COALESCE((SELECT do.dp_id FROM delivery_overrides do WHERE do.user_id = u.ITS_ID AND do.start_date <= $date_esc AND do.end_date >= $date_esc ORDER BY do.id DESC LIMIT 1),(SELECT dpm.dp_id FROM delivery_person_mapping dpm WHERE dpm.user_id = u.ITS_ID AND dpm.start_date <= $date_esc AND (dpm.end_date >= $date_esc OR dpm.end_date IS NULL) ORDER BY dpm.id DESC LIMIT 1)) AS effective_dp_id FROM user u WHERE u.HOF_FM_TYPE = 'HOF'";
    $sql = "SELECT COUNT(*) AS hof_signup_count, COUNT(DISTINCT edp.effective_dp_id) AS delivery_person_count FROM ($families_with_signup) f JOIN user hof ON hof.ITS_ID = f.hof_id LEFT JOIN ($effective_dp_per_hof) edp ON edp.hof_id = f.hof_id WHERE hof.Inactive_Status IS NULL AND hof.Sector IS NOT NULL";
    return $this->db->query($sql)->row_array();
  }

  /**
   * Aggregated signup listing at family (HOF) level.
   * Shows a single row per HOF if any member (including HOF) signed up.
   * Includes aggregated member IDs & thali sizes for those wanting thali.
   * Filters:
   *  - date (required)
   *  - thali_taken: 1 (only families wanting), 0 (only families not wanting)
   *  - reg_dp_id: filter by regular (mapping) dp id
   *  - sub_dp_id: filter by override dp id
   */
  public function getsignupforaday_aggregated($data)
  {
    if (empty($data['date']))
      return [];
    $date = $data['date'];
    // Member-level snapshot for date
    $member_signups_sql = "SELECT u.ITS_ID, u.HOF_ID, MAX(CASE WHEN fs.signup_date = '$date' THEN fs.want_thali END) AS want_thali, MAX(CASE WHEN fs.signup_date = '$date' THEN fs.thali_size END) AS thali_size FROM user u LEFT JOIN fmb_weekly_signup fs ON fs.user_id = u.ITS_ID AND fs.signup_date = '$date' WHERE u.Inactive_Status IS NULL AND u.Sector IS NOT NULL GROUP BY u.ITS_ID, u.HOF_ID";
    // Family aggregation
    $hof_agg_sql = "SELECT hof_u.ITS_ID AS HOF_ID, hof_u.Full_Name AS HOF_Name, hof_u.Sector, hof_u.Sub_Sector, MAX(ms.want_thali) AS family_want_thali, GROUP_CONCAT(CASE WHEN ms.want_thali = 1 THEN ms.ITS_ID END) AS member_ids_wanting, GROUP_CONCAT(CASE WHEN ms.want_thali = 1 THEN ms.thali_size END) AS member_thali_sizes FROM ($member_signups_sql) ms JOIN user hof_u ON hof_u.ITS_ID = ms.HOF_ID WHERE hof_u.HOF_FM_TYPE = 'HOF' AND hof_u.Inactive_Status IS NULL AND hof_u.Sector IS NOT NULL GROUP BY hof_u.ITS_ID, hof_u.Full_Name, hof_u.Sector, hof_u.Sub_Sector";
    // Latest override & mapping for each HOF
    $latest_override_sql = "SELECT do1.* FROM delivery_overrides do1 WHERE do1.start_date <= '$date' AND do1.end_date >= '$date' AND do1.id = (SELECT MAX(do2.id) FROM delivery_overrides do2 WHERE do2.user_id = do1.user_id AND do2.start_date <= '$date' AND do2.end_date >= '$date')";
    $latest_mapping_sql = "SELECT dpm1.* FROM delivery_person_mapping dpm1 WHERE dpm1.start_date <= '$date' AND (dpm1.end_date >= '$date' OR dpm1.end_date IS NULL) AND dpm1.id = (SELECT MAX(dpm2.id) FROM delivery_person_mapping dpm2 WHERE dpm2.user_id = dpm1.user_id AND dpm2.start_date <= '$date' AND (dpm2.end_date >= '$date' OR dpm2.end_date IS NULL))";
    $this->db->from("($hof_agg_sql) fam");
    $this->db->join("($latest_override_sql) do", "do.user_id = fam.HOF_ID", 'left');
    $this->db->join("($latest_mapping_sql) dpm", "dpm.user_id = fam.HOF_ID", 'left');
    $this->db->join('delivery_person dp_override', 'dp_override.id = do.dp_id', 'left');
    $this->db->join('delivery_person dp_mapping', 'dp_mapping.id = dpm.dp_id', 'left');
    $this->db->select("fam.HOF_ID AS ITS_ID, fam.HOF_Name AS Full_Name, fam.Sector, fam.Sub_Sector, fam.family_want_thali AS want_thali, fam.member_ids_wanting, fam.member_thali_sizes, dp_mapping.name AS delivery_person_name, dp_override.name AS sub_delivery_person_name", false);
    if (isset($data['thali_taken'])) {
      if ($data['thali_taken'] == 1) {
        $this->db->where('fam.family_want_thali', 1);
      } elseif ($data['thali_taken'] == 0) {
        $this->db->group_start()->where('fam.family_want_thali !=', 1)->or_where('fam.family_want_thali IS NULL', null, false)->group_end();
      }
    }
    if (!empty($data['reg_dp_id']) || !empty($data['sub_dp_id'])) {
      $this->db->group_start();
      if (!empty($data['reg_dp_id']))
        $this->db->where('dpm.dp_id', $data['reg_dp_id']);
      if (!empty($data['sub_dp_id']))
        $this->db->or_where('do.dp_id', $data['sub_dp_id']);
      $this->db->group_end();
    }
    $this->db->order_by('fam.Sector, fam.Sub_Sector, fam.HOF_Name');
    return $this->db->get()->result_array();
  }

  /**
   * Get monthly thaali stats for a given Hijri month/year.
   * Returns:
   *  [
   *    'families_signed_up' => int,
   *    'signed_hof_list'    => [hof rows...],
   *    'no_thaali_list'     => [hof rows...]
   *  ]
   */
  public function get_monthly_thaali_stats($hijri_month, $hijri_year)
  {
    if (empty($hijri_month) || empty($hijri_year))
      return ['families_signed_up' => 0, 'signed_hof_list' => [], 'no_thaali_list' => []];
    // Load HijriCalendar model (models can load other models)
    $this->load->model('HijriCalendar');
    $days = $this->HijriCalendar->get_hijri_days_for_month_year($hijri_month, $hijri_year);
    if (empty($days))
      return ['families_signed_up' => 0, 'signed_hof_list' => [], 'no_thaali_list' => []];

    $dates = array_map(function ($r) {
      return $r['greg_date'];
    }, $days);
    // Build safe quoted list for SQL IN
    $esc = array_map(function ($d) {
      return $this->db->escape($d);
    }, $dates);
    $inList = implode(',', $esc);

    // HOFs who signed for any date in the month
    $sql = "SELECT DISTINCT u.HOF_ID AS hof_id FROM fmb_weekly_signup fs JOIN user u ON u.ITS_ID = fs.user_id WHERE fs.signup_date IN ($inList) AND fs.want_thali = 1 AND u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.Sector IS NOT NULL";
    $rows = $this->db->query($sql)->result_array();
    $signedHofs = [];
    foreach ($rows as $r) {
      if (!empty($r['hof_id']))
        $signedHofs[$r['hof_id']] = true;
    }

    // Get all active HOF users with required fields for modal
    $allHofs = $this->db->select('ITS_ID, Full_Name, Sector, Sub_Sector, Mobile, HOF_FM_TYPE, Inactive_Status')
      ->from('user')
      ->where('HOF_FM_TYPE', 'HOF')
      ->where('Sector != ""', null, false)
      ->where("(Inactive_Status IS NULL OR Inactive_Status = '')", null, false)
      ->get()->result_array();
    $no = [];
    $yes = [];
    foreach ($allHofs as $h) {
      $its = isset($h['ITS_ID']) ? $h['ITS_ID'] : null;
      if (!$its)
        continue;
      if (!isset($signedHofs[$its])) {
        $no[] = $h;
      } else {
        $yes[] = $h;
      }
    }

    return [
      'families_signed_up' => count($signedHofs),
      'signed_hof_list' => $yes,
      'no_thaali_list' => $no
    ];
  }
  /* ================= End Aggregated Methods ================= */

  /**
   * Sector/Sub-sector wise HOF Thaali signup breakdown for a given Gregorian date (Y-m-d).
   * Returns an associative array with:
   * - breakdown: [ [sector, sub_sector, total_families, signed_up, not_signed, percent] ... ]
   * - totals: [ 'families' => int, 'signed_up' => int, 'not_signed' => int, 'percent' => float ]
   */
  public function get_thaali_signup_breakdown($date)
  {
    if (empty($date))
      $date = date('Y-m-d');
    $escDate = $this->db->escape($date);

    // Member-level snapshot for date (want_thali per member on date)
    $member_signups_sql = "
      SELECT u.ITS_ID, u.HOF_ID, MAX(CASE WHEN fs.signup_date = {$escDate} THEN fs.want_thali END) AS want_thali
      FROM user u
      LEFT JOIN fmb_weekly_signup fs ON fs.user_id = u.ITS_ID AND fs.signup_date = {$escDate}
      WHERE u.Inactive_Status IS NULL AND u.Sector IS NOT NULL
      GROUP BY u.ITS_ID, u.HOF_ID
    ";

    // Family-level aggregation (one row per HOF)
    $hof_agg_sql = "
      SELECT hof_u.ITS_ID AS HOF_ID, hof_u.Sector, hof_u.Sub_Sector,
             MAX(ms.want_thali) AS family_want_thali
      FROM ({$member_signups_sql}) ms
      JOIN user hof_u ON hof_u.ITS_ID = ms.HOF_ID
      WHERE hof_u.HOF_FM_TYPE = 'HOF' AND hof_u.Inactive_Status IS NULL AND hof_u.Sector IS NOT NULL
      GROUP BY hof_u.ITS_ID, hof_u.Sector, hof_u.Sub_Sector
    ";

    // Total families by sector/sub-sector (denominator)
    $families_all_sql = "
      SELECT u.Sector AS sector, u.Sub_Sector AS sub_sector, COUNT(*) AS total_families
      FROM user u
      WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.Sector IS NOT NULL
      GROUP BY u.Sector, u.Sub_Sector
    ";

    // Signed-up families by sector/sub-sector (numerator)
    $signed_up_sql = "
      SELECT fam.Sector AS sector, fam.Sub_Sector AS sub_sector,
             COUNT(*) AS families,
             SUM(CASE WHEN fam.family_want_thali = 1 THEN 1 ELSE 0 END) AS signed_up
      FROM ({$hof_agg_sql}) fam
      GROUP BY fam.Sector, fam.Sub_Sector
    ";

    // Combine totals and signed-up with LEFT JOIN (include sectors with zero signups)
    $sql = "
      SELECT fa.sector, fa.sub_sector, fa.total_families,
             COALESCE(su.signed_up, 0) AS signed_up
      FROM ({$families_all_sql}) fa
      LEFT JOIN ({$signed_up_sql}) su
        ON su.sector = fa.sector AND su.sub_sector = fa.sub_sector
      ORDER BY fa.sector ASC, fa.sub_sector ASC
    ";

    $rows = $this->db->query($sql)->result_array();
    $breakdown = [];
    $totals = ['families' => 0, 'signed_up' => 0, 'not_signed' => 0, 'percent' => 0.0];
    foreach ($rows as $r) {
      $families = (int) ($r['total_families'] ?? 0);
      $signed = (int) ($r['signed_up'] ?? 0);
      $not_signed = max($families - $signed, 0);
      $pct = $families > 0 ? round(($signed / $families) * 100, 2) : 0.0;
      $breakdown[] = [
        'sector' => (string) ($r['sector'] ?? ''),
        'sub_sector' => (string) ($r['sub_sector'] ?? ''),
        'total_families' => $families,
        'signed_up' => $signed,
        'not_signed' => $not_signed,
        'percent' => $pct,
      ];
      $totals['families'] += $families;
      $totals['signed_up'] += $signed;
      $totals['not_signed'] += $not_signed;
    }
    if ($totals['families'] > 0) {
      $totals['percent'] = round(($totals['signed_up'] / $totals['families']) * 100, 2);
    }
    return [
      'date' => $date,
      'breakdown' => $breakdown,
      'totals' => $totals,
    ];
  }

  /**
   * Date range variant: sector/sub-sector wise HOF signup breakdown for [start_date, end_date].
   */
  public function get_thaali_signup_breakdown_range($start_date, $end_date)
  {
    if (empty($start_date) && empty($end_date)) {
      $start_date = $end_date = date('Y-m-d');
    }
    if (empty($start_date))
      $start_date = $end_date;
    if (empty($end_date))
      $end_date = $start_date;
    // Swap if reversed
    if (strtotime($start_date) > strtotime($end_date)) {
      $tmp = $start_date;
      $start_date = $end_date;
      $end_date = $tmp;
    }
    $escStart = $this->db->escape($start_date);
    $escEnd = $this->db->escape($end_date);

    // Member-level snapshot for range (any day in range wanting thali)
    $member_signups_sql = "
      SELECT u.ITS_ID, u.HOF_ID,
             MAX(CASE WHEN fs.signup_date BETWEEN {$escStart} AND {$escEnd} THEN fs.want_thali END) AS want_thali
      FROM user u
      LEFT JOIN fmb_weekly_signup fs ON fs.user_id = u.ITS_ID AND fs.signup_date BETWEEN {$escStart} AND {$escEnd}
      WHERE u.Inactive_Status IS NULL AND u.Sector IS NOT NULL
      GROUP BY u.ITS_ID, u.HOF_ID
    ";

    // Family-level aggregation (one row per HOF)
    $hof_agg_sql = "
      SELECT hof_u.ITS_ID AS HOF_ID, hof_u.Sector, hof_u.Sub_Sector,
             MAX(ms.want_thali) AS family_want_thali
      FROM ({$member_signups_sql}) ms
      JOIN user hof_u ON hof_u.ITS_ID = ms.HOF_ID
      WHERE hof_u.HOF_FM_TYPE = 'HOF' AND hof_u.Inactive_Status IS NULL AND hof_u.Sector IS NOT NULL
      GROUP BY hof_u.ITS_ID, hof_u.Sector, hof_u.Sub_Sector
    ";

    // Total families by sector/sub-sector
    $families_all_sql = "
      SELECT u.Sector AS sector, u.Sub_Sector AS sub_sector, COUNT(*) AS total_families
      FROM user u
      WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.Sector IS NOT NULL
      GROUP BY u.Sector, u.Sub_Sector
    ";

    // Signed-up families in range by sector/sub-sector
    $signed_up_sql = "
      SELECT fam.Sector AS sector, fam.Sub_Sector AS sub_sector,
             COUNT(*) AS families,
             SUM(CASE WHEN fam.family_want_thali = 1 THEN 1 ELSE 0 END) AS signed_up
      FROM ({$hof_agg_sql}) fam
      GROUP BY fam.Sector, fam.Sub_Sector
    ";

    $sql = "
      SELECT fa.sector, fa.sub_sector, fa.total_families,
             COALESCE(su.signed_up, 0) AS signed_up
      FROM ({$families_all_sql}) fa
      LEFT JOIN ({$signed_up_sql}) su
        ON su.sector = fa.sector AND su.sub_sector = fa.sub_sector
      ORDER BY fa.sector ASC, fa.sub_sector ASC
    ";

    $rows = $this->db->query($sql)->result_array();
    $breakdown = [];
    $totals = ['families' => 0, 'signed_up' => 0, 'not_signed' => 0, 'percent' => 0.0];
    foreach ($rows as $r) {
      $families = (int) ($r['total_families'] ?? 0);
      $signed = (int) ($r['signed_up'] ?? 0);
      $not_signed = max($families - $signed, 0);
      $pct = $families > 0 ? round(($signed / $families) * 100, 2) : 0.0;
      $breakdown[] = [
        'sector' => (string) ($r['sector'] ?? ''),
        'sub_sector' => (string) ($r['sub_sector'] ?? ''),
        'total_families' => $families,
        'signed_up' => $signed,
        'not_signed' => $not_signed,
        'percent' => $pct,
      ];
      $totals['families'] += $families;
      $totals['signed_up'] += $signed;
      $totals['not_signed'] += $not_signed;
    }
    if ($totals['families'] > 0) {
      $totals['percent'] = round(($totals['signed_up'] / $totals['families']) * 100, 2);
    }
    return [
      'start_date' => $start_date,
      'end_date' => $end_date,
      'breakdown' => $breakdown,
      'totals' => $totals,
    ];
  }


  public function get_thaali_signup_breakdown_by_sector($date, $sector)
  {
    if (empty($date))
      $date = date('Y-m-d');
    if (empty($sector))
      return ['date' => $date, 'breakdown' => [], 'totals' => []];

    $escDate = $this->db->escape($date);
    $escSector = $this->db->escape($sector);

    // ✅ SAME AS REFERENCE (ONLY sector filter added)
    $member_signups_sql = "
      SELECT u.ITS_ID, u.HOF_ID,
             MAX(CASE WHEN fs.signup_date = {$escDate} THEN fs.want_thali END) AS want_thali
      FROM user u
      LEFT JOIN fmb_weekly_signup fs
        ON fs.user_id = u.ITS_ID AND fs.signup_date = {$escDate}
      WHERE u.Inactive_Status IS NULL
        AND u.Sector = {$escSector}
      GROUP BY u.ITS_ID, u.HOF_ID
    ";

    // ✅ SAME AGGREGATION PATH
    $hof_agg_sql = "
      SELECT hof_u.ITS_ID AS HOF_ID,
             hof_u.Sector,
             hof_u.Sub_Sector,
             MAX(ms.want_thali) AS family_want_thali
      FROM ({$member_signups_sql}) ms
      JOIN user hof_u ON hof_u.ITS_ID = ms.HOF_ID
      WHERE hof_u.HOF_FM_TYPE = 'HOF'
        AND hof_u.Inactive_Status IS NULL
        AND hof_u.Sector = {$escSector}
      GROUP BY hof_u.ITS_ID, hof_u.Sector, hof_u.Sub_Sector
    ";

    // Total families per sub-sector (DENOMINATOR)
    $families_sql = "
      SELECT Sub_Sector AS sub_sector, COUNT(*) AS total_families
      FROM user
      WHERE HOF_FM_TYPE='HOF'
        AND Inactive_Status IS NULL
        AND Sector = {$escSector}
      GROUP BY Sub_Sector
    ";

    // Signed-up families per sub-sector (NUMERATOR)
    $signed_sql = "
      SELECT Sub_Sector AS sub_sector,
             SUM(CASE WHEN family_want_thali = 1 THEN 1 ELSE 0 END) AS signed_up
      FROM ({$hof_agg_sql}) x
      GROUP BY Sub_Sector
    ";

    $sql = "
      SELECT f.sub_sector, f.total_families,
             COALESCE(s.signed_up,0) AS signed_up
      FROM ({$families_sql}) f
      LEFT JOIN ({$signed_sql}) s
        ON s.sub_sector = f.sub_sector
      ORDER BY f.sub_sector ASC
    ";

    $rows = $this->db->query($sql)->result_array();

    $breakdown = [];
    $totals = ['families' => 0, 'signed_up' => 0, 'not_signed' => 0, 'percent' => 0];

    foreach ($rows as $r) {
      $fam = (int) $r['total_families'];
      $sig = (int) $r['signed_up'];
      $not = max($fam - $sig, 0);

      $breakdown[] = [
        'sector' => $sector,
        'sub_sector' => $r['sub_sector'],
        'total_families' => $fam,
        'signed_up' => $sig,
        'not_signed' => $not,
        'percent' => $fam ? round(($sig / $fam) * 100, 2) : 0
      ];

      $totals['families'] += $fam;
      $totals['signed_up'] += $sig;
      $totals['not_signed'] += $not;
    }

    if ($totals['families'] > 0) {
      $totals['percent'] = round(($totals['signed_up'] / $totals['families']) * 100, 2);
    }

    return [
      'date' => $date,
      'breakdown' => $breakdown,
      'totals' => $totals
    ];
  }





  public function get_thaali_signup_breakdown_range_by_sector($start_date, $end_date, $sector)
  {
    if (empty($sector))
      return ['breakdown' => [], 'totals' => []];

    if (empty($start_date))
      $start_date = $end_date;
    if (empty($end_date))
      $end_date = $start_date;

    $escStart = $this->db->escape($start_date);
    $escEnd = $this->db->escape($end_date);
    $escSector = $this->db->escape($sector);

    $member_sql = "
      SELECT u.ITS_ID, u.HOF_ID,
             MAX(CASE WHEN fs.signup_date BETWEEN {$escStart} AND {$escEnd}
                      THEN fs.want_thali END) AS want_thali
      FROM user u
      LEFT JOIN fmb_weekly_signup fs
        ON fs.user_id = u.ITS_ID
       AND fs.signup_date BETWEEN {$escStart} AND {$escEnd}
      WHERE u.Inactive_Status IS NULL
        AND u.Sector = {$escSector}
      GROUP BY u.ITS_ID, u.HOF_ID
    ";

    $hof_sql = "
      SELECT hof_u.Sub_Sector,
             MAX(ms.want_thali) AS family_want_thali
      FROM ({$member_sql}) ms
      JOIN user hof_u ON hof_u.ITS_ID = ms.HOF_ID
      WHERE hof_u.HOF_FM_TYPE = 'HOF'
        AND hof_u.Inactive_Status IS NULL
        AND hof_u.Sector = {$escSector}
      GROUP BY hof_u.Sub_Sector
    ";

    $families_sql = "
      SELECT u.Sub_Sector AS sub_sector, COUNT(*) AS total_families
      FROM user u
      WHERE u.HOF_FM_TYPE = 'HOF'
        AND u.Inactive_Status IS NULL
        AND u.Sector = {$escSector}
      GROUP BY u.Sub_Sector
    ";

    $signed_sql = "
      SELECT Sub_Sector AS sub_sector,
             SUM(CASE WHEN family_want_thali = 1 THEN 1 ELSE 0 END) AS signed_up
      FROM ({$hof_sql}) x
      GROUP BY Sub_Sector
    ";

    $sql = "
      SELECT f.sub_sector, f.total_families,
             COALESCE(s.signed_up,0) AS signed_up
      FROM ({$families_sql}) f
      LEFT JOIN ({$signed_sql}) s
        ON s.sub_sector = f.sub_sector
      ORDER BY f.sub_sector ASC
    ";

    $rows = $this->db->query($sql)->result_array();

    $breakdown = [];
    $totals = ['families' => 0, 'signed_up' => 0, 'not_signed' => 0, 'percent' => 0];

    foreach ($rows as $r) {
      $families = (int) $r['total_families'];
      $signed = (int) $r['signed_up'];
      $not = max($families - $signed, 0);
      $pct = $families > 0 ? round(($signed / $families) * 100, 2) : 0;

      $breakdown[] = [
        'sector' => $sector,
        'sub_sector' => $r['sub_sector'],
        'total_families' => $families,
        'signed_up' => $signed,
        'not_signed' => $not,
        'percent' => $pct
      ];

      $totals['families'] += $families;
      $totals['signed_up'] += $signed;
      $totals['not_signed'] += $not;
    }

    if ($totals['families'] > 0) {
      $totals['percent'] = round(($totals['signed_up'] / $totals['families']) * 100, 2);
    }

    return [
      'start_date' => $start_date,
      'end_date' => $end_date,
      'breakdown' => $breakdown,
      'totals' => $totals
    ];
  }


  /**
   * Get comprehensive Takhmeen summary
   * Includes:
   * - Mohallah-wise breakdown (Sabeel + Thaali)
   * - Total Thaali Takhmeen amount
   * - Grade-wise Mumineen count
   * - Total Outstanding Sabeel amount
   * - Total Sabeel Takhmeen amount
   */
  public function get_sabeel_takhmeen_reports($year = null, $include_allocations = false)
  {
    $summary = [];

    $current_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"));
    if ($current_hijri_date) {
      $current_hijri_date = $current_hijri_date["hijri_date"];
      $current_hijri_month = explode("-", $current_hijri_date)[1];
      $current_hijri_year = explode("-", $current_hijri_date)[2];
    } else {
      $current_hijri_date = null;
      $current_hijri_month = null;
      $current_hijri_year = null;
    }

    if (isset($year) && !empty($year)) {
      $takhmeen_year_current = $year . "-" . substr($year + 1, -2);
    } else {
      if ($current_hijri_month >= "01" && $current_hijri_month <= "06") {
        $y1 = $current_hijri_year - 1;
        $y2 = substr($current_hijri_year, -2);
        $takhmeen_year_current = "$y1-$y2";
      } else if ($current_hijri_month >= "07" && $current_hijri_month <= "12") {
        $y1 = $current_hijri_year;
        $y2 = substr($current_hijri_year + 1, -2);
        $takhmeen_year_current = "$y1-$y2";
      }
    }
    /** -------------------------------
     * 1️⃣ Grade-wise breakdown (sum of establishment + residential amounts)
     * ------------------------------- */
    $this->db->select('COALESCE(est.grade, "Unknown") AS grade,
            SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)) AS sabeel_total,
            COUNT(DISTINCT st.user_id) AS member_count')
      ->from('sabeel_takhmeen st')
      ->join('sabeel_takhmeen_grade est', 'est.id = st.establishment_grade', 'left')
      ->join('sabeel_takhmeen_grade res', 'res.id = st.residential_grade', 'left')
    ;

    $this->db->where('st.year', $takhmeen_year_current);
    $this->db
      ->group_by('grade')
      ->order_by('grade', 'ASC');
    $summary['grade_wise_breakdown'] = $this->db->get()->result_array();

    /** -------------------------------
     * 2️⃣ Establishment Grade-wise breakdown (est.amount only)
     * ------------------------------- */
    $this->db->select('COALESCE(est.grade, "Unknown") AS grade,
            SUM(COALESCE(est.amount,0)) AS est_total,
            COUNT(DISTINCT st.user_id) AS member_count')
      ->from('sabeel_takhmeen st')
      ->join('sabeel_takhmeen_grade est', 'est.id = st.establishment_grade', 'left')
      ->join('sabeel_takhmeen_grade res', 'res.id = st.residential_grade', 'left');

    $this->db->where('st.year', $takhmeen_year_current);
    $this->db
      ->group_by('grade')
      ->order_by('grade', 'ASC');
    $summary['establishment_grade_breakdown'] = $this->db->get()->result_array();

    /** -------------------------------
     * 2️⃣b Residential Grade-wise breakdown (res.yearly_amount only)
     * ------------------------------- */
    $this->db->select('COALESCE(res.grade, "Unknown") AS grade,
            SUM(COALESCE(res.yearly_amount,0)) AS res_total,
            COUNT(DISTINCT st.user_id) AS member_count')
      ->from('sabeel_takhmeen st')
      ->join('sabeel_takhmeen_grade est', 'est.id = st.establishment_grade', 'left')
      ->join('sabeel_takhmeen_grade res', 'res.id = st.residential_grade', 'left');

    $this->db->where('st.year', $takhmeen_year_current);
    $this->db
      ->group_by('grade')
      ->order_by('grade', 'ASC');
    $summary['residential_grade_breakdown'] = $this->db->get()->result_array();

    /** -------------------------------
     * 3️⃣ Grade-wise Mumineen count
     * ------------------------------- */
    $this->db->select('g.grade, COUNT(st.id) as count')
      ->from('sabeel_takhmeen st')
      ->join('sabeel_takhmeen_grade g', 'g.id = st.establishment_grade', 'left');

    $this->db->where('st.year', $takhmeen_year_current);
    $this->db->group_by('g.grade');
    $query = $this->db->get();
    $summary['grade_wise_mumineen_count'] = $query->result_array();

    /** -------------------------------
     * 3️⃣b Sector-wise breakdown (include all sectors; zero when no takhmeen)
     * ------------------------------- */
    $sector_list_sql = "SELECT DISTINCT u.Sector AS sector
      FROM user u
      WHERE u.Sector IS NOT NULL AND TRIM(u.Sector) <> ''";

    $agg_sql = "SELECT 
        u.Sector AS sector,
        SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)) AS sector_total,
        COUNT(DISTINCT st.user_id) AS member_count
      FROM sabeel_takhmeen st
      LEFT JOIN user u ON u.ITS_ID = st.user_id
      LEFT JOIN sabeel_takhmeen_grade est ON est.id = st.establishment_grade
      LEFT JOIN sabeel_takhmeen_grade res ON res.id = st.residential_grade
      WHERE st.year = ?
      GROUP BY u.Sector";

    $sql = "SELECT s.sector,
              COALESCE(a.sector_total, 0) AS sector_total,
              COALESCE(a.member_count, 0) AS member_count
            FROM (" . $sector_list_sql . ") s
            LEFT JOIN (" . $agg_sql . ") a ON a.sector = s.sector
            ORDER BY s.sector ASC";

    $summary['sector_breakdown'] = $this->db->query($sql, [$takhmeen_year_current])->result_array();

    // Compute per-user allocation for the selected year (FIFO across years)
    // so we can derive per-sector paid and due for that year.
    $per_user_years = $this->db->query(
      "SELECT st.user_id, st.year, SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)) AS total,
              CAST(SUBSTRING_INDEX(st.year, '-', 1) AS UNSIGNED) AS yr_start
         FROM sabeel_takhmeen st
         LEFT JOIN sabeel_takhmeen_grade est ON est.id = st.establishment_grade
         LEFT JOIN sabeel_takhmeen_grade res ON res.id = st.residential_grade
        GROUP BY st.user_id, st.year
        ORDER BY st.user_id ASC, yr_start ASC"
    )->result_array();

    // Total paid per user (all years)
    $paid_by_user = $this->db->query(
      "SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments GROUP BY user_id"
    )->result_array();

    $paid_map = [];
    foreach ($paid_by_user as $r) {
      $paid_map[$r['user_id']] = (float) ($r['total_paid'] ?? 0);
    }

    // Build map user => years[]
    $user_years = [];
    foreach ($per_user_years as $r) {
      $uid = $r['user_id'];
      if (!isset($user_years[$uid]))
        $user_years[$uid] = [];
      $user_years[$uid][] = [
        'year' => $r['year'],
        'yr_start' => (int) $r['yr_start'],
        'total' => (float) ($r['total'] ?? 0),
      ];
    }

    // Allocate per user and record per-user paid/due for the selected year
    $user_allocations = []; // user_id => ['total'=>..., 'paid'=>..., 'due'=>...]
    foreach ($user_years as $uid => $rows) {
      usort($rows, function ($a, $b) {
        return $a['yr_start'] <=> $b['yr_start'];
      });
      $remain = $paid_map[$uid] ?? 0.0;
      foreach ($rows as $row) {
        $alloc = min($row['total'], $remain);
        $due = max(0.0, $row['total'] - $alloc);
        if ($row['year'] === $takhmeen_year_current) {
          $user_allocations[$uid] = ['total' => $row['total'], 'paid' => $alloc, 'due' => $due];
        }
        $remain -= $alloc;
        if ($remain <= 0)
          $remain = 0;
      }
      if (!isset($user_allocations[$uid])) {
        $user_allocations[$uid] = ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
      }
    }

    // Recompute year-scoped paid and outstanding Sabeel amount from per-user allocations
    $total_due_from_alloc = 0.0;
    $total_paid_from_alloc = 0.0;
    foreach ($user_allocations as $a) {
      $total_due_from_alloc += (float) ($a['due'] ?? 0.0);
      $total_paid_from_alloc += (float) ($a['paid'] ?? 0.0);
    }
    // Year-scoped amounts (for the selected hijri year)
    $summary['year_paid_sabeel_amount'] = $total_paid_from_alloc;
    $summary['year_outstanding_sabeel_amount'] = $total_due_from_alloc;

    // Build sector-wise aggregates for paid and due using the allocations
    $sector_map = [];
    $user_ids = array_keys($user_years);
    if (!empty($user_ids)) {
      $in_ids = implode(',', array_map(function ($id) {
        return $this->db->escape($id);
      }, $user_ids));
      $users_sectors = $this->db->query("SELECT ITS_ID AS user_id, COALESCE(Sector, 'Unknown') AS sector FROM user WHERE ITS_ID IN ($in_ids)")->result_array();
      $user_sector_map = [];
      foreach ($users_sectors as $urow) {
        $user_sector_map[$urow['user_id']] = $urow['sector'];
      }

      foreach ($user_allocations as $uid => $alloc) {
        $sector = isset($user_sector_map[$uid]) ? trim((string) $user_sector_map[$uid]) : '';
        if ($sector === '' || strcasecmp($sector, 'unknown') === 0) {
          continue;
        }
        if (!isset($sector_map[$sector])) {
          $sector_map[$sector] = [
            'sector' => $sector,
            'sector_total' => 0.0,
            'sector_paid' => 0.0,
            'sector_due' => 0.0,
            'total_takhmeen' => 0.0,
            'total_paid' => 0.0,
            'outstanding' => 0.0,
            'member_count' => 0
          ];
        }
        $sector_map[$sector]['sector_total'] += (float) $alloc['total'];
        $sector_map[$sector]['sector_paid'] += (float) $alloc['paid'];
        $sector_map[$sector]['sector_due'] += (float) $alloc['due'];
        $sector_map[$sector]['total_takhmeen'] += (float) $alloc['total'];
        $sector_map[$sector]['total_paid'] += (float) $alloc['paid'];
        $sector_map[$sector]['outstanding'] += (float) $alloc['due'];
        $sector_map[$sector]['member_count'] += 1;
      }
    }

    // Replace sector_breakdown with enriched data (ordered by sector)
    if (!empty($sector_map)) {
      ksort($sector_map);
      $summary['sector_breakdown'] = array_values($sector_map);
    }

    // ----- Build establishment & residential grade aggregates (paid/due)
    // Fetch user -> grade mapping for the selected year
    $est_map = [];
    $res_map = [];
    $user_grade_rows = $this->db->query(
      "SELECT st.user_id, COALESCE(est.grade, 'Unknown') AS est_grade, COALESCE(res.grade, 'Unknown') AS res_grade
         FROM sabeel_takhmeen st
         LEFT JOIN sabeel_takhmeen_grade est ON est.id = st.establishment_grade
         LEFT JOIN sabeel_takhmeen_grade res ON res.id = st.residential_grade
        WHERE st.year = ?",
      [$takhmeen_year_current]
    )->result_array();

    foreach ($user_grade_rows as $ug) {
      $uid = $ug['user_id'];
      $estg = trim((string) ($ug['est_grade'] ?? '')) ?: 'Unknown';
      $resg = trim((string) ($ug['res_grade'] ?? '')) ?: 'Unknown';
      $alloc = $user_allocations[$uid] ?? ['total' => 0.0, 'paid' => 0.0, 'due' => 0.0];

      if ($estg !== '' && strcasecmp($estg, 'unknown') !== 0) {
        if (!isset($est_map[$estg]))
          $est_map[$estg] = ['grade' => $estg, 'est_total' => 0.0, 'est_paid' => 0.0, 'est_due' => 0.0, 'member_count' => 0];
        $est_map[$estg]['est_total'] += (float) $alloc['total'];
        $est_map[$estg]['est_paid'] += (float) $alloc['paid'];
        $est_map[$estg]['est_due'] += (float) $alloc['due'];
        $est_map[$estg]['member_count'] += 1;
      }

      if ($resg !== '' && strcasecmp($resg, 'unknown') !== 0) {
        if (!isset($res_map[$resg]))
          $res_map[$resg] = ['grade' => $resg, 'res_total' => 0.0, 'res_paid' => 0.0, 'res_due' => 0.0, 'member_count' => 0];
        $res_map[$resg]['res_total'] += (float) $alloc['total'];
        $res_map[$resg]['res_paid'] += (float) $alloc['paid'];
        $res_map[$resg]['res_due'] += (float) $alloc['due'];
        $res_map[$resg]['member_count'] += 1;
      }
    }

    if (!empty($est_map)) {
      ksort($est_map);
      $summary['establishment_grade_breakdown'] = array_values($est_map);
    }
    if (!empty($res_map)) {
      ksort($res_map);
      $summary['residential_grade_breakdown'] = array_values($res_map);
    }

    /** -------------------------------
     * 4️⃣ Total Outstanding Sabeel amount (cumulative across all years)
     * ------------------------------- */
    // Compute cumulative outstanding across all years: per-user total takhmeen (all years) minus total paid
    $due_sql = "SELECT SUM(GREATEST(user_due, 0)) AS total_due FROM (
      SELECT st.user_id,
        SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)) AS total_takhmeen,
        COALESCE(paid.total_paid,0) AS total_paid,
        SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)) - COALESCE(paid.total_paid,0) AS user_due
      FROM sabeel_takhmeen st
      LEFT JOIN sabeel_takhmeen_grade est ON est.id = st.establishment_grade
      LEFT JOIN sabeel_takhmeen_grade res ON res.id = st.residential_grade
      LEFT JOIN (
        SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments GROUP BY user_id
      ) paid ON paid.user_id = st.user_id
      GROUP BY st.user_id
    ) AS user_dues";
    $row = $this->db->query($due_sql)->row_array();
    // Cumulative outstanding (all years)
    $summary['total_outstanding_sabeel_amount'] = (float) ($row['total_due'] ?? 0);

    /** -------------------------------
     * 5️⃣ Total Sabeel Takhmeen amount (sum of all grade amounts)
     * ------------------------------- */
    // Total for the selected takhmeen year
    $this->db->select('SUM(COALESCE(est.amount,0) + COALESCE(res.yearly_amount,0)) as total_sabeel')
      ->from('sabeel_takhmeen st')
      ->join('sabeel_takhmeen_grade est', 'est.id = st.establishment_grade', 'left')
      ->join('sabeel_takhmeen_grade res', 'res.id = st.residential_grade', 'left')
      ->where('st.year', $takhmeen_year_current);
    $row = $this->db->get()->row_array();
    $summary['total_sabeel_takhmeen_amount'] = (float) ($row['total_sabeel'] ?? 0);

    if ($include_allocations) {
      // Expose debug information to help validate allocations/payments
      $summary['_debug'] = [
        'paid_map' => $paid_map ?? [],
        'user_allocations' => $user_allocations ?? [],
        'per_user_years' => $per_user_years ?? []
      ];
    }

    return $summary;
  }







  private function parse_username_scope($username)
  {
    $sector = '';
    $sub = '';

    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $m)) {
      $sector = ucfirst(strtolower($m[1]));
      $sub = strtoupper($m[2] ?? '');
    }

    return [
      'sector' => $sector,
      'sub_sector' => $sub
    ];
  }

  public function get_weekly_thaali_by_username($username, $start_date, $end_date)
  {
    log_message('debug', '[CommonM] Weekly by username: ' . $username);

    $scope = $this->parse_username_scope($username);
    $sector = $scope['sector'];
    $sub = $scope['sub_sector'];

    if ($sector === '') {
      log_message('error', '[CommonM] Invalid username scope');
      return [
        'items' => [],
        'scope' => $scope,
        'days' => 0
      ];
    }

    $days = 7;
    $bySub = [];

    $cursor = strtotime($start_date);
    $endTs = strtotime($end_date);

    while ($cursor <= $endTs) {
      $date = date('Y-m-d', $cursor);
      $bd = $this->get_thaali_signup_breakdown($date);

      foreach ($bd['breakdown'] ?? [] as $r) {

        if (strcasecmp($r['sector'], $sector) !== 0)
          continue;
        if ($sub !== '' && strcasecmp($r['sub_sector'], $sub) !== 0)
          continue;

        $key = $r['sector'] . '||' . $r['sub_sector'];

        if (!isset($bySub[$key])) {
          $bySub[$key] = [
            'sector' => $r['sector'],
            'sub_sector' => $r['sub_sector'],
            'total' => 0
          ];
        }

        $bySub[$key]['total'] += (int) $r['signed_up'];
      }

      $cursor = strtotime('+1 day', $cursor);
    }

    $items = [];
    foreach ($bySub as $v) {
      $items[] = [
        'sector' => $v['sector'],
        'sub_sector' => $v['sub_sector'],
        'total' => $v['total'],
        'avg' => round($v['total'] / $days, 2)
      ];
    }

    usort($items, function ($a, $b) {
      return ($b['total'] ?? 0) <=> ($a['total'] ?? 0);
    });

    return [
      'start' => $start_date,
      'end' => $end_date,
      'days' => $days,
      'items' => $items,
      'scope' => $scope
    ];
  }

  public function get_monthly_thaali_by_username($username, $hijri_month, $hijri_year)
  {
    log_message('debug', '[CommonM] Monthly by username: ' . $username);

    $scope = $this->parse_username_scope($username);
    $sector = $scope['sector'];
    $sub = $scope['sub_sector'];

    if ($sector === '')
      return [];

    $this->load->model('HijriCalendar');
    $days = $this->HijriCalendar
      ->get_hijri_days_for_month_year($hijri_month, $hijri_year);

    if (empty($days))
      return [];

    $start = $days[0]['greg_date'];
    $end = $days[count($days) - 1]['greg_date'];

    $range = $this->get_thaali_signup_breakdown_range($start, $end);

    $signed = 0;
    $no = 0;

    foreach ($range['breakdown'] ?? [] as $r) {

      if (strcasecmp($r['sector'], $sector) !== 0)
        continue;
      if ($sub !== '' && strcasecmp($r['sub_sector'], $sub) !== 0)
        continue;

      $signed += (int) $r['signed_up'];
      $no += (int) $r['not_signed'];
    }

    return [
      'families_signed_up' => $signed,
      'no_thaali_count' => $no,
      'start' => $start,
      'end' => $end,
      'scope' => $scope
    ];
  }
}
