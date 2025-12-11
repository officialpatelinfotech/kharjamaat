<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class AdminM extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    $this->load->model('HijriCalendar');
  }
  function check_db()
  {
    $sql = " SELECT * from `login`";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  function get_raza()
  {
    $sql = " SELECT * FROM `raza` where active=1 ORDER BY `raza`.`time-stamp` DESC";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  function delet_raza_type($id)
  {
    $data = array(
      'active' => 0,
    );

    $this->db->where('id', $id);
    $this->db->update('raza_type', $data);
    return $this->db->affected_rows() > 0;
  }
  function add_new_razatype($razaname, $umoor)
  {
    $data = array(
      'name' => $razaname,
      'umoor' => $umoor,
      'fields' => json_encode(['id' => null, 'name' => $razaname, 'umoor' => $umoor, 'fields' => []]),
    );

    if (!empty($data)) {
      $this->db->insert('raza_type', $data);
      $lastId = $this->db->insert_id();
      $updatedField = json_encode(['id' => $lastId, 'name' => $razaname, 'umoor' => $umoor, 'fields' => []]);
      $this->db->where('id', $lastId);
      $this->db->update('raza_type', array('fields' => $updatedField));

      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function update_raza($rowId, $data)
  {
    $this->db->where('id', $rowId);
    $this->db->update('raza_type', $data);
  }



  function approve_raza($raza_id, $remark)
  {

    $remark = str_replace(array("\r", "\n"), '', $remark);
    $data = array(
      'coordinator-status' => 1,
      'status' => 1,
      'remark' => $remark
    );


    if (!empty($data)) {
      $this->db->where('id', $raza_id);
      $this->db->update('raza', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  function reject_raza($raza_id, $remark)
  {

    $remark = str_replace(array("\r", "\n"), '', $remark);
    $data = array(
      'coordinator-status' => 2,
      'status' => 4,
      'remark' => $remark
    );


    if (!empty($data)) {
      $this->db->where('id', $raza_id);
      $this->db->update('raza', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  public function get_user_by_raza_id($raza_id)
  {
    $this->db->select('u.*');
    $this->db->from('raza r');
    $this->db->join('user u', 'r.user_id = u.ITS_ID');
    $this->db->where('r.id', $raza_id);

    $query = $this->db->get();
    echo print_r($query);

    if ($query->num_rows() > 0) {
      return $query->row_array();
    } else {
      return null;
    }
  }
  public function get_razatype()
  {
    $sql = 'SELECT * from `raza_type`';
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_umoorrazatype()
  {
    $sql = "SELECT * from `raza_type` WHERE `umoor` IN ('UmoorDeeniyah', 'UmoorTalimiyah', 'UmoorMarafiqBurhaniyah', 'UmoorMaaliyah', 'UmoorMawaridBashariyah', 'UmoorDakheliyah', 'UmoorKharejiyah', 'UmoorIqtesadiyah', 'UmoorFMB', 'UmoorAl-Qaza', 'UmoorAl-Amlaak', 'UmoorAl-Sehhat')";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_eventrazatype()
  {
    $sql = "SELECT * from `raza_type` WHERE  `umoor` IN ('Public-Event', 'Private-Event')";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_umoortype()
  {
    $this->db->distinct();
    $this->db->select('umoor');
    $this->db->from('raza_type');
    $this->db->where_not_in('umoor', array('Public-Event', 'Private-Event'));

    $query = $this->db->get();
    return $query->result_array();
  }


  public function get_eventtype()
  {
    $this->db->distinct();
    $this->db->select('umoor');
    $this->db->from('raza_type');
    $this->db->where_not_in('umoor', array('UmoorDeeniyah', 'UmoorTalimiyah', 'UmoorMarafiqBurhaniyah', 'UmoorMaaliyah', 'UmoorMawaridBashariyah', 'UmoorDakheliyah', 'UmoorKharejiyah', 'UmoorIqtesadiyah', 'UmoorFMB', 'UmoorAl-Qaza', 'UmoorAl-Amlaak', 'UmoorAl-Sehhat'));

    $query = $this->db->get();
    return $query->result_array();
  }
  public function get_razatype_byid($id)
  {
    $sql = "SELECT * from `raza_type` where `id`= $id ";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function update_raza_type($id, $raza)
  {
    $data = array(
      'fields' => $raza
    );
    if (!empty($data)) {
      $this->db->where('id', $id);
      $this->db->update('raza_type', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  // Updated by Patel Infotech Services
  public function updateHijriGregorianDates()
  {
    $todayGreg = date('Y-m-d');
    $currentHijri = $this->HijriCalendar->get_hijri_date($todayGreg);
    if (!$currentHijri || empty($currentHijri['hijri_date'])) return;
    $partsNow = explode('-', $currentHijri['hijri_date']); // d-m-Y
    if (count($partsNow) < 3) return;
    $currentMonth = (int)$partsNow[1];
    $currentYear  = (int)$partsNow[2];
    $nextYear     = $currentYear + 1;

    $nextYearExists = $this->db->like('hijri_date', '-' . $nextYear)->limit(1)->get('hijri_calendar')->num_rows() > 0;
    if ($nextYearExists) return;

    $monthsRemaining = 12 - $currentMonth;
    if ($monthsRemaining > 6) return;

    $get_last_date = "SELECT * FROM hijri_calendar ORDER BY greg_date DESC LIMIT 1";
    $last_date = $this->db->query($get_last_date)->result_array();
    $last_greg_date = $last_date[0]["greg_date"];

    $last_hijri_date = (int)explode("-", $last_date[0]["hijri_date"])[2] + 1;

    $position_in_cycle = $last_hijri_date % 30;
    $leap_years = [2, 5, 8, 10, 13, 16, 19, 21, 24, 27, 29];
    $is_leap_year = in_array($position_in_cycle, $leap_years);
    $number_of_days_in_year = $is_leap_year ? 355 : 354;

    $get_hijri_dates = "SELECT * FROM hijri_calendar WHERE hijri_date LIKE '%-1442'";

    $hijri_dates = $this->db->query($get_hijri_dates)->result_array();

    $last_greg_date = new DateTime($last_greg_date);

    for ($day = 0; $day < $number_of_days_in_year; $day++) {
      if (isset($hijri_dates[$day])) {
        $upcoming_year_hijri_date = preg_replace("/1442/", $last_hijri_date, $hijri_dates[$day]["hijri_date"]);
      } else {
        $upcoming_year_hijri_date = "30-12-" . $last_hijri_date;
      };
      $month_number = (int)explode("-", $upcoming_year_hijri_date)[1];
      $new_greg_date = $last_greg_date->add(new DateInterval('P1D'))->format('Y-m-d');
      $new_hijri_date = array(
        "greg_date" => $new_greg_date,
        "hijri_date" => $upcoming_year_hijri_date,
        "hijri_month_id" => $month_number
      );
      $this->db->insert("hijri_calendar", $new_hijri_date);
    }
  }

  public function all_users()
  {
    $this->db->from("user");
    $this->db->where("Inactive_Status IS NULL AND HOF_FM_TYPE = 'HOF' AND Sector IS NOT NULL");
    $this->db->order_by("Sector, Sub_Sector, First_Name, Surname");
    return $this->db->get()->result_array();
  }

  // FMB General Contribution
  public function getallfmbgc($filter_status = NULL)
  {
    $this->db->from("fmb_general_contribution_master");
    if (isset($filter_status) && $filter_status == 0) {
      $this->db->where("status", $filter_status);
    } else {
      $this->db->where("status", 1);
    }
    return $this->db->get()->result_array();
  }
  public function addfmbcontritype($fmb_type, $contri_for)
  {
    $result = $this->db->insert("fmb_general_contribution_master", ["name" => $contri_for, "fmb_type" => $fmb_type]);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  public function updatefmbgc(
    $id,
    $name,
    $fmb_type,
    $status
  ) {
    $this->db->where("id", $id);
    $result = $this->db->update("fmb_general_contribution_master", ["name" => $name, "fmb_type" => $fmb_type, "status" => $status]);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  // FMB General Contribution

  public function get_user_fmb_takhmeen_details($filter_data = null)
  {
    // Get current Hijri year

    $current_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"));
    if ($current_hijri_date) {
      $current_hijri_date = $current_hijri_date["hijri_date"];
      $current_hijri_month = explode("-", $current_hijri_date)[1];
      $current_hijri_year = explode("-", $current_hijri_date)[2];
    } else {
      $current_hijri_date = null;
    }

    // Step 2: Fetch all rows
    $this->db->select("
    u.ITS_ID,
    u.First_Name,
    u.Surname,
    u.Sector,
    u.Full_Name,
    u.Sub_Sector,
    fmb_t.id AS takhmeen_id,
    fmb_t.year AS takhmeen_year,
    IFNULL(fmb_t.total_amount, 0) AS total_fmb_takhmeen,
    fmb_t.remark AS takhmeen_remark
  ");
    $this->db->from("user u");
    $this->db->join("fmb_takhmeen fmb_t", "fmb_t.user_id = u.ITS_ID", "left");

    if (!empty($filter_data["member_name"])) {
      $this->db->like("u.Full_Name", $filter_data["member_name"]);
    }
    if (!empty($filter_data["sector"])) {
      $this->db->where('u.Sector', $filter_data['sector']);
    }
    if (!empty($filter_data["sub_sector"])) {
      $this->db->where('u.Sub_Sector', $filter_data['sub_sector']);
    }

    $this->db->where("u.Inactive_Status IS NULL 
                      AND u.HOF_FM_TYPE = 'HOF' 
                      AND u.Sector IS NOT NULL");

    $this->db->order_by("u.Sector, u.Sub_Sector, u.First_Name, u.Surname, fmb_t.year DESC");

    $query = $this->db->get();
    $rows = $query->result_array();

    // Step 3: Restructure results (group by user)
    $users = [];
    foreach ($rows as $row) {
      $itsId = $row['ITS_ID'];

      if (!isset($users[$itsId])) {
        $users[$itsId] = [
          'ITS_ID' => $row['ITS_ID'],
          'Full_Name' => $row['Full_Name'],
          'Sector' => $row['Sector'],
          'Sub_Sector' => $row['Sub_Sector'],
          'First_Name' => $row['First_Name'],
          'Surname' => $row['Surname'],
          'takhmeens' => [],
          'current_year_takhmeen' => null,
          'hijri_year' => null,
        ];
      }

      // Add each takhmeen to takhmeens[]
      if (!empty($row['takhmeen_id'])) {
        $takhmeenEntry = [
          'id' => $row['takhmeen_id'],
          'year' => $row['takhmeen_year'],
          'amount' => $row['total_fmb_takhmeen'],
          'remark' => isset($row['takhmeen_remark']) ? $row['takhmeen_remark'] : null,
        ];
        $users[$itsId]['takhmeens'][] = $takhmeenEntry;

        if (isset($filter_data["year"]) && !empty($filter_data["year"])) {
          $takhmeen_year = $filter_data["year"];
        } else {
          if ($current_hijri_month >= "01" && $current_hijri_month <= "08") {
            $y1 = $current_hijri_year - 1;
            $y2 = substr($current_hijri_year, -2);
            $takhmeen_year = "$y1-$y2";
          } else if ($current_hijri_month >= "09" && $current_hijri_month <= "12") {
            $y1 = $current_hijri_year;
            $y2 = substr($current_hijri_year + 1, -2);
            $takhmeen_year = "$y1-$y2";
          }
        }

        $users[$itsId]['hijri_year'] = $takhmeen_year;

        if ($row['takhmeen_year'] == $takhmeen_year) {
          $users[$itsId]['current_year_takhmeen'] = $takhmeenEntry;
        }
      }
    }

    return array_values($users);
  }

  public function addfmbtakhmeenamount($data)
  {
    return $this->db->insert("fmb_takhmeen", $data);
  }

  public function deletefmbtakhmeen($data)
  {
    if ($data) {
      $user_id = $data["user_id"];
      $year = $data["year"];
    } else {
      return false;
    }
    $this->db->where("user_id", $user_id);
    $this->db->where("year", $year);
    return $this->db->delete("fmb_takhmeen");
  }
  public function validatefmbtakhmeen($data)
  {
    $user_id = $data["user_id"];
    $year = $data["year"];

    $this->db->from("fmb_takhmeen");
    $this->db->where("user_id = $user_id AND year = '$year'");
    return $this->db->get()->row_array();
  }
  public function updatefmbtakhmeen($data)
  {
    // Fetch old value for audit if available
    $this->db->from("fmb_takhmeen");
    $this->db->where("user_id", $data["user_id"]);
    $this->db->where("year", $data["year"]);
    $existing = $this->db->get()->row_array();

    // Update amount
    $this->db->where("user_id", $data["user_id"]);
    $this->db->where("year", $data["year"]);
    $ok = $this->db->update("fmb_takhmeen", ["total_amount" => $data["fmb_takhmeen_amount"], "remark" => isset($data["remark"]) ? $data["remark"] : null]);

    // Best-effort audit log if table exists and remark provided
    if ($ok && isset($data['remark']) && trim((string)$data['remark']) !== '') {
      $remark = trim($data['remark']);
      $updated_by = isset($data['updated_by']) ? $data['updated_by'] : 'admin';
      $old_amount = isset($existing['total_amount']) ? $existing['total_amount'] : null;
      // Check audit table existence
      $tbl = $this->db->query("SHOW TABLES LIKE 'fmb_takhmeen_audit'")->result_array();
      if (!empty($tbl)) {
        $this->db->insert('fmb_takhmeen_audit', [
          'user_id' => $data['user_id'],
          'year' => $data['year'],
          'old_amount' => $old_amount,
          'new_amount' => $data['fmb_takhmeen_amount'],
          'remark' => $remark,
          'updated_by' => $updated_by,
          'updated_at' => date('Y-m-d H:i:s')
        ]);
      }
    }
    return $ok;
  }

  public function get_user_sabeel_takhmeen_details($filter_data = null)
  {
    // 🔹 Step 1: Get current Hijri year & month
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

  // 🔹 Step 2: Fetch all raw rows (without duplicate payment join)
  // Coalesce numeric grade amounts to 0 so PHP receives numeric values (no NULLs)
  $this->db->select("
    u.ITS_ID,
    u.First_Name,
    u.Surname,
    u.Full_Name,
    u.Sector,
    u.Sub_Sector,

    st.id AS takhmeen_id,
    st.year AS takhmeen_year,

    est_grade.grade AS establishment_grade,
    COALESCE(est_grade.amount, 0) AS establishment_amount,

    res_grade.grade AS residential_grade,
    COALESCE(res_grade.amount, 0) AS residential_amount,
    COALESCE(res_grade.yearly_amount, COALESCE(res_grade.amount,0) * 12) AS residential_yearly_amount
  ");
    $this->db->from("user u");

    $this->db->join("sabeel_takhmeen st", "st.user_id = u.ITS_ID", "left");

    $this->db->join(
      "sabeel_takhmeen_grade est_grade",
      "est_grade.id = st.establishment_grade 
        AND est_grade.type = 'establishment' 
        AND est_grade.year = st.year",
      "left"
    );

    $this->db->join(
      "sabeel_takhmeen_grade res_grade",
      "res_grade.id = st.residential_grade 
        AND res_grade.type = 'residential' 
        AND res_grade.year = st.year",
      "left"
    );

    if (!empty($filter_data["member_name"])) {
      $this->db->like("u.Full_Name", $filter_data["member_name"]);
    }

    $this->db->where("u.Inactive_Status IS NULL 
                      AND u.HOF_FM_TYPE = 'HOF' 
                      AND u.Sector IS NOT NULL");

    $this->db->order_by("u.Sector, u.Sub_Sector, u.First_Name, u.Surname, st.year DESC");

    $query = $this->db->get();
    $rows = $query->result_array();

    // 🔹 Step 3: Fetch aggregated payments separately
    // 🔹 Step 3: Fetch aggregated payments (joined with takhmeen to get year)
    $this->db->select("
      p.user_id, 
      p.type, 
      SUM(p.amount) as total_paid
    ");

    $this->db->from("sabeel_takhmeen_payments p");
    $this->db->where_in("p.type", ["establishment", "residential"]);
    $this->db->group_by(["p.user_id", "p.type"]);
    $payQuery = $this->db->get();
    $payments = $payQuery->result_array();

    $paymentIndex = [];
    foreach ($payments as $p) {
      // index by user_id -> type ('establishment'|'residential') => total_paid (numeric)
      $paymentIndex[$p['user_id']][$p['type']] = isset($p['total_paid']) ? (float)$p['total_paid'] : 0.0;
    }

    // 🔹 Step 4: Restructure into grouped users
    $users = [];
    foreach ($rows as $row) {
      $itsId = $row['ITS_ID'];

      if (!isset($users[$itsId])) {
        $users[$itsId] = [
          'ITS_ID' => $row['ITS_ID'],
          'Full_Name' => $row['Full_Name'],
          'Sector' => $row['Sector'],
          'Sub_Sector' => $row['Sub_Sector'],
          'First_Name' => $row['First_Name'],
          'Surname' => $row['Surname'],
          'takhmeens' => [],
          'current_year_takhmeen' => null,
          'hijri_year' => null,
        ];
      }

      if (!empty($row['takhmeen_id'])) {
        // Payments are aggregated per user_id and type (not per year in the payments table)
        $estPaid = $paymentIndex[$itsId]['establishment'] ?? 0.0;
        $resPaid = $paymentIndex[$itsId]['residential'] ?? 0.0;

        // Ensure amounts are numeric (COALESCE above helps, but double-guard here)
        $est_amount = isset($row['establishment_amount']) ? (float)$row['establishment_amount'] : 0.0;
        $res_amount = isset($row['residential_amount']) ? (float)$row['residential_amount'] : 0.0;
        $res_yearly_amount = isset($row['residential_yearly_amount']) ? (float)$row['residential_yearly_amount'] : ($res_amount ? $res_amount * 12 : 0.0);

        $takhmeenEntry = [
          'id' => $row['takhmeen_id'],
          'year' => $row['takhmeen_year'],
          'establishment' => [
            'grade' => $row['establishment_grade'],
            'yearly' => $est_amount,
            'monthly' => $est_amount ? round($est_amount / 12, 2) : 0.0,
            'paid' => (float)$estPaid,
            'due' => max(0, $est_amount - (float)$estPaid),
          ],
          'residential' => [
            'grade' => $row['residential_grade'],
            'monthly' => $res_amount,
            'yearly' => $res_yearly_amount,
            'paid' => (float)$resPaid,
            'due' => max(0, $res_yearly_amount - (float)$resPaid),
          ]
        ];

        // Assign Hijri year calculation
        if (isset($filter_data["year"]) && !empty($filter_data["year"])) {
          $takhmeen_year = $filter_data["year"];
        } else {
          if ($current_hijri_month >= "01" && $current_hijri_month <= "08") {
            $y1 = $current_hijri_year - 1;
            $y2 = substr($current_hijri_year, -2);
            $takhmeen_year = "$y1-$y2";
          } else if ($current_hijri_month >= "09" && $current_hijri_month <= "12") {
            $y1 = $current_hijri_year;
            $y2 = substr($current_hijri_year + 1, -2);
            $takhmeen_year = "$y1-$y2";
          }
        }

        $users[$itsId]['hijri_year'] = $takhmeen_year;

        $users[$itsId]['takhmeens'][] = $takhmeenEntry;

        if ($row['takhmeen_year'] == $takhmeen_year) {
          $users[$itsId]['current_year_takhmeen'] = $takhmeenEntry;
        }
      }
    }

    // 🔹 Step 5: Ensure current_year_takhmeen fallback
    foreach ($users as &$u) {
      if (empty($u['current_year_takhmeen']) && !empty($u['takhmeens'])) {
        $u['current_year_takhmeen'] = $u['takhmeens'][0]; // latest year
      }
    }
    unset($u);

    return array_values($users);
  }

  public function checkSabeelTakhmeenExists($user_id, $year)
  {
    $exists = $this->db
      ->where("user_id", $user_id)
      ->where("year", $year)
      ->count_all_results("sabeel_takhmeen");

    return json_encode(["exists" => $exists > 0]);
  }
  public function getUserTakhmeenDetails($user_id, $year = null)
  {
    // 1. Get the latest year if not provided
    if (empty($year)) {
      $this->db->select("MAX(year) as year");
      $this->db->from("sabeel_takhmeen");
      $this->db->where("user_id", $user_id);
      $row = $this->db->get()->row_array();
      $year = $row ? $row["year"] : null;
    }

    if (!$year) {
      return []; // No takhmeen found
    }

    // 2. Fetch all grades for that year
    $this->db->from("sabeel_takhmeen_grade");
    $this->db->where("year", $year);
    $this->db->order_by("type ASC, grade ASC");
    $grades = $this->db->get()->result_array();

    // 3. Fetch user’s takhmeen record for that year
    $this->db->select("
        st.id,
        st.establishment_grade,
        st.residential_grade,
        est.amount AS establishment_amount,
        res.amount AS residential_amount
    ");
    $this->db->from("sabeel_takhmeen st");
    $this->db->join("sabeel_takhmeen_grade est", "est.id = st.establishment_grade", "left");
    $this->db->join("sabeel_takhmeen_grade res", "res.id = st.residential_grade", "left");
    $this->db->where("st.user_id", $user_id);
    $this->db->where("st.year", $year);
    $selected = $this->db->get()->row_array();

    return [
      "grades"   => $grades,
      "selected" => $selected,
      "year"     => $year
    ];
  }

  public function updatesabeeltakhmeen($data)
  {
    $user_id = $data["user_id"];
    $takhmeen_id = $data["takhmeen_id"];
    $establishment_grade = $data["establishment_grade"];
    $residential_grade = $data["residential_grade"];
    if (!$user_id || !$takhmeen_id) return false;
    $update = [];
    if (!empty($establishment_grade)) { $update['establishment_grade'] = $establishment_grade; }
    if (!empty($residential_grade))   { $update['residential_grade']   = $residential_grade; }
    if (empty($update)) return false; // nothing to update

    $this->db->where("user_id", $user_id);
    $this->db->where("id", $takhmeen_id);
    return $this->db->update("sabeel_takhmeen", $update);
  }

  public function deletesabeeltakhmeen($takhmeen_id)
  {
    if (!$takhmeen_id) return false;
    $this->db->where('id', $takhmeen_id);
    return $this->db->delete('sabeel_takhmeen');
  }

  public function getSabeelGrades($sabeel_year = null)
  {
    // ✅ Get latest year if none passed
    $this->db->from("sabeel_takhmeen_grade");
    $this->db->order_by("id DESC");
    $this->db->limit(1);
    $result = $this->db->get()->result_array();
    $year = $result[0]["year"] ?? null;

    if ($sabeel_year) {
      $year = $sabeel_year;
    }

    if (!$year) {
      return []; // no data
    }

    // ✅ Get all grades for the selected year
    $this->db->from("sabeel_takhmeen_grade");
    $this->db->where("year", $year);
    $this->db->order_by("grade ASC");
    $grades = $this->db->get()->result_array();

    // ✅ Add in_use flag by checking sabeel_takhmeen
    foreach ($grades as &$g) {
      $in_use = $this->db->where("year", $year)
        ->group_start()
        ->where("establishment_grade", $g['id'])
        ->or_where("residential_grade", $g['id'])
        ->group_end()
        ->count_all_results("sabeel_takhmeen");

      $g['in_use'] = $in_use > 0 ? true : false;
    }
    return $grades;
  }

  public function validatesabeelgrade($type, $year, $grade)
  {
    $this->db->from("sabeel_takhmeen_grade");
    $this->db->where("type", $type);
    $this->db->where("year", $year);
    $this->db->where("grade", $grade);
    $result = $this->db->get()->result_array();
    if (count($result) > 0) {
      return true;
    }
    return false;
  }
  public function addsabeelgrade($data)
  {
    return $this->db->insert("sabeel_takhmeen_grade", $data);
  }
  public function updatesabeelgrade($data)
  {
    $this->db->where("id", $data['id']);
    $result = $this->db->update("sabeel_takhmeen_grade", ["amount" => $data["amount"], "yearly_amount" => $data["yearly_amount"]]);
    if ($result) {
      return true;
    }
    return false;
  }
  public function deletesabeelgrade($id)
  {
    $this->db->where("id", $id);
    $result = $this->db->delete("sabeel_takhmeen_grade");
    if ($result) {
      return true;
    }
    return false;
  }

  // Sabeel Dashboard

  public function addsabeeltakhmeenamount($data)
  {
    // Allow inserting with only one of the two grades; store nulls for missing
    $insert = [
      'user_id' => $data['user_id'],
      'year' => $data['year'],
      'establishment_grade' => !empty($data['establishment_grade']) ? $data['establishment_grade'] : null,
      'residential_grade' => !empty($data['residential_grade']) ? $data['residential_grade'] : null,
    ];
    $result = $this->db->insert("sabeel_takhmeen", $insert);
    if ($result) {
      return true;
    }
    return false;
  }

  public function get_all_members()
  {
    $this->db->from("user");
    $this->db->where("Inactive_Status IS NULL AND Sector IS NOT NULL");
    $this->db->order_by("HOF_ID, Sector, Sub_Sector, First_Name, Surname");
    return $this->db->get()->result_array();
  }

  /**
   * Fetch distinct values for member filters (Sector, Sub_Sector, HOF ids, Status)
   * Status logic: Active if Inactive_Status IS NULL else Inactive
   */
  public function get_member_filter_meta()
  {
    // Distinct sectors (use query builder distinct to avoid escaping issue)
    $sectors = $this->db->distinct()
      ->select('Sector AS value')
      ->where("Sector IS NOT NULL AND Sector != ''")
      ->order_by('Sector ASC')
      ->get('user')->result_array();

    // Distinct sub sectors
    $subSectors = $this->db->distinct()
      ->select('Sub_Sector AS value')
      ->where("Sub_Sector IS NOT NULL AND Sub_Sector != ''")
      ->order_by('Sub_Sector ASC')
      ->get('user')->result_array();

    // Distinct HOF IDs (where HOF_FM_TYPE = HOF)
    $hofs = $this->db->select('ITS_ID AS value, Full_Name')
      ->where("HOF_FM_TYPE = 'HOF'")
      ->order_by('Full_Name ASC')
      ->get('user')->result_array();

    return [
      'sectors' => array_map(function ($r) {
        return $r['value'];
      }, $sectors),
      'sub_sectors' => array_map(function ($r) {
        return $r['value'];
      }, $subSectors),
      'hofs' => $hofs,
      'statuses' => ['active', 'inactive'],
    ];
  }

  /**
   * Build a mapping of Sector => list of distinct Sub_Sector values (excluding empty) for dependent dropdowns.
   * Returns array: [ 'SectorName' => ['Sub1','Sub2',...], ... ]
   */
  public function get_sector_hierarchy()
  {
    // Get all distinct sector/sub-sector combos
    $rows = $this->db->select('Sector, Sub_Sector')
      ->where("Sector IS NOT NULL AND Sector != ''")
      ->order_by('Sector ASC, Sub_Sector ASC')
      ->get('user')->result_array();
    $map = [];
    foreach ($rows as $r) {
      $sector = $r['Sector'];
      if (!isset($map[$sector])) $map[$sector] = [];
      $sub = $r['Sub_Sector'];
      if ($sub !== null && $sub !== '' && !in_array($sub, $map[$sector], true)) {
        $map[$sector][] = $sub;
      }
    }
    return $map;
  }

  /**
   * Get members with dynamic filters.
   * Filters keys: name (Full_Name partial), sector, sub_sector, status (active|inactive|all), hof (ITS_ID of HOF)
   */
  public function get_members_filtered($filters = [])
  {
    $this->db->from('user');

    // Status filter
    if (!empty($filters['status']) && in_array(strtolower($filters['status']), ['active', 'inactive'])) {
      if (strtolower($filters['status']) === 'active') {
        $this->db->where('Inactive_Status IS NULL');
      } else if (strtolower($filters['status']) === 'inactive') {
        $this->db->where('Inactive_Status IS NOT NULL');
      }
    }

    // Name filter
    if (!empty($filters['name'])) {
      $this->db->like('Full_Name', $filters['name']);
    }

    // Sector filter
    if (!empty($filters['sector'])) {
      $this->db->where('Sector', $filters['sector']);
    }

    // Sub Sector filter
    if (!empty($filters['sub_sector'])) {
      $this->db->where('Sub_Sector', $filters['sub_sector']);
    }

    // HOF filter (show only members belonging to that HOF household)
    if (!empty($filters['hof'])) {
      // Show either the HOF or FM with matching HOF_ID
      $hof_id = $filters['hof'];
      $this->db->group_start();
      $this->db->where('ITS_ID', $hof_id);
      $this->db->or_where('HOF_ID', $hof_id);
      $this->db->group_end();
    }

    $this->db->order_by('HOF_ID, Sector, Sub_Sector, First_Name, Surname');

    return $this->db->get()->result_array();
  }
  // Updated by Patel Infotech Services

  public function insert_miqaat($data)
  {
    $this->db->insert('rsvp', $data);
    return $this->db->affected_rows() > 0;
  }
  public function get_rsvp_byid($id)
  {
    $sql = "SELECT * FROM rsvp WHERE id = ? and active = 1";
    $query = $this->db->query($sql, array($id));
    return $query->result_array();
  }
  public function modify_miqaat($data, $id)
  {
    if (!empty($data)) {
      $this->db->where('id', $id);
      $this->db->update('rsvp', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  public function delete_miqaat($id)
  {
    $this->db->where('rsvp_id', $id);
    $this->db->delete('rsvp_attendance');
    $this->db->where('id', $id);
    $this->db->delete('rsvp');

    return true;
  }

  public function get_all_rsvp()
  {
    $sql = "SELECT *
        FROM rsvp
        ORDER BY rsvp.timestamp DESC";

    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_rsvp_attendance($rsvp)
  {
    $sql = "SELECT * FROM  rsvp_attendance where rsvp_id = ?";
    $query = $this->db->query($sql, array($rsvp));
    return $query->num_rows();
  }
  public function get_rsvp_attendance_guest($rsvp)
  {
    $sql = "SELECT COALESCE(SUM(male), 0) as male_count, COALESCE(SUM(female), 0) as female_count FROM guest_rsvp WHERE rsvp_id = ?";
    $query = $this->db->query($sql, array($rsvp));
    $result = $query->row_array();
    return $result;
  }
  public function get_rsvp_attendance_present($rsvp)
  {
    $sql = "SELECT * FROM  rsvp_attendance where rsvp_id=$rsvp and attend=1";
    $query = $this->db->query($sql);
    return $query->num_rows();
  }
  public function get_rsvp_attendance_present_gender($rsvp)
  {
    $sql = "SELECT SUM(CASE WHEN u.Gender = 'Male' THEN 1 ELSE 0 END) as male_count, SUM(CASE WHEN u.Gender = 'Female' THEN 1 ELSE 0 END) as female_count FROM rsvp_attendance a JOIN user u ON a.user_id = u.id WHERE a.rsvp_id = ? and attend =1";
    $query = $this->db->query($sql, array($rsvp));
    $result = $query->row_array();

    // Check if the result is not empty before returning
    if (!empty($result)) {
      return $result;
    } else {
      return array('male_count' => 0, 'female_count' => 0);
    }
  }
  public function get_user_count()
  {
    $sql = "SELECT COUNT(*) as user_count FROM `user`";
    $query = $this->db->query($sql);
    $result = $query->row_array();

    // Check if the result is not empty before returning
    if (!empty($result['user_count'])) {
      return $result['user_count'];
    } else {
      return 0; // Return 0 if there are no users
    }
  }
  public function addMumineen($data, $logindata)
  {
    $this->db->trans_start();

    // Insert member in user table
    $this->db->insert('user', $data);

    // Prepare login payload with safe defaults
    $itsId = isset($data['ITS_ID']) ? $data['ITS_ID'] : (isset($logindata['username']) ? $logindata['username'] : null);
    $hofId = null;
    if (isset($data['HOF_ID']) && $data['HOF_ID']) {
      $hofId = $data['HOF_ID'];
    } elseif (isset($data['HOF_FM_TYPE']) && $data['HOF_FM_TYPE'] === 'HOF') {
      $hofId = $itsId;
    } elseif (isset($logindata['hof'])) {
      $hofId = $logindata['hof'];
    } else {
      $hofId = $itsId;
    }

    $loginPayload = [
      'username' => $itsId,
      'password' => isset($logindata['password']) ? $logindata['password'] : md5($itsId),
      'hof'      => $hofId,
      'role'     => isset($logindata['role']) ? $logindata['role'] : 0,
      'active'   => isset($logindata['active']) ? $logindata['active'] : 1,
    ];

    // Create login only if it doesn't already exist
    if ($itsId) {
      $exists = $this->db->where('username', $itsId)->get('login')->row_array();
      if (!$exists) {
        $this->db->insert('login', $loginPayload);
      }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      // Transaction failed
      return false;
    } else {
      // Transaction succeeded
      return true;
    }
  }

  /* ================= Member Maintenance (Edit / Update) ================= */
  public function get_member_by_its($its_id)
  {
    if (!$its_id) return null;
    return $this->db->where('ITS_ID', $its_id)->get('user')->row_array();
  }

  public function get_all_hofs()
  {
    return $this->db->select('ITS_ID, Full_Name')
      ->where("HOF_FM_TYPE = 'HOF'")
      ->order_by('Full_Name ASC')
      ->get('user')->result_array();
  }

  public function update_member($its_id, $data)
  {
    if (!$its_id || empty($data)) return false;
    $this->db->where('ITS_ID', $its_id);
    return $this->db->update('user', $data);
  }

  /**
   * Return members for export applying lightweight filters.
   * Filters: status (active|inactive), sector, sub_sector
   */
  public function get_all_members_for_export($filters = [])
  {
    $this->db->from('user');
    if (!empty($filters['status'])) {
      if ($filters['status'] === 'active') $this->db->where('Inactive_Status IS NULL', null, false);
      elseif ($filters['status'] === 'inactive') $this->db->where('Inactive_Status IS NOT NULL', null, false);
    }
    if (!empty($filters['sector'])) {
      $this->db->where('Sector', $filters['sector']);
    }
    if (!empty($filters['sub_sector'])) {
      $this->db->where('Sub_Sector', $filters['sub_sector']);
    }
    $query = $this->db->get();
    return $query->result_array();
  }

  /**
   * Upsert member from associative row (CSV line). Returns: inserted|updated|skipped
   * Minimal fields enforced: ITS_ID, Full_Name
   */
  public function upsert_member_from_row($row)
  {
    $its = $row['ITS_ID'];
    $exists = $this->db->where('ITS_ID', $its)->get('user')->row_array();
    $allowed = [
      'ITS_ID',
      'Full_Name',
      'Full_Name_Arabic',
      'First_Name',
      'Surname',
      'First_Prefix',
      'Prefix_Year',
      'Father_Prefix',
      'Father_Name',
      'Father_Surname',
      'Husband_Prefix',
      'Husband_Name',
      'Gender',
      'Age',
      'Mobile',
      'Email',
      'WhatsApp_No',
      'Member_Type',
      'member_type',
      'Registered_Family_Mobile',
      'Father_ITS_ID',
      'Mother_ITS_ID',
      'Spouse_ITS_ID',
      'Family_ID',
      'TanzeemFile_No',
      'Misaq',
      'Marital_Status',
      'Blood_Group',
      'Warakatul_Tarkhis',
      'Date_Of_Nikah',
      'Date_Of_Nikah_Hijri',
      'Organisation',
      'Organisation_CSV',
      'Vatan',
      'Nationality',
      'Jamaat',
      'Jamiaat',
      'Qualification',
      'Languages',
      'Hunars',
      'Occupation',
      'Sub_Occupation',
      'Sub_Occupation2',
      'Quran_Sanad',
      'Qadambosi_Sharaf',
      'Raudat_Tahera_Ziyarat',
      'Karbala_Ziyarat',
      'Ashara_Mubaraka',
      'Housing',
      'Type_of_House',
      'Address',
      'Building',
      'Street',
      'Area',
      'State',
      'City',
      'Pincode',
      'Sector',
      'Sub_Sector',
      'Sector_Incharge_ITSID',
      'Sector_Incharge_Name',
      'Sector_Incharge_Female_ITSID',
      'Sector_Incharge_Female_Name',
      'Sub_Sector_Incharge_ITSID',
      'Sub_Sector_Incharge_Name',
      'Sub_Sector_Incharge_Female_ITSID',
      'Sub_Sector_Incharge_Female_Name',
      'Data_Verifcation_Status',
      'Data_Verification_Date',
      'Photo_Verifcation_Status',
      'Photo_Verification_Date',
      'Last_Scanned_Event',
      'Last_Scanned_Place',
      'Title',
      'Category',
      'Idara',
      'Inactive_Status'
    ];
    $clean = [];
    foreach ($allowed as $f) {
      if (isset($row[$f]) && $row[$f] !== '') $clean[$f] = trim($row[$f]);
    }
    if (empty($clean['Full_Name'])) return 'skipped';
    if (!$exists) {
      // Default new imports to HOF_FM_TYPE FM unless self-referencing later corrections
      if (!isset($clean['HOF_FM_TYPE'])) $clean['HOF_FM_TYPE'] = 'FM';
      $this->db->insert('user', $clean);
      return 'inserted';
    } else {
      if (empty($clean)) return 'skipped';
      $this->db->where('ITS_ID', $its)->update('user', $clean);
      return 'updated';
    }
  }

  /**
   * Mark members as 'Moved-Out Mumineen' if their ITS_ID is not in the latest imported list.
   * Skips already moved-out to avoid redundant writes.
   * Returns number of rows affected.
   */
  public function mark_members_moved_out($currentItsIds)
  {
    if (!is_array($currentItsIds)) return 0;
    // Ensure sanitized ITS IDs
    $cleanIds = array_values(array_filter(array_map('trim', $currentItsIds), function ($v) {
      return $v !== '';
    }));
    if (empty($cleanIds)) {
      // If nothing imported, avoid marking entire table; caller should guard.
      return 0;
    }
    $this->db->where_not_in('ITS_ID', $cleanIds);
    $this->db->where("(member_type != 'Moved-Out Mumineen' OR member_type IS NULL)", null, false);
    $this->db->set('member_type', 'Moved-Out Mumineen');
    $this->db->update('user');
    return $this->db->affected_rows();
  }

  /**
   * Insert a new member. Enforces ITS_ID uniqueness and HOF logic.
   * Returns ['status'=>'success','message'=>'...'] or ['status'=>'error','message'=>'...']
   */
  public function create_member($payload)
  {
    if (empty($payload['ITS_ID'])) {
      return ['status' => 'error', 'message' => 'ITS ID is required'];
    }
    // Uniqueness check
    $exists = $this->db->where('ITS_ID', $payload['ITS_ID'])->get('user')->row_array();
    if ($exists) {
      return ['status' => 'error', 'message' => 'ITS ID already exists'];
    }
    // Validate member_type if provided
    if (isset($payload['member_type']) && $payload['member_type'] !== '') {
      $allowed_member_types = [
        'Resident Mumineen',
        'External Sabeel Payers',
        'Moved-Out Mumineen',
        'Non-Sabeel Residents',
        'Temporary Mumineen/Visitors'
      ];
      if (!in_array($payload['member_type'], $allowed_member_types, true)) {
        return ['status' => 'error', 'message' => 'Invalid member type'];
      }
    }
    $isHof = isset($payload['hof_type']) && $payload['hof_type'] === 'HOF';
    // Normalize HOF fields
    $payload['HOF_FM_TYPE'] = $isHof ? 'HOF' : 'FM';
    $payload['HOF_ID'] = $isHof ? $payload['ITS_ID'] : ($payload['HOF_ID'] ?? null);
    if (!$isHof && empty($payload['HOF_ID'])) {
      return ['status' => 'error', 'message' => 'HOF selection required for family member'];
    }
    // Allowed fields similar to update whitelist
    $fields = [
      'ITS_ID',
      'Full_Name',
      'Full_Name_Arabic',
      'First_Name',
      'Surname',
      'First_Prefix',
      'Prefix_Year',
      'Father_Prefix',
      'Father_Name',
      'Father_Surname',
      'Husband_Prefix',
      'Husband_Name',
      'Gender',
      'Age',
      'Mobile',
      'Email',
      'WhatsApp_No',
      'HOF_FM_TYPE',
      'HOF_ID',
      'Member_Type',
      'member_type',
      'Registered_Family_Mobile',
      'Father_ITS_ID',
      'Mother_ITS_ID',
      'Spouse_ITS_ID',
      'Family_ID',
      'TanzeemFile_No',
      'Misaq',
      'Marital_Status',
      'Blood_Group',
      'Warakatul_Tarkhis',
      'Date_Of_Nikah',
      'Date_Of_Nikah_Hijri',
      'Organisation',
      'Organisation_CSV',
      'Vatan',
      'Nationality',
      'Jamaat',
      'Jamiaat',
      'Qualification',
      'Languages',
      'Hunars',
      'Occupation',
      'Sub_Occupation',
      'Sub_Occupation2',
      'Quran_Sanad',
      'Qadambosi_Sharaf',
      'Raudat_Tahera_Ziyarat',
      'Karbala_Ziyarat',
      'Ashara_Mubaraka',
      'Housing',
      'Type_of_House',
      'Address',
      'Building',
      'Street',
      'Area',
      'State',
      'City',
      'Pincode',
      'Sector',
      'Sub_Sector',
      'Sector_Incharge_ITSID',
      'Sector_Incharge_Name',
      'Sector_Incharge_Female_ITSID',
      'Sector_Incharge_Female_Name',
      'Sub_Sector_Incharge_ITSID',
      'Sub_Sector_Incharge_Name',
      'Sub_Sector_Incharge_Female_ITSID',
      'Sub_Sector_Incharge_Female_Name',
      'Data_Verifcation_Status',
      'Data_Verification_Date',
      'Photo_Verifcation_Status',
      'Photo_Verification_Date',
      'Last_Scanned_Event',
      'Last_Scanned_Place',
      'Title',
      'Category',
      'Idara',
      'Inactive_Status'
    ];
    $insert = [];
    foreach ($fields as $f) {
      if (isset($payload[$f]) && $payload[$f] !== '') {
        $insert[$f] = is_string($payload[$f]) ? trim($payload[$f]) : $payload[$f];
      }
    }
    // Final safety: ensure mandatory minimal fields
    if (empty($insert['Full_Name'])) {
      return ['status' => 'error', 'message' => 'Full Name required'];
    }
    if (empty($insert['ITS_ID'])) {
      return ['status' => 'error', 'message' => 'ITS ID required'];
    }
    // Wrap in transaction to also create a corresponding login entry atomically
    $this->db->trans_start();
    $ok = $this->db->insert('user', $insert);

    if ($ok) {
      // Create login for the new member if not present
      $itsId = $insert['ITS_ID'];
      $hofId = !empty($insert['HOF_ID']) ? $insert['HOF_ID'] : $itsId; // default to self if HOF unknown
      $existingLogin = $this->db->where('username', $itsId)->get('login')->row_array();
      if (!$existingLogin) {
        $loginRow = [
          'username' => $itsId,
          'password' => md5($itsId), // default password = ITS ID (hashed)
          'role'     => 0,
          'hof'      => $hofId,
          'active'   => 1,
        ];
        $this->db->insert('login', $loginRow);
      }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      return ['status' => 'error', 'message' => 'Insert failed'];
    }
    return ['status' => 'success', 'message' => 'Member created'];
  }
}
