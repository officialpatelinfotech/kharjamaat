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
    $current_hijri_year = substr($hijri_date_today["hijri_date"], 4);

    $this->db->from("hijri_calendar");
    if (isset($year)) {
      $this->db->where("hijri_date like '%$year%'");
    } else {
      $this->db->where("hijri_date like '%$current_hijri_year%'");
    }
    $result = $this->db->get()->result_array();
    return count($result) > 0 ? $result : [];
  }
  public function get_hijri_date($gerg_date)
  {
    $this->db->from("hijri_calendar");
    $this->db->where('greg_date', $gerg_date);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $hijri_date = $result->result_array();
      return $hijri_date[0];
    }
  }
  public function hijri_month_name($hijri_month_id)
  {
    $this->db->from("hijri_month");
    $this->db->where('id', $hijri_month_id);
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $hijri_month = $result->result_array();
      return $hijri_month[0];
    }
  }
  public function get_hijri_month()
  {
    $this->db->from("hijri_month");
    $hijri_date = $this->db->get()->result_array();
    return $hijri_date;
  }
  public function getPendingDaysInYear()
  {
    $this->db->from("hijri_calendar");
    $this->db->where("greg_date", date("Y-m-d"));
    $hijri_date = $this->db->get()->result_array()[0];
    $hijri_year = substr($hijri_date["hijri_date"], 0, 4);

    $this->db->from("hijri_calendar");
    $this->db->like("hijri_date", $hijri_year);
    $this->db->where("hijri_date >=", $hijri_date["hijri_date"]);
    $pendingDaysInYear = $this->db->get()->result_array();
    return $pendingDaysInYear;
  }
}
