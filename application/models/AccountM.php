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
  public function get_assigned_miqaats($user_id)
  {
    // Get miqaat IDs where user is assigned as individual or group leader
    $this->db->select('miqaat_id');
    $this->db->from('miqaat_assignments');
    $this->db->where('(assign_type = "Individual" AND member_id = ' . $this->db->escape($user_id) . ')', NULL, FALSE);
    $this->db->or_where('(assign_type = "Group" AND group_leader_id = ' . $this->db->escape($user_id) . ')', NULL, FALSE);
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
        u3.Full_Name as co_leader_name,
        u1.ITS_ID as member_id, u2.ITS_ID as group_leader_id, u3.ITS_ID as co_leader_id');
      $this->db->from('miqaat_assignments ma');
      $this->db->join('user u1', 'u1.ITS_ID = ma.member_id', 'left');
      $this->db->join('user u2', 'u2.ITS_ID = ma.group_leader_id', 'left');
      $this->db->join('user u3', 'u3.ITS_ID = ma.member_id', 'left');
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

  public function get_member_total_fmb_due($user_id)
  {
    $this->db->select("
        latest.year,
        latest.total_amount,
        overall.total_paid,
        (overall.total_amount - overall.total_paid) AS total_due
    ");
    $this->db->from("(SELECT user_id, year, total_amount, amount_paid 
                      FROM fmb_takhmeen 
                      WHERE user_id = {$this->db->escape_str($user_id)} 
                      ORDER BY year DESC
                      LIMIT 1) latest");

    $this->db->join(
      "(SELECT user_id, 
          COALESCE(SUM(total_amount),0) AS total_amount, 
          COALESCE(SUM(amount_paid),0) AS total_paid
          FROM fmb_takhmeen 
          WHERE user_id = {$this->db->escape_str($user_id)}
          GROUP BY user_id) overall",
      "overall.user_id = latest.user_id",
      "left"
    );

    $result = $this->db->get()->row_array();
    return $result ?: null;
  }

  public function viewfmbtakhmeen($user_id)
  {
    // 1️⃣ Get all takhmeen entries (year-wise, descending)
    $this->db->select("id, year, total_amount, amount_paid");
    $this->db->from("fmb_takhmeen");
    $this->db->where("user_id", $user_id);
    $this->db->order_by("year", "DESC");
    $takhmeen_list = $this->db->get()->result_array();

    // 2️⃣ Get overall totals (due = sum of total_amount - sum of amount_paid)
    $this->db->select("
        COALESCE(SUM(total_amount), 0) AS total_amount,
        COALESCE(SUM(amount_paid), 0) AS total_paid,
        COALESCE(SUM(total_amount), 0) - COALESCE(SUM(amount_paid), 0) AS total_due
    ", false);
    $this->db->from("fmb_takhmeen");
    $this->db->where("user_id", $user_id);
    $overall = $this->db->get()->row_array();

    // 3️⃣ Find the latest takhmeen entry (first in sorted list)
    $latest = !empty($takhmeen_list) ? $takhmeen_list[0] : null;

    // 4. Payment history
    $this->db->select("id, amount, payment_method, payment_date, remarks");
    $this->db->from("fmb_takhmeen_payments");
    $this->db->where("user_id", $user_id);
    $this->db->order_by("payment_date", "DESC");
    $payments = $this->db->get()->result_array();


    $this->db->select("fmb_general_contribution.*, user.ITS_ID, user.First_Name, user.Surname");
    $this->db->from("fmb_general_contribution");
    $this->db->join("user", "user.ITS_ID = fmb_general_contribution.user_id", "left");
    $this->db->where("user.ITS_ID", $user_id);
    $general_contributions = $this->db->get()->result_array();

    // 4️⃣ Return everything together
    return [
      "all_takhmeen"   => $takhmeen_list,
      "all_payments"   => $payments,
      "latest" => $latest,
      "overall" => $overall,
      "general_contributions" => $general_contributions
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
      "
        (
        SELECT 
          st.user_id,
          COALESCE(SUM(eg.amount),0) AS establishment_total,
          p.establishment_paid,
          COALESCE(SUM(rg.yearly_amount),0) AS residential_total,
          p.residential_paid
        FROM sabeel_takhmeen st
        LEFT JOIN sabeel_takhmeen_grade eg 
          ON eg.id = st.establishment_grade AND eg.type = 'establishment'
        LEFT JOIN sabeel_takhmeen_grade rg 
          ON rg.id = st.residential_grade AND rg.type = 'residential'
        LEFT JOIN (
          SELECT 
            user_id,
            COALESCE(SUM(CASE WHEN type = 'establishment' THEN amount ELSE 0 END),0) AS establishment_paid,
            COALESCE(SUM(CASE WHEN type = 'residential' THEN amount ELSE 0 END),0) AS residential_paid
          FROM sabeel_takhmeen_payments
          GROUP BY user_id
        ) p ON p.user_id = st.user_id
        WHERE st.user_id = {$this->db->escape_str($user_id)}
        GROUP BY st.user_id
      ) overall",
      "overall.user_id = latest.user_id",
      "left"
    );

    $result = $this->db->get()->row_array();
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

    // Establishment takhmeen
    // 1. Fetch establishment takhmeen list
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
    $startOfMonth = "";
    $endOfMonth = "";

    $today = date('Y-m-d');

    $this->db->select('hijri_month_id');
    $this->db->from('hijri_calendar');
    $this->db->where('greg_date', $today);
    $query = $this->db->get();
    $row = $query->row();

    if (!$row) {
      return [];
    }

    $hijri_month_id = $row->hijri_month_id;

    $this->db->from('hijri_calendar');
    $this->db->where('hijri_month_id', $hijri_month_id);
    $this->db->order_by('greg_date', 'ASC');
    $month_data = $this->db->get()->result_array();

    $first_hijri_day = $month_data[0]["greg_date"];
    $last_hijri_day = $month_data[count($month_data) - 1]["greg_date"];

    $startOfMonth = date('Y-m-d', strtotime($first_hijri_day));
    $endOfMonth = date('Y-m-d', strtotime($last_hijri_day));

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
    // echo "<pre>"; print_r($results); die();

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
      return;
    }

    $get_feedback_data = "SELECT * FROM fmb_weekly_signup WHERE id = $fwsid";
    return $this->db->query($get_feedback_data)->result_array();
  }

  public function update_fmb_feedback($data)
  {
    if (
      empty($data['feedback_id']) ||
      empty($data['delivery_time']) ||
      empty($data['quality']) ||
      empty($data['freshness']) ||
      empty($data['quantity'])
    ) {
      return false;
    }

    $feedback_id = $data['feedback_id'];
    $delivery_time = $data['delivery_time'];
    $quality = $data['quality'];
    $freshness = $data['freshness'];
    $quantity = $data['quantity'];
    $feedback = $data['feedback_remark'];

    $data = array(
      "feedback_date" => date("Y-m-d H:i:s"),
      "delivery_time" => $delivery_time,
      "quality" => $quality,
      "freshness" => $freshness,
      "quantity" => $quantity,
      "feedback_remark" => $feedback,
      "status" => 1
    );

    $this->db->where('id', $feedback_id);
    $this->db->update('fmb_weekly_signup', $data);
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

  public function get_all_upcoming_miqaat()
  {
    $sql = "SELECT * FROM miqaat WHERE date > NOW() ORDER BY date ASC";
    $query = $this->db->query($sql);
    return $query->result_array();
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

  public function insert_rsvp($data)
  {
    if (empty($data)) {
      return false;
    }
    $this->db->insert('general_rsvp', $data);
    return $this->db->affected_rows() > 0;
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
    $this->db->select('time');
    $this->db->select('slot_id');
    $this->db->select('date');
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

  public function book_slot($slot_id, $its_id, $full_name)
  {
    // Store the appointment in the appointments table
    $appointment_data = array(
      'slot_id' => $slot_id,
      'its' => $its_id,
      'name' => $full_name,
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
    $this->db->select('appointments.id,appointments.slot_id, appointments.status');
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
    $sql = 'SELECT id, name, umoor from `raza_type` where `active`=1';
    $query = $this->db->query($sql);
    return $query->result_array();
  }
}
