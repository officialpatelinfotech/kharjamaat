<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class AmilsahebM extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
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
    return $this->db->get('user')->result();
  }

  /**
   * Get users filtered by a set of criteria.
   * Accepts array of GET params. Supports legacy `filter`/`value` pair
   * and explicit params: name, sector, sub, status, hof, min, max.
   */
  public function get_users_filtered($params = [])
  {
    $this->db->select('*')->from('user');

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
        $this->db->where('inactive_status IS NULL', null, false);
      } elseif ($status === 'inactive') {
        $this->db->where('inactive_status IS NOT NULL', null, false);
      }
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
    $this->db->where('u.sector is not null and u.sub_sector is not null and u.inactive_status is null', null, false); // Exclude umoor fmb users
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
      'SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) as seniors_count',
      'SUM(CASE WHEN ao.LeaveStatus IS NULL OR ao.LeaveStatus = "" THEN 1 ELSE 0 END) as no_status_count'
    ]);
    $this->db->from('user u');
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');
    }
    $this->db->group_by('u.Sector');
    $this->db->order_by('u.Sector');
    return $this->db->get()->result_array();
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
      'SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) as seniors_count',
      'SUM(CASE WHEN ao.LeaveStatus IS NULL OR ao.LeaveStatus = "" THEN 1 ELSE 0 END) as no_status_count'
    ]);
    $this->db->from('user u');
    $this->db->where('u.sector is not null and u.sub_sector is not null and u.inactive_status is null');
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
   * Get distribution of member types for dashboard.
   * Buckets are derived from user.member_type using simple keyword matching.
   * Returns: [resident, external, moved_out, non_sabeel, temporary, total]
   */
  public function get_member_type_distribution()
  {
    $sql = "
      SELECT
        SUM(CASE WHEN LOWER(TRIM(member_type)) LIKE 'resident%' THEN 1 ELSE 0 END) AS resident,
        SUM(CASE WHEN LOWER(TRIM(member_type)) LIKE 'external%' THEN 1 ELSE 0 END) AS external,
        SUM(CASE WHEN LOWER(TRIM(member_type)) LIKE 'moved%' THEN 1 ELSE 0 END) AS moved_out,
        SUM(CASE WHEN LOWER(TRIM(member_type)) LIKE 'non-sabeel%' THEN 1 ELSE 0 END) AS non_sabeel,
        SUM(CASE WHEN LOWER(TRIM(member_type)) LIKE 'temporary%' OR LOWER(TRIM(member_type)) LIKE 'visitor%' THEN 1 ELSE 0 END) AS temporary,
        COUNT(*) AS total
      FROM user
      WHERE sector IS NOT NULL AND sub_sector IS NOT NULL AND inactive_status IS NULL
    ";
    $row = $this->db->query($sql)->row_array();
    // Ensure integer values and defaults
    return [
      'resident'   => (int)($row['resident'] ?? 0),
      'external'   => (int)($row['external'] ?? 0),
      'moved_out'  => (int)($row['moved_out'] ?? 0),
      'non_sabeel' => (int)($row['non_sabeel'] ?? 0),
      'temporary'  => (int)($row['temporary'] ?? 0),
      'total'      => (int)($row['total'] ?? 0),
    ];
  }

  /**
   * Resident-only overview counts for dashboard (HOF/FM/Gender/Age/Seniors/Total).
   * Returns keys: hof, fm, male, female, age_0_4, age_5_15, seniors, total
   */
  public function get_resident_overview_counts()
  {
    $sql = "
      SELECT
        SUM(CASE WHEN u.HOF_FM_TYPE = 'HOF' THEN 1 ELSE 0 END) AS hof,
        SUM(CASE WHEN u.HOF_FM_TYPE = 'FM' THEN 1 ELSE 0 END) AS fm,
        SUM(CASE WHEN LOWER(u.Gender) = 'male' THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN LOWER(u.Gender) = 'female' THEN 1 ELSE 0 END) AS female,
        SUM(CASE WHEN u.Age BETWEEN 0 AND 4 THEN 1 ELSE 0 END) AS age_0_4,
        SUM(CASE WHEN u.Age BETWEEN 5 AND 15 THEN 1 ELSE 0 END) AS age_5_15,
        SUM(CASE WHEN u.Age > 65 THEN 1 ELSE 0 END) AS seniors,
        COUNT(*) AS total
      FROM user u
      WHERE LOWER(TRIM(u.member_type)) LIKE 'resident%' AND u.sector IS NOT NULL AND u.sub_sector IS NOT NULL AND u.inactive_status IS NULL
    ";
    $row = $this->db->query($sql)->row_array();
    return [
      'hof' => (int)($row['hof'] ?? 0),
      'fm' => (int)($row['fm'] ?? 0),
      'male' => (int)($row['male'] ?? 0),
      'female' => (int)($row['female'] ?? 0),
      'age_0_4' => (int)($row['age_0_4'] ?? 0),
      'age_5_15' => (int)($row['age_5_15'] ?? 0),
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
        SUM(CASE WHEN u.HOF_FM_TYPE = 'FM' THEN 1 ELSE 0 END) AS fm_count
      FROM user u
      WHERE LOWER(TRIM(u.member_type)) LIKE 'resident%'
      GROUP BY u.Sector
      ORDER BY u.Sector
    ";
    $rows = $this->db->query($sql)->result_array();
    // Normalize keys/types to match existing view expectations
    foreach ($rows as &$r) {
      $r['Sector'] = isset($r['Sector']) ? $r['Sector'] : null;
      $r['total'] = (int)($r['total'] ?? 0);
      $r['hof_count'] = (int)($r['hof_count'] ?? 0);
      $r['fm_count'] = (int)($r['fm_count'] ?? 0);
    }
    unset($r);
    return $rows;
  }



  public function update_ashara_row($ITS, $data)
  {
    $this->db->where('ITS', $ITS);
    return $this->db->update('ashara_ohbat', $data);
  }
}
