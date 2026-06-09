<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HijriCalendar extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }
  public function get_gregorian_date_for_hijri($hijri_date)
  {
    $this->db->select('greg_date');
    $this->db->from('hijri_calendar');
    $this->db->where('hijri_date', $hijri_date);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $row = $result->row_array();
      return $row['greg_date'];
    }
    return null;
  }
  
  public function get_hijri_calendar($year = NULL)
  {
    $hijri_date_today = $this->get_hijri_date(date("Y-m-d"));
    $current_hijri_year = explode("-", $hijri_date_today["hijri_date"])[2];

    $this->db->from("hijri_calendar");
    if (isset($year)) {
      $this->db->where("hijri_date like '%$year%'");
    } else {
      $this->db->where("hijri_date like '%$current_hijri_year%'");
    }
    $result = $this->db->get()->result_array();
    return count($result) > 0 ? $result : [];
  }
  private static $_hijri_date_cache = [];

  public function get_hijri_date($gerg_date)
  {
    if (!$gerg_date) return null;
    if (isset(self::$_hijri_date_cache[$gerg_date])) {
      return self::$_hijri_date_cache[$gerg_date];
    }
    $this->db->from("hijri_calendar");
    $this->db->where('greg_date', $gerg_date);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $hijri_date = $result->row_array();

      // Switch to next Hijri year 30 days before the current year end
      if (date('Y-m-d', strtotime($gerg_date)) === date('Y-m-d')) {
        $parts = explode('-', $hijri_date['hijri_date']);
        $year = (int)$parts[2];
        
        $query = $this->db->select('COUNT(*) as cnt')
                          ->from('hijri_calendar')
                          ->where("SUBSTRING_INDEX(hijri_date, '-', -1) =", (string)$year)
                          ->where('id >=', (int)$hijri_date['id'])
                          ->get();
        if ($query->num_rows() > 0) {
          $row = $query->row_array();
          $days_left = (int)$row['cnt'];
          if ($days_left <= 30) {
            $parts[2] = $year + 1;
            $hijri_date['hijri_date'] = implode('-', $parts);
          }
        }
      }

      self::$_hijri_date_cache[$gerg_date] = $hijri_date;
      return $hijri_date;
    }
    return null;
  }
  private static $_hijri_month_cache = [];

  public function hijri_month_name($hijri_month_id)
  {
    if (!$hijri_month_id) return null;
    if (isset(self::$_hijri_month_cache[$hijri_month_id])) {
      return self::$_hijri_month_cache[$hijri_month_id];
    }
    $this->db->from("hijri_month");
    $this->db->where('id', $hijri_month_id);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $hijri_month = $result->row_array();
      self::$_hijri_month_cache[$hijri_month_id] = $hijri_month;
      return $hijri_month;
    }
    return null;
  }
  public function get_hijri_month()
  {
    $this->db->from("hijri_month");
    $hijri_date = $this->db->get()->result_array();
    return $hijri_date;
  }
  public function getPendingDaysInYear()
  {
    $hijri_date = $this->get_hijri_date(date("Y-m-d"));
    if (!$hijri_date) return [];
    
    $hijri_year = explode("-", $hijri_date["hijri_date"])[2];

    $this->db->from("hijri_calendar");
    $this->db->where("greg_date", date("Y-m-d"));
    $db_row = $this->db->get()->row_array();
    $todays_hijri_date_id = $db_row ? $db_row["id"] : 0;

    $this->db->from("hijri_calendar");
    $this->db->where("SUBSTRING_INDEX(hijri_date, '-', -1) =", (string)$hijri_year);
    $this->db->where("id >=", $todays_hijri_date_id);
    $pendingDaysInYear = $this->db->get()->result_array();
    return $pendingDaysInYear;
  }

  // NEW METHODS FOR HIJRI DATE SELECTOR
  public function get_distinct_hijri_years()
  {
    $this->db->select("DISTINCT(SUBSTRING_INDEX(hijri_date, '-', -1)) as year", false);
    $this->db->from("hijri_calendar");
    $this->db->order_by("CAST(year AS UNSIGNED)", "ASC");
    $result = $this->db->get()->result_array();
    return array_column($result, 'year');
  }

  public function get_hijri_months_for_year($year)
  {
    if (!$year) return [];
    $this->db->select("DISTINCT(SUBSTRING_INDEX(SUBSTRING_INDEX(hijri_date, '-', 2), '-', -1)) as month", false);
    $this->db->from("hijri_calendar");
    $this->db->like("hijri_date", '-' . $year);
    $this->db->order_by("CAST(month AS UNSIGNED)", "ASC");
    $months = $this->db->get()->result_array();
    $out = [];
    foreach ($months as $m) {
      $month_id = $m['month'];
      $month_name_row = $this->hijri_month_name($month_id);
      $out[] = [
        'id' => $month_id,
        'name' => $month_name_row ? $month_name_row['hijri_month'] : $month_id
      ];
    }
    return $out;
  }

  public function get_hijri_days_for_month_year($month, $year)
  {
    if (!$month || !$year) return [];
    $this->db->select('hijri_date, greg_date, hijri_month_id');
    $this->db->from('hijri_calendar');
    // Try strict LIKE with zero-padded month (e.g., -06-1447)
    $this->db->like('hijri_date', '-' . $month . '-' . $year);
    $this->db->order_by('greg_date', 'ASC');
    $rows = $this->db->get()->result_array();

    // Fallback: match by month id and year suffix in hijri_date to handle non-padded months
    if (empty($rows)) {
      $this->db->select('hijri_date, greg_date, hijri_month_id');
      $this->db->from('hijri_calendar');
      $this->db->where('hijri_month_id', (int)$month);
      $this->db->like('hijri_date', '-' . $year);
      $this->db->order_by('greg_date', 'ASC');
      $rows = $this->db->get()->result_array();
    }
    $out = [];
    foreach ($rows as $r) {
      // hijri_date assumed format d-m-Y
      $parts = explode('-', $r['hijri_date']);
      if (count($parts) === 3) {
        $out[] = [
          'day' => $parts[0],
          'hijri_date' => $r['hijri_date'],
          'greg_date' => $r['greg_date']
        ];
      }
    }
    return $out;
  }

  // Returns associative array with hijri_year, hijri_month, hijri_day, month_name for a given greg_date (Y-m-d)
  public function get_hijri_parts_by_greg_date($greg_date)
  {
    if (!$greg_date) return null;
    $row = $this->get_hijri_date($greg_date);
    if (!$row) return null;
    $parts = explode('-', $row['hijri_date']); // d-m-Y
    if (count($parts) !== 3) return null;
    $month_row = $this->hijri_month_name($row['hijri_month_id']);
    return [
      'hijri_day' => $parts[0],
      'hijri_month' => $parts[1],
      'hijri_year' => $parts[2],
      'hijri_month_name' => $month_row ? $month_row['hijri_month'] : $parts[1]
    ];
  }
}
