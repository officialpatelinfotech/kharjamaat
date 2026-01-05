<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class AccountM extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }
  function check_user($username, $password)
  {
    $sql = "SELECT * FROM `login` WHERE `username` = ? AND `password` = ? AND `active` = 1";

    $query = $this->db->query($sql, array($username, $password));

    return $query->result_array();
  }
  function check_user_exist($username)
  {
    $sql = "SELECT * FROM `user` WHERE `ITS_ID` = ? ";

    $query = $this->db->query($sql, array($username));

    return $query->result_array();
  }
  public function change_password_to_default($its_id, $password)
  {
    $data = array(
      'password' => md5($password)
    );

    $this->db->where('username', $its_id);
    $this->db->update('login', $data);
    return $this->db->affected_rows() > 0;
  }
  public function get_all_family_member($user_id)
  {
    $sql = "SELECT * FROM user WHERE HOF_ID = $user_id";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  // Updated by Patel Infotech Services
  public function get_assigned_miqaats_count($user_id)
  {
    // include family members (HOF children) along with the user
    $memberIds = [$user_id];
    $family = $this->get_all_family_member($user_id);
    if (!empty($family)) {
      foreach ($family as $f) {
        if (!empty($f['ITS_ID'])) $memberIds[] = $f['ITS_ID'];
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    $escaped_user = $this->db->escape($user_id);
    $escaped_list = array_map(array($this->db, 'escape'), $memberIds);
    $in_list = implode(',', $escaped_list);

    $today = date('Y-m-d');
    $this->db->select('COUNT(DISTINCT ma.miqaat_id) as pending_cnt', FALSE);
    $this->db->from('miqaat_assignments ma');
    // only count upcoming miqaats (miqaat date on or after today)
    $this->db->join('miqaat m', 'm.id = ma.miqaat_id', 'inner');
    $this->db->where('m.date >=', $today);
    // keep raza check for current user only (r.user_id = current user)
    $this->db->join('raza r', 'r.miqaat_id = ma.miqaat_id AND r.user_id = ' . $escaped_user, 'left');
    $this->db->where('((ma.assign_type = "Individual" AND ma.member_id IN (' . $in_list . ')) OR (ma.assign_type = "Group" AND ma.group_leader_id IN (' . $in_list . ')))', NULL, FALSE);
    $this->db->where('r.id IS NULL', NULL, FALSE);
    $row = $this->db->get()->row();
    return $row ? (int)$row->pending_cnt : 0;
  }

  public function get_assigned_miqaats($user_id)
  {
    // Include assignments for the user and their family (HOF children)
    $memberIds = [$user_id];
    $family = $this->get_all_family_member($user_id);
    if (!empty($family)) {
      foreach ($family as $f) {
        if (!empty($f['ITS_ID'])) $memberIds[] = $f['ITS_ID'];
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    // Get miqaat IDs where user or any family member is assigned as individual or group leader
    $this->db->select('miqaat_id');
    $this->db->from('miqaat_assignments');
    // Note: do not prefix columns with alias here since the table isn't aliased in from()
    $this->db->where('(assign_type = "Individual" AND member_id IN (' . implode(',', array_map(array($this->db, 'escape'), $memberIds)) . '))', NULL, FALSE);
    $this->db->or_where('(assign_type = "Group" AND group_leader_id IN (' . implode(',', array_map(array($this->db, 'escape'), $memberIds)) . '))', NULL, FALSE);
    $query = $this->db->get();
    $miqaat_ids = array_unique(array_column($query->result_array(), 'miqaat_id'));
    if (empty($miqaat_ids)) return [];
    // Fetch all miqaats with those IDs
    $this->db->select('*');
    $this->db->from('miqaat');
    $this->db->where_in('id', $miqaat_ids);
    $this->db->order_by('date', 'ASC');
    $miqaats = $this->db->get()->result_array();

    // Fetch assignments for these miqaats, joining user table for member, group leader, and co-leader names
    $assignments = [];
    if (!empty($miqaat_ids)) {
      $this->db->select('ma.*, 
        u1.Full_Name as member_name, 
        u2.Full_Name as group_leader_name,
        u1.ITS_ID as member_id, u2.ITS_ID as group_leader_id');
      $this->db->from('miqaat_assignments ma');
      $this->db->join('user u1', 'u1.ITS_ID = ma.member_id', 'left');
      $this->db->join('user u2', 'u2.ITS_ID = ma.group_leader_id', 'left');
      $this->db->where_in('ma.miqaat_id', $miqaat_ids);
      $assignments_result = $this->db->get()->result_array();
      // Group assignments by miqaat_id
      foreach ($assignments_result as $row) {
        $assignments[$row['miqaat_id']][] = $row;
      }
    }

    // Attach assignments to each miqaat
    foreach ($miqaats as &$miqaat) {
      $miqaat['assignments'] = isset($assignments[$miqaat['id']]) ? $assignments[$miqaat['id']] : [];
    }
    unset($miqaat);
    return $miqaats;
  }

  public function get_raza_by_miqaat($miqaat_id, $user_id)
  {
    $this->db->from("raza");
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->where('user_id', $user_id);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      return $result->row_array();
    }
    return null;
  }

  public function submit_miqaat_raza($data)
  {
    $user_id = $data['user_id'];
    $miqaat_id = $data['miqaat_id'];

    $this->db->where('user_id', $user_id);
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->from("raza");
    $existing = $this->db->get()->result_array();
    if ($existing) {
      return -1;
    }

    $this->db->insert('raza', $data);
    return $this->db->insert_id();
  }

  public function generate_raza_id($year)
  {

    $this->db->select('raza_id');
    $this->db->like('raza_id', $year . '-', 'after');
    $this->db->order_by('id', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('raza');

    if ($query->num_rows() > 0) {
      $last_raza = $query->row()->raza_id;
      $parts = explode('-', $last_raza);
      $next_number = intval($parts[1]) + 1;
    } else {
      $next_number = 1;
    }

    return $year . '-' . $next_number;
  }

  public function update_raza_by_id($raza_id, $data)
  {
    $this->db->where('id', $raza_id);
    $this->db->update('raza', $data);
    return $this->db->affected_rows() > 0;
  }

  public function get_member_total_fmb_due($user_id)
  {
    $user_id = (int) $user_id;

    // ✅ Get the latest takhmeen row for this user
    $latest = $this->db->select('ft.year, ft.total_amount')
      ->from('fmb_takhmeen ft')
      ->where('ft.user_id', $user_id)
      ->order_by('ft.year', 'DESC')
      ->limit(1)
      ->get()
      ->row_array();

    if (!$latest) return null;

    // ✅ Get overall takhmeen total (avoid duplication by NOT joining payments here)
    $takhmeen = $this->db->select("COALESCE(SUM(total_amount),0) as total_amount")
      ->from('fmb_takhmeen')
      ->where('user_id', $user_id)
      ->get()
      ->row_array();

    // ✅ Get total payments separately
    $payments = $this->db->select("COALESCE(SUM(amount),0) as total_paid")
      ->from('fmb_takhmeen_payments')
      ->where('user_id', $user_id)
      ->get()
      ->row_array();

    $total_amount = (float)($takhmeen['total_amount'] ?? 0);
    $total_paid_raw = (float)($payments['total_paid'] ?? 0);
    $excess = 0.0;
    if ($total_paid_raw > $total_amount) {
      $excess = $total_paid_raw - $total_amount;
    }
    $total_paid = min($total_paid_raw, $total_amount);

    $result = [
      'year'         => $latest['year'],
      'total_amount' => $total_amount,
      'total_paid'   => $total_paid,
      'total_due'    => $total_amount - $total_paid,
    ];
    if ($excess > 0) {
      $result['excess_paid'] = $excess;
    }
    return $result;
  }

  /**
   * Get total outstanding amount for FMB General Contribution invoices for a user
   * Returns a float representing total due (>= 0)
   */
  public function get_member_total_general_contrib_due($user_id)
  {
    $user_id = (int) $user_id;
    $sql = "SELECT COALESCE(SUM(gc.amount - COALESCE(p.total_received,0)),0) AS total_due
            FROM fmb_general_contribution gc
            LEFT JOIN (SELECT fmbgc_id, SUM(amount) AS total_received FROM fmb_general_contribution_payments GROUP BY fmbgc_id) p ON p.fmbgc_id = gc.id
            WHERE gc.user_id = ?";
    $row = $this->db->query($sql, [$user_id])->row_array();
    return isset($row['total_due']) ? (float)$row['total_due'] : 0.0;
  }

  public function viewfmbtakhmeen($user_id)
  {
    $user_id = (int)$user_id;

    // 🔹 Fetch all takhmeen records
    $takhmeen_list = $this->db->select("id, year, total_amount")
      ->from("fmb_takhmeen")
      ->where("user_id", $user_id)
      ->order_by("year", "DESC")
      ->get()
      ->result_array();

    $latest = !empty($takhmeen_list) ? $takhmeen_list[0] : null;
    $latest_year = $latest ? $latest['year'] : null;

    // 🔹 Calculate overall takhmeen total
    $takhmeen = $this->db->select("COALESCE(SUM(total_amount),0) as total_amount")
      ->from("fmb_takhmeen")
      ->where("user_id", $user_id)
      ->get()
      ->row_array();

    // 🔹 Calculate overall payments
    $payments_summary = $this->db->select("COALESCE(SUM(amount),0) as total_paid")
      ->from("fmb_takhmeen_payments")
      ->where("user_id", $user_id)
      ->get()
      ->row_array();

    // 🔹 Overall summary
    $overall_total_amount = (float)($takhmeen['total_amount'] ?? 0);
    $overall_total_paid_raw = (float)($payments_summary['total_paid'] ?? 0);
    $overall_excess = 0.0;
    if ($overall_total_paid_raw > $overall_total_amount) {
      $overall_excess = $overall_total_paid_raw - $overall_total_amount;
    }
    $overall_total_paid = min($overall_total_paid_raw, $overall_total_amount);
    $overall = [
      "total_amount" => $overall_total_amount,
      "total_paid"   => $overall_total_paid,
      "total_due"    => $overall_total_amount - $overall_total_paid,
    ];
    if ($overall_excess > 0) {
      $overall['excess_paid'] = $overall_excess;
    }

    // 🔹 Fetch current year summary
    $current_year_takhmeen = null;
    $current_hijri_date = $this->db->select("hijri_date")
      ->from("hijri_calendar")
      ->where("greg_date =", date("Y-m-d"))
      ->get()
      ->row_array();

    if ($current_hijri_date) {
      $current_hijri_date = $current_hijri_date["hijri_date"];
      $current_hijri_month = explode("-", $current_hijri_date)[1];
      $current_hijri_year = explode("-", $current_hijri_date)[2];
    } else {
      $current_hijri_date = null;
    }

    if ($current_hijri_month >= "01" && $current_hijri_month <= "08") {
      $current_year = $current_hijri_year - 1;
    } else {
      $current_year = $current_hijri_year;
    }

    if ($current_year) {
      // Fetch takhmeen for derived current year (Hijri-year mapped logic)
      $takhmeen_year = $this->db->select("COALESCE(SUM(total_amount),0) as total_amount")
        ->from("fmb_takhmeen")
        ->where("user_id", $user_id)
        ->where("year", $current_year)
        ->get()
        ->row_array();

      // Hijri-aware payments: join hijri_calendar to map each payment_date to its Hijri month/year
      // Business rule: Hijri months 01-08 belong to previous takhmeen cycle (year -1)
      $payments_sql = "SELECT COALESCE(SUM(p.amount),0) AS total_paid
        FROM fmb_takhmeen_payments p
        JOIN hijri_calendar hc ON hc.greg_date = p.payment_date
        WHERE p.user_id = ? AND (
          (
            CAST(SUBSTRING_INDEX(hc.hijri_date,'-',-1) AS UNSIGNED) = ?
            AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(hc.hijri_date,'-',2),'-',-1) AS UNSIGNED) BETWEEN 9 AND 12
          )
          OR (
            CAST(SUBSTRING_INDEX(hc.hijri_date,'-',-1) AS UNSIGNED) = (? + 1)
            AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(hc.hijri_date,'-',2),'-',-1) AS UNSIGNED) BETWEEN 1 AND 8
          )
        )";

      // Explanation: For takhmeen year Y: include Hijri months 09-12 of Hijri year Y AND months 01-08 of Hijri year Y+1.
      $payments_year = $this->db->query($payments_sql, [$user_id, $current_year, $current_year])->row_array();

      $cy_total_amount = (float)($takhmeen_year['total_amount'] ?? 0);
      $cy_total_paid_raw = (float)($payments_year['total_paid'] ?? 0);
      $cy_excess = 0.0;
      if ($cy_total_paid_raw > $cy_total_amount) {
        $cy_excess = $cy_total_paid_raw - $cy_total_amount;
      }
      $cy_total_paid = min($cy_total_paid_raw, $cy_total_amount);
      $current_year_takhmeen = [
        "year"               => $current_year,
        "derived_hijri_year" => isset($current_hijri_year) ? (int)$current_hijri_year : null,
        "total_amount"       => $cy_total_amount,
        "total_paid"         => $cy_total_paid,
        "total_due"          => $cy_total_amount - $cy_total_paid,
      ];
      if ($cy_excess > 0) {
        $current_year_takhmeen['excess_paid'] = $cy_excess;
      }
    }

    // 🔹 Fetch all payment history
    $payments = $this->db->select("id, amount, payment_method, payment_date, remarks")
      ->from("fmb_takhmeen_payments")
      ->where("user_id", $user_id)
      ->order_by("payment_date", "DESC")
      ->get()
      ->result_array();

    // 🔹 Fetch general contributions with payment aggregation (amount_paid, total_due)
    // Using subquery aggregation to stay compatible with ONLY_FULL_GROUP_BY
    $general_contributions = $this->db->select("gc.*, u.ITS_ID, u.First_Name, u.Surname, IFNULL(paid.total_received,0) AS amount_paid, (gc.amount - IFNULL(paid.total_received,0)) AS total_due", false)
      ->from("fmb_general_contribution gc")
      ->join("user u", "u.ITS_ID = gc.user_id", "left")
      ->join("(SELECT fmbgc_id, SUM(amount) AS total_received FROM fmb_general_contribution_payments GROUP BY fmbgc_id) paid", "paid.fmbgc_id = gc.id", "left")
      ->where("u.ITS_ID", $user_id)
      ->order_by('gc.created_at', 'DESC')
      ->get()
      ->result_array();

    return [
      "all_takhmeen"          => $takhmeen_list,
      "all_payments"          => $payments,
      "latest"                => $latest,
      "overall"               => $overall,
      "current_year"          => $current_year_takhmeen,
      "current_hijri_year"    => isset($current_hijri_year) ? (int)$current_hijri_year : null,
      "general_contributions" => $general_contributions
    ];
  }

  /**
   * Fetch payment history for a single General Contribution invoice owned by the given user.
   * Returns array: [success => bool, invoice => meta|NULL, payments => [], total_received => float, balance_due => float, message?]
   */
  public function get_user_gc_payment_history($user_id, $fmbgc_id)
  {
    $user_id = (int)$user_id;
    $fmbgc_id = (int)$fmbgc_id;
    if (!$user_id || !$fmbgc_id) {
      return ['success' => false, 'message' => 'Invalid parameters'];
    }

    $invoice = $this->db->select('gc.id, gc.user_id, gc.contri_year, gc.fmb_type, gc.contri_type, gc.amount, gc.payment_status, gc.created_at, gc.description')
      ->from('fmb_general_contribution gc')
      ->where('gc.id', $fmbgc_id)
      ->where('gc.user_id', $user_id)
      ->get()->row_array();
    if (!$invoice) {
      return ['success' => false, 'message' => 'Invoice not found'];
    }

    $payments = $this->db->select('id AS payment_id, amount, payment_method, payment_date, remarks, created_at')
      ->from('fmb_general_contribution_payments')
      ->where('fmbgc_id', $fmbgc_id)
      ->order_by('payment_date', 'DESC')
      ->order_by('id', 'DESC')
      ->get()->result_array();

    $total_received = 0.0;
    foreach ($payments as $p) {
      $total_received += (float)$p['amount'];
    }
    $balance = (float)$invoice['amount'] - $total_received;
    if ($balance < 0) $balance = 0.0;

    return [
      'success' => true,
      'invoice' => $invoice,
      'payments' => $payments,
      'total_received' => $total_received,
      'balance_due' => $balance
    ];
  }



  public function get_member_total_sabeel_due($user_id)
  {
    $this->db->select("
        latest.year,
        latest.establishment_amount AS latest_establishment_amount,
        latest.residential_amount AS latest_residential_amount,

        overall.establishment_total,
        overall.establishment_paid,
        (overall.establishment_total - overall.establishment_paid) AS establishment_due,

        (overall.residential_total) AS residential_total,
        overall.residential_paid,
        (overall.residential_total - overall.residential_paid) AS residential_due,

        (overall.establishment_total + overall.residential_total) AS total_takhmeen,
        (overall.establishment_paid + overall.residential_paid) AS total_paid,
        ((overall.establishment_total - overall.establishment_paid) +
        (overall.residential_total - overall.residential_paid)) AS total_due
    ");

    $this->db->from("(SELECT 
                        st.user_id, 
                        st.year,
                        eg.amount AS establishment_amount,
                        rg.yearly_amount AS residential_amount
                      FROM sabeel_takhmeen st
                      LEFT JOIN sabeel_takhmeen_grade eg 
                        ON eg.id = st.establishment_grade 
                        AND eg.type = 'establishment'
                      LEFT JOIN sabeel_takhmeen_grade rg 
                        ON rg.id = st.residential_grade 
                        AND rg.type = 'residential'
                      WHERE st.user_id = {$this->db->escape_str($user_id)}
                      ORDER BY st.year DESC
                      LIMIT 1
                    ) latest");
    $this->db->join(
      "(
        SELECT 
          st.user_id,
          COALESCE(SUM(eg.amount),0) AS establishment_total,
          COALESCE(SUM(rg.yearly_amount),0) AS residential_total,
          -- payments aggregated separately to avoid duplication
          (
            SELECT COALESCE(SUM(amount),0)
            FROM sabeel_takhmeen_payments p1
            WHERE p1.user_id = st.user_id AND p1.type = 'establishment'
          ) AS establishment_paid,
          (
            SELECT COALESCE(SUM(amount),0)
            FROM sabeel_takhmeen_payments p2
            WHERE p2.user_id = st.user_id AND p2.type = 'residential'
          ) AS residential_paid
        FROM sabeel_takhmeen st
        LEFT JOIN sabeel_takhmeen_grade eg 
          ON eg.id = st.establishment_grade AND eg.type = 'establishment'
        LEFT JOIN sabeel_takhmeen_grade rg 
          ON rg.id = st.residential_grade AND rg.type = 'residential'
        WHERE st.user_id = {$this->db->escape_str($user_id)}
        GROUP BY st.user_id
      ) overall",
      "overall.user_id = latest.user_id",
      "left"
    );

    $result = $this->db->get()->row_array();

    // Compute current Hijri composite year (09–12 same year, 01–08 next year)
    $today = date('Y-m-d');
    $row = $this->db->select('hijri_date')
      ->from('hijri_calendar')
      ->where('greg_date', $today)
      ->get()
      ->row_array();
    $currentCompositeYear = '';
    if (!empty($row) && isset($row['hijri_date'])) {
      $parts = explode('-', $row['hijri_date']); // d-m-Y
      if (count($parts) === 3) {
        $hm = (int)$parts[1];
        $hy = (int)$parts[2];
        if ($hm >= 9) {
          $sy = $hy;
          $ey = $hy + 1;
        } else {
          $sy = $hy - 1;
          $ey = $hy;
        }
        $currentCompositeYear = $sy . '-' . substr((string)$ey, -2);
      }
    }

    // Derive current-year totals/paid using existing FIFO-distributed details
    $cy_total = 0.0;
    $cy_paid = 0.0;
    if ($currentCompositeYear !== '') {
      $details = $this->viewSabeelTakhmeen($user_id);
      if (isset($details['e_takhmeen']) && is_array($details['e_takhmeen'])) {
        foreach ($details['e_takhmeen'] as $row) {
          if (isset($row['year']) && (string)$row['year'] === $currentCompositeYear) {
            $cy_total += (float)($row['total'] ?? 0);
            $cy_paid  += (float)($row['paid'] ?? 0);
          }
        }
      }
      if (isset($details['r_takhmeen']) && is_array($details['r_takhmeen'])) {
        foreach ($details['r_takhmeen'] as $row) {
          if (isset($row['year']) && (string)$row['year'] === $currentCompositeYear) {
            $cy_total += (float)($row['total'] ?? 0);
            $cy_paid  += (float)($row['paid'] ?? 0);
          }
        }
      }
    }
    $cy_due = max(0, $cy_total - $cy_paid);

    if (!is_array($result)) $result = [];
    $result['current_year']        = $currentCompositeYear;
    $result['current_year_total']  = $cy_total;
    $result['current_year_paid']   = $cy_paid;
    $result['current_year_due']    = $cy_due;

    return $result ?: null;
  }

  public function viewSabeelTakhmeen($user_id)
  {
    $this->db->select("
    st.id,
    st.year,
    eg.grade AS establishment_grade,
    eg.amount AS establishment_amount,
    rg.grade AS residential_grade,
    rg.yearly_amount AS residential_amount,

    (COALESCE(eg.amount,0) + COALESCE(rg.yearly_amount,0)) AS total_amount,

    COALESCE(SUM(p.amount), 0) AS amount_paid,

    COALESCE(eg.amount,0) AS establishment_total,
    COALESCE(SUM(CASE WHEN p.type = 'establishment' THEN p.amount ELSE 0 END),0) AS establishment_paid,
    (COALESCE(eg.amount,0) - COALESCE(SUM(CASE WHEN p.type = 'establishment' THEN p.amount ELSE 0 END),0)) AS establishment_due,

    COALESCE(rg.yearly_amount,0) AS residential_total,
    COALESCE(SUM(CASE WHEN p.type = 'residential' THEN p.amount ELSE 0 END),0) AS residential_paid,
    (COALESCE(rg.yearly_amount,0) - COALESCE(SUM(CASE WHEN p.type = 'residential' THEN p.amount ELSE 0 END),0)) AS residential_due,

    -- Overall due
    ((COALESCE(eg.amount,0) - COALESCE(SUM(CASE WHEN p.type = 'establishment' THEN p.amount ELSE 0 END),0)) +
    (COALESCE(rg.yearly_amount,0) - COALESCE(SUM(CASE WHEN p.type = 'residential' THEN p.amount ELSE 0 END),0))) AS total_due
    ", false);

    $this->db->from("sabeel_takhmeen st");
    $this->db->join("sabeel_takhmeen_grade eg", "eg.id = st.establishment_grade AND eg.type = 'establishment'", "left");
    $this->db->join("sabeel_takhmeen_grade rg", "rg.id = st.residential_grade AND rg.type = 'residential'", "left");
    $this->db->join("sabeel_takhmeen_payments p", "p.user_id = st.user_id AND p.type IN ('establishment','residential')", "left");

    $this->db->where("st.user_id", $user_id);
    $this->db->order_by("st.year", "DESC");
    $this->db->group_by("st.id");

    $takhmeen_list = $this->db->get()->result_array();

    $this->db->select("
    st.id,
    st.year,
    eg.grade AS grade,
    COALESCE(eg.amount,0) AS total
", false);
    $this->db->from("sabeel_takhmeen st");
    $this->db->join("sabeel_takhmeen_grade eg", "eg.id = st.establishment_grade AND eg.type = 'establishment'", "left");
    $this->db->where("st.user_id", $user_id);
    $this->db->order_by("st.year", "ASC"); // FIFO distribution
    $establishment_takhmeen = $this->db->get()->result_array();

    // 2. Fetch total payments for establishment
    $this->db->select("COALESCE(SUM(amount),0) AS total_paid");
    $this->db->from("sabeel_takhmeen_payments p");
    $this->db->where("p.user_id", $user_id);
    $this->db->where("p.type", "establishment");
    $total_paid = $this->db->get()->row()->total_paid;

    // 3. Distribute payments FIFO
    $remaining = $total_paid;
    foreach ($establishment_takhmeen as &$row) {
      if ($remaining >= $row['total']) {
        $row['paid'] = $row['total'];
        $row['due']  = 0;
        $remaining  -= $row['total'];
      } else {
        $row['paid'] = $remaining;
        $row['due']  = $row['total'] - $remaining;
        $remaining   = 0;
      }
    }
    unset($row);

    // 4. Reverse array → latest year first
    $establishment_takhmeen = array_reverse($establishment_takhmeen);


    $this->db->select("
    st.id,
    st.year,
    rg.grade AS grade,
    COALESCE(rg.yearly_amount,0) AS total
", false);
    $this->db->from("sabeel_takhmeen st");
    $this->db->join("sabeel_takhmeen_grade rg", "rg.id = st.residential_grade AND rg.type = 'residential'", "left");
    $this->db->where("st.user_id", $user_id);
    $this->db->order_by("st.year", "ASC"); // ASC for proper allocation
    $residential_takhmeen = $this->db->get()->result_array();

    // 2. Get total payments for residential
    $this->db->select("COALESCE(SUM(amount),0) AS total_paid");
    $this->db->from("sabeel_takhmeen_payments p");
    $this->db->where("p.user_id", $user_id);
    $this->db->where("p.type", "residential");
    $total_paid = $this->db->get()->row()->total_paid;

    // 3. Distribute payments across takhmeens (FIFO)
    $remaining = $total_paid;
    foreach ($residential_takhmeen as &$row) {
      if ($remaining >= $row['total']) {
        $row['paid'] = $row['total'];
        $row['due']  = 0;
        $remaining  -= $row['total'];
      } else {
        $row['paid'] = $remaining;
        $row['due']  = $row['total'] - $remaining;
        $remaining   = 0;
      }
    }
    unset($row);

    // 4. Reverse to show latest first
    $residential_takhmeen = array_reverse($residential_takhmeen);



    $this->db->select("
    st.user_id,
    COALESCE(SUM(eg.amount), 0) AS establishment_total,
    COALESCE(SUM(rg.yearly_amount), 0) AS residential_total,
    (COALESCE(SUM(eg.amount), 0) + COALESCE(SUM(rg.yearly_amount), 0)) AS total_amount
    ", false);
    $this->db->from("sabeel_takhmeen st");
    $this->db->join("sabeel_takhmeen_grade eg", "eg.id = st.establishment_grade AND eg.type = 'establishment'", "left");
    $this->db->join("sabeel_takhmeen_grade rg", "rg.id = st.residential_grade AND rg.type = 'residential'", "left");
    $this->db->where("st.user_id", $user_id);
    $takhmeen = $this->db->get()->row_array() ?? [];   // ✅ ensure array

    $this->db->select("
    COALESCE(SUM(CASE WHEN type = 'establishment' THEN amount ELSE 0 END), 0) AS establishment_paid,
    COALESCE(SUM(CASE WHEN type = 'residential' THEN amount ELSE 0 END), 0) AS residential_paid,
    COALESCE(SUM(amount), 0) AS total_paid
    ", false);
    $this->db->from("sabeel_takhmeen_payments");
    $this->db->where("user_id", $user_id);
    $payments = $this->db->get()->row_array() ?? [];   // ✅ ensure array

    // ✅ merge safely
    $overall = array_merge($takhmeen, $payments);

    $overall['establishment_due'] = $overall['establishment_total'] - $overall['establishment_paid'];
    $overall['residential_due']   = $overall['residential_total'] - $overall['residential_paid'];
    $overall['total_due']         = $overall['total_amount'] - $overall['total_paid'];

    // Sort takhmeen list by year ASC (oldest first for distribution)
    usort($takhmeen_list, function ($a, $b) {
      return $a['year'] <=> $b['year'];
    });

    // Total paid amounts
    $total_establishment_paid = $overall['establishment_paid'];
    $total_residential_paid   = $overall['residential_paid'];

    // Distribute payments year by year
    foreach ($takhmeen_list as &$takhmeen) {
      // Establishment
      if ($total_establishment_paid > 0) {
        if ($total_establishment_paid >= $takhmeen['establishment_total']) {
          $takhmeen['establishment_paid'] = $takhmeen['establishment_total'];
          $total_establishment_paid -= $takhmeen['establishment_total'];
        } else {
          $takhmeen['establishment_paid'] = $total_establishment_paid;
          $total_establishment_paid = 0;
        }
      } else {
        $takhmeen['establishment_paid'] = 0;
      }
      $takhmeen['establishment_due'] = $takhmeen['establishment_total'] - $takhmeen['establishment_paid'];

      // Residential
      if ($total_residential_paid > 0) {
        if ($total_residential_paid >= $takhmeen['residential_total']) {
          $takhmeen['residential_paid'] = $takhmeen['residential_total'];
          $total_residential_paid -= $takhmeen['residential_total'];
        } else {
          $takhmeen['residential_paid'] = $total_residential_paid;
          $total_residential_paid = 0;
        }
      } else {
        $takhmeen['residential_paid'] = 0;
      }
      $takhmeen['residential_due'] = $takhmeen['residential_total'] - $takhmeen['residential_paid'];

      // Total for that takhmeen row
      $takhmeen['amount_paid'] = $takhmeen['establishment_paid'] + $takhmeen['residential_paid'];
      $takhmeen['total_due']   = $takhmeen['total_amount'] - $takhmeen['amount_paid'];
    }

    usort($takhmeen_list, function ($a, $b) {
      return $b['year'] <=> $a['year'];
    });

    $latest = !empty($takhmeen_list) ? $takhmeen_list[0] : null;

    $this->db->select("id, amount, type, payment_method, payment_date, remarks");
    $this->db->from("sabeel_takhmeen_payments");
    $this->db->where("user_id", $user_id);
    $this->db->order_by("payment_date", "DESC");
    $payments = $this->db->get()->result_array();

    return [
      "all_takhmeen" => $takhmeen_list,
      "e_takhmeen" => $establishment_takhmeen,
      "r_takhmeen" => $residential_takhmeen,
      "all_payments" => $payments,
      "latest"       => $latest,
      "overall"      => $overall
    ];
  }


  public function get_month_wise_menu()
  {
    $today = date('Y-m-d');

    // Get today's Hijri month/year
    $row = $this->db->select('hijri_month_id, hijri_date')
      ->from('hijri_calendar')
      ->where('greg_date', $today)
      ->get()
      ->row();

    if (!$row) return [];

    $hijri_month_id = (int)$row->hijri_month_id;
    $hijri_date = $row->hijri_date;
    $hijri_month = explode('-', $hijri_date)[1];
    $hijri_year  = explode('-', $hijri_date)[2];
    $hijri_month_year = $hijri_month . '-' . $hijri_year;

    // All Hijri month days
    $month_days = $this->db->select('greg_date, hijri_date, hijri_month_id')
      ->from('hijri_calendar')
      ->where('hijri_month_id', $hijri_month_id)
      ->like('hijri_date', $hijri_month_year)
      ->order_by('greg_date', 'ASC')
      ->get()
      ->result_array();

    if (empty($month_days)) return [];

    $startOfMonth = $month_days[0]['greg_date'];
    $endOfMonth   = $month_days[count($month_days) - 1]['greg_date'];

    // Fetch menus
    $sql = "SELECT 
              m.id, m.date,
              mim.item_id,
              mi.name AS item_name
            FROM menu m
            LEFT JOIN menu_items_map mim ON mim.menu_id = m.id
            LEFT JOIN menu_item mi ON mi.id = mim.item_id
            WHERE m.date BETWEEN ? AND ?
            ORDER BY m.date ASC, m.id ASC";

    $menu_results = $this->db->query($sql, [$startOfMonth, $endOfMonth])->result_array();

    // Map menus by date
    $menu_map = [];
    foreach ($menu_results as $r) {
      if (!isset($menu_map[$r['date']])) {
        $menu_map[$r['date']] = [
          'id' => $r['id'],
          'items' => []
        ];
      }
      if (!empty($r['item_id']) && !empty($r['item_name'])) {
        $menu_map[$r['date']]['items'][] = $r['item_name'];
      }
    }

    // Fetch miqaats
    $miqaat_results = $this->db->select('id, name, date')
      ->from('miqaat')
      ->where('date >=', $startOfMonth)
      ->where('date <=', $endOfMonth)
      ->get()
      ->result_array();

    // Map miqaats by date (multiple allowed)
    $miqaat_map = [];
    foreach ($miqaat_results as $m) {
      $miqaat_map[$m['date']][] = [
        'id'   => $m['id'],
        'name' => $m['name']
      ];
    }

    // Final build: all days
    $grouped = [];
    foreach ($month_days as $d) {
      $greg_date = $d['greg_date'];
      $parts = explode('-', $d['hijri_date']);
      $hijri_day = $parts[0];

      $entry = [
        'date' => $greg_date,
        'hijri_day' => $hijri_day,
        'hijri_month_id' => $hijri_month_id,
        'menu' => [],
        'miqaats' => [],
        'is_holiday' => true
      ];

      if (isset($menu_map[$greg_date])) {
        $entry['menu'] = [
          'menu_id' => $menu_map[$greg_date]['id'],
          'items'   => $menu_map[$greg_date]['items']
        ];
        $entry['is_holiday'] = false;
      }

      if (isset($miqaat_map[$greg_date])) {
        $entry['miqaats'] = $miqaat_map[$greg_date];
        $entry['is_holiday'] = false;
      }

      $grouped[] = $entry;
    }

    return $grouped;
  }

  /**
   * Fetch menu + miqaat + holiday structure for a specific Hijri month (month numeric, year numeric).
   * Returns same structure as get_month_wise_menu() but for provided hijri month/year.
   */
  public function get_hijri_month_menu($hijri_year, $hijri_month)
  {
    if (empty($hijri_year) || empty($hijri_month)) return [];
    $hijri_year = (int)$hijri_year;
    $hijri_month = sprintf('%02d', (int)$hijri_month);
    $hijri_month_year = $hijri_month . '-' . $hijri_year;

    // All days for given hijri month/year
    // Primary filter using LIKE on month-year substring
    $month_days = $this->db->select('greg_date, hijri_date, hijri_month_id')
      ->from('hijri_calendar')
      ->like('hijri_date', $hijri_month_year) // matches DD-MM-YYYY where MM-YYYY substring appears
      ->order_by('greg_date', 'ASC')
      ->get()
      ->result_array();

    // Fallback: explicit filters (in case LIKE fails or collation issues) using hijri_month_id and year substring
    if (empty($month_days)) {
      $month_days = $this->db->select('greg_date, hijri_date, hijri_month_id')
        ->from('hijri_calendar')
        ->where('hijri_month_id', (int)$hijri_month)
        ->like('hijri_date', '-' . $hijri_year) // ensure year present
        ->order_by('greg_date', 'ASC')
        ->get()
        ->result_array();
    }
    if (empty($month_days)) return [];

    $startOfMonth = $month_days[0]['greg_date'];
    $endOfMonth   = $month_days[count($month_days) - 1]['greg_date'];

    // Menus for range
    $sql = "SELECT m.id, m.date, mim.item_id, mi.name AS item_name
            FROM menu m
            LEFT JOIN menu_items_map mim ON mim.menu_id = m.id
            LEFT JOIN menu_item mi ON mi.id = mim.item_id
            WHERE m.date BETWEEN ? AND ?
            ORDER BY m.date ASC, m.id ASC";
    $menu_results = $this->db->query($sql, [$startOfMonth, $endOfMonth])->result_array();
    $menu_map = [];
    foreach ($menu_results as $r) {
      if (!isset($menu_map[$r['date']])) {
        $menu_map[$r['date']] = ['id' => $r['id'], 'items' => []];
      }
      if (!empty($r['item_id']) && !empty($r['item_name'])) {
        $menu_map[$r['date']]['items'][] = $r['item_name'];
      }
    }

    // Miqaats for range
    $miqaat_results = $this->db->select('id, name, date')
      ->from('miqaat')
      ->where('date >=', $startOfMonth)
      ->where('date <=', $endOfMonth)
      ->get()->result_array();
    $miqaat_map = [];
    foreach ($miqaat_results as $m) {
      $miqaat_map[$m['date']][] = ['id' => $m['id'], 'name' => $m['name']];
    }

    // Build
    $grouped = [];
    foreach ($month_days as $d) {
      $greg_date = $d['greg_date'];
      $parts = explode('-', $d['hijri_date']);
      $hijri_day = $parts[0];
      $entry = [
        'date' => $greg_date,
        'hijri_day' => $hijri_day,
        'hijri_month_id' => $d['hijri_month_id'],
        'hijri_date' => $d['hijri_date'],
        'menu' => [],
        'miqaats' => [],
        'is_holiday' => true
      ];
      if (isset($menu_map[$greg_date])) {
        $entry['menu'] = ['menu_id' => $menu_map[$greg_date]['id'], 'items' => $menu_map[$greg_date]['items']];
        $entry['is_holiday'] = false;
      }
      if (isset($miqaat_map[$greg_date])) {
        $entry['miqaats'] = $miqaat_map[$greg_date];
        $entry['is_holiday'] = false;
      }
      $grouped[] = $entry;
    }
    return $grouped;
  }

  public function get_next_week_menu()
  {
    $today = date('Y-m-d');
    $startOfWeek = date('Y-m-d', strtotime('monday next week', strtotime($today)));
    $endOfWeek = date('Y-m-d', strtotime('saturday next week', strtotime($today)));

    $sql = "SELECT 
                m.*, 
                mim.item_id, 
                mi.name AS item_name
            FROM menu m 
            LEFT JOIN menu_items_map mim ON mim.menu_id = m.id 
            LEFT JOIN menu_item mi ON mi.id = mim.item_id 
            WHERE m.date BETWEEN ? AND ? 
            ORDER BY m.date ASC";

    $query = $this->db->query($sql, array($startOfWeek, $endOfWeek));
    $results = $query->result_array();

    // Group items by menu
    $grouped = [];

    foreach ($results as $row) {
      $menuId = $row['id'];

      if (!isset($grouped[$menuId])) {
        $grouped[$menuId] = [
          'id' => $row['id'],
          'date' => $row['date'],
          'items' => [],
        ];
      }

      if (!empty($row['item_id'])) {
        $grouped[$menuId]['items'][] = $row['item_name'];
      }
    }

    // Convert associative to indexed array
    return array_values($grouped);
  }

  /**
   * Fetch a single menu (with item names) for a specific Gregorian date (Y-m-d).
   * Returns [date, id, items[]] or null if no menu.
   */
  public function get_menu_by_date($greg_date)
  {
    if (empty($greg_date)) return null;
    $sql = "SELECT m.id, m.date, mim.item_id, mi.name AS item_name
            FROM menu m
            LEFT JOIN menu_items_map mim ON mim.menu_id = m.id
            LEFT JOIN menu_item mi ON mi.id = mim.item_id
            WHERE m.date = ?";
    $rows = $this->db->query($sql, [$greg_date])->result_array();
    if (empty($rows)) return null;
    $menu = ['id' => $rows[0]['id'], 'date' => $rows[0]['date'], 'items' => []];
    foreach ($rows as $r) {
      if (!empty($r['item_id']) && !empty($r['item_name'])) {
        $menu['items'][] = $r['item_name'];
      }
    }
    return $menu;
  }

  /**
   * Get menus between two Gregorian dates (inclusive), grouped with item names.
   * Returns array of [ id, date, items[] ] ordered by date ASC.
   */
  public function get_menus_between($startDate, $endDate)
  {
    if (empty($startDate) || empty($endDate)) {
      return [];
    }

    $sql = "SELECT 
                m.*, 
                mim.item_id, 
                mi.name AS item_name
            FROM menu m 
            LEFT JOIN menu_items_map mim ON mim.menu_id = m.id 
            LEFT JOIN menu_item mi ON mi.id = mim.item_id 
            WHERE m.date BETWEEN ? AND ? 
            ORDER BY m.date ASC, m.id ASC";

    $query = $this->db->query($sql, array($startDate, $endDate));
    $results = $query->result_array();

    $grouped = [];
    foreach ($results as $row) {
      $menuId = $row['id'];
      if (!isset($grouped[$menuId])) {
        $grouped[$menuId] = [
          'id' => $row['id'],
          'date' => $row['date'],
          'items' => [],
        ];
      }
      if (!empty($row['item_id'])) {
        $grouped[$menuId]['items'][] = $row['item_name'];
      }
    }
    return array_values($grouped);
  }

  /**
   * Get all menu days (raw rows) between two dates. Useful to compute total signup days.
   */
  public function get_fmb_signup_days_between($startDate, $endDate)
  {
    if (empty($startDate) || empty($endDate)) {
      return [];
    }
    $sql = "SELECT * FROM menu WHERE date BETWEEN ? AND ? ORDER BY date ASC";
    $query = $this->db->query($sql, array($startDate, $endDate));
    return $query->result_array();
  }

  /**
   * Get user's signup data for dates between range.
   */
  public function get_fmb_signup_data_between($userId, $startDate, $endDate)
  {
    if (empty($userId) || empty($startDate) || empty($endDate)) {
      return [];
    }
    $sql = "SELECT * FROM fmb_weekly_signup WHERE user_id = ? AND signup_date BETWEEN ? AND ? ORDER BY signup_date ASC";
    $query = $this->db->query($sql, array($userId, $startDate, $endDate));
    return $query->result_array();
  }

  public function save_fmb_signup($data)
  {
    if (empty($data['user_id']) || empty($data['signup_date'])) {
      return false; // Ensure all required fields are present
    }
    if ($data['want_thali'] == 1 && empty($data['thali_size'])) {
      return false;
    }

    $get_signup_date_result = $this->db->where('user_id', $data['user_id'])
      ->where_in('signup_date', $data['signup_date'])
      ->get('fmb_weekly_signup');

    if (!$get_signup_date_result->num_rows() > 0) {

      $this->db->insert('fmb_weekly_signup', $data);
      return $this->db->affected_rows() > 0;
    } else {
      // If the user has already signed up for the same date, update the existing record
      $this->db->where('user_id', $data['user_id']);
      $this->db->where_in('signup_date', $data['signup_date']);
      $result = $this->db->update('fmb_weekly_signup', $data);
    }

    return $result ? true : false;
  }

  public function get_fmb_signup_days()
  {
    $today = date('Y-m-d');
    $startOfWeek = date('Y-m-d', strtotime('monday next week', strtotime($today)));
    $endOfWeek = date('Y-m-d', strtotime('saturday next week', strtotime($today)));
    $sql = "SELECT * FROM menu WHERE date BETWEEN ? AND ? ORDER BY date ASC";
    $query = $this->db->query($sql, array($startOfWeek, $endOfWeek));
    return $query->result_array();
  }

  public function get_fmb_signup_data($userId)
  {
    $today = date('Y-m-d');
    $startOfWeek = date('Y-m-d', strtotime('monday next week', strtotime($today)));
    $endOfWeek = date('Y-m-d', strtotime('saturday next week', strtotime($today)));
    $sql = "SELECT * FROM fmb_weekly_signup WHERE user_id = ? AND signup_date BETWEEN ? AND ? ORDER BY signup_date ASC";
    $query = $this->db->query($sql, array($userId, $startOfWeek, $endOfWeek));
    return $query->result_array();
  }

  public function get_this_week_menu($user_id)
  {
    $today = date('Y-m-d');
    $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($today)));
    $endOfWeek = date('Y-m-d', strtotime('saturday this week', strtotime($today)));

    $sql = "SELECT 
      m.*,
      fws.id as fwsid,
      fws.want_thali,
      fws.thali_size,
      fws.signup_date,
      fws.delivery_time,
      fws.quality,
      fws.freshness,
      fws.quantity,
      fws.feedback_remark,
      fws.status,
      GROUP_CONCAT(mi.name SEPARATOR ', ') AS item_names
      FROM menu m
      LEFT JOIN menu_items_map mim ON mim.menu_id = m.id
      LEFT JOIN menu_item mi ON mi.id = mim.item_id
      LEFT JOIN fmb_weekly_signup fws 
          ON fws.signup_date = m.date AND fws.user_id = ?
      WHERE m.date BETWEEN ? AND ?
      GROUP BY m.id, fws.id
      ORDER BY m.date ASC
    ";
    $query = $this->db->query($sql, [$user_id, $startOfWeek, $endOfWeek]);
    return $query->result_array();

    // Group items by menu
    $grouped = [];

    foreach ($results as $row) {
      $menuId = $row['id'];

      if (!isset($grouped[$menuId])) {
        $grouped[$menuId] = [
          'id' => $row['id'],
          'date' => $row['date'],
          'items' => [],
        ];
      }

      if (!empty($row['item_id'])) {
        $grouped[$menuId]['items'][] = $row['item_name'];
      }
    }

    return array_values($grouped);
  }

  public function get_feedback_data($fwsid)
  {
    if (!isset($fwsid)) {
      return [];
    }
    // Use Query Builder to prevent injection and return single row
    $row = $this->db->get_where('fmb_weekly_signup', ['id' => $fwsid])->row_array();
    return $row ? $row : [];
  }

  public function update_fmb_feedback($data)
  {
    // Require only the primary identifier; allow optional/blank fields
    if (empty($data['feedback_id'])) {
      return false;
    }

    $feedback_id  = $data['feedback_id'];
    // Preserve existing values if any field omitted: fetch current row first
    $existing = $this->db->get_where('fmb_weekly_signup', ['id' => $feedback_id])->row_array();
    if (!$existing) {
      return false;
    }

    // Use null coalescing; allow empty strings (not treated as absence)
    $delivery_time = array_key_exists('delivery_time', $data) ? $data['delivery_time'] : $existing['delivery_time'];
    $quality       = array_key_exists('quality', $data) ? $data['quality'] : $existing['quality'];
    $freshness     = array_key_exists('freshness', $data) ? $data['freshness'] : $existing['freshness'];
    $quantity      = array_key_exists('quantity', $data) ? $data['quantity'] : $existing['quantity'];
    $feedback      = array_key_exists('feedback_remark', $data) ? $data['feedback_remark'] : $existing['feedback_remark'];

    $update = [
      'feedback_date'   => date('Y-m-d H:i:s'),
      'delivery_time'   => $delivery_time,
      'quality'         => $quality,
      'freshness'       => $freshness,
      'quantity'        => $quantity,
      'feedback_remark' => $feedback,
      'status'          => 1
    ];

    $this->db->where('id', $feedback_id);
    $this->db->update('fmb_weekly_signup', $update);
    return $this->db->affected_rows() > 0;
  }

  public function get_fmb_feedback_data($userId)
  {
    $today = date('Y-m-d');
    $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($today)));
    $endOfWeek = date('Y-m-d', strtotime('saturday this week', strtotime($today)));
    $sql = "SELECT * FROM fmb_weekly_signup WHERE user_id = ? AND signup_date BETWEEN ? AND ? ORDER BY signup_date ASC";
    $query = $this->db->query($sql, array($userId, $startOfWeek, $endOfWeek));
    return $query->result_array();
  }

  public function get_miqaat_invoice_status($miqaat_id)
  {
    $sql = "SELECT * FROM miqaat_invoice WHERE miqaat_id = ?";
    $query = $this->db->query($sql, array($miqaat_id));
    return count($query->result_array()) > 0;
  }

  /**
   * Fetch all Miqaat invoices for a user (as HOF) with aggregated paid & due amounts.
   * Returns array of invoices: id, miqaat_id, miqaat_name, miqaat_type, invoice_date, amount, paid_amount, due_amount, description.
   */
  public function get_user_miqaat_invoices($user_id)
  {
    if (!$user_id) {
      return [];
    }
    $sql = "SELECT 
              i.id,
              i.miqaat_id,
              m.name AS miqaat_name,
              m.type AS miqaat_type,
              i.date AS invoice_date,
              i.amount,
              i.description,
              COALESCE(SUM(p.amount),0) AS paid_amount,
              (i.amount - COALESCE(SUM(p.amount),0)) AS due_amount
            FROM miqaat_invoice i
            LEFT JOIN miqaat_payment p ON p.miqaat_invoice_id = i.id
            LEFT JOIN miqaat m ON m.id = i.miqaat_id
            WHERE i.user_id = ?
            GROUP BY i.id, i.miqaat_id, m.name, m.type, i.date, i.amount, i.description
            ORDER BY i.date DESC, i.id DESC";
    $query = $this->db->query($sql, [$user_id]);
    return $query->result_array();
  }

  /**
   * Get detailed payment history for a single miqaat invoice belonging to the user.
   * Returns [ invoice => {...}, payments => [...] ] or empty arrays if not found/unauthorized.
   */
  public function get_user_miqaat_invoice_history($user_id, $invoice_id)
  {
    if (!$user_id || !$invoice_id) {
      return ['invoice' => null, 'payments' => []];
    }
    $inv = $this->db->select('i.id, i.miqaat_id, m.name AS miqaat_name, m.type AS miqaat_type, i.date AS invoice_date, i.amount, i.description, i.user_id')
      ->from('miqaat_invoice i')
      ->join('miqaat m', 'm.id = i.miqaat_id', 'left')
      ->where('i.id', $invoice_id)
      ->get()->row_array();
    if (!$inv || (int)$inv['user_id'] !== (int)$user_id) {
      return ['invoice' => null, 'payments' => []];
    }
    $pays = $this->db->select('p.id AS payment_id, p.amount, p.payment_method, p.payment_date, p.remarks, p.created_at')
      ->from('miqaat_payment p')
      ->where('p.miqaat_invoice_id', $invoice_id)
      ->order_by('p.payment_date', 'DESC')
      ->order_by('p.id', 'DESC')
      ->get()->result_array();
    // Compute aggregates
    $total_paid = 0.0;
    foreach ($pays as $pp) {
      $total_paid += (float)$pp['amount'];
    }
    $inv['paid_amount'] = $total_paid;
    $inv['due_amount'] = max(0, (float)$inv['amount'] - $total_paid);
    return ['invoice' => $inv, 'payments' => $pays];
  }

  public function get_all_upcoming_miqaat()
  {
    $sql = "
      SELECT m.*, 1 AS janab_status
      FROM miqaat m
      WHERE m.status = 1
      ORDER BY m.date ASC
    ";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  /**
   * Search upcoming miqaats by query string (name, type or date)
   * Returns array of miqaat rows
   */
  public function search_upcoming_miqaat($q)
  {
    $q = trim($q);
    $this->db->select('m.*');
    $this->db->from('miqaat m');
    $this->db->where('m.status', 1);
    if ($q !== '') {
      $like = '%' . $this->db->escape_like_str($q) . '%';
      $this->db->group_start();
      $this->db->like('m.name', $q);
      $this->db->or_like('m.type', $q);
      // allow searching by formatted date as typed by user (e.g., 01 Jan 2026)
      $this->db->or_like("DATE_FORMAT(m.date, '%d %b %Y')", $q);
      $this->db->group_end();
    }
    $this->db->order_by('m.date', 'ASC');
    $res = $this->db->get()->result_array();
    return $res;
  }

  public function get_miqaat_raza_status($miqaat_id)
  {
    $miqaat_id = (int)$miqaat_id;
    $row = $this->db
      ->select('`Janab-status` AS janab_status')
      ->from('raza')
      ->where('miqaat_id', $miqaat_id)
      ->order_by('id', 'DESC')
      ->limit(1)
      ->get()
      ->row_array();

    if (!empty($row) && isset($row['janab_status'])) {
      return (string)$row['janab_status'];
    }
    return false;
  }

  public function get_rsvp_overview($hof_id, $miqaats)
  {
    if (empty($miqaats)) {
      return [];
    }
    $miqaat_ids = array_column($miqaats, 'id');
    $this->db->where("hof_id", $hof_id);
    $this->db->where_in("miqaat_id", $miqaat_ids);
    $query = $this->db->get("general_rsvp");
    $results = $query->result_array();
    // Group by hof_id
    $grouped = [];
    foreach ($results as $row) {
      $grouped[$row['hof_id']][$row['miqaat_id']][] = $row;
    }
    return $grouped;
  }


  public function get_miqaat_by_id($miqaat_id)
  {
    $this->db->where("id", $miqaat_id);
    $query = $this->db->get("miqaat");
    return $query->row_array();
  }

  public function clear_existing_rsvps($hof_id, $miqaat_id)
  {
    $this->db->where("hof_id", $hof_id);
    $this->db->where("miqaat_id", $miqaat_id);
    $this->db->delete("general_rsvp");
    return $this->db->affected_rows() > 0;
  }

  /**
   * Clear any existing guest RSVP record for given hof and miqaat
   */
  public function clear_existing_guest_rsvp($hof_id, $miqaat_id)
  {
    $this->db->where('hof_id', $hof_id);
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->delete('general_guest_rsvp');
    return $this->db->affected_rows() > 0;
  }

  public function insert_rsvp($data)
  {
    if (empty($data)) {
      return false;
    }
    $this->db->insert('general_rsvp', $data);
    return $this->db->affected_rows() > 0;
  }

  /**
   * Insert guest RSVP counts for a hof + miqaat
   * $data should contain hof_id, miqaat_id, gents, ladies, children
   */
  public function insert_guest_rsvp($data)
  {
    if (empty($data) || empty($data['hof_id']) || empty($data['miqaat_id'])) return false;
    $row = [
      'hof_id' => (int)$data['hof_id'],
      'miqaat_id' => (int)$data['miqaat_id'],
      'gents' => isset($data['gents']) ? (int)$data['gents'] : 0,
      'ladies' => isset($data['ladies']) ? (int)$data['ladies'] : 0,
      'children' => isset($data['children']) ? (int)$data['children'] : 0,
    ];
    $this->db->insert('general_guest_rsvp', $row);
    return $this->db->insert_id();
  }

  /**
   * Get guest RSVP row for given hof and miqaat
   * Returns associative array or null
   */
  public function get_guest_rsvp($hof_id, $miqaat_id)
  {
    $this->db->where('hof_id', $hof_id);
    $this->db->where('miqaat_id', $miqaat_id);
    $query = $this->db->get('general_guest_rsvp');
    $row = $query->row_array();
    return empty($row) ? null : $row;
  }

  public function get_rsvp_by_miqaat_id($hof_id, $miqaat_id)
  {
    $this->db->where("hof_id", $hof_id);
    $this->db->where("miqaat_id", $miqaat_id);
    $query = $this->db->get("general_rsvp");
    return $query->result_array();
  }

  // Updated by Patel Infotech Services

  public function get_rsvp_attendance($rsvp, $userId)
  {
    $sql = "SELECT * FROM  rsvp_attendance where user_id=$userId AND rsvp_id=$rsvp";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }
  public function get_rsvp_attendance_present($rsvp, $userId)
  {
    $sql = "SELECT * FROM  rsvp_attendance where user_id=$userId AND rsvp_id=$rsvp and attend=1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function insert_raza($userId, $razaType, $data, $miqaat_id, $sabil, $fmb)
  {
    $data = array(
      'user_id' => $userId,
      'razaType' => $razaType,
      'razadata' => $data,
      'miqaat_id' => $miqaat_id,
      'sabil' => $sabil,
      'fmb' => $fmb,
    );

    if (!empty($data)) {
      $this->db->insert('raza', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function update_raza($id, $razadata)
  {
    $data = array(
      'razadata' => $razadata
    );

    $this->db->where('id', $id);
    $this->db->update('raza', $data);

    return $this->db->affected_rows() > 0;
  }
  public function delete_raza($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('raza');
    return $this->db->affected_rows() > 0;
  }
  public function delete_vasan_req($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('vasan_request');
    return $this->db->affected_rows() > 0;
  }
  public function update_vasan_req($id, $reason, $from_date, $to_date, $utensil)
  {
    $data = array(
      'reason' => $reason,
      'from_date' => $from_date,
      'to_date' => $to_date,
      'utensils' => $utensil,
    );
    if (!empty($data)) {
      $this->db->where('id', $id);
      $this->db->update('vasan_request', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function insert_vasan_req($userId, $reason, $from_date, $to_date, $utensil)
  {
    $data = array(
      'user_id' => $userId,
      'reason' => $reason,
      'from_date' => $from_date,
      'to_date' => $to_date,
      'utensils' => $utensil,
    );


    if (!empty($data)) {
      $this->db->insert('vasan_request', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function get_razatype()
  {
    $sql = 'SELECT * from `raza_type` where  `active`=1';
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_raza_byid($id)
  {
    $sql = 'SELECT * from `raza` where  id = ?';
    $query = $this->db->query($sql, array($id));
    return $query->result_array();
  }
  public function get_raza_miqaat_details($miqaat_id, $raza_id)
  {
    $sql = "SELECT m.*, r.razadata FROM miqaat m INNER JOIN raza r ON m.id = r.miqaat_id WHERE m.id = ? AND r.id = ?";
    $query = $this->db->query($sql, array($miqaat_id, $raza_id));
    return $query->row_array();
  }
  public function get_vasantype()
  {
    $sql = 'SELECT * from `vasan_type` where  `active`=1';
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_raza($user_id)
  {
    $sql = "SELECT * FROM raza WHERE user_id = ? AND active = 1 ORDER BY raza.`time-stamp` DESC";
    $query = $this->db->query($sql, array($user_id));
    return $query->result_array();
  }
  public function get_vasan_request($user_id)
  {
    $sql = "SELECT * FROM vasan_request WHERE user_id = ? AND active = 1";
    $query = $this->db->query($sql, array($user_id));
    return $query->result_array();
  }
  public function get_rsvp()
  {
    $sql = "SELECT *
        FROM rsvp
        WHERE expired BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 15 DAY)
        ORDER BY rsvp.timestamp DESC";

    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_rsvp_byid($id)
  {
    $sql = "SELECT * FROM rsvp WHERE id = ? and active = 1";
    $query = $this->db->query($sql, array($id));
    return $query->result_array();
  }
  public function get_vasan_byid($id)
  {
    $sql = "SELECT * FROM vasan_type WHERE id = ? and active = 1";
    $query = $this->db->query($sql, array($id));
    return $query->result_array();
  }
  public function get_vasanreq_byid($id)
  {
    $sql = "SELECT * FROM vasan_request WHERE id = ? and active = 1";
    $query = $this->db->query($sql, array($id));
    return $query->result_array();
  }
  public function get_razatype_byid($id)
  {
    $sql = "SELECT * FROM raza_type WHERE id = ? and active = 1";
    $query = $this->db->query($sql, array($id));
    return $query->result_array();
  }
  public function insert_rsvp_attendance($id, $user_id, $attend)
  {
    $data = array(
      'user_id' => $user_id,
      'rsvp_id' => $id,
      'attend' => $attend,
    );

    if (!empty($data)) {
      $this->db->insert('rsvp_attendance', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function update_rsvp_attendance($id, $user_id, $attend)
  {
    $data = array(
      'attend' => $attend,
    );

    if (!empty($data)) {
      $this->db->where('rsvp_id', $id);
      $this->db->where('user_id', $user_id);
      $this->db->update('rsvp_attendance', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  public function change_password($id, $password)
  {
    $data = array(
      'password' => $password,
    );


    if (!empty($data)) {
      $this->db->where('username', $id);
      $this->db->update('login', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function get_user($id)
  {
    $sql = "SELECT * from `user` where  `ITS_ID`= '$id'";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_all_rsvp()
  {
    $sql = "SELECT *
        FROM rsvp
        ORDER BY rsvp.timestamp DESC";

    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_guset_rsvp($familyid, $rsvp)
  {
    $sql = "SELECT * FROM guest_rsvp WHERE rsvp_id = ?  and familyid = ?";
    $query = $this->db->query($sql, array($rsvp, $familyid));
    return $query->result_array();
  }
  public function insert_rsvp_attendance_guest($hofid, $guest_male, $guest_female, $id)
  {
    $data = array(
      'familyid' => $hofid,
      'rsvp_id' => $id,
      'male' => $guest_male,
      'female' => $guest_female,
    );

    // Check if already present
    $existing_record = $this->db->get_where('guest_rsvp', array('rsvp_id' => $id, 'familyid' => $hofid))->row_array();

    if (empty($existing_record)) {
      // Insert new record if not exist
      $this->db->insert('guest_rsvp', $data);
      return $this->db->affected_rows() > 0;
    } else {
      // Update existing record
      $this->db->where('id', $existing_record['id']);
      $this->db->update('guest_rsvp', $data);
      return $this->db->affected_rows() > 0;
    }
  }
  public function getUserData($user_id)
  {
    $this->db->where('ITS_ID', $user_id);
    $query = $this->db->get('user');
    return $query->row_array();
  }

  public function getFatherData($father_its_id)
  {
    if ($father_its_id !== null && $father_its_id !== 0) {
      $this->db->where('ITS_ID', $father_its_id);
      $query = $this->db->get('user');
      $result = $query->row_array();

      // Check if data is found and ITS_ID is not 0 or null
      if ($result && $result['ITS_ID'] !== null && $result['ITS_ID'] !== 0) {
        return $result;
      } else {
        // Return default values or handle as needed
        return array(
          'ITS_ID' => '---',
          'Full_Name' => '---',
        );
      }
    } else {
      return array(
        'ITS_ID' => '---',
        'Full_Name' => '---',
      );
    }
  }

  public function getMotherData($mother_its_id)
  {
    if ($mother_its_id !== null && $mother_its_id !== 0) {
      $this->db->where('ITS_ID', $mother_its_id);
      $query = $this->db->get('user');
      $result = $query->row_array();

      // Check if data is found and ITS_ID is not 0 or null
      if ($result && $result['ITS_ID'] !== null && $result['ITS_ID'] !== 0) {
        return $result;
      } else {
        return array(
          'ITS_ID' => '---',
          'Full_Name' => '---',
        );
      }
    } else {
      return array(
        'ITS_ID' => '---',
        'Full_Name' => '---',
      );
    }
  }

  public function getHOFData($hof_id)
  {
    if ($hof_id !== null && $hof_id !== 0) {
      $this->db->where('ITS_ID', $hof_id);
      $query = $this->db->get('user');
      $result = $query->row_array();

      // Check if data is found and ITS_ID is not 0 or null
      if ($result && $result['ITS_ID'] !== null && $result['ITS_ID'] !== 0) {
        return $result;
      } else {
        return array(
          'ITS_ID' => '--',
          'Full_Name' => '--',
        );
      }
    } else {
      return array(
        'ITS_ID' => '--',
        'Full_Name' => '--',
      );
    }
  }

  public function getFamilyMembers($hof_id)
  {
    $this->db->where('HOF_ID', $hof_id);
    $query = $this->db->get('user');
    return $query->result_array();
  }

  public function getInchargeDetails($user_id)
  {
    $this->db->where('ITS_ID', $user_id);
    $query = $this->db->get('user');
    return $query->row_array();
  }

  public function get_dates()
  {
    $start_date = new DateTime('2024-03-10');
    $end_date = new DateTime('2024-04-08');
    $interval = new DateInterval('P1D');
    $date_range = new DatePeriod($start_date, $interval, $end_date);

    $dates = array();
    foreach ($date_range as $date) {
      $dates[] = $date->format('Y-m-d');
    }

    return $dates;
  }

  public function get_available_time_slots($date)
  {
    // Select date and time and pick a representative slot_id (MIN) to satisfy ONLY_FULL_GROUP_BY
    $this->db->select('date, time, MIN(slot_id) AS slot_id', false);
    $this->db->from('slots');
    $this->db->where('date', $date);
    $this->db->where('booked', 0);
    $this->db->group_by('date, time');
    $this->db->having('COUNT(*) = 1 OR MAX(active) = 1');

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      $result = [];

      foreach ($query->result() as $row) {
        $time_slot = new stdClass();
        $time_slot->time = $row->time;
        $time_slot->slot_id = $row->slot_id;
        $time_slot->date = $row->date;

        $this->db->select('COUNT(*) as count');
        $this->db->from('slots');
        $this->db->where('date', $date);
        $this->db->where('time', $row->time);
        $this->db->where('booked', 0);
        $count_query = $this->db->get();
        $time_slot->count = $count_query->row()->count;

        $result['time_slots'][] = $time_slot;
      }

      $this->db->select('COUNT(DISTINCT date, time) as count', false);
      $this->db->from('slots');
      $this->db->where('date', $date);
      $this->db->where('booked', 0);
      $count_query = $this->db->get();
      $result['total_count'] = $count_query->row()->count;

      return $result;
    } else {
      return "No time slots are available for the specified date.";
    }
  }



  public function get_user_info($user_id)
  {
    // Retrieve ITS_ID and Full_Name from the user table based on user_id
    $this->db->select('ITS_ID, Full_Name');
    $this->db->where('ITS_ID', $user_id); // Assuming 'user_id' is the column in the 'user' table
    $query = $this->db->get('user');

    return $query->row(); // Fetch a single row as an object
  }

  public function book_slot($slot_id, $its_id, $full_name, $purpose = null, $other_details = null)
  {
    // Store the appointment in the appointments table
    $appointment_data = array(
      'slot_id' => $slot_id,
      'its' => $its_id,
      'name' => $full_name,
      'purpose' => $purpose,
      'other_details' => $other_details,
    );

    $this->db->insert('appointments', $appointment_data);

    // Update the slots table to mark the slot as booked
    $slot_update_data = array(
      'booked' => 1,
    );

    $this->db->where('slot_id', $slot_id);
    $this->db->update('slots', $slot_update_data);
  }

  public function get_user_appointments($user_name)
  {
    // Assuming you have a 'slots' table and an 'appointments' table
    // include purpose and other_details so views can display them
    $this->db->select('appointments.id, appointments.slot_id, appointments.status, appointments.purpose, appointments.other_details');
    $this->db->from('appointments');
    $this->db->where('appointments.its', $user_name);
    $query = $this->db->get();

    $user_appointments = $query->result();

    // Fetch date and time for each appointment
    foreach ($user_appointments as &$appointment) {
      $slot_info = $this->get_slot_info($appointment->slot_id);
      $appointment->date = $slot_info->date;
      $appointment->time = $slot_info->time;
    }

    return $user_appointments;
  }

  public function get_slot_info($slot_id)
  {
    // Assuming you have a 'slots' table
    $this->db->select('date, time');
    $this->db->from('slots');
    $this->db->where('slot_id', $slot_id);
    $query = $this->db->get();

    return $query->row();
  }



  public function delete_appointment($appointment_id)
  {
    // Assuming you have an 'appointments' table
    $this->db->select('slot_id');
    $this->db->where('id', $appointment_id);
    $query = $this->db->get('appointments');

    if ($query->num_rows() > 0) {
      $appointment = $query->row();
      $slot_id = $appointment->slot_id;

      // Update the 'slots' table to set booked = 0
      $this->db->set('booked', 0);
      $this->db->where('slot_id', $slot_id);
      $this->db->update('slots');

      // Delete the appointment
      $this->db->where('id', $appointment_id);
      $this->db->delete('appointments');
    }
  }

  public function get_chat_by_raza_id($id)
  {
    // Fetch chat data from the database based on $id
    $this->db->where('raza_id', $id);
    $this->db->order_by('created_at', 'asc');
    $query = $this->db->get('chat');

    return $query->result(); // Return chat data
  }

  public function get_status_by_raza_id($id)
  {
    $this->db->select('coordinator-status, Janab-status');
    $this->db->where('id', $id);
    $query = $this->db->get('raza');

    return $query->row(); // Return status data
  }

  public function insert_message($data)
  {
    // Insert message data into the database
    $this->db->insert('chat', $data);
    return $this->db->insert_id(); // Return the ID of the inserted record
  }

  public function get_chat_count($raza_id)
  {
    $this->db->where('raza_id', $raza_id);
    $query = $this->db->get('chat'); // Assuming 'chat' is the name of your table
    return $query->num_rows();
  }

  public function deleteMessage($message_id)
  {
    $this->db->where('id', $message_id);
    $result = $this->db->delete('chat');

    return $result;
  }

  public function RazaTypesDetails()
  {
    $sql = 'SELECT id, name, umoor from `raza_type` where `active`=1 and umoor like "%Umoor%"';
    $query = $this->db->query($sql);
    return $query->result_array();
  }
}
