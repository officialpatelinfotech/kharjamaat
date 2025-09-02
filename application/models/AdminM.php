<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class AdminM extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
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
    $last_7th_day_in_calendar = "";
    $get_last_7th_day_in_calendar = "SELECT * FROM hijri_calendar ORDER BY greg_date DESC LIMIT 7";
    $last_7th_day = $this->db->query($get_last_7th_day_in_calendar)->result_array();
    $last_7th_day_in_calendar = $last_7th_day[count($last_7th_day) - 1]["greg_date"];

    $today = date("Y-m-d");
    $last_date_in_calendar = date("Y-m-d", strtotime($last_7th_day_in_calendar));

    if ($today != $last_date_in_calendar) {
      return;
    }

    $get_last_date = "SELECT * FROM hijri_calendar ORDER BY greg_date DESC LIMIT 1";
    $last_date = $this->db->query($get_last_date)->result_array();
    $last_greg_date = $last_date[0]["greg_date"];

    $last_hijri_date = (int)explode("-", $last_date[0]["hijri_date"])[2] + 1;

    $position_in_cycle = $last_hijri_date % 30;
    $leap_years = [2, 5, 8, 10, 13, 16, 19, 21, 24, 27, 29];
    $is_leap_year = in_array($position_in_cycle, $leap_years);
    $number_of_days_in_year = $is_leap_year ? 355 : 354;

    $get_hijri_dates = "SELECT * FROM hijri_calendar WHERE hijri_date BETWEEN '1447-01-01' AND '1447-12-29'";

    $hijri_dates = $this->db->query($get_hijri_dates)->result_array();


    $last_greg_date = new DateTime($last_greg_date);

    for ($day = 0; $day < $number_of_days_in_year; $day++) {
      if (isset($hijri_dates[$day])) {
        $upcoming_year_hijri_date = preg_replace("/1447/", $last_hijri_date, $hijri_dates[$day]["hijri_date"]);
        $month_number = preg_match('/^\d{4}-(\d{2})-\d{2}$/', $upcoming_year_hijri_date, $matches);
        $month_number = (int)$matches[1];
      } else {
        $upcoming_year_hijri_date = $last_hijri_date . "-12-30";
      };
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
    $this->db->select("
      u.ITS_ID,
      u.First_Name,
      u.Surname,
      u.Sector,
      u.Sub_Sector,
      fmb_t.id AS takhmeen_id,
      fmb_t.year AS takhmeen_year,
      IFNULL(fmb_t.total_amount, 0) AS total_fmb_takhmeen
    ");
    $this->db->from("user u");

    $this->db->join(
      "fmb_takhmeen fmb_t",
      "fmb_t.user_id = u.ITS_ID 
      AND fmb_t.year = (
        SELECT MAX(fmb_t1.year) 
        FROM fmb_takhmeen fmb_t1 
        WHERE fmb_t1.user_id = u.ITS_ID
      )",
      "left",
      false
    );
    if (isset($filter_data)) {
      $this->db->like("u.Full_Name", $filter_data["member_name"]);
    }
    $this->db->where("u.Inactive_Status IS NULL AND u.HOF_FM_TYPE = 'HOF' AND u.Sector IS NOT NULL");
    $this->db->order_by("u.Sector, u.Sub_Sector, u.First_Name, u.Surname");
    $query = $this->db->get();
    return $query->result_array();
  }
  public function addfmbtakhmeenamount($data)
  {
    return $this->db->insert("fmb_takhmeen", $data);
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
    $this->db->where("user_id", $data["user_id"]);
    $this->db->where("year", $data["year"]);
    return $this->db->update("fmb_takhmeen", ["total_amount" => $data["fmb_takhmeen_amount"]]);
  }

  public function get_user_sabeel_takhmeen_details($filter_data = null)
  {
    $this->db->select("
        u.ITS_ID,
        u.First_Name,
        u.Surname,
        u.Sector,
        u.Sub_Sector,

        est_grade.grade AS establishment_grade,
        est_grade.amount AS establishment_yearly,
        ROUND(est_grade.amount / 12, 2) AS establishment_monthly,

        res_grade.grade as residential_grade,
        res_grade.amount AS residential_monthly,
        ROUND(res_grade.amount * 12, 2) AS residential_yearly,

        st.id as sabeel_id,
        st.year AS sabeel_year
    ");
    $this->db->from("user u");

    // Latest Sabeel Takhmeen per user
    $this->db->join(
      "sabeel_takhmeen st",
      "st.user_id = u.ITS_ID 
        AND st.year = (
            SELECT MAX(st1.year) 
            FROM sabeel_takhmeen st1 
            WHERE st1.user_id = u.ITS_ID
        )",
      "left",
      false
    );

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

    if (!empty($filter_data) && !empty($filter_data["member_name"])) {
      $this->db->like("u.Full_Name", $filter_data["member_name"]);
    }

    $this->db->where("u.Inactive_Status IS NULL 
                      AND u.HOF_FM_TYPE = 'HOF' 
                      AND u.Sector IS NOT NULL");

    $this->db->order_by("u.Sector, u.Sub_Sector, u.First_Name, u.Surname");

    $query = $this->db->get();
    return $query->result_array();
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

    if (
      $user_id
      && $takhmeen_id
      && $establishment_grade
      && $residential_grade
    ) {
      $this->db->where("user_id = '$user_id' AND id = $takhmeen_id");
      $result = $this->db->update("sabeel_takhmeen", [
        "establishment_grade" => $establishment_grade,
        "residential_grade" => $residential_grade,
      ]);
      return $result;
    }
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
    $result = $this->db->insert("sabeel_takhmeen", $data);
    if ($result) {
      return true;
    }
    return false;
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

    $this->db->insert('user', $data);
    $this->db->insert('login', $logindata);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      // Transaction failed
      return false;
    } else {
      // Transaction succeeded
      return true;
    }
  }
}
