<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MasoolMusaidM extends CI_Model
{
  function __construct()
  {
    // Call the parent constructor
    parent::__construct();
  }

  // Example: Fetch all users for MasoolMusaid
  public function get_all_users()
  {
    $query = $this->db->get('user');
    return $query->result_array();
  }

  // Example: Fetch single user by ITS_ID
  public function get_user_by_id($its_id)
  {
    $this->db->where('ITS_ID', $its_id);
    return $this->db->get('user')->row_array();
  }

  // Example: Update user details
  public function update_user($its_id, $data)
  {
    $this->db->where('ITS_ID', $its_id);
    $this->db->update('user', $data);
    return $this->db->affected_rows() > 0;
  }
  public function get_users_by_sector($sector, $subsector = '')
  {
    $this->db->where('Sector', $sector);
    if (!empty($subsector)) {
      $this->db->where('Sub_Sector', $subsector);
    }
    return $this->db->get('user')->result_array();
  }

  public function search_users_by_sector($keyword, $sector, $subsector = '')
  {
    $this->db->group_start()
      ->like('Full_Name', $keyword)
      ->or_like('ITS_ID', $keyword)
      ->or_like('Mobile', $keyword)
      ->or_like('Address', $keyword)
      ->group_end();

    $this->db->where('Sector', $sector);
    if (!empty($subsector)) {
      $this->db->where('Sub_Sector', $subsector);
    }

    return $this->db->get('user')->result_array();
  }


  public function get_ashara_by_sector($sector, $subsector, $year = null)
  {
    $this->db->select(
      'u.ITS_ID as ITS, ao.LeaveStatus, ao.Comment, 
         u.Full_Name, u.HOF_ID, u.HOF_FM_TYPE, 
         u.Age, u.Gender, u.Mobile, u.Sector, u.Sub_Sector'
    );
    $this->db->from('user u');
    // Left join ashara_ohbat, scoping by year in the JOIN so roster remains when no rows for that year
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');
    }

    // Add active filter
    $active_cond = "((u.inactive_status IS NULL OR u.inactive_status = '') AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = ''))";
    $this->db->where($active_cond, null, false);

    $this->db->where('u.Sector', $sector);
    if (!empty($subsector)) {
      $this->db->where('u.Sub_Sector', $subsector);
    }
    return $this->db->get()->result_array();
  }

  public function search_ashara_by_sector($keyword, $sector, $subsector, $year = null)
  {
    $this->db->select(
      'u.ITS_ID as ITS, ao.LeaveStatus, ao.Comment, 
         u.Full_Name, u.HOF_ID, u.HOF_FM_TYPE, 
         u.Age, u.Gender, u.Mobile, u.Sector, u.Sub_Sector'
    );
    $this->db->from('user u');
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');
    }

    // Add active filter
    $active_cond = "((u.inactive_status IS NULL OR u.inactive_status = '') AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = ''))";
    $this->db->where($active_cond, null, false);

    $this->db->group_start();
    $this->db->like('ao.ITS', $keyword);
    $this->db->or_like('u.Full_Name', $keyword);
    $this->db->or_like('u.Mobile', $keyword);
    $this->db->or_like('ao.LeaveStatus', $keyword);
    $this->db->or_like('ao.Comment', $keyword);
    $this->db->group_end();

    $this->db->where('u.Sector', $sector);
    if (!empty($subsector)) {
      $this->db->where('u.Sub_Sector', $subsector);
    }
    return $this->db->get()->result_array();
  }


  public function get_sectors_stats($sector = null, $subsector = null, $year = null)
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
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');
    }

    if ($sector) {
      $this->db->where('u.Sector', $sector);
    }


    $this->db->group_by('u.Sector');
    $this->db->order_by('u.Sector');

    $rows = $this->db->get()->result_array();

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

  public function get_sub_sectors_stats($sector = null, $subsector = null, $year = null)
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
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS AND ao.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_ohbat ao', 'u.ITS_ID = ao.ITS', 'left');
    }

    if ($sector) {
      $this->db->where('u.Sector', $sector);
    }

    if ($subsector) {
      $this->db->where('u.Sub_Sector', $subsector);
    }



    $this->db->group_by('u.Sector, u.Sub_Sector');
    $this->db->order_by('u.Sector, u.Sub_Sector');

    return $this->db->get()->result_array();
  }

  public function upsert_ashara_row($ITS, $data, $year = null)
  {
    $this->db->where('ITS', $ITS);
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
      $this->db->where('year', (int)$year);
    }
    $query = $this->db->get('ashara_ohbat');

    if ($query->num_rows() > 0) {
      $this->db->where('ITS', $ITS);
      if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
        $this->db->where('year', (int)$year);
      }
      return $this->db->update('ashara_ohbat', $data);
    } else {
      $data['ITS'] = $ITS; // Ensure ITS is included for insert
      if (!is_null($year) && $this->db->field_exists('year', 'ashara_ohbat')) {
        $data['year'] = (int)$year;
      }
      return $this->db->insert('ashara_ohbat', $data);
    }
  }

  public function update_attendance_leave_status($ITS, $leaveStatus, $year = null)
  {
    $data = [
      'Day2' => $leaveStatus,
      'Day3' => $leaveStatus,
      'Day4' => $leaveStatus,
      'Day5' => $leaveStatus,
      'Day6' => $leaveStatus,
      'Day7' => $leaveStatus,
      'Day8' => $leaveStatus,
      'Day9' => $leaveStatus,
      'Ashura' => $leaveStatus
    ];

    $this->db->where('ITS', $ITS);
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_attendance')) {
      $this->db->where('year', (int)$year);
    }
    $updated = $this->db->update('ashara_attendance', $data);

    if (!$updated && $this->db->affected_rows() === 0) {
      // If no row existed, insert a new one (upsert behavior)
      $insert = ['ITS' => $ITS] + $data;
      if (!is_null($year) && $this->db->field_exists('year', 'ashara_attendance')) {
        $insert['year'] = (int)$year;
      }
      return $this->db->insert('ashara_attendance', $insert);
    }
    return $updated;
  }



  public function get_attendance_by_sub_sector($sector, $sub_sector, $year = null)
  {
    if (empty($sector))
      return $this->get_all_attendance($year);
    return $this->get_ashara_attendance($sector, $sub_sector, $year);
  }

  public function get_attendance_by_sector($sector, $sub_sector = '', $year = null)
  {
    if (empty($sector))
      return $this->get_all_attendance($year);
    return $this->get_ashara_attendance($sector, $sub_sector, $year);
  }

  public function get_sub_sector_stats($sector, $sub_sector, $year = null)
  {
    if (empty($sector))
      return $this->get_all_attendance_stats($year);
    return $this->get_attendance_stats($sector, $sub_sector, $year);
  }

  public function get_sector_stats($sector, $sub_sector = '', $year = null)
  {
    if (empty($sector))
      return $this->get_all_attendance_stats($year);
    return $this->get_attendance_stats($sector, $sub_sector, $year);
  }

  private function get_ashara_attendance($sector, $sub_sector = '', $year = null)
  {
    $this->db->select('u.ITS_ID, u.HOF_ID, u.Full_Name, u.Mobile, u.Sector, u.Sub_Sector, 
        a.Day2, a.Comment2, a.Day3, a.Comment3, a.Day4, a.Comment4, 
        a.Day5, a.Comment5, a.Day6, a.Comment6, a.Day7, a.Comment7, 
        a.Day8, a.Comment8, a.Day9, a.Comment9, a.Ashura, a.CommentAshura');
    $this->db->from('user u');
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_attendance')) {
      $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS AND a.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
    }
    $this->db->where('u.Sector', $sector);
    if (!empty($sub_sector)) {
      $this->db->where('u.Sub_Sector', $sub_sector);
    }
    $this->db->order_by('u.Sub_Sector, u.Full_Name');
    return $this->db->get()->result_array();
  }

  public function get_all_attendance($year = null)
  {
    $this->db->select('u.ITS_ID, u.HOF_ID, u.Full_Name, u.Mobile, u.Sector, u.Sub_Sector, 
        a.Day2, a.Comment2, a.Day3, a.Comment3, a.Day4, a.Comment4, 
        a.Day5, a.Comment5, a.Day6, a.Comment6, a.Day7, a.Comment7, 
        a.Day8, a.Comment8, a.Day9, a.Comment9, a.Ashura, a.CommentAshura');
    $this->db->from('user u');
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_attendance')) {
      $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS AND a.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
    }
    $this->db->order_by('u.Sector, u.Sub_Sector, u.Full_Name');
    return $this->db->get()->result_array();
  }

  public function get_all_attendance_stats($year = null)
  {
    $days = ['Day2', 'Day3', 'Day4', 'Day5', 'Day6', 'Day7', 'Day8', 'Day9', 'Ashura'];
    $stats = [];

    foreach ($days as $day) {
      $this->db->select("
            COUNT(CASE WHEN a.$day = 'Attended with Maula' THEN 1 END) as with_maula,
            COUNT(CASE WHEN a.$day = 'Attended in Khar on Time' THEN 1 END) as khar_on_time,
            COUNT(CASE WHEN a.$day = 'Attended in Khar Late' THEN 1 END) as khar_late,
            COUNT(CASE WHEN a.$day = 'Attended in Other Jamaat' THEN 1 END) as other_jamaat,
            COUNT(CASE WHEN a.$day = 'Not attended anywhere' THEN 1 END) as not_attended,
            COUNT(CASE WHEN a.$day = 'Not in Town' THEN 1 END) as not_in_town,
            COUNT(CASE WHEN a.$day = 'Married Outcaste' THEN 1 END) as outcaste,
            COUNT(u.ITS_ID) as total_members
        ");
      $this->db->from('user u');
      if (!is_null($year) && $this->db->field_exists('year', 'ashara_attendance')) {
        $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS AND a.year = ' . $this->db->escape((int)$year), 'left');
      } else {
        $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
      }
      $stats[$day] = $this->db->get()->row_array();
    }

    return $stats;
  }

  public function search_attendance_by_sector($keyword, $sector = '', $sub_sector = '', $year = null)
  {
    $this->db->select('u.ITS_ID, u.HOF_ID, u.Full_Name, u.Mobile, u.Sector, u.Sub_Sector, 
        a.Day2, a.Comment2, a.Day3, a.Comment3, a.Day4, a.Comment4, 
        a.Day5, a.Comment5, a.Day6, a.Comment6, a.Day7, a.Comment7, 
        a.Day8, a.Comment8, a.Day9, a.Comment9, a.Ashura, a.CommentAshura');
    $this->db->from('user u');
    if (!is_null($year) && $this->db->field_exists('year', 'ashara_attendance')) {
      $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS AND a.year = ' . $this->db->escape((int)$year), 'left');
    } else {
      $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
    }

    if (!empty($sector)) {
      $this->db->where('u.Sector', $sector);
    }
    if (!empty($sub_sector)) {
      $this->db->where('u.Sub_Sector', $sub_sector);
    }

    $this->db->group_start();
    $this->db->like('u.ITS_ID', $keyword);
    $this->db->or_like('u.Full_Name', $keyword);
    $this->db->or_like('u.HOF_ID', $keyword);
    $this->db->group_end();

    $this->db->order_by('u.Sub_Sector, u.Full_Name');
    return $this->db->get()->result_array();
  }

  public function get_all_sectors()
  {
    $this->db->select('DISTINCT(Sector) as sector');
    $this->db->from('user');
    $this->db->order_by('sector');
    return $this->db->get()->result_array();
  }

  public function get_all_sub_sectors($sector)
  {
    $this->db->select('DISTINCT(Sub_Sector) as sub_sector');
    $this->db->from('user');
    $this->db->where('Sector', $sector);
    $this->db->order_by('sub_sector');
    return $this->db->get()->result_array();
  }



  private function get_attendance_stats($sector, $sub_sector = '', $year = null)
  {
    $days = ['Day2', 'Day3', 'Day4', 'Day5', 'Day6', 'Day7', 'Day8', 'Day9', 'Ashura'];
    $stats = [];

    foreach ($days as $day) {
      $this->db->select("
            COUNT(CASE WHEN a.$day = 'Attended with Maula' THEN 1 END) as with_maula,
            COUNT(CASE WHEN a.$day = 'Attended in Khar on Time' THEN 1 END) as khar_on_time,
            COUNT(CASE WHEN a.$day = 'Attended in Khar Late' THEN 1 END) as khar_late,
            COUNT(CASE WHEN a.$day = 'Attended in Other Jamaat' THEN 1 END) as other_jamaat,
            COUNT(CASE WHEN a.$day = 'Not attended anywhere' THEN 1 END) as not_attended,
            COUNT(CASE WHEN a.$day = 'Not in Town' THEN 1 END) as not_in_town,
            COUNT(CASE WHEN a.$day = 'Married Outcaste' THEN 1 END) as outcaste,
            COUNT(u.ITS_ID) as total_members
        ");
      $this->db->from('user u');
      if (!is_null($year) && $this->db->field_exists('year', 'ashara_attendance')) {
        $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS AND a.year = ' . $this->db->escape((int)$year), 'left');
      } else {
        $this->db->join('ashara_attendance a', 'u.ITS_ID = a.ITS', 'left');
      }
      $this->db->where('u.Sector', $sector);

      if (!empty($sub_sector)) {
        $this->db->where('u.Sub_Sector', $sub_sector);
      }

      $stats[$day] = $this->db->get()->row_array();
    }

    return $stats;
  }

  public function get_upcoming_miqaats()
  {
    $today = date('Y-m-d');
    $this->db->order_by('date', 'ASC');
    $query = $this->db->get('miqaat');
    return $query->result_array();
  }

  /**
   * Search upcoming miqaats by name or formatted date
   */
  public function search_upcoming_miqaats($keyword = '')
  {
    $today = date('Y-m-d');
    $this->db->from('miqaat m');
    if (!empty($keyword)) {
      $this->db->group_start();
      $this->db->like('m.name', $keyword);
      $this->db->or_like("DATE_FORMAT(m.date, '%d %b %Y')", $keyword);
      $this->db->group_end();
    }
    $this->db->order_by('m.date', 'ASC');
    return $this->db->get()->result_array();
  }

  public function get_miqaat_by_id($miqaat_id)
  {
    $this->db->where('id', $miqaat_id);
    return $this->db->get('miqaat')->row_array();
  }

  public function get_members_by_sector_sub_sector($sector = '', $sub_sector = '')
  {
    $this->db->select('ITS_ID, HOF_ID, Full_Name, Mobile, Sector, Sub_Sector');
    $this->db->from('user');

    if (!empty($sector)) {
      $this->db->where('Sector', $sector);
    }
    if (!empty($sub_sector)) {
      $this->db->where('Sub_Sector', $sub_sector);
    }

    $this->db->order_by('Full_Name', 'ASC');
    return $this->db->get()->result_array();
  }

  public function get_miqaat_attendee_count($miqaat_id, $sector = '', $sub_sector = '')
  {
    $this->db->from('miqaat_attendance a');
    $this->db->join('user u', 'a.user_id = u.ITS_ID', 'left');
    $this->db->where('a.miqaat_id', $miqaat_id);
    if (!empty($sector)) {
      $this->db->where('u.Sector', $sector);
    }
    if (!empty($sub_sector)) {
      $this->db->where('u.Sub_Sector', $sub_sector);
    }
    return $this->db->count_all_results();
  }

  public function get_rsvps_by_miqaat($miqaat_id)
  {
    $this->db->select('r.user_id, u.Full_Name, u.Mobile');
    $this->db->from('general_rsvp r');
    $this->db->join('user u', 'r.user_id = u.ITS_ID', 'left');
    $this->db->where('r.miqaat_id', $miqaat_id);
    $this->db->order_by('u.Sub_Sector, u.Full_Name', 'ASC');
    return $this->db->get()->result_array();
  }

  public function get_rsvp_counts_by_miqaat($sector = '', $sub_sector = '')
  {
    $this->db->select('
        m.id as miqaat_id, 
        m.name as miqaat_name, 
        m.date as miqaat_date,
        COUNT(DISTINCT CASE WHEN u.ITS_ID IS NOT NULL THEN r.user_id END) as rsvp_count,
        COUNT(DISTINCT u2.ITS_ID) as member_count
    ');
    $this->db->from('miqaat m');

    // Join RSVP + User (to apply sector filters on RSVP side)
    $this->db->join('general_rsvp r', 'm.id = r.miqaat_id', 'left');
    $this->db->join('user u', 'u.ITS_ID = r.user_id', 'left');

    // Count all members in this sector/subsector (separate alias)
    $this->db->join('user u2', '1=1', 'left');

    // Filters
    if (!empty($sector)) {
      $this->db->where('u2.Sector', $sector);
      $this->db->where('(u.Sector IS NULL OR u.Sector = ' . $this->db->escape($sector) . ')');
    }
    if (!empty($sub_sector)) {
      $this->db->where('u2.Sub_Sector', $sub_sector);
      $this->db->where('(u.Sub_Sector IS NULL OR u.Sub_Sector = ' . $this->db->escape($sub_sector) . ')');
    }

    // Future miqaāts
    // $this->db->where('m.date >=', date('Y-m-d'));

    // One row per miqaat
    $this->db->group_by('m.id');
    $this->db->order_by('m.date', 'ASC');

    return $this->db->get()->result_array();
  }

  /**
   * Search miqaat RSVP counts by name or date (for MasoolMusaid search)
   */
  public function search_rsvp_counts_by_miqaat($keyword = '', $sector = '', $sub_sector = '')
  {
    $this->db->select('
        m.id as miqaat_id, 
        m.name as miqaat_name, 
        m.date as miqaat_date,
        COUNT(DISTINCT CASE WHEN u.ITS_ID IS NOT NULL THEN r.user_id END) as rsvp_count,
        COUNT(DISTINCT u2.ITS_ID) as member_count
    ');
    $this->db->from('miqaat m');
    $this->db->join('general_rsvp r', 'm.id = r.miqaat_id', 'left');
    $this->db->join('user u', 'u.ITS_ID = r.user_id', 'left');
    $this->db->join('user u2', '1=1', 'left');

    if (!empty($sector)) {
      $this->db->where('u2.Sector', $sector);
      $this->db->where('(u.Sector IS NULL OR u.Sector = ' . $this->db->escape($sector) . ')');
    }
    if (!empty($sub_sector)) {
      $this->db->where('u2.Sub_Sector', $sub_sector);
      $this->db->where('(u.Sub_Sector IS NULL OR u.Sub_Sector = ' . $this->db->escape($sub_sector) . ')');
    }

    if (!empty($keyword)) {
      $this->db->group_start();
      $this->db->like('m.name', $keyword);
      $this->db->or_like("DATE_FORMAT(m.date, '%d %b %Y')", $keyword);
      $this->db->group_end();
    }

    $this->db->group_by('m.id');
    $this->db->order_by('m.date', 'ASC');

    return $this->db->get()->result_array();
  }


  public function get_miqaat_raza_status($miqaat_id)
  {
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->from('raza');
    return $this->db->get()->result_array();
  }

  public function add_general_rsvp($miqaat_id, $user_id, $hof_id)
  {
    if (empty($user_id)) {
      return false;
    }
    $data = [
      'miqaat_id' => $miqaat_id,
      'user_id' => $user_id,
      'hof_id' => $hof_id,
    ];
    return $this->db->insert('general_rsvp', $data);
  }

  public function remove_general_rsvp($miqaat_id, $user_ids)
  {
    if (empty($user_ids)) {
      return false;
    }

    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->where_in('user_id', $user_ids);
    return $this->db->delete('general_rsvp');
  }

  public function get_miqaat_attendance_by_miqaat($miqaat_id)
  {
    $this->db->select('a.user_id, u.Full_Name, u.Mobile, a.comment');
    $this->db->from('miqaat_attendance a');
    $this->db->join('user u', 'a.user_id = u.ITS_ID', 'left');
    $this->db->where('a.miqaat_id', $miqaat_id);
    $this->db->order_by('u.Sub_Sector, u.Full_Name', 'ASC');
    return $this->db->get()->result_array();
  }

  public function add_miqaat_attendance($miqaat_id, $user_id, $hof_id, $comment = '')
  {
    if (empty($user_id)) {
      return false;
    }
    $data = [
      'miqaat_id' => $miqaat_id,
      'user_id' => $user_id,
      'hof_id' => $hof_id,
      'comment' => $comment,
    ];
    return $this->db->insert('miqaat_attendance', $data);
  }

  public function remove_miqaat_attendance($miqaat_id, $user_ids)
  {
    if (empty($user_ids)) {
      return false;
    }

    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->where_in('user_id', $user_ids);
    return $this->db->delete('miqaat_attendance');
  }

  public function search_members($query, $sector, $subsector = '')
  {
    $this->db->select('ITS_ID as its_id, Full_Name as full_name, Sector as sector, HOF_ID as hof_id, Gender as gender');
    $this->db->from('user');
    $this->db->group_start();
    $this->db->like('Full_Name', $query);
    $this->db->or_like('ITS_ID', $query);
    $this->db->group_end();
    $this->db->where('Sector', $sector);
    if (!empty($subsector)) {
      $this->db->where('Sub_Sector', $subsector);
    }
    $this->db->limit(10);
    return $this->db->get()->result_array();
  }

  public function get_active_inactive_counts($sector = '', $subsector = '')
  {
    $active_cond = "((u.inactive_status IS NULL OR u.inactive_status = '') AND (u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = ''))";
    $inactive_cond = "((u.inactive_status IS NOT NULL AND u.inactive_status != '') OR (u.activity_status != 'active' AND u.activity_status IS NOT NULL AND u.activity_status != ''))";

    $where = [];
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    $sql = "
      SELECT 
        SUM(CASE WHEN {$active_cond} THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN {$inactive_cond} THEN 1 ELSE 0 END) as inactive,
        SUM(CASE WHEN u.its_sabeel_match = 'its_sabeel_both_khar' THEN 1 ELSE 0 END) as its_sabeel_both_khar,
        SUM(CASE WHEN u.its_sabeel_match = 'both_not_khar' THEN 1 ELSE 0 END) as both_not_khar,
        SUM(CASE WHEN u.its_sabeel_match = 'sabeel_khar_its_out' THEN 1 ELSE 0 END) as sabeel_khar_its_out,
        SUM(CASE WHEN u.its_sabeel_match = 'its_khar_sabeel_out' THEN 1 ELSE 0 END) as its_khar_sabeel_out
      FROM user u
      {$where_sql}
    ";
    return $this->db->query($sql)->row_array();
  }

  public function get_resident_overview_counts($sector = '', $subsector = '', $active_only = false)
  {
    $where = ["(u.inactive_status IS NULL OR u.inactive_status = '')"];
    if ($active_only) {
      $where[] = "(u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')";
    }
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $where_sql = "WHERE " . implode(" AND ", $where);

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
      $where_sql
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

  public function get_deeni_eligible_count($sector = '', $subsector = '')
  {
    $where = ["u.Age BETWEEN 5 AND 15", "(u.inactive_status IS NULL OR u.inactive_status = '')", "(u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')"];
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $where_sql = "WHERE " . implode(" AND ", $where);
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      $where_sql
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_deeni_taking_count($sector = '', $subsector = '')
  {
    $where = ["u.Age BETWEEN 5 AND 15", "(u.inactive_status IS NULL OR u.inactive_status = '')", "(u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')"];
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $where_sql = "WHERE " . implode(" AND ", $where);
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      $where_sql
        AND u.ITS_ID IN (SELECT students_its_id FROM madresa_class_admission)
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_madresa_deprived_count($sector = '', $subsector = '')
  {
    $where = ["u.Age BETWEEN 5 AND 15", "(u.inactive_status IS NULL OR u.inactive_status = '')", "(u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')"];
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $where_sql = "WHERE " . implode(" AND ", $where);
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      $where_sql
        AND u.ITS_ID NOT IN (SELECT students_its_id FROM madresa_class_admission)
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_singles_21_40_count($sector = '', $subsector = '')
  {
    $where = ["u.Age BETWEEN 21 AND 40", "LOWER(TRIM(u.Marital_Status)) = 'single'", "(u.inactive_status IS NULL OR u.inactive_status = '')", "(u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')"];
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $where_sql = "WHERE " . implode(" AND ", $where);
    $sql = "
      SELECT COUNT(*) as count
      FROM user u
      $where_sql
    ";
    $row = $this->db->query($sql)->row_array();
    return (int)($row['count'] ?? 0);
  }

  public function get_marital_status_distribution($sector = '', $subsector = '')
  {
    $where = ["u.Age >= 21", "(u.inactive_status IS NULL OR u.inactive_status = '')", "(u.activity_status = 'active' OR u.activity_status IS NULL OR u.activity_status = '')"];
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $where_sql = "WHERE " . implode(" AND ", $where);
    $sql = "
      SELECT COALESCE(NULLIF(TRIM(u.Marital_Status),''),'Unknown') AS ms, COUNT(*) AS cnt
      FROM user u
      $where_sql
      GROUP BY ms
    ";
    $rows = $this->db->query($sql)->result_array();
    $counts = [];
    foreach ($rows as $r) {
      $counts[$r['ms']] = (int)($r['cnt'] ?? 0);
    }
    return $counts;
  }

  public function get_status_counts($sector = '', $subsector = '')
  {
    $stats = [
      'deeni' => [],
      'health' => [],
      'residential' => [],
      'activity' => [],
      'education' => [],
      'occupation' => []
    ];
    
    $where = [];
    if (!empty($sector)) {
      $where[] = "u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $where[] = "u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $baseWhere = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
    
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
    $qAct = $this->db->query("SELECT COALESCE(NULLIF(TRIM(activity_status),''), 'Not Set') as status, COUNT(*) as count FROM user u $baseWhere GROUP BY COALESCE(NULLIF(TRIM(activity_status),''), 'Not Set')")->result_array();
    foreach($qAct as $row) { 
      $lbl = $row['status'];
      $stats['activity'][$lbl] = ($stats['activity'][$lbl] ?? 0) + (int)$row['count']; 
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

  public function get_scoped_next_miqaat_rsvp_stats($sector, $subsector = '', $miqaat_id = null)
  {
    // Find target miqaat: if $miqaat_id provided try to load it, otherwise pick next upcoming active miqaat
    if (!empty($miqaat_id)) {
      $mi = $this->db->select('m.*, r.id AS raza_id, r.`Janab-status` AS janab_status')
        ->from('miqaat m')
        ->join('raza r', 'r.miqaat_id = m.id AND r.`Janab-status` = 1', 'inner')
        ->where('m.id', (int)$miqaat_id)
        ->get()->row_array();
    } else {
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
        'will_not_attend' => 0,
        'rsvp_not_submitted' => 0,
        'rsvp_list' => [],
        'not_rsvp_list' => [],
        'rsvp_member_list' => [],
        'rsvp_male_member_list' => [],
        'rsvp_female_member_list' => [],
        'rsvp_children_member_list' => [],
        'not_rsvp_member_list' => [],
        'not_submitted_member_list' => [],
        'guest_summary' => ['gents' => 0, 'ladies' => 0, 'children' => 0, 'total' => 0],
        'member_summary' => ['gents' => 0, 'ladies' => 0, 'children' => 0, 'total' => 0],
        'combined_summary' => ['gents' => 0, 'ladies' => 0, 'children' => 0, 'total' => 0]
      ];
    }

    $miqaat_pk = $mi['id'];

    // Total active HOFs in this sector/subsector
    $total_hof_sql = "SELECT COUNT(*) AS total FROM user WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND Sector IS NOT NULL AND Sub_Sector IS NOT NULL";
    if (!empty($sector)) {
      $total_hof_sql .= " AND Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $total_hof_sql .= " AND Sub_Sector = " . $this->db->escape($subsector);
    }
    $total_hof_row = $this->db->query($total_hof_sql)->row_array();
    $total_hof = isset($total_hof_row['total']) ? (int)$total_hof_row['total'] : 0;

    // Get RSVPed HOF IDs
    $rsvp_sql = "SELECT DISTINCT gr.hof_id 
                 FROM general_rsvp gr 
                 JOIN user u ON u.ITS_ID = gr.user_id 
                 WHERE gr.miqaat_id = " . (int)$miqaat_pk . " AND u.Inactive_Status IS NULL";
    if (!empty($sector)) {
      $rsvp_sql .= " AND u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $rsvp_sql .= " AND u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $rsvp_rows = $this->db->query($rsvp_sql)->result_array();
    $rsvp_ids = [];
    foreach ($rsvp_rows as $r) {
      if (!empty($r['hof_id'])) $rsvp_ids[] = $r['hof_id'];
    }
    $rsvp_ids = array_values(array_unique($rsvp_ids));
    $rsvp_count = count($rsvp_ids);
    $not_rsvp_count = max(0, $total_hof - $rsvp_count);

    $rsvp_list = [];
    $not_rsvp_list = [];
    if (!empty($rsvp_ids)) {
      $in = implode(',', array_map(function($id) { return $this->db->escape($id); }, $rsvp_ids));
      
      $rsvp_list_sql = "SELECT ITS_ID AS ITS_ID, Full_Name, Sector, Sub_Sector, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile 
                        FROM user 
                        WHERE ITS_ID IN ($in) AND COALESCE(Sector,'') <> '' AND COALESCE(Sub_Sector,'') <> '' 
                        ORDER BY Sector ASC, Sub_Sector ASC, Full_Name ASC";
      $rsvp_list = $this->db->query($rsvp_list_sql)->result_array();

      $not_rsvp_sql = "SELECT ITS_ID AS ITS_ID, Full_Name, Sector, Sub_Sector, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile 
                       FROM user 
                       WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND COALESCE(Sector,'') <> '' AND COALESCE(Sub_Sector,'') <> '' AND ITS_ID NOT IN ($in)";
      if (!empty($sector)) {
        $not_rsvp_sql .= " AND Sector = " . $this->db->escape($sector);
      }
      if (!empty($subsector)) {
        $not_rsvp_sql .= " AND Sub_Sector = " . $this->db->escape($subsector);
      }
      $not_rsvp_sql .= " ORDER BY Sector ASC, Sub_Sector ASC, Full_Name ASC";
      $not_rsvp_list = $this->db->query($not_rsvp_sql)->result_array();
    } else {
      $not_rsvp_sql = "SELECT ITS_ID AS ITS_ID, Full_Name, Sector, Sub_Sector, COALESCE(Registered_Family_Mobile, Mobile, WhatsApp_No, '') AS mobile 
                       FROM user 
                       WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND COALESCE(Sector,'') <> '' AND COALESCE(Sub_Sector,'') <> ''";
      if (!empty($sector)) {
        $not_rsvp_sql .= " AND Sector = " . $this->db->escape($sector);
      }
      if (!empty($subsector)) {
        $not_rsvp_sql .= " AND Sub_Sector = " . $this->db->escape($subsector);
      }
      $not_rsvp_sql .= " ORDER BY Sector ASC, Sub_Sector ASC, Full_Name ASC";
      $not_rsvp_list = $this->db->query($not_rsvp_sql)->result_array();
    }

    $rsvp_member_list = [];
    $rsvp_male_member_list = [];
    $rsvp_female_member_list = [];
    $rsvp_children_member_list = [];
    $not_rsvp_member_list = [];
    $not_submitted_member_list = [];

    // RSVP'd members
    $sql_rsvp_mem = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, u.Gender, u.Age, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile 
                     FROM user u 
                     JOIN general_rsvp gr ON gr.user_id = u.ITS_ID 
                     WHERE gr.miqaat_id = " . (int)$miqaat_pk . " AND u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''";
    if (!empty($sector)) {
      $sql_rsvp_mem .= " AND u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $sql_rsvp_mem .= " AND u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $sql_rsvp_mem .= " ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
    $rsvp_member_list = $this->db->query($sql_rsvp_mem)->result_array();

    foreach ($rsvp_member_list as $rm) {
      $age = isset($rm['Age']) ? (int)$rm['Age'] : null;
      $gender = strtolower(trim($rm['Gender'] ?? ''));
      if ($age !== null && $age <= 15) {
        $rsvp_children_member_list[] = $rm;
      } else if ($gender === 'male') {
        $rsvp_male_member_list[] = $rm;
      } else if ($gender === 'female') {
        $rsvp_female_member_list[] = $rm;
      }
    }

    // not submitted individual members
    $base_active = "u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''";
    if (!empty($sector)) {
      $base_active .= " AND u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $base_active .= " AND u.Sub_Sector = " . $this->db->escape($subsector);
    }

    if (!empty($rsvp_ids)) {
      $in_hofs = implode(',', array_map(function($id) { return (int)$id; }, $rsvp_ids));
      $sql_ns = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, u.HOF_ID, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                 FROM `user` u
                 WHERE {$base_active} AND u.HOF_ID NOT IN ({$in_hofs})
                 ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
      $not_submitted_member_list = $this->db->query($sql_ns)->result_array();
    } else {
      $sql_ns = "SELECT u.ITS_ID AS ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, u.HOF_ID, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile
                 FROM `user` u
                 WHERE {$base_active}
                 ORDER BY u.Sector ASC, u.Sub_Sector ASC, u.Full_Name ASC";
      $not_submitted_member_list = $this->db->query($sql_ns)->result_array();
    }

    $not_rsvp_member_list = $not_rsvp_list;

    // guest rsvps
    $guest_summary = ['gents' => 0, 'ladies' => 0, 'children' => 0, 'total' => 0];
    $guest_sql = "SELECT COALESCE(SUM(g.gents),0) AS gents, COALESCE(SUM(g.ladies),0) AS ladies, COALESCE(SUM(g.children),0) AS children 
                  FROM general_guest_rsvp g
                  JOIN user u ON u.ITS_ID = g.hof_id 
                  WHERE g.miqaat_id = " . (int)$miqaat_pk;
    if (!empty($sector)) {
      $guest_sql .= " AND u.Sector = " . $this->db->escape($sector);
    }
    if (!empty($subsector)) {
      $guest_sql .= " AND u.Sub_Sector = " . $this->db->escape($subsector);
    }
    $g_row = $this->db->query($guest_sql)->row_array();
    if ($g_row) {
      $guest_summary['gents'] = (int)($g_row['gents'] ?? 0);
      $guest_summary['ladies'] = (int)($g_row['ladies'] ?? 0);
      $guest_summary['children'] = (int)($g_row['children'] ?? 0);
      $guest_summary['total'] = $guest_summary['gents'] + $guest_summary['ladies'] + $guest_summary['children'];
    }

    $member_summary = [
      'gents' => count($rsvp_male_member_list),
      'ladies' => count($rsvp_female_member_list),
      'children' => count($rsvp_children_member_list),
      'total' => count($rsvp_member_list)
    ];

    $combined_summary = [
      'gents' => $member_summary['gents'] + $guest_summary['gents'],
      'ladies' => $member_summary['ladies'] + $guest_summary['ladies'],
      'children' => $member_summary['children'] + $guest_summary['children'],
      'total' => $member_summary['total'] + $guest_summary['total']
    ];

    // Count Will Not Attend from individual general_rsvp table
    $will_not_attend = 0;
    $rsvp_not_submitted = count($not_submitted_member_list);
    try {
      if (!empty($rsvp_ids)) {
        // Fetch distinct user_ids who submitted RSVP for this miqaat
        $rsvp_user_rows = $this->db->query("
          SELECT DISTINCT gr.user_id 
          FROM general_rsvp gr 
          WHERE gr.miqaat_id = ?
        ", [$miqaat_pk])->result_array();
        $rsvp_user_ids = [];
        foreach ($rsvp_user_rows as $r) {
          if (!empty($r['user_id'])) {
            $rsvp_user_ids[] = (int)$r['user_id'];
          }
        }

        $this->db->from('user u')
          ->where("u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''", null, false)
          ->where_in('u.HOF_ID', $rsvp_ids);
        if (!empty($rsvp_user_ids)) {
          $this->db->where_not_in('u.ITS_ID', $rsvp_user_ids);
        }
        if (!empty($sector)) {
          $this->db->where('u.Sector', $sector);
        }
        if (!empty($subsector)) {
          $this->db->where('u.Sub_Sector', $subsector);
        }
        $will_not_attend = (int)$this->db->count_all_results();
      }
    } catch(Exception $e) {}

    return [
      'next_miqaat' => $mi,
      'total_hof' => $total_hof,
      'rsvp_count' => $rsvp_count,
      'not_rsvp_count' => $not_rsvp_count,
      'will_not_attend' => $will_not_attend,
      'rsvp_not_submitted' => $rsvp_not_submitted,
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
}
