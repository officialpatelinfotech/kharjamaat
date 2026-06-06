<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class AmilsahebM extends CI_Model
{
  /** @var bool Whether user table has the activity_status column (may not exist on older DBs) */
  private $has_activity_status = false;

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
    // Detect if activity_status column exists (graceful degradation for older DB schemas)
    $this->has_activity_status = $this->db->field_exists('activity_status', 'user');
  }
  function get_raza_event($event_type = null)
  {
    if ($event_type == 1) {
      $sql = " SELECT * FROM `raza` WHERE active=1  AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE  `umoor` IN ('Public-Event')) ORDER BY `raza`.`time-stamp` DESC";
    } else if ($event_type == 2) {
      $sql = " SELECT * FROM `raza` WHERE active=1  AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE  `umoor` IN ('Private-Event')) ORDER BY `raza`.`time-stamp` DESC";
    } else {
      $sql = " SELECT * FROM `raza` WHERE active=1  AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE  `umoor` IN ('Public-Event', 'Private-Event')) ORDER BY `raza`.`time-stamp` DESC";
    }
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  function get_raza_umoor()
  {
    $sql = " SELECT * FROM `raza` WHERE active=1  AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE `umoor` IN ('UmoorDeeniyah', 'UmoorTalimiyah', 'UmoorMarafiqBurhaniyah', 'UmoorMaaliyah', 'UmoorMawaridBashariyah', 'UmoorDakheliyah', 'UmoorKharejiyah', 'UmoorIqtesadiyah', 'UmoorFMB', 'UmoorAl-Qaza', 'UmoorAl-Amlaak', 'UmoorAl-Sehhat')) ORDER BY `raza`.`time-stamp` DESC";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_raza_count_event($condition, $event_type, $timestampCondition = null)
  {
    // Convert the condition array into a string for SQL query
    $whereClause = '';
    foreach ($condition as $key => $value) {
      $whereClause .= "`$key` = '$value' AND ";
    }
    $whereClause = rtrim($whereClause, 'AND ');

    // Add timestamp condition if provided
    // if ($timestampCondition) {
    //   $whereClause .= " AND `time-stamp` >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
    // }

    if ($event_type == 1) {
      $sql = "SELECT COUNT(*) AS count FROM `raza` WHERE active = 1 AND $whereClause AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE `umoor` IN ('Public-Event'))";
      $query = $this->db->query($sql);
      return $query->row()->count;
    } else if ($event_type == 2) {
      $sql = "SELECT COUNT(*) AS count FROM `raza` WHERE active = 1 AND $whereClause AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE `umoor` IN ('Private-Event'))";
      $query = $this->db->query($sql);
      return $query->row()->count;
    }
    $sql = "SELECT COUNT(*) AS count FROM `raza` WHERE active = 1 AND $whereClause AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE `umoor` IN ('Private-Event', 'Personal-Event'))";
    $query = $this->db->query($sql);
    return $query->row()->count;
  }



  public function get_raza_count_umoor($condition, $timestampCondition = null)
  {
    // Convert the condition array into a string for SQL query
    $whereClause = '';
    foreach ($condition as $key => $value) {
      $whereClause .= "`$key` = '$value' AND ";
    }
    $whereClause = rtrim($whereClause, 'AND ');

    // Add timestamp condition if provided
    // if ($timestampCondition) {
    //   $whereClause .= " AND `time-stamp` >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
    // }

    // echo json_encode($whereClause); exit;

    $sql = "SELECT COUNT(*) AS count FROM `raza` WHERE active = 1 AND $whereClause AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE `umoor` IN ('UmoorDeeniyah', 'UmoorTalimiyah', 'UmoorMarafiqBurhaniyah', 'UmoorMaaliyah', 'UmoorMawaridBashariyah', 'UmoorDakheliyah', 'UmoorKharejiyah', 'UmoorIqtesadiyah', 'UmoorFMB', 'UmoorAl-Qaza', 'UmoorAl-Amlaak', 'UmoorAl-Sehhat'))";
    $query = $this->db->query($sql);
    return $query->row()->count;
  }
  function approve_raza($raza_id, $remark)
  {
    $data = array(
      'Janab-status' => 1,
      'status' => 2,
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
    $data = array(
      'Janab-status' => 2,
      'status' => 3,
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
  public function get_all_users()
  {
    $this->db->select('user.*, (CASE WHEN user.ITS_ID NOT IN (SELECT students_its_id FROM madresa_class_admission) THEN 1 ELSE 0 END) as madresa_deprived', false);
    return $this->db->get('user')->result();
  }

  /**
   * Get users filtered by a set of criteria.
   * Accepts array of GET params. Supports legacy `filter`/`value` pair
   * and explicit params: name, sector, sub, status, hof, min, max.
   */
  public function get_users_filtered($params = [])
  {
    $this->db->select('user.*, (CASE WHEN user.ITS_ID NOT IN (SELECT students_its_id FROM madresa_class_admission) THEN 1 ELSE 0 END) as madresa_deprived', false)->from('user');

    // Legacy single filter (case-insensitive match to actual DB columns)
    if (!empty($params['filter']) && isset($params['value'])) {
      $filterRaw = $params['filter'];
      $value = $params['value'];
      $matchedField = null;
      // list_fields returns actual column names; match ignoring case
      $fields = $this->db->list_fields('user');
      foreach ($fields as $f) {
        if (strtolower($f) === strtolower($filterRaw)) {
          $matchedField = $f;
          break;
        }
      }
      if ($matchedField) {
        $this->db->where($matchedField, $value);
      } else {
        // common alias fallback
        if (strtolower($filterRaw) === 'hof_fm_type') {
          $this->db->where('HOF_FM_TYPE', $value);
        } elseif (strtolower($filterRaw) === 'gender') {
          $this->db->where('Gender', $value);
        }
      }
    }

    // Explicit params
    if (!empty($params['name'])) {
      $name = trim($params['name']);
      $this->db->group_start();
      $this->db->like('Full_Name', $name);
      $this->db->or_like('ITS_ID', $name);
      $this->db->or_like('Mobile', $name);
      $this->db->group_end();
    }
    if (!empty($params['sector'])) {
      $this->db->where('Sector', $params['sector']);
    }
    if (!empty($params['sub'])) {
      $this->db->where('Sub_Sector', $params['sub']);
    }
    if (!empty($params['status'])) {
      $status = strtolower(trim($params['status']));
      if ($status === 'active') {
        if ($this->has_activity_status) {
          $this->db->where("((inactive_status IS NULL OR inactive_status = '') AND (activity_status = 'active' OR activity_status IS NULL OR activity_status = ''))", null, false);
        } else {
          $this->db->where("(inactive_status IS NULL OR inactive_status = '')", null, false);
        }
      } elseif ($status === 'inactive') {
        if ($this->has_activity_status) {
          $this->db->where("((inactive_status IS NOT NULL AND inactive_status != '') OR (activity_status != 'active' AND activity_status IS NOT NULL AND activity_status != ''))", null, false);
        } else {
          $this->db->where("(inactive_status IS NOT NULL AND inactive_status != '')", null, false);
        }
      }
    }
    if (!empty($params['its_sabeel_match'])) {
      $this->db->where('its_sabeel_match', $params['its_sabeel_match']);
    }
    if (!empty($params['hof'])) {
      // hof can be ITS_ID of hof
      $this->db->where('HOF_ID', $params['hof']);
    }

      // Marital status
      if (!empty($params['marital_status'])) {
        $this->db->where('Marital_Status', $params['marital_status']);
      } elseif (!empty($params['marital'])) {
        $this->db->where('Marital_Status', $params['marital']);
      } elseif (!empty($params['ms'])) {
        $this->db->where('Marital_Status', $params['ms']);
      }

    // Age range
      if (isset($params['age_min']) && is_numeric($params['age_min']) && !isset($params['min'])) {
        $params['min'] = $params['age_min'];
      }
      if (isset($params['age_max']) && is_numeric($params['age_max']) && !isset($params['max'])) {
        $params['max'] = $params['age_max'];
      }
    if (isset($params['min']) && is_numeric($params['min'])) {
      $this->db->where('Age >=', (int)$params['min']);
    }
    if (isset($params['max']) && is_numeric($params['max'])) {
      $this->db->where('Age <=', (int)$params['max']);
    }
    if (isset($params['madresa_deprived']) && $params['madresa_deprived'] !== '') {
      if ($params['madresa_deprived'] == '1') {
        $this->db->where("ITS_ID NOT IN (SELECT students_its_id FROM madresa_class_admission)", null, false);
      } elseif ($params['madresa_deprived'] == '0') {
        $this->db->where("ITS_ID IN (SELECT students_its_id FROM madresa_class_admission)", null, false);
      }
    }

    return $this->db->get()->result();
  }
  public function update_user_by_its_id($its_id, $data)
  {
    // Ensure ITS_ID is set
    if (!$its_id || empty($data))
      return false;

    $this->db->where('ITS_ID', $its_id);
    return $this->db->update('user', $data);
  }

  public function getSlotsData()
  {
    // Change 'slots' to your actual table name
    $query = $this->db->get('slots');

    // Return the result as an array
    return $query->result_array();
  }

  /**
   * Get per-day slot summary for a given month/year.
   * Returns associative array keyed by YYYY-MM-DD => ['available'=>int,'booked'=>int]
   */
  public function get_slot_summary_for_month($year, $month)
  {
    $start = sprintf('%04d-%02d-01', (int)$year, (int)$month);
    $end = date('Y-m-t', strtotime($start));

    $sql = "SELECT s.date AS dt, COUNT(s.slot_id) AS total_slots, 
      COALESCE(SUM(CASE WHEN a.id IS NOT NULL THEN 1 ELSE 0 END),0) AS booked
      FROM slots s
      LEFT JOIN appointments a ON a.slot_id = s.slot_id
      WHERE s.active = 1 AND s.date >= ? AND s.date <= ?
      GROUP BY s.date";

    $query = $this->db->query($sql, [$start, $end]);
    $rows = $query->result_array();
    $out = [];
    foreach ($rows as $r) {
      $total = (int)$r['total_slots'];
      $booked = (int)$r['booked'];
      $available = max(0, $total - $booked);
      $out[$r['dt']] = [
        'total' => $total,
        'booked' => $booked,
        'available' => $available
      ];
    }
    // Merge FMB calendar days (if any) for the month range
    $fmbRows = $this->db->query("SELECT date, day_type FROM fmb_calendar_days WHERE date >= ? AND date <= ?", [$start, $end])->result_array();
    foreach ($fmbRows as $fr) {
      $d = $fr['date'];
      if (!isset($out[$d])) {
        $out[$d] = ['total' => 0, 'booked' => 0, 'available' => 0];
      }
      $out[$d]['fmb_day'] = isset($fr['day_type']) ? $fr['day_type'] : '';
    }
    return $out;
  }

  /**
   * Get per-day slot summary for an arbitrary date range (inclusive).
   * Returns associative array keyed by YYYY-MM-DD => ['total'=>int,'booked'=>int,'available'=>int]
   */
  public function get_slot_summary_for_range($start_date, $end_date)
  {
    $sql = "SELECT s.date AS dt, COUNT(s.slot_id) AS total_slots, 
      COALESCE(SUM(CASE WHEN a.id IS NOT NULL THEN 1 ELSE 0 END),0) AS booked
      FROM slots s
      LEFT JOIN appointments a ON a.slot_id = s.slot_id
      WHERE s.active = 1 AND s.date >= ? AND s.date <= ?
      GROUP BY s.date";

    $query = $this->db->query($sql, [$start_date, $end_date]);
    $rows = $query->result_array();
    $out = [];
    foreach ($rows as $r) {
      $total = (int)$r['total_slots'];
      $booked = (int)$r['booked'];
      $available = max(0, $total - $booked);
      $out[$r['dt']] = [
        'total' => $total,
        'booked' => $booked,
        'available' => $available
      ];
    }
    // Merge FMB calendar days (if any) for the requested range
    $fmbRows = $this->db->query("SELECT date, day_type FROM fmb_calendar_days WHERE date >= ? AND date <= ?", [$start_date, $end_date])->result_array();
    foreach ($fmbRows as $fr) {
      $d = $fr['date'];
      if (!isset($out[$d])) {
        $out[$d] = ['total' => 0, 'booked' => 0, 'available' => 0];
      }
      $out[$d]['fmb_day'] = isset($fr['day_type']) ? $fr['day_type'] : '';
    }
    return $out;
  }
  public function saveSlots($selectedDate, $selectedTimeSlots)
  {
    // Delete existing records for the selected date
    $this->db->where('date', $selectedDate);
    $this->db->update('slots', array('active' => 0));

    // Insert new records for the selected date and time slots
    foreach ($selectedTimeSlots as $selectedTime) {
      $data = array(
        'date' => $selectedDate,
        'time' => $selectedTime
      );
      $this->db->insert('slots', $data);
    }
  }
  public function deleteSlots($selectedDate)
  {
    $this->db->where('date', $selectedDate);
    $this->db->update('slots', array('active' => 0));
  }
  public function deleteSlot($id)
  {
    $this->db->where('slot_id', $id);
    $this->db->update('slots', array('active' => 0));
  }
  public function unassignSlot($id)
  {
    $this->db->where('slot_id', $id);
    $this->db->delete('appointments');
  }
  public function addslot($selectedDate, $selectedTime)
  {
    $data = array(
      'date' => $selectedDate,
      'time' => $selectedTime
    );
    // Insert three physical slot rows per time to represent capacity of 3
    for ($i = 0; $i < 3; $i++) {
      $this->db->insert('slots', $data);
    }
  }

  public function getExistingTimeSlots($selectedDate)
  {
    $this->db->select('time , slot_id');
    $this->db->where('date', $selectedDate);
    $this->db->where('active', 1);
    $query = $this->db->get('slots');
    return $query->result_array();
  }
  public function get_today_appointment()
  {
    $current_date = date('Y-m-d');
    $current_date = "2024-03-14";

    $this->db->select('*');
    $this->db->from('appointments');
    $this->db->join('slots', 'appointments.slot_id = slots.slot_id');
    $this->db->where('slots.date', $current_date);
    $query = $this->db->get();
    return $query->result_array();
  }
  public function get_all_appointment()
  {

    $this->db->select('appointments.*, slots.date, slots.time, u.Mobile as mobile');
    $this->db->from('appointments');
    $this->db->join('slots', 'appointments.slot_id = slots.slot_id');
    $this->db->join('user u', 'appointments.its = u.ITS_ID', 'left');
    $this->db->where('slots.active', 1);
    $this->db->order_by('slots.date', 'DESC');
    $query = $this->db->get();
    return $query->result_array();
  }
  public function update_appointment_list($appointment_id)
  {
    $this->db->select('status');
    $this->db->where('id', $appointment_id);
    $current_status = $this->db->get('appointments')->row()->status;

    $new_status = ($current_status == 0) ? 1 : 0;
    $this->db->where('id', $appointment_id);
    $this->db->update('appointments', array('status' => $new_status));
    $toggled = ($new_status != $current_status);

    return $toggled;
  }



  public function get_all_ashara($year = null)
  {
    $this->db->select('u.ITS_ID as ITS, ao.LeaveStatus, ao.Comment, u.Full_Name, u.HOF_ID, u.HOF_FM_TYPE, u.Age, u.Gender, u.Mobile, u.Sector, u.Sub_Sector');
    $this->db->from('user u');
    $actFilter = $this->has_activity_status ? " and (u.activity_status = 'active' or u.activity_status is null or u.activity_status = '')" : '';
    $this->db->where("((u.inactive_status is null or u.inactive_status = '')$actFilter)", null, false);
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'ao.ITS = u.ITS_ID AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'ao.ITS = u.ITS_ID', 'left');
    }
    return $this->db->get()->result_array();
  }

  public function search_all_ashara($keyword, $year = null)
  {
    $this->db->select('u.ITS_ID as ITS, ao.LeaveStatus, ao.Comment, u.Full_Name, u.HOF_ID, u.HOF_FM_TYPE, u.Age, u.Gender, u.Mobile, u.Sector, u.Sub_Sector');
    $this->db->from('user u');
    $actFilter = $this->has_activity_status ? " and (u.activity_status = 'active' or u.activity_status is null or u.activity_status = '')" : '';
    $this->db->where("((u.inactive_status is null or u.inactive_status = '')$actFilter)", null, false);
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'ao.ITS = u.ITS_ID AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'ao.ITS = u.ITS_ID', 'left');
    }
    $this->db->group_start()
      ->like('u.ITS_ID', $keyword)
      ->or_like('u.Full_Name', $keyword)
      ->or_like('u.Mobile', $keyword)
      ->or_like('ao.LeaveStatus', $keyword)
      ->or_like('ao.Comment', $keyword)
      ->group_end();
    return $this->db->get()->result_array();
  }

  public function get_all_sector_stats($year = null)
  {
    $this->db->select([
      'u.Sector',
      'COUNT(*) as total',
      'SUM(CASE WHEN u.HOF_FM_TYPE = "HOF" THEN 1 ELSE 0 END) as hof_count',
      'SUM(CASE WHEN u.HOF_FM_TYPE = "FM" THEN 1 ELSE 0 END) as fm_count',
      'SUM(CASE WHEN LOWER(u.Gender) = "male" THEN 1 ELSE 0 END) as male_count',
      'SUM(CASE WHEN LOWER(u.Gender) = "female" THEN 1 ELSE 0 END) as female_count',
      'SUM(CASE WHEN u.Age BETWEEN 0 AND 4 THEN 1 ELSE 0 END) as age_0_4',
      'SUM(CASE WHEN u.Age BETWEEN 5 AND 15 THEN 1 ELSE 0 END) as age_5_15',
      'SUM(CASE WHEN u.Age BETWEEN 16 AND 25 THEN 1 ELSE 0 END) as age_16_25',
      'SUM(CASE WHEN u.Age BETWEEN 26 AND 65 THEN 1 ELSE 0 END) as age_26_65',
      'SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) as seniors_count',
      'SUM(CASE WHEN ao.LeaveStatus IS NULL OR ao.LeaveStatus = "" THEN 1 ELSE 0 END) as no_status_count',
      'MAX(u.Sector_Incharge_Name) as Sector_Incharge_Name',
      'MAX(u.Sector_Incharge_Female_Name) as Sector_Incharge_Female_Name'
    ]);
    $this->db->from('user u');
    $actFilter = $this->has_activity_status ? " and (u.activity_status = 'active' or u.activity_status is null or u.activity_status = '')" : '';
    $this->db->where("((u.inactive_status is null or u.inactive_status = '')$actFilter)", null, false);
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');
    }
    $this->db->group_by('u.Sector');
    $this->db->order_by('u.Sector');
    $rows = $this->db->get()->result_array();

    // Fetch sub-sectors and attach their incharges to each sector row
    $subSql = "
      SELECT Sector, Sub_Sector, MAX(Sub_Sector_Incharge_Name) as Sub_Sector_Incharge_Name, MAX(Sub_Sector_Incharge_Female_Name) as Sub_Sector_Incharge_Female_Name
      FROM user
      WHERE Sector IS NOT NULL AND Sub_Sector IS NOT NULL
      GROUP BY Sector, Sub_Sector
    ";
    $subRows = $this->db->query($subSql)->result_array();

    $subMap = [];
    foreach ($subRows as $sr) {
      $sec = $sr['Sector'];
      if (!isset($subMap[$sec])) $subMap[$sec] = [];
      // Only include sub-sectors that have at least one incharge assigned
      if (!empty(trim($sr['Sub_Sector_Incharge_Name'] ?? '')) || !empty(trim($sr['Sub_Sector_Incharge_Female_Name'] ?? ''))) {
        $subMap[$sec][] = $sr;
      }
    }

    foreach ($rows as &$r) {
      $r['sub_sectors'] = isset($subMap[$r['Sector']]) ? $subMap[$r['Sector']] : [];
    }
    unset($r);

    return $rows;
  }

  public function get_all_sub_sector_stats($year = null)
  {
    $this->db->select([
      'u.Sector',
      'u.Sub_Sector as SubSector',
      'COUNT(*) as total',
      'SUM(CASE WHEN u.HOF_FM_TYPE = "HOF" THEN 1 ELSE 0 END) as hof_count',
      'SUM(CASE WHEN u.HOF_FM_TYPE = "FM" THEN 1 ELSE 0 END) as fm_count',
      'SUM(CASE WHEN LOWER(u.Gender) = "male" THEN 1 ELSE 0 END) as male_count',
      'SUM(CASE WHEN LOWER(u.Gender) = "female" THEN 1 ELSE 0 END) as female_count',
      'SUM(CASE WHEN u.Age BETWEEN 0 AND 4 THEN 1 ELSE 0 END) as age_0_4',
      'SUM(CASE WHEN u.Age BETWEEN 5 AND 15 THEN 1 ELSE 0 END) as age_5_15',
      'SUM(CASE WHEN u.Age BETWEEN 16 AND 25 THEN 1 ELSE 0 END) as age_16_25',
      'SUM(CASE WHEN u.Age BETWEEN 26 AND 65 THEN 1 ELSE 0 END) as age_26_65',
      'SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) as seniors_count',
      'SUM(CASE WHEN ao.LeaveStatus IS NULL OR ao.LeaveStatus = "" THEN 1 ELSE 0 END) as no_status_count'
    ]);
    $this->db->from('user u');
    $actFilter2 = $this->has_activity_status ? " and (u.activity_status = 'active' or u.activity_status is null or u.activity_status = '')" : '';
    $this->db->where("((u.inactive_status is null or u.inactive_status = '')$actFilter2)", null, false);
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');
    }
    $this->db->group_by('u.Sector, u.Sub_Sector');
    $this->db->order_by('u.Sector, u.Sub_Sector');
    return $this->db->get()->result_array();
  }



  /**
   * Resident-only overview counts for dashboard (HOF/FM/Gender/Age/Seniors/Total).
   * Returns keys: hof, fm, male, female, age_0_4, age_5_15, seniors, total
   */
  public function get_resident_overview_counts($active_only = false)
  {
    $where = "WHERE (u.inactive_status IS NULL OR u.inactive_status = '')";
    if ($active_only && $this->has_activity_status) {
      $where .= " AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')";
    }
    $sql = "
      SELECT
        SUM(CASE WHEN u.HOF_FM_TYPE = 'HOF' THEN 1 ELSE 0 END) AS hof,
        SUM(CASE WHEN u.HOF_FM_TYPE = 'FM' THEN 1 ELSE 0 END) AS fm,
        SUM(CASE WHEN LOWER(u.Gender) = 'male' THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN LOWER(u.Gender) = 'female' THEN 1 ELSE 0 END) AS female,
        SUM(CASE WHEN u.Age BETWEEN 0 AND 4 THEN 1 ELSE 0 END) AS age_0_4,
        SUM(CASE WHEN u.Age BETWEEN 5 AND 15 THEN 1 ELSE 0 END) AS age_5_15,
        SUM(CASE WHEN u.Age BETWEEN 16 AND 25 THEN 1 ELSE 0 END) AS age_16_25,
        SUM(CASE WHEN u.Age BETWEEN 26 AND 65 THEN 1 ELSE 0 END) AS age_26_65,
        SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) AS seniors,
        COUNT(*) AS total
      FROM user u
      $where
    ";
    $row = $this->db->query($sql)->row_array();
    return [
      'hof' => (int)($row['hof'] ?? 0),
      'fm' => (int)($row['fm'] ?? 0),
      'male' => (int)($row['male'] ?? 0),
      'female' => (int)($row['female'] ?? 0),
      'age_0_4' => (int)($row['age_0_4'] ?? 0),
      'age_5_15' => (int)($row['age_5_15'] ?? 0),
      'age_16_25' => (int)($row['age_16_25'] ?? 0),
      'age_26_65' => (int)($row['age_26_65'] ?? 0),
      'seniors' => (int)($row['seniors'] ?? 0),
      'total' => (int)($row['total'] ?? 0),
    ];
  }

  /**
   * Resident-only sector totals for dashboard's sector-wise members.
   * Returns rows of ['Sector' => string|null, 'total' => int, 'hof_count' => int, 'fm_count' => int]
   */
  public function get_resident_sector_stats()
  {
    $sql = "
      SELECT
        u.Sector,
        COUNT(*) AS total,
        SUM(CASE WHEN u.HOF_FM_TYPE = 'HOF' THEN 1 ELSE 0 END) AS hof_count,
        SUM(CASE WHEN u.HOF_FM_TYPE = 'FM' THEN 1 ELSE 0 END) AS fm_count,
        MAX(u.Sector_Incharge_Name) AS Sector_Incharge_Name,
        MAX(u.Sector_Incharge_Female_Name) AS Sector_Incharge_Female_Name
      FROM user u
      WHERE u.sector IS NOT NULL AND u.sub_sector IS NOT NULL 
      GROUP BY u.Sector
      ORDER BY u.Sector
    ";
    $rows = $this->db->query($sql)->result_array();

    // Fetch subsectors to attach their incharges
    $subSql = "
      SELECT Sector, Sub_Sector, MAX(Sub_Sector_Incharge_Name) as Sub_Sector_Incharge_Name, MAX(Sub_Sector_Incharge_Female_Name) as Sub_Sector_Incharge_Female_Name
      FROM user
      WHERE Sector IS NOT NULL AND Sub_Sector IS NOT NULL
      GROUP BY Sector, Sub_Sector
    ";
    $subRows = $this->db->query($subSql)->result_array();

    $subMap = [];
    foreach($subRows as $sr) {
        $sec = $sr['Sector'];
        if (!isset($subMap[$sec])) $subMap[$sec] = [];
        // Only include if there is an incharge
        if (!empty(trim($sr['Sub_Sector_Incharge_Name'] ?? '')) || !empty(trim($sr['Sub_Sector_Incharge_Female_Name'] ?? ''))) {
            $subMap[$sec][] = $sr;
        }
    }

    // Normalize keys/types to match existing view expectations and attach sub_sectors
    foreach ($rows as &$r) {
      $r['Sector'] = isset($r['Sector']) ? $r['Sector'] : null;
      $r['total'] = (int)($r['total'] ?? 0);
      $r['hof_count'] = (int)($r['hof_count'] ?? 0);
      $r['fm_count'] = (int)($r['fm_count'] ?? 0);
      $r['sub_sectors'] = isset($subMap[$r['Sector']]) ? $subMap[$r['Sector']] : [];
    }
    unset($r);
    return $rows;
  }



  public function update_ashara_row($ITS, $data)
  {
    $this->db->where('ITS', $ITS);
    return $this->db->update('ashara_ohbat', $data);
  }

  public function get_deeni_eligible_count()
  {
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      WHERE u.Age BETWEEN 5 AND 15
        AND ((u.inactive_status IS NULL OR u.inactive_status = '')
        " . ($this->has_activity_status ? " AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')" : "") . ")
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_deeni_taking_count()
  {
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      WHERE u.Age BETWEEN 5 AND 15
        AND ((u.inactive_status IS NULL OR u.inactive_status = '')
        " . ($this->has_activity_status ? " AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')" : "") . ")
        AND u.ITS_ID IN (SELECT students_its_id FROM madresa_class_admission)
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_madresa_deprived_count()
  {
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      WHERE u.Age BETWEEN 5 AND 15
        AND ((u.inactive_status IS NULL OR u.inactive_status = '')
        " . ($this->has_activity_status ? " AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')" : "") . ")
        AND u.ITS_ID NOT IN (SELECT students_its_id FROM madresa_class_admission)
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_singles_21_40_count()
  {
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      WHERE u.Age BETWEEN 21 AND 40
        AND LOWER(TRIM(u.Marital_Status)) = 'single'
        AND ((u.inactive_status IS NULL OR u.inactive_status = '')
        " . ($this->has_activity_status ? " AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')" : "") . ")
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_marital_status_distribution()
  {
    $sql = "
      SELECT COALESCE(NULLIF(TRIM(u.Marital_Status),''),'Unknown') AS ms, COUNT(*) AS cnt
      FROM user u
      WHERE u.Age >= 21
        AND ((u.inactive_status IS NULL OR u.inactive_status = '')
        " . ($this->has_activity_status ? " AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')" : "") . ")
      GROUP BY ms
    ";
    $rows = $this->db->query($sql)->result_array();
    $counts = [];
    foreach ($rows as $r) {
      $counts[$r['ms']] = (int)($r['cnt'] ?? 0);
    }
    return $counts;
  }

  public function get_status_counts()
  {
    $stats = [
      'deeni' => [],
      'health' => [],
      'residential' => [],
      'activity' => [],
      'education' => [],
      'occupation' => []
    ];
    
    // No filter — count ALL 452 members
    $baseWhere = "";
    
    // Deeni Status
    $qDeeni = $this->db->query("SELECT COALESCE(NULLIF(TRIM(deeni_status),''), 'Not Set') as status, COUNT(*) as count FROM user u $baseWhere GROUP BY COALESCE(NULLIF(TRIM(deeni_status),''), 'Not Set')")->result_array();
    foreach($qDeeni as $row) { 
      $lbl = $row['status'];
      $stats['deeni'][$lbl] = ($stats['deeni'][$lbl] ?? 0) + (int)$row['count']; 
    }
    
    // Health Status
    $qHealth = $this->db->query("SELECT COALESCE(NULLIF(TRIM(health_status),''), 'Not Set') as status, COUNT(*) as count FROM user u $baseWhere GROUP BY COALESCE(NULLIF(TRIM(health_status),''), 'Not Set')")->result_array();
    foreach($qHealth as $row) { 
      $lbl = $row['status'];
      $stats['health'][$lbl] = ($stats['health'][$lbl] ?? 0) + (int)$row['count']; 
    }
    
    // Residential Status
    $qRes = $this->db->query("SELECT COALESCE(NULLIF(TRIM(residential_status),''), 'Not Set') as status, COUNT(*) as count FROM user u $baseWhere GROUP BY COALESCE(NULLIF(TRIM(residential_status),''), 'Not Set')")->result_array();
    foreach($qRes as $row) { 
      $lbl = $row['status'];
      $stats['residential'][$lbl] = ($stats['residential'][$lbl] ?? 0) + (int)$row['count']; 
    }
    
    // Activity Status
    if ($this->has_activity_status) {
        $qAct = $this->db->query("SELECT COALESCE(NULLIF(TRIM(activity_status),''), 'Not Set') as status, COUNT(*) as count FROM user u $baseWhere GROUP BY COALESCE(NULLIF(TRIM(activity_status),''), 'Not Set')")->result_array();
        foreach($qAct as $row) { 
          $lbl = $row['status'];
          $stats['activity'][$lbl] = ($stats['activity'][$lbl] ?? 0) + (int)$row['count']; 
        }
    }

    // Education (Qualification)
    $qEdu = $this->db->query("SELECT COALESCE(NULLIF(TRIM(Qualification),''), 'Not Set') as status, COUNT(*) as count FROM user u $baseWhere GROUP BY COALESCE(NULLIF(TRIM(Qualification),''), 'Not Set')")->result_array();
    foreach($qEdu as $row) { 
      $lbl = $row['status'];
      $norm = null;
      foreach(array_keys($stats['education']) as $existing) {
        if (strtolower($existing) === strtolower($lbl)) { $norm = $existing; break; }
      }
      if ($norm) {
        $stats['education'][$norm] += (int)$row['count'];
      } else {
        $stats['education'][$lbl] = (int)$row['count'];
      }
    }

    // Occupation
    $qOcc = $this->db->query("SELECT COALESCE(NULLIF(TRIM(Occupation),''), 'Not Set') as status, COUNT(*) as count FROM user u $baseWhere GROUP BY COALESCE(NULLIF(TRIM(Occupation),''), 'Not Set')")->result_array();
    foreach($qOcc as $row) { 
      $lbl = $row['status'];
      $norm = null;
      foreach(array_keys($stats['occupation']) as $existing) {
        if (strtolower($existing) === strtolower($lbl)) { $norm = $existing; break; }
      }
      if ($norm) {
        $stats['occupation'][$norm] += (int)$row['count'];
      } else {
        $stats['occupation'][$lbl] = (int)$row['count'];
      }
    }
    
    return $stats;
}

  public function get_active_inactive_counts()
  {
    if ($this->has_activity_status) {
      $active_cond = "((u.inactive_status IS NULL OR u.inactive_status = '') AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = ''))";
      $inactive_cond = "((u.inactive_status IS NOT NULL AND u.inactive_status != '') OR (u.activity_status != 'active' AND u.activity_status IS NOT NULL AND u.activity_status != ''))";
    } else {
      $active_cond = "(u.inactive_status IS NULL OR u.inactive_status = '')";
      $inactive_cond = "(u.inactive_status IS NOT NULL AND u.inactive_status != '')";
    }

    $sql = "
      SELECT 
        SUM(CASE WHEN {$active_cond} THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN {$inactive_cond} THEN 1 ELSE 0 END) as inactive,
        SUM(CASE WHEN u.its_sabeel_match = 'its_sabeel_both_khar' THEN 1 ELSE 0 END) as its_sabeel_both_khar,
        SUM(CASE WHEN u.its_sabeel_match = 'both_not_khar' THEN 1 ELSE 0 END) as both_not_khar,
        SUM(CASE WHEN u.its_sabeel_match = 'sabeel_khar_its_out' THEN 1 ELSE 0 END) as sabeel_khar_its_out,
        SUM(CASE WHEN u.its_sabeel_match = 'its_khar_sabeel_out' THEN 1 ELSE 0 END) as its_khar_sabeel_out
      FROM user u
    ";
    return $this->db->query($sql)->row_array();
  }

  public function search_members($query)
  {
    $this->db->select('ITS_ID as its_id, Full_Name as full_name, Sector as sector, HOF_ID as hof_id, Gender as gender');
    $this->db->from('user');
    $this->db->group_start();
    $this->db->like('Full_Name', $query);
    $this->db->or_like('ITS_ID', $query);
    $this->db->group_end();
    $this->db->limit(10);
    return $this->db->get()->result_array();
  }
}
