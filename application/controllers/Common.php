<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Common extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('CommonM');
    $this->load->model('AccountM');
    $this->load->model('HijriCalendar');
  }

  private function validateUser($user)
  {
    if (empty($user) || ($user['role'] != 1 && $user['role'] != 3 && $user['role'] != 2 && $user['role'] != 16)) {
      redirect('/accounts');
    }
  }

  public function fmbcalendar()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $this->input->get('from');
    $_SESSION["from"] = $from;
    $data["from"] = $from;
    $data['active_controller'] = $from ? base_url($from) : base_url();

    // Handle Month/Year filter
    $hijri_month_id = $this->input->post('hijri_month');
    $sort_type = $this->input->post('sort_type');
    $assignment_filter = $this->input->post('assignment_filter');
    $miqaat_type = $this->input->post('miqaat_type');
    $member_name_filter = $this->input->post('member_name_filter');
    $data['hijri_months'] = $this->HijriCalendar->get_hijri_month();
    if (empty($hijri_month_id)) {
      $hijri_month_id = -1;
    }

    $data['hijri_month_id'] = $hijri_month_id;
    $data['sort_type'] = $sort_type;
    $data['miqaat_type'] = isset($miqaat_type) ? $miqaat_type : '';
    $data['miqaat_types'] = $this->CommonM->get_distinct_miqaat_types();
    $data['member_name_filter'] = isset($member_name_filter) ? $member_name_filter : '';
    $data['assignment_filter'] = isset($assignment_filter) ? $assignment_filter : '';
    // Fetch calendar data for table (assignments now included in model)
    $calendar = $this->CommonM->get_full_calendar($hijri_month_id, $sort_type);
    // Optional: filter rows by miqaat type
    if (!empty($miqaat_type)) {
      $calendar = array_values(array_filter($calendar, function ($row) use ($miqaat_type) {
        return isset($row['miqaat_type']) && $row['miqaat_type'] === $miqaat_type;
      }));
    }
    // Optional: filter rows by assignment status (assigned/unassigned) for miqaat rows; ignore Thaali rows
    // Apply Fala ni Niyaz logic: treat FNN as Assigned even without explicit assignments
    if (!empty($assignment_filter)) {
      $calendar = array_values(array_filter($calendar, function ($row) use ($assignment_filter) {
        // Only apply to miqaat rows (skip holidays and Thaali rows). Allow miqaat rows even if name is empty.
        $row_type = isset($row['miqaat_type']) ? $row['miqaat_type'] : '';
        $row_name = isset($row['miqaat_name']) ? $row['miqaat_name'] : '';
        if ($row_type === 'Thaali')
          return false;
        if ($row_type === '' && $row_name === '')
          return false; // likely a holiday/empty row

        $hasAssignments = !empty($row['assignments']);
        $type = $row_type;
        $name = $row_name;
        $assigned_to = isset($row['assigned_to']) ? $row['assigned_to'] : '';
        // Build a combined haystack for robust FNN detection
        $hay_parts = [(string) $type, (string) $name, (string) $assigned_to];
        if ($hasAssignments) {
          foreach ((array) $row['assignments'] as $as) {
            $hay_parts[] = (string) ($as['assign_type'] ?? '');
          }
        }
        $hay = strtolower(trim(implode(' ', $hay_parts)));
        $basic = preg_replace('/\s+/', ' ', $hay);
        $letters = preg_replace('/[^a-z]/', '', $basic);
        $letters = preg_replace(['/a{2,}/', '/i{2,}/', '/y{2,}/'], ['a', 'i', 'y'], $letters);
        $isFnn = (strpos($basic, 'fnn') !== false)
          || ((strpos($basic, 'fala') !== false) && (strpos($basic, 'niyaz') !== false || strpos($basic, 'niaz') !== false))
          || ((strpos($letters, 'fala') !== false) && (strpos($letters, 'niyaz') !== false || strpos($letters, 'niaz') !== false));

        if ($assignment_filter === 'assigned') {
          return $hasAssignments || $isFnn;
        }
        if ($assignment_filter === 'unassigned') {
          // Unassigned only when there are no assignments and not FNN
          return !$hasAssignments && !$isFnn;
        }
        return true;
      }));
    }
    // Optional: filter rows by member name across assignments; exclude non-miqaat rows when member filter is present
    $memberFilter = strtolower(trim($member_name_filter ?? ''));
    if ($memberFilter !== '') {
      $calendar = array_values(array_filter($calendar, function ($row) use ($memberFilter) {
        // Only consider miqaat rows with assignments
        if (empty($row['miqaat_name']) || $row['miqaat_type'] === 'Thaali')
          return false;
        $assignments = isset($row['assignments']) ? $row['assignments'] : [];
        if (empty($assignments))
          return false;
        foreach ($assignments as $as) {
          if (isset($as['assign_type']) && $as['assign_type'] === 'Group') {
            $leaderName = strtolower($as['group_leader_name'] ?? '');
            $groupName = strtolower($as['group_name'] ?? '');
            if ($leaderName !== '' && strpos($leaderName, $memberFilter) !== false)
              return true;
            if ($groupName !== '' && strpos($groupName, $memberFilter) !== false)
              return true;
            if (!empty($as['members'])) {
              foreach ($as['members'] as $memb) {
                $mname = strtolower($memb['name'] ?? '');
                if ($mname !== '' && strpos($mname, $memberFilter) !== false)
                  return true;
              }
            }
          } elseif (isset($as['assign_type']) && $as['assign_type'] === 'Individual') {
            $indName = strtolower($as['member_name'] ?? '');
            if ($indName !== '' && strpos($indName, $memberFilter) !== false)
              return true;
          }
        }
        return false;
      }));
    }
    // After fetching $calendar, update each entry to add hijri month name to the date
    foreach ($calendar as &$entry) {
      if (!empty($entry['hijri_date'])) {
        $hijri_parts = explode('-', $entry['hijri_date']);
        $hijri_month_id = isset($hijri_parts[1]) ? $hijri_parts[1] : '';
        $hijri_month_name = '';
        if ($hijri_month_id) {
          $month_info = $this->HijriCalendar->hijri_month_name($hijri_month_id);
          $hijri_month_name = isset($month_info['hijri_month']) ? $month_info['hijri_month'] : '';
        }
        $entry['hijri_date_with_month'] = explode('-', $entry['hijri_date'])[0] . ' ' . $hijri_month_name . ' ' . explode('-', $entry['hijri_date'])[2];
      } else {
        $entry['hijri_date_with_month'] = '';
      }
    }
    $data['calendar'] = $calendar;

    if (!empty($calendar)) {
      $first_day_of_calendar = $calendar[0]["greg_date"];
      $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($first_day_of_calendar)));
      $data['hijri_year'] = explode('-', $hijri_date['hijri_date'])[2];
    } else {
      $today_hijri_date = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
      $data['hijri_year'] = isset($today_hijri_date['hijri_date']) ? explode('-', $today_hijri_date['hijri_date'])[2] : '';
    }

    // Yearly stats: Miqaat days, Thaali days, Holidays for the selected/current Hijri year
    $stats_year = $data['hijri_year'];
    $data['year_daytype_stats'] = $this->CommonM->get_year_calendar_daytypes($stats_year);

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/FMBCalendar', $data);
  }

  // New simple page to host the Takhmeen Pay button and modal
  public function takhmeen_pay()
  {
    $this->validateUser(isset($_SESSION['user']) ? $_SESSION['user'] : null);
    $data = [];
    $data['user_name'] = isset($_SESSION['user']) ? $_SESSION['user']['username'] : '';
    $data['active_controller'] = base_url('common/takhmeen_pay');
    // Provide hijri_year for the payment form
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $data['hijri_year'] = isset($today_hijri['hijri_date']) ? explode('-', $today_hijri['hijri_date'])[2] : '';
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/PayTakhmeen', $data);
  }

  public function generate_pdf()
  {
    $this->load->library('dompdf_lib');
    $dompdf = $this->dompdf_lib->load();

    $payment_id = $this->input->post("id");
    $for = $this->input->post("for");

    $table = null;
    switch ($for) {
      case 1:
        $table = "fmb_takhmeen_payments";
        break;
      case 2:
        $table = "miqaat_payment";
        break;
      case 3:
        $table = "fmb_general_contribution_payments";
        break;
      case 4:
        $table = "sabeel_takhmeen_payments";
        break;
      case 5:
        $table = "corpus_fund_payment";
        break;
      default:
        // Handle invalid 'for' value
        echo "Invalid request.";
        return;
    }

    $result = $this->CommonM->get_payment_details($payment_id, $table);

    $data = [];

    if ($result) {
      // Indian number words using Crore/Lakh/Thousand/Hundred
      $amtInt = is_numeric($result["amount"]) ? (int) floor($result["amount"]) : 0;
      $ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
      $tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
      $twoDigits = function ($n) use ($ones, $tens) {
        if ($n == 0)
          return "";
        if ($n < 20)
          return $ones[$n];
        $ten = intdiv($n, 10);
        $one = $n % 10;
        return $tens[$ten] . ($one ? "-" . $ones[$one] : "");
      };
      $threeDigits = function ($n) use ($ones, $twoDigits) {
        $s = "";
        $hund = intdiv($n, 100);
        $rem = $n % 100;
        if ($hund)
          $s .= $ones[$hund] . " Hundred" . ($rem ? " " : "");
        if ($rem)
          $s .= $twoDigits($rem);
        return $s;
      };
      $parts = [];
      $crore = intdiv($amtInt, 10000000);
      $amtInt %= 10000000;
      $lakh = intdiv($amtInt, 100000);
      $amtInt %= 100000;
      $thousand = intdiv($amtInt, 1000);
      $amtInt %= 1000;
      $hundreds = $amtInt; // 0..999
      if ($crore)
        $parts[] = ($crore < 100 ? $twoDigits($crore) : $threeDigits($crore)) . " Crore";
      if ($lakh)
        $parts[] = ($lakh < 100 ? $twoDigits($lakh) : $threeDigits($lakh)) . " Lakh";
      if ($thousand)
        $parts[] = ($thousand < 100 ? $twoDigits($thousand) : $threeDigits($thousand)) . " Thousand";
      if ($hundreds)
        $parts[] = $threeDigits($hundreds);
      if (empty($parts))
        $parts[] = "Zero";
      $amount_words = implode(" ", $parts);

      // Format amount in INR (Indian grouping, no decimals) with Rupee symbol
      $amt = is_numeric($result["amount"]) ? (int) round($result["amount"]) : 0;
      $amtStr = (string) max(0, $amt);
      if (strlen($amtStr) > 3) {
        $last3 = substr($amtStr, -3);
        $rest = substr($amtStr, 0, strlen($amtStr) - 3);
        $rest_rev = strrev($rest);
        $rest_groups = str_split($rest_rev, 2);
        $rest_formatted = strrev(implode(',', $rest_groups));
        $amtStr = $rest_formatted . ',' . $last3;
      }
      $amount_inr = "₹" . $amtStr;

      $data = array(
        "date" => $result["payment_date"],
        "name" => $result["Full_Name"],
        "address" => $result["Address"],
        "amount" => $amount_inr,
        "amount_words" => $amount_words . " Only",
      );
    }

    $html = $this->load->view('pdf_template', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Download as file
    // $dompdf->stream("myfile.pdf", array("Attachment" => 1));

    // Or show in browser
    $dompdf->stream("myfile.pdf", array("Attachment" => 0));
  }

  // public function update_day()
  // {
  //   $this->validateUser($_SESSION['user']);

  //   $greg_date = $this->input->post("greg_date");
  //   $day_type = $this->input->post("day_type");
  //   $this->set_fmbcddt_session($greg_date, $day_type);

  //   switch ($day_type) {
  //     case 'Thaali':
  //       redirect("common/createmenu");
  //       break;
  //     case 'Miqaat':
  //       redirect("common/createmiqaat");
  //       break;
  //     case 'Both':
  //       redirect("common/createmenu");
  //       break;
  //     case 'Holiday':
  //       $result = $this->CommonM->update_day($greg_date, $day_type);
  //       if ($result) {
  //         redirect("common/fmbcalendar");
  //       }
  //       break;

  //     default:
  //       break;
  //   }
  // }
  public function get_hijri_day_month($greg_date)
  {
    $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($greg_date)));
    $hijri_month_name = $this->HijriCalendar->hijri_month_name($hijri_date["hijri_month_id"]);
    return explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month_name["hijri_month"] . " " . explode("-", $hijri_date["hijri_date"])[2];
  }

  public function fmbthaalimenu()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    // Get current hijri year
    $today = date('Y-m-d');
    $hijri_today = $this->HijriCalendar->get_hijri_date($today);
    $hijri_year = explode('-', $hijri_today['hijri_date'])[2];
    if (!isset($member_name_filter)) {
      $member_name_filter = '';
    }

    $filter_data = [
      'hijri_month_id' => -1, // -1 means full year
      'hijri_year' => $hijri_year
    ];
    $data['menu'] = $this->CommonM->get_month_wise_menu($filter_data);
    foreach ($data["menu"] as $key => $value) {
      $data['menu'][$key]['hijri_date'] = $this->get_hijri_day_month($value["date"], $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"]);
    }

    // Summary cards data (moved from view)
    // Summary cards data aggregated for the Hijri year using AccountM helpers
    $this->load->model('AccountM');
    $summary_total_thaali_days = 0;
    $summary_miqaat_days = 0;
    $summary_sundays_utlat = 0;
    // Loop hijri months 1..12 for the selected year
    for ($hm = 1; $hm <= 12; $hm++) {
      $month_data = $this->AccountM->get_hijri_month_menu($hijri_year, $hm);
      foreach ($month_data as $d) {
        $dayName = isset($d['date']) ? date('l', strtotime($d['date'])) : '';
        $hasMenu = isset($d['menu']['items']) && is_array($d['menu']['items']) && count($d['menu']['items']) > 0;
        $hasMiqaat = isset($d['miqaats']) && is_array($d['miqaats']) && count($d['miqaats']) > 0;
        if ($hasMenu)
          $summary_total_thaali_days++;
        if ($hasMiqaat)
          $summary_miqaat_days++;
        // Sundays + Utlat: Sundays with no Miqaat (ignore menu), plus non-Sunday holidays
        $isHoliday = isset($d['is_holiday']) ? (bool) $d['is_holiday'] : false;
        if ($dayName === 'Sunday' && !$hasMiqaat) {
          $summary_sundays_utlat++;
        } elseif ($isHoliday && $dayName !== 'Sunday') {
          $summary_sundays_utlat++;
        }
      }
    }
    // Assignment-based counts for Hijri year (Individual/Group/FNN)
    // Determine Gregorian range for this Hijri year from hijri_calendar
    $this->db->select('greg_date');
    $this->db->from('hijri_calendar');
    $this->db->like('hijri_date', $hijri_year);
    $this->db->order_by('greg_date', 'ASC');
    $hc_rows = $this->db->get()->result_array();
    if (!empty($hc_rows)) {
      $range_start = $hc_rows[0]['greg_date'];
      $range_end = $hc_rows[count($hc_rows) - 1]['greg_date'];
      // Individual count: distinct miqaat days with any individual assignment
      $individual_row = $this->db->query(
        "SELECT COUNT(DISTINCT m.date) AS c
         FROM miqaat m
         JOIN miqaat_assignments ma ON ma.miqaat_id = m.id
         WHERE m.date >= ? AND m.date <= ? AND LOWER(TRIM(ma.assign_type)) = 'individual'",
        [$range_start, $range_end]
      )->row_array();
      // Group count
      $group_row = $this->db->query(
        "SELECT COUNT(DISTINCT m.date) AS c
         FROM miqaat m
         JOIN miqaat_assignments ma ON ma.miqaat_id = m.id
         WHERE m.date >= ? AND m.date <= ? AND LOWER(TRIM(ma.assign_type)) = 'group'",
        [$range_start, $range_end]
      )->row_array();
      // FNN count: match common variants (fnn, fala + niyaz/niaz)
      $fnn_row = $this->db->query(
        "SELECT COUNT(DISTINCT m.date) AS c
         FROM miqaat m
         JOIN miqaat_assignments ma ON ma.miqaat_id = m.id
         WHERE m.date >= ? AND m.date <= ? AND (
           LOWER(ma.assign_type) LIKE '%fnn%'
           OR (LOWER(ma.assign_type) LIKE '%fala%' AND (LOWER(ma.assign_type) LIKE '%niyaz%' OR LOWER(ma.assign_type) LIKE '%niaz%'))
         )",
        [$range_start, $range_end]
      )->row_array();
      $data['summary_individual'] = isset($individual_row['c']) ? (int) $individual_row['c'] : 0;
      $data['summary_group'] = isset($group_row['c']) ? (int) $group_row['c'] : 0;
      $data['summary_fnn'] = isset($fnn_row['c']) ? (int) $fnn_row['c'] : 0;
    } else {
      $data['summary_individual'] = 0;
      $data['summary_group'] = 0;
      $data['summary_fnn'] = 0;
    }
    $data['summary_miqaat_days'] = (int) $summary_miqaat_days;
    $data['summary_total_thaali_days'] = (int) $summary_total_thaali_days;
    $data['summary_total_days'] = isset($data['menu']) ? count($data['menu']) : 0;
    $data['summary_sundays'] = (int) $summary_sundays_utlat;

    $from = $this->input->get('from');
    $_SESSION["from"] = $from;
    $data['from'] = $from;
    $data['active_controller'] = $from ? base_url($from) : base_url();

    $data["hijri_months"] = $this->HijriCalendar->get_hijri_month();
    $data["hijri_year"] = explode(" ", $data['menu'][0]['hijri_date'])[3];

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/FMBThaaliMenu', $data);
  }

  // Create Menu
  public function createmenu()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data["menu_dates"] = $this->CommonM->get_menu_dates();

    $data['menu_items'] = $this->CommonM->get_menu_items();
    $data['edit_mode'] = false;
    $data['date'] = $this->input->get('date') ? date("d-m-Y", strtotime($this->input->get('date'))) : '';

    $from = $_SESSION["from"];
    $data['active_controller'] = $from ? base_url($from) : base_url();
    $data["from"] = $from;

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMenu', $data);
  }

  public function add_menu()
  {
    $this->validateUser($_SESSION['user']);
    $edit_mode = $this->input->post('edit_mode') == 'true' ? true : false;
    $menu_date = $this->input->post('menu_date');

    $selected_item_ids = json_decode($this->input->post('selected_item_ids'), true);

    if ($edit_mode) {
      $menu_id = $this->input->post('menu_id');
      if ($menu_id) {
        $menu_date ? $this->CommonM->update_menu($menu_id, $menu_date) : 0;
        if (!empty($selected_item_ids)) {
          $this->CommonM->remove_items_from_menu($menu_id);
          $this->CommonM->add_items_to_menu($menu_id, $selected_item_ids);
          $this->session->set_flashdata('success', 'Menu created successfully!');
        }
      } else {
        $this->session->set_flashdata('error', 'Failed to create menu.');
      }
    } else {
      if (!empty($menu_date) && !empty($selected_item_ids)) {
        $menu_id = $this->CommonM->insert_menu($menu_date);
        if ($menu_id) {
          $result = $this->CommonM->add_items_to_menu($menu_id, $selected_item_ids);
          $this->session->set_flashdata('success', 'Menu created successfully!');
        } else {
          $this->session->set_flashdata('error', 'Failed to create menu.');
        }
      } else {
        $this->session->set_flashdata('error', 'Please provide a name and select at least one item.');
      }
    }

    // if ($_SESSION["day_type"] == "Thaali") {
    //   $session_date = $_SESSION["date"];
    //   $session_day_type = $_SESSION["day_type"];
    //   $result = $this->CommonM->update_day(
    //     $session_date,
    //     $session_day_type
    //   );
    //   if ($result) {
    //     $this->session->unset_userdata(
    //       "date",
    //       "day_type"
    //     );
    //     redirect('Common/fmbcalendar');
    //   }
    // } else {
    //   redirect("Common/createmiqaat");
    // }
    $from = $_SESSION["from"];
    redirect("common/fmbthaalimenu?from=" . $from);
  }

  public function delete_menu()
  {
    $this->validateUser($_SESSION["user"]);
    if ($this->input->post('menu_id')) {
      $menu_id = $this->input->post('menu_id');
      $this->CommonM->delete_menu($menu_id);
      $this->session->set_flashdata('success', 'Menu deleted successfully!');
    } else {
      $this->session->set_flashdata('error', 'No Menu ID provided.');
    }
    $from = $_SESSION["from"];
    $data["from"] = $from;
    redirect('common/fmbthaalimenu?from=' . $from);
  }

  public function verify_menu_date()
  {
    $menu_date = $this->input->post('menu_date');

    if ($this->CommonM->validate_menu_date($menu_date)) {
      echo json_encode(['status' => 'available', 'hijri_date' => date("d-m-Y", strtotime($this->HijriCalendar->get_hijri_date($menu_date)["hijri_date"]))]);
    } else {
      echo json_encode(['status' => 'exists']);
    }
  }

  public function insert_menu()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $menu_name = $this->input->post('menu_name');
    $data['menu_name'] = $menu_name;

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMenu', $data);
  }

  public function search_items()
  {
    $keyword = $this->input->post('keyword');
    $items = $this->CommonM->search_items($keyword);
    echo json_encode($items);
  }

  public function edit_menu($id = null)
  {
    $this->validateUser($_SESSION['user']);

    if ($this->input->get('id')) {
      $id = $this->input->get('id');
    }
    $from = $this->input->get('from');
    if ($from) {
      $data["from"] = $from;
    }

    $data['edit_mode'] = true;

    $menu = $this->CommonM->get_menu_by_id($id);

    if ($menu) {
      $data['menu'] = $menu;
      $data["mapped_menu_items"]['items'] = $this->CommonM->get_menu_items_by_menu_id($menu['id']);
    } else {
      $data['menu'] = [];
    }

    $data["menu_dates"] = $this->CommonM->get_menu_dates();
    $data['user_name'] = $_SESSION['user']['username'];

    $data['active_controller'] = $_SESSION["from"];

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMenu', $data);
  }

  public function filter_menu()
  {
    $this->validateUser($_SESSION['user']);
    $hijri_month_id = $this->input->post('hijri_month');
    $today_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"];
    $today_hijri_year = explode("-", $today_hijri_date)[2];
    if ($hijri_month_id == -1) {
      $this_hijri_year = $today_hijri_year;
    } else if ($hijri_month_id == -2) {
      $this_hijri_year = $today_hijri_year + 1;
    } else if ($hijri_month_id == -3) {
      $this_hijri_year = $today_hijri_year - 1;
    } else {
      $this_hijri_year = $today_hijri_year;
    }

    $fitler_data = [
      'hijri_month_id' => $this->input->post('hijri_month'),
      'hijri_year' => $this_hijri_year,

      // 'start_date' => $this->input->post('start_date'),
      // 'end_date' => $this->input->post('end_date'),
      // 'sort_type' => $this->input->post('sort_type')
    ];

    $data['menu'] = $this->CommonM->get_month_wise_menu($fitler_data);

    if (isset($data['menu']) && !empty($data['menu'])) {
      foreach ($data["menu"] as $key => $value) {
        $data['menu'][$key]['hijri_date'] = $this->get_hijri_day_month($value["date"]);
      }
    }

    $data["hijri_months"] = $this->HijriCalendar->get_hijri_month();

    $data['user_name'] = $_SESSION['user']['username'];
    $data['hijri_month_id'] = $this->input->post('hijri_month');
    // $data['start_date'] = $this->input->post('start_date');
    // $data['end_date'] = $this->input->post('end_date');
    // $data['sort_type'] = $this->input->post('sort_type');
    $data['active_controller'] = $_SESSION["from"] ? base_url($_SESSION["from"]) : base_url();
    $data["from"] = $_SESSION["from"];
    $data["hijri_year"] = explode(" ", $data['menu'][0]['hijri_date'])[3];

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/FMBThaaliMenu', $data);
  }

  // public function duplicate_last_month_menu()
  // {
  //   if (!empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
  //     redirect('/accounts');
  //   }

  //   $result = $this->CommonM->duplicate_last_month_menu();

  //   if ($result) {
  //     $this->session->set_flashdata('success', 'Menu duplicated successfully.');
  //   } else {
  //     $this->session->set_flashdata('error', 'No records found for last month or duplication failed.');
  //   }

  //   redirect('Common/fmbthaalimenu'); // Adjust this to your actual menu listing route
  // }


  // Menu Item Management
  public function add_menu_item()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['menu_items'] = $this->CommonM->get_menu_items();
    $data['item_types'] = $this->CommonM->get_item_types();

    $data["active_controller"] = $_SESSION["from"];

    $data["from"] = $this->input->get('from');
    $_SESSION["from"] = $data["from"];

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/AddMenuItem', $data);
  }

  public function insert_item_type()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $from = $_SESSION["from"];

    $type_name = $this->input->post('type_name');

    $data = array(
      'type_name' => $type_name,
    );

    $check = $this->CommonM->insert_item_type($data);
    if ($check) {
      redirect('common/add_menu_item?from=' . $from);
    } else {
      redirect('common/add_menu_item?from=' . $from);
    }
  }

  public function insert_menu_item()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $item_name = $this->input->post('item_name');
    $item_type = $this->input->post('item_type');

    $from = $this->input->get('from');

    $data = array(
      'name' => $item_name,
      'type' => $item_type,
    );

    $check = $this->CommonM->insert_menu_item($data);
    if ($check) {
      redirect('common/add_menu_item?from=' . $from);
    } else {
      redirect('common/add_menu_item?from=' . $from);
    }

    redirect('common/fmbthaalimenu?from=' . $from);
  }

  public function edit_menu_item()
  {
    $this->validateUser($_SESSION['user']);

    $data = [
      'id' => $this->input->post('id'),
      'name' => $this->input->post('name'),
      'type' => $this->input->post('type')
    ];

    $result = $this->CommonM->edit_menu_item($data);

    echo json_encode(['status' => $result ? 'success' : 'failed']);
  }

  public function delete_menu_item()
  {
    $this->validateUser($_SESSION['user']);

    $id = $this->input->post('id');

    $result = $this->CommonM->delete_menu_item($id);

    echo json_encode(['status' => 'success']);
  }

  public function filter_menu_item()
  {
    $this->validateUser($_SESSION['user']);

    $fitler_data = [
      'filter_type' => $this->input->post('filter-type'),
      'search_item' => $this->input->post('search-item'),
      'sort_type' => $this->input->post('sort-type')
    ];

    $data["active_controller"] = $_SESSION["from"];
    $data['user_name'] = $_SESSION['user']['username'];
    $data['filter_type'] = $this->input->post('filter-type');
    $data['search_item'] = $this->input->post('search-item');
    $data['sort_type'] = $this->input->post('sort-type');
    $data['item_types'] = $this->CommonM->get_item_types();
    $data['menu_items'] = $this->CommonM->filter_menu_item($fitler_data);
    $data['from'] = $this->input->get('from');
    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/AddMenuItem', $data);
  }
  // Create Menu

  // Create Miqaat
  public function managemiqaat()
  {
    $this->validateUser($_SESSION["user"]);
    $from = $this->input->get('from');
    $_SESSION["from"] = $from;
    $data["active_controller"] = $from ? base_url($from) : base_url();
    $data['user_name'] = $_SESSION['user']['username'];
    $data["from"] = $from;

    // Get filter options
    $hijri_month_id = $this->input->post('hijri_month');
    $sort_type = $this->input->post('sort_type');
    $assignment_filter = $this->input->post('assignment_filter');
    $miqaat_type = $this->input->post('miqaat_type');
    $member_name_filter = $this->input->post('member_name_filter');
    $today_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"];
    $today_hijri_year = explode("-", $today_hijri_date)[2];
    if (empty($hijri_month_id) || $hijri_month_id == '') {
      $hijri_month_id = -1;
    }
    if ($hijri_month_id == -1) {
      $this_hijri_year = $today_hijri_year;
    } else if ($hijri_month_id == -2) {
      $this_hijri_year = $today_hijri_year + 1;
    } else if ($hijri_month_id == -3) {
      $this_hijri_year = $today_hijri_year - 1;
    } else {
      $this_hijri_year = $today_hijri_year;
    }

    $filter_data = [
      'hijri_month_id' => $hijri_month_id,
      'hijri_year' => $this_hijri_year,
      'sort_type' => $sort_type,
      'assignment_filter' => $assignment_filter,
      'miqaat_type' => $miqaat_type,
      'member_name_filter' => $member_name_filter
    ];

    $data['miqaats'] = $this->CommonM->get_miqaats_with_assignments($filter_data);
    // Also fetch full calendar rows to mirror FMB Calendar cards/totals
    $calendar = $this->CommonM->get_full_calendar($hijri_month_id, $sort_type);
    // Apply optional filters similar to fmbcalendar
    if (!empty($miqaat_type)) {
      $calendar = array_values(array_filter($calendar, function ($row) use ($miqaat_type) {
        return isset($row['miqaat_type']) && $row['miqaat_type'] === $miqaat_type;
      }));
    }
    $memberFilter = strtolower(trim($member_name_filter ?? ''));
    if ($memberFilter !== '') {
      $calendar = array_values(array_filter($calendar, function ($row) use ($memberFilter) {
        if (empty($row['miqaat_name']) || $row['miqaat_type'] === 'Thaali')
          return false;
        $assignments = isset($row['assignments']) ? $row['assignments'] : [];
        if (empty($assignments))
          return false;
        foreach ($assignments as $as) {
          if (isset($as['assign_type']) && $as['assign_type'] === 'Group') {
            $leaderName = strtolower($as['group_leader_name'] ?? '');
            $groupName = strtolower($as['group_name'] ?? '');
            if ($leaderName !== '' && strpos($leaderName, $memberFilter) !== false)
              return true;
            if ($groupName !== '' && strpos($groupName, $memberFilter) !== false)
              return true;
            if (!empty($as['members'])) {
              foreach ($as['members'] as $memb) {
                $mname = strtolower($memb['name'] ?? '');
                if ($mname !== '' && strpos($mname, $memberFilter) !== false)
                  return true;
              }
            }
          } elseif (isset($as['assign_type']) && $as['assign_type'] === 'Individual') {
            $indName = strtolower($as['member_name'] ?? '');
            if ($indName !== '' && strpos($indName, $memberFilter) !== false)
              return true;
          }
        }
        return false;
      }));
    }
    // Build calendar-based summary like FMBCalendar
    $summary_individual = 0;
    $summary_group = 0;
    $summary_fnn = 0;
    $summary_miqaat_days_excl_ladies = 0;
    $summary_holidays_excl_sundays = 0;
    $summary_sundays_without_miqaat_thaali = 0;
    $summary_thaali_days = 0;
    if (!empty($calendar)) {
      foreach ($calendar as $row) {
        $type = isset($row['miqaat_type']) ? $row['miqaat_type'] : '';
        $miqaat_name = isset($row['miqaat_name']) ? $row['miqaat_name'] : '';
        $menu_items_present = !empty($row['menu_items']) && (!is_array($row['menu_items']) || count($row['menu_items']) > 0);
        $isHoliday = empty($type) && empty($miqaat_name) && !$menu_items_present;
        $isSunday = (strtolower(date('l', strtotime($row['greg_date']))) === 'sunday');
        // Holidays excluding Sundays
        if ($isHoliday && !$isSunday) {
          $summary_holidays_excl_sundays++;
        }
        // Sundays without any Miqaat (ignore menu presence)
        if ($isSunday) {
          $hasMiqaat = !empty($miqaat_name) || (isset($type) && in_array(strtolower($type), ['miqaat', 'both']));
          if (!$hasMiqaat) {
            $summary_sundays_without_miqaat_thaali++;
          }
        }
        // Thaali day
        if (strtolower($type) === 'thaali') {
          $summary_thaali_days++;
        }
        if (!$isHoliday && $type !== 'Thaali') {
          $assignments = isset($row['assignments']) ? $row['assignments'] : [];
          // FNN detection
          $hay_parts = [(string) $type, (string) $miqaat_name, (string) ($row['assigned_to'] ?? '')];
          if (!empty($assignments)) {
            foreach ($assignments as $as_chk) {
              $hay_parts[] = (string) ($as_chk['assign_type'] ?? '');
            }
          }
          $hay = strtolower(trim(implode(' ', $hay_parts)));
          $basic = preg_replace('/\s+/', ' ', $hay);
          $letters = preg_replace('/[^a-z]/', '', $basic);
          $letters = preg_replace(['/a{2,}/', '/i{2,}/', '/y{2,}/'], ['a', 'i', 'y'], $letters);
          $is_fnn = (strpos($basic, 'fnn') !== false)
            || ((strpos($basic, 'fala') !== false) && (strpos($basic, 'niyaz') !== false || strpos($basic, 'niaz') !== false))
            || ((strpos($letters, 'fala') !== false) && (strpos($letters, 'niyaz') !== false || strpos($letters, 'niaz') !== false));
          if ($is_fnn)
            $summary_fnn++;
          // Individual/Group flags per row
          $row_has_individual = false;
          $row_has_group = false;
          foreach ($assignments as $as) {
            $t = isset($as['assign_type']) ? strtolower($as['assign_type']) : '';
            if ($t === 'individual')
              $row_has_individual = true;
            elseif ($t === 'group')
              $row_has_group = true;
          }
          if ($row_has_individual)
            $summary_individual++;
          if ($row_has_group)
            $summary_group++;
          // Miqaat days excluding Ladies
          if (strtolower($type) !== 'ladies') {
            $summary_miqaat_days_excl_ladies++;
          }
        }
      }
    }
    $data['calendar_summary'] = [
      'total_miqaat_days' => (int) $summary_miqaat_days_excl_ladies,
      'total_thaali_days' => (int) $summary_thaali_days,
      'sundays_utlat' => (int) ($summary_holidays_excl_sundays + $summary_sundays_without_miqaat_thaali),
      'individual' => (int) $summary_individual,
      'group' => (int) $summary_group,
      'fnn' => (int) $summary_fnn,
    ];
    $data["hijri_months"] = $this->HijriCalendar->get_hijri_month();
    $data['hijri_month_id'] = $hijri_month_id;
    $data['sort_type'] = $sort_type;
    $data['assignment_filter'] = $assignment_filter;
    $data['miqaat_type'] = $miqaat_type;
    $data['member_name_filter'] = $member_name_filter;

    // For filter dropdown
    $data['miqaat_types'] = $this->CommonM->get_distinct_miqaat_types();


    $memberFilter = strtolower(trim($member_name_filter ?? ''));
    foreach ($data["miqaats"] as &$entry) {
      if (!empty($entry['hijri_date'])) {
        $hijri_parts = explode('-', $entry['hijri_date']);
        $hijri_month_id = isset($hijri_parts[1]) ? $hijri_parts[1] : '';
        $hijri_month_name = '';
        if ($hijri_month_id) {
          $month_info = $this->HijriCalendar->hijri_month_name($hijri_month_id);
          $hijri_month_name = isset($month_info['hijri_month']) ? $month_info['hijri_month'] : '';
        }
        $entry['hijri_date_with_month'] = explode('-', $entry['hijri_date'])[0] . ' ' . $hijri_month_name . ' ' . explode('-', $entry['hijri_date'])[2];
      } else {
        $entry['hijri_date_with_month'] = '';
      }

      // Filter assignments within each miqaat if member name filter present
      if ($memberFilter !== '') {
        foreach ($entry['miqaats'] as $mk => $mv) {
          if (!empty($mv['assignments'])) {
            $filteredAssignments = [];
            foreach ($mv['assignments'] as $as) {
              $match = false;
              if (isset($as['assign_type']) && $as['assign_type'] === 'Group') {
                $leaderName = strtolower($as['group_leader_name'] ?? '');
                $groupName = strtolower($as['group_name'] ?? '');
                if (strpos($leaderName, $memberFilter) !== false || strpos($groupName, $memberFilter) !== false) {
                  $match = true;
                }
                if (!$match && !empty($as['members'])) {
                  foreach ($as['members'] as $memb) {
                    if (strpos(strtolower($memb['name'] ?? ''), $memberFilter) !== false) {
                      $match = true;
                      break;
                    }
                  }
                }
              } elseif (isset($as['assign_type']) && $as['assign_type'] === 'Individual') {
                if (strpos(strtolower($as['member_name'] ?? ''), $memberFilter) !== false) {
                  $match = true;
                }
              }
              if ($match) {
                $filteredAssignments[] = $as;
              }
            }
            $entry['miqaats'][$mk]['assignments'] = $filteredAssignments;
          }
        }
        // After narrowing assignments, optionally drop miqaat entries that end up empty when filtering for member names
        $memberFilterLocal = $memberFilter; // bring into closure
        $entry['miqaats'] = array_values(array_filter($entry['miqaats'], function ($mi) use ($memberFilterLocal) {
          return $memberFilterLocal === '' || !empty($mi['assignments']);
        }));
      }

      foreach ($entry["miqaats"] as $key => $value) {
        $assignment_count = count($value["assignments"]);
        $miqaat_invoice_status = $this->CommonM->get_miqaat_invoice_status($value["id"]);
        if ($miqaat_invoice_status) {
          $entry["miqaats"][$key]["invoice_status"] = $miqaat_invoice_status >= $assignment_count ? "Generated" : "Partially Generated";
        } else {
          $entry["miqaats"][$key]["invoice_status"] = "Not Generated";
        }
      }
    }

    // If member name filter applied, drop any days that now have zero miqaat rows
    if ($memberFilter !== '') {
      $data['miqaats'] = array_values(array_filter($data['miqaats'], function ($d) {
        return !empty($d['miqaats']);
      }));
    }
    // If filtering by miqaat type, show only rows (days) that contain at least one miqaat of that type
    if (!empty($miqaat_type)) {
      $data['miqaats'] = array_values(array_filter($data['miqaats'], function ($d) {
        return !empty($d['miqaats']);
      }));
    }

    // Safely derive hijri year for heading if at least one row remains
    if (!empty($data['miqaats'])) {
      $first_day_of_calendar = $data["miqaats"][0]["date"];
      $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($first_day_of_calendar)));
      $data['hijri_year'] = explode('-', $hijri_date['hijri_date'])[2];
    } else {
      // Fallback: current hijri year (so heading is still meaningful)
      $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
      $data['hijri_year'] = isset($today_hijri['hijri_date']) ? explode('-', $today_hijri['hijri_date'])[2] : '';
    }

    // Compute Hijri financial-year summary counts for cards: Total Miqaat Days, Total Thaali Days, Sundays + Utlat
    $summary_miqaat_days = 0;
    $summary_total_thaali_days = 0;
    $summary_sundays = 0;
    $sunday_no_miqaat_dates = [];
    $no_miqaat_no_thaali_dates = [];
    $year_for_summary = isset($data['hijri_year']) ? (int) $data['hijri_year'] : (int) $this_hijri_year;
    if ($year_for_summary) {
      // Financial year: Hijri months 09–12 of current year + 01–08 of next year
      $months_current = [9, 10, 11, 12];
      $months_next = [1, 2, 3, 4, 5, 6, 7, 8];
      $years_and_months = [];
      // Determine selection context: current/last year shortcuts vs explicit month
      if ($hijri_month_id == -1) { // Current Year financial range
        foreach ($months_current as $m) {
          $years_and_months[] = [$year_for_summary, $m];
        }
        foreach ($months_next as $m) {
          $years_and_months[] = [$year_for_summary + 1, $m];
        }
      } elseif ($hijri_month_id == -3) { // Last Year financial range
        foreach ($months_current as $m) {
          $years_and_months[] = [$year_for_summary - 1, $m];
        }
        foreach ($months_next as $m) {
          $years_and_months[] = [$year_for_summary, $m];
        }
      } else {
        // Default: include full hijri-year 01–12 when explicit month/year context
        for ($hm = 1; $hm <= 12; $hm++) {
          $years_and_months[] = [$year_for_summary, $hm];
        }
      }

      foreach ($years_and_months as $ym) {
        list($yr, $hm) = $ym;
        $monthData = $this->AccountM->get_hijri_month_menu($yr, $hm);
        if (empty($monthData))
          continue;
        foreach ($monthData as $day) {
          if (!empty($day['miqaats'])) {
            $summary_miqaat_days++;
          }
          if (!empty($day['menu']) && !empty($day['menu']['items'])) {
            $summary_total_thaali_days++;
          }
          // Sundays + Utlat = union(Sundays without miqaat, days with no miqaat and no thaali)
          $dayName = '';
          if (!empty($day['date'])) {
            $dayName = date('l', strtotime($day['date']));
          }
          $hasMiqaat = (isset($day['miqaats']) && is_array($day['miqaats']) && count($day['miqaats']) > 0);
          $hasThaali = (!empty($day['menu']) && !empty($day['menu']['items']));
          if ($dayName === 'Sunday' && !$hasMiqaat) {
            $summary_sundays++;
            if (!empty($day['date']))
              $sunday_no_miqaat_dates[] = $day['date'];
          } elseif (!$hasMiqaat && !$hasThaali) {
            // Any day (including non-Sundays) with neither miqaat nor thaali
            $summary_sundays++;
            if (!empty($day['date']))
              $no_miqaat_no_thaali_dates[] = $day['date'];
          }
        }
      }
    }
    $data['summary_miqaat_days'] = $summary_miqaat_days;
    $data['summary_total_thaali_days'] = $summary_total_thaali_days;
    $data['summary_sundays'] = $summary_sundays;
    // Optional debug payload to inspect which dates contributed to Sundays + Utlat
    if ($this->input->get('debug_sundays') === '1') {
      $data['debug_sundays_union'] = [
        'sundays_without_miqaat' => $sunday_no_miqaat_dates,
        'no_miqaat_no_thaali' => $no_miqaat_no_thaali_dates,
        'total_count' => (int) $summary_sundays
      ];
    }

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/ManageMiqaat', $data);
  }

  public function createmiqaat()
  {
    $this->validateUser($_SESSION["user"]);

    $data["active_controller"] = $_SESSION["from"];
    $data['user_name'] = $_SESSION['user']['username'];

    $date = $this->input->get('date');
    if ($date) {
      $data['date'] = $date ? date('Y-m-d', strtotime($date)) : '';
    } else {
      $data['date'] = date('Y-m-d');
    }
    $hijri_date = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($data['date'])))['hijri_date']);
    $hijri_month_name = $this->HijriCalendar->hijri_month_name($hijri_date[1]);

    $data['hijri_date'] = $hijri_date[0] . " " . $hijri_month_name['hijri_month'] . " " . $hijri_date[2];
    $data['edit_mode'] = false;

    $data['miqaats'] = $this->CommonM->get_miqaats_with_assignments();

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMiqaat', $data);
  }

  // AJAX endpoint to fetch hijri date for a given gregorian date
  public function get_hijri_date_ajax()
  {
    $greg_date = $this->input->post('greg_date');
    $hijri_date = '';
    if (!empty($greg_date)) {
      $result = $this->CommonM->get_hijri_date_by_greg_date($greg_date);
      if ($result && isset($result['hijri_date'])) {
        $hijri_date_with_month_name = explode("-", $result['hijri_date']);
        $hijri_date = $hijri_date_with_month_name[0] . " " . $result["hijri_month_name"] . " " . $hijri_date_with_month_name[2];
      }
    }
    echo json_encode(['status' => "success", 'hijri_date' => $hijri_date]);
    exit;
  }

  // AJAX: check if a miqaat exists for a given Gregorian date (expects Y-m-d)
  public function check_miqaat_for_date()
  {
    header('Content-Type: application/json');
    $date = $this->input->post('date');
    $resp = ['has_miqaat' => false];
    if (!empty($date)) {
      // If date is in d-m-Y, normalize to Y-m-d
      if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
        $parts = explode('-', $date);
        $date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
      }
      $row = $this->CommonM->get_miqaat_by_date($date);
      if ($row) {
        $resp['has_miqaat'] = true;
        $resp['miqaat'] = [
          'id' => $row['id'] ?? null,
          'name' => $row['name'] ?? '',
          'type' => $row['type'] ?? '',
          'date' => $row['date'] ?? $date,
        ];
      }
    }
    echo json_encode($resp);
    exit;
  }

  // --- NEW HIJRI SELECTOR ENDPOINTS ---
  public function get_hijri_years()
  {
    $years = $this->HijriCalendar->get_distinct_hijri_years();
    echo json_encode(['status' => 'success', 'years' => $years]);
    exit;
  }

  public function get_hijri_months()
  {
    $year = $this->input->get_post('year');
    if (!$year) {
      echo json_encode(['status' => 'error', 'message' => 'Missing year']);
      exit;
    }
    $months = $this->HijriCalendar->get_hijri_months_for_year($year);
    echo json_encode(['status' => 'success', 'months' => $months]);
    exit;
  }

  public function get_hijri_days()
  {
    $year = $this->input->get_post('year');
    $month = $this->input->get_post('month');
    if (!$year || !$month) {
      echo json_encode(['status' => 'error', 'message' => 'Missing year or month']);
      exit;
    }
    $days = $this->HijriCalendar->get_hijri_days_for_month_year($month, $year);
    echo json_encode(['status' => 'success', 'days' => $days]);
    exit;
  }

  // Returns hijri parts (day, month, year, month_name) for a given greg_date (Y-m-d)
  public function get_hijri_parts()
  {
    $greg = $this->input->get_post('greg_date');
    if (!$greg) {
      echo json_encode(['status' => 'error', 'message' => 'Missing greg_date']);
      return;
    }
    $parts = $this->HijriCalendar->get_hijri_parts_by_greg_date($greg);
    if (!$parts) {
      echo json_encode(['status' => 'error', 'message' => 'Not found']);
      return;
    }
    echo json_encode(['status' => 'success', 'parts' => $parts]);
  }

  public function edit_miqaat()
  {
    $this->validateUser($_SESSION["user"]);

    $from = $this->input->get('from') ?? $_SESSION["from"];
    if ($from) {
      $data["from"] = $from;
    }

    $data["active_controller"] = $_SESSION["from"];
    $data['user_name'] = $_SESSION['user']['username'];

    $miqaat_id = $this->input->get('id');
    $data['miqaat_id'] = $miqaat_id;

    $miqaat = null;
    if ($miqaat_id) {
      $miqaat = $this->CommonM->get_miqaat_by_id($miqaat_id);
      $data['date'] = isset($miqaat['date']) ? $miqaat['date'] : '';
      // Prepare assignment data for pre-filling
      $individual_ids = [];
      $group_leader_id = '';
      $group_member_ids = [];
      $group_name = '';
      if (isset($miqaat['assignments'])) {
        foreach ($miqaat['assignments'] as $assignment) {
          if ($assignment['assign_type'] === 'Individual') {
            $individual_ids[] = $assignment['member_id'];
          } elseif ($assignment['assign_type'] === 'Group') {
            $group_leader_id = $assignment['group_leader_id'];
            $group_name = $assignment['group_name'];
            if (!empty($assignment['members'])) {
              foreach ($assignment['members'] as $member) {
                $group_member_ids[] = $member['id'];
              }
            }
          }
        }
      }
      $data['individual_ids'] = implode(',', $individual_ids);
      $data['group_leader_id'] = $group_leader_id;
      $data['group_member_ids'] = implode(',', $group_member_ids);
      $data['group_name'] = $group_name;
    } else {
      $data['date'] = '';
    }
    $data['miqaat'] = $miqaat;
    $data['edit_mode'] = true;

    $data['miqaats'] = $this->CommonM->get_miqaats_with_assignments();

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMiqaat', $data);
  }

  public function search_users()
  {
    $term = $this->input->get('term');

    $this->db->select('ITS_ID, Full_Name');
    $this->db->from('user');
    $this->db->where("Inactive_Status IS NULL AND Sector IS NOT NULL");
    if (!empty($term)) {
      $this->db->like('Full_Name', $term);
    }
    $this->db->order_by('Full_Name', 'ASC');

    $query = $this->db->get()->result_array();

    $results = [];
    foreach ($query as $row) {
      $results[] = [
        'id' => $row['ITS_ID'],
        'name' => $row['Full_Name']
      ];
    }

    echo json_encode($results);
  }

  public function add_miqaat()
  {
    if ($this->input->post()) {
      $name = $this->input->post('name');
      $miqaat_type = $this->input->post('miqaat_type');
      $date = $this->input->post('date');

      $hijri_date = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($date)))["hijri_date"]);

      $hijri_day = $hijri_date[0];
      $hijri_month = $hijri_date[1];

      if ($hijri_month == 1 && ($hijri_day >= 1 && $hijri_day <= 10) && $miqaat_type != "Ashara") {
        $this->session->set_flashdata('error', 'Only Ashara Miqaat can be created between 1st to 10th Muharram.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($miqaat_type == "Ashara" && !($hijri_month == 1 && ($hijri_day >= 1 && $hijri_day <= 10))) {
        $this->session->set_flashdata('error', 'Ashara Miqaat can only be created between 1st to 10th Muharram.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($hijri_month == 9 && ($hijri_day >= 1 && $hijri_day <= 30) && $miqaat_type != "Shehrullah") {
        $this->session->set_flashdata('error', 'Only Shehrullah Miqaat can be created between 1st to 30th Ramadan.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($miqaat_type == "Shehrullah" && !($hijri_month == 9 && ($hijri_day >= 1 && $hijri_day <= 30))) {
        $this->session->set_flashdata('error', 'Shehrullah Miqaat can only be created between 1st to 30th Ramadan.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($hijri_month == 10 && $hijri_day == 1 && $miqaat_type != "Shehrullah") {
        $this->session->set_flashdata('error', 'Only Shehrullah Miqaat can be created on this date.');
        redirect('common/createmiqaat?date=' . $date);
      }

      $assign_type = $this->input->post('assign_to');

      $hijri_year = $hijri_date[2];

      $new_miqaat_id = $this->CommonM->insert_miqaat([
        'name' => $name,
        "type" => $miqaat_type,
        "assigned_to" => $assign_type,
        'date' => date("Y-m-d", strtotime($date)),
        'status' => $assign_type == "Fala ni Niyaz" ? 1 : 0,
      ]);

      $miqaat_id = $this->CommonM->generate_miqaat_id($hijri_year);
      $this->CommonM->update_miqaat_by_id($new_miqaat_id, ['miqaat_id' => $miqaat_id]);

      $hijri_year = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"])[2];

      if ($assign_type == 'Individual') {
        $ids = explode(",", $this->input->post('individual_ids'));
        foreach ($ids as $id) {
          $this->CommonM->insert_assignment([
            'miqaat_id' => $new_miqaat_id,
            'assign_type' => 'individual',
            'member_id' => $id
          ]);

          $this->CommonM->delete_fala_ni_niyaz_by_user_id($id, $miqaat_type, $hijri_year);
          $this->load->model('EmailQueueM');
          $this->load->model('AccountM');
          $member = $this->AccountM->getUserData($id);
          $miqaatRow = $this->CommonM->get_miqaat_by_id($new_miqaat_id);
          $miqaatName = isset($miqaatRow['name']) ? $miqaatRow['name'] : '';
          $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
          if (!empty($member) && !empty($member['Email'])) {
            $subject = 'Miqaat Assignment: ' . $miqaatName;
            $body = '<p>Baad Afzalus Salaam,</p>';
            $body .= htmlspecialchars($member['Full_Name'] ?? $id);
            $body .= '<p>You have been assigned to the miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on <strong>' . htmlspecialchars($miqaatDate) . '</strong>.</p>';
            $body .= '<p>Regards,<br/>Anjuman E Saifee Dawoodi Bohra Jamaat Khar</p>';
            $this->EmailQueueM->enqueue($member['Email'], $subject, $body, null, 'html');
          }
          // Admin notification
          $admins = [
            'anjuman@kharjamaat.in',
            'amilsaheb@kharjamaat.in',
            '3042@carmelnmh.in',
            'kharjamaat@gmail.com',
            'kharamilsaheb@gmail.com',
            'kharjamaat786@gmail.com',
            'khozemtopiwalla@gmail.com',
            'ybookwala@gmail.com'
          ];
          foreach ($admins as $a) {
            $asub = 'Miqaat Assigned: ' . $miqaatName;
            $abody = '<p>Member <strong>' . htmlspecialchars($member['Full_Name'] ?? $id) . ' (' . htmlspecialchars($id) . ')</strong> has been assigned to miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on ' . htmlspecialchars($miqaatDate) . '.</p>';
            $this->EmailQueueM->enqueue($a, $asub, $abody, null, 'html');
          }
        }
      } elseif ($assign_type == 'Group') {
        $group_name = $this->input->post('group_name');
        $leader_id = $this->input->post('group_leader_id');
        $members = explode(",", $this->input->post('group_member_ids'));

        foreach ($members as $member_id) {
          $this->CommonM->insert_assignment([
            'miqaat_id' => $new_miqaat_id,
            'assign_type' => 'group',
            'group_name' => $group_name,
            'group_leader_id' => $leader_id,
            'member_id' => $member_id
          ]);
        }
        // Notify group leader and admins about group assignment
        $this->load->model('EmailQueueM');
        $this->load->model('AccountM');
        $leader = $this->AccountM->getUserData($leader_id);
        $miqaatRow = $this->CommonM->get_miqaat_by_id($new_miqaat_id);
        $miqaatName = isset($miqaatRow['name']) ? $miqaatRow['name'] : '';
        $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
        if (!empty($leader) && !empty($leader['Email'])) {
          $subject = 'You have been appointed group leader for miqaat: ' . $miqaatName;
          $body = '<p>Baad Afzalus Salaam,</p>';
          $body .= '<p>You have been appointed as the group leader (<strong>' . htmlspecialchars($group_name) . '</strong>) for the miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on <strong>' . htmlspecialchars($miqaatDate) . '</strong>.</p>';
          $body .= '<p>Regards,<br/>Anjuman E Saifee Dawoodi Bohra Jamaat Khar</p>';
          $this->EmailQueueM->enqueue($leader['Email'], $subject, $body, null, 'html');
        }
        // Admin notification
        $admins = [
          'anjuman@kharjamaat.in',
          'amilsaheb@kharjamaat.in',
          '3042@carmelnmh.in',
          'kharjamaat@gmail.com',
          'kharamilsaheb@gmail.com',
          'kharjamaat786@gmail.com',
          'khozemtopiwalla@gmail.com',
          'ybookwala@gmail.com'
        ];
        foreach ($admins as $a) {
          $asub = 'Group Assigned for Miqaat: ' . $miqaatName;
          $abody = '<p>Group <strong>' . htmlspecialchars($group_name) . '</strong> led by <strong>' . htmlspecialchars($leader['Full_Name'] ?? $leader_id) . ' (' . htmlspecialchars($leader_id) . ')</strong> has been assigned to miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on ' . htmlspecialchars($miqaatDate) . '.</p>';
          $this->EmailQueueM->enqueue($a, $asub, $abody, null, 'html');
        }
      } elseif ($assign_type == 'Fala ni Niyaz') {
        $fmb_users = $this->CommonM->get_umoor_fmb_users();
        foreach ($fmb_users as $user) {
          $assignment_status = $this->CommonM->insert_assignment([
            'miqaat_id' => $new_miqaat_id,
            'assign_type' => 'Fala ni Niyaz',
            'member_id' => $user->user_id
          ]);

          if ($assignment_status) {
            $result = $this->CommonM->insert_raza([
              'user_id' => $user->user_id,
              'razaType' => 2,
              'razaData' => '{"miqaat_id": "' . $new_miqaat_id . '"}',
              'miqaat_id' => $new_miqaat_id,
              'status' => 2,
              'Janab-status' => 1,
              'coordinator-status' => 1,
              'active' => 1,
            ]);

            $hijri_year = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"])[2];

            $raza_id = $this->AccountM->generate_raza_id($hijri_year);
            $this->AccountM->update_raza_by_id($result, array("raza_id" => $raza_id));
          }
          break;
        }
      }

      $this->session->set_flashdata('success', 'Miqaat created successfully!');
      redirect('common/managemiqaat');
    } else {
      $this->load->view('common/managemiqaat');
    }
  }

  public function update_miqaat()
  {
    $this->validateUser($_SESSION["user"]);
    if ($this->input->post()) {
      $miqaat_id = $this->input->post('miqaat_id');
      $name = $this->input->post('name');
      $miqaat_type = $this->input->post('miqaat_type');
      $date = $this->input->post('date');
      $status = $this->input->post('status');
      $miqaat_data = [
        'name' => $name,
        'type' => $miqaat_type,
        'date' => date("Y-m-d", strtotime($date)),
      ];

      // If miqaat is currently active, deactivate it on update
      $this->load->model('CommonM');
      $current = $this->CommonM->get_miqaat_by_id($miqaat_id);
      if (!empty($current) && isset($current['status']) && (int)$current['status'] === 1) {
        $this->CommonM->set_miqaat_status($miqaat_id, 0); // deactivate before applying updates
      }

      $hijri_date = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($date)))["hijri_date"]);

      $hijri_year = $hijri_date[2];

      $hijri_day = $hijri_date[0];
      $hijri_month = $hijri_date[1];

      if ($hijri_month == 1 && ($hijri_day >= 1 && $hijri_day <= 10) && $miqaat_type != "Ashara") {
        $this->session->set_flashdata('error', 'Only Ashara Miqaat can be created between 1st to 10th Muharram.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($miqaat_type == "Ashara" && !($hijri_month == 1 && ($hijri_day >= 1 && $hijri_day <= 10))) {
        $this->session->set_flashdata('error', 'Ashara Miqaat can only be created between 1st to 10th Muharram.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($hijri_month == 9 && ($hijri_day >= 1 && $hijri_day <= 30) && $miqaat_type != "Shehrullah") {
        $this->session->set_flashdata('error', 'Only Shehrullah Miqaat can be created between 1st to 30th Ramadan.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($miqaat_type == "Shehrullah" && !($hijri_month == 9 && ($hijri_day >= 1 && $hijri_day <= 30))) {
        $this->session->set_flashdata('error', 'Shehrullah Miqaat can only be created between 1st to 30th Ramadan.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($hijri_month == 10 && $hijri_day == 1 && $miqaat_type != "Shehrullah") {
        $this->session->set_flashdata('error', 'Only Shehrullah Miqaat can be created on this date.');
        redirect('common/createmiqaat?date=' . $date);
      }

      if ($this->input->post('assign_to')) {
        $assign_type = $this->input->post('assign_to');
        $miqaat_data['assigned_to'] = $assign_type;

        $this->CommonM->update_miqaat_by_id($miqaat_id, $miqaat_data);
        $this->CommonM->remove_assignments_from_miqaat($miqaat_id);
        if ($assign_type == 'Individual') {
          $miqaat_data['status'] = 0;
          $ids = explode(",", $this->input->post('individual_ids'));

          $this->CommonM->delete_raza_by_miqaat_id($miqaat_id, $ids);

          foreach ($ids as $id) {
            $this->CommonM->insert_assignment([
              'miqaat_id' => $miqaat_id,
              'assign_type' => 'Individual',
              'member_id' => $id
            ]);

            $this->CommonM->delete_fala_ni_niyaz_by_user_id($id, $miqaat_type, $hijri_year);
            // Notify individual and admins about assignment (update)
            $this->load->model('EmailQueueM');
            $this->load->model('AccountM');
            $member = $this->AccountM->getUserData($id);
            $miqaatRow = $this->CommonM->get_miqaat_by_id($miqaat_id);
            $miqaatName = isset($miqaatRow['name']) ? $miqaatRow['name'] : '';
            $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
            if (!empty($member) && !empty($member['Email'])) {
              $subject = 'Miqaat Assignment: ' . $miqaatName;
              $body = '<p>Baad Afzalus Salaam,</p>';
              $body .= '<p>You have been assigned to the miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on <strong>' . htmlspecialchars($miqaatDate) . '</strong>.</p>';
              $body .= '<p>Regards,<br/>Anjuman E Saifee Dawoodi Bohra Jamaat Khar</p>';
              $this->EmailQueueM->enqueue($member['Email'], $subject, $body, null, 'html');
            }
            $admins = [
              'anjuman@kharjamaat.in',
              'amilsaheb@kharjamaat.in',
              '3042@carmelnmh.in',
              'kharjamaat@gmail.com',
              'kharamilsaheb@gmail.com',
              'kharjamaat786@gmail.com',
              'khozemtopiwalla@gmail.com',
              'ybookwala@gmail.com'
            ];
            foreach ($admins as $a) {
              $asub = 'Miqaat Assigned: ' . $miqaatName;
              $abody = '<p>Member <strong>' . htmlspecialchars($member['Full_Name'] ?? $id) . ' (' . htmlspecialchars($id) . ')</strong> has been assigned to miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on ' . htmlspecialchars($miqaatDate) . '.</p>';
              $this->EmailQueueM->enqueue($a, $asub, $abody, null, 'html');
            }
          }
        } elseif ($assign_type == 'Group') {
          $miqaat_data['status'] = 0;
          $group_name = $this->input->post('group_name');
          $leader_id = $this->input->post('group_leader_id');
          $members = explode(",", $this->input->post('group_member_ids'));

          $this->CommonM->delete_raza_by_miqaat_id($miqaat_id, [$leader_id]);

          foreach ($members as $member_id) {
            $this->CommonM->insert_assignment([
              'miqaat_id' => $miqaat_id,
              'assign_type' => 'Group',
              'group_name' => $group_name,
              'group_leader_id' => $leader_id,
              'member_id' => $member_id
            ]);
          }
          // Notify group leader and admins about group assignment (update)
          $this->load->model('EmailQueueM');
          $this->load->model('AccountM');
          $leader = $this->AccountM->getUserData($leader_id);
          $miqaatRow = $this->CommonM->get_miqaat_by_id($miqaat_id);
          $miqaatName = isset($miqaatRow['name']) ? $miqaatRow['name'] : '';
          $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
          if (!empty($leader) && !empty($leader['Email'])) {
            $subject = 'You have been appointed group leader for miqaat: ' . $miqaatName;
            $body = '<p>Baad Afzalus Salaam,</p>';
            $body .= '<p>You have been appointed as the group leader (<strong>' . htmlspecialchars($group_name) . '</strong>) for the miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on <strong>' . htmlspecialchars($miqaatDate) . '</strong>.</p>';
            $body .= '<p>Regards,<br/>Anjuman E Saifee Dawoodi Bohra Jamaat Khar</p>';
            $this->EmailQueueM->enqueue($leader['Email'], $subject, $body, null, 'html');
          }
          $admins = [
            'anjuman@kharjamaat.in',
            'amilsaheb@kharjamaat.in',
            '3042@carmelnmh.in',
            'kharjamaat@gmail.com',
            'kharamilsaheb@gmail.com',
            'kharjamaat786@gmail.com',
            'khozemtopiwalla@gmail.com',
            'ybookwala@gmail.com'
          ];
          foreach ($admins as $a) {
            $asub = 'Group Assigned for Miqaat: ' . $miqaatName;
            $abody = '<p>Group <strong>' . htmlspecialchars($group_name) . '</strong> led by <strong>' . htmlspecialchars($leader['Full_Name'] ?? $leader_id) . ' (' . htmlspecialchars($leader_id) . ')</strong> has been assigned to miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> on ' . htmlspecialchars($miqaatDate) . '.</p>';
            $this->EmailQueueM->enqueue($a, $asub, $abody, null, 'html');
          }
        } elseif ($assign_type == 'Fala ni Niyaz') {
          $miqaat_data['status'] = 1;
          $fmb_users = $this->CommonM->get_umoor_fmb_users();
          foreach ($fmb_users as $user) {
            $assignment_status = $this->CommonM->insert_assignment([
              'miqaat_id' => $miqaat_id,
              'assign_type' => 'Fala ni Niyaz',
              'member_id' => $user->user_id
            ]);

            if ($assignment_status) {
              $result = $this->CommonM->delete_raza_by_miqaat_and_user($miqaat_id);
              if ($result) {
                $this->CommonM->insert_raza([
                  'user_id' => $user->user_id,
                  'razaType' => 2,
                  'razaData' => '{"miqaat_id": "' . $miqaat_id . '"}',
                  'miqaat_id' => $miqaat_id,
                  'status' => 2,
                  'Janab-status' => 1,
                  'coordinator-status' => 1,
                  'active' => 1,
                ]);
              }
            }
            break;
          }
        }
      } else {
        // Only update miqaat details, leave assignments untouched
        $this->CommonM->update_miqaat_by_id($miqaat_id, $miqaat_data);
      }
      $this->session->set_flashdata('success', 'Miqaat updated successfully!');
      redirect('common/managemiqaat');
    } else {
      $this->session->set_flashdata('error', 'No data received for update.');
      redirect('common/managemiqaat');
    }
  }

  public function delete_miqaat()
  {
    $this->validateUser($_SESSION["user"]);
    if ($this->input->post('miqaat_id')) {
      $miqaat_id = $this->input->post('miqaat_id');
      $result = $this->CommonM->delete_miqaat($miqaat_id);
      if ($result) {
        $this->session->set_flashdata('success', 'Miqaat deleted successfully!');
      } else {
        $this->session->set_flashdata('error', 'Cannot delete Miqaat with approved Raza. It can only be cancelled.');
      }
    } else {
      $this->session->set_flashdata('error', 'No Miqaat ID provided.');
    }
    redirect('common/managemiqaat');
  }
  // Create Miqaat

  public function cancel_miqaat()
  {
    $miqaat_id = $this->input->post('miqaat_id');
    if ($miqaat_id) {
      $this->load->model('CommonM');
      $this->CommonM->set_miqaat_status($miqaat_id, 2); // Set miqaat inactive
      $this->CommonM->set_raza_status_by_miqaat($miqaat_id, 0); // Set all related razas inactive
      $this->session->set_flashdata('success', 'Miqaat and related razas cancelled.');
    } else {
      $this->session->set_flashdata('error', 'Invalid Miqaat.');
    }
    $from = $_SESSION["from"] ?? '';
    redirect('common/managemiqaat?from=' . $from);
  }

  public function activate_miqaat()
  {
    $miqaat_id = $this->input->post('miqaat_id');
    if ($miqaat_id) {
      $this->load->model('CommonM');
      $this->CommonM->set_miqaat_status($miqaat_id, 1); // Set miqaat active
      // Notify admins and assigned members/group leaders about activation
      $this->load->model('EmailQueueM');
      $this->load->model('AccountM');
      $miqaat = $this->CommonM->get_miqaat_by_id($miqaat_id);
      $miqaatName = isset($miqaat['name']) ? $miqaat['name'] : '';
      $miqaatDate = isset($miqaat['date']) ? date('d-m-Y', strtotime($miqaat['date'])) : '';

      // Admin recipients
      $admins = [
        'anjuman@kharjamaat.in',
        'amilsaheb@kharjamaat.in',
        '3042@carmelnmh.in',
        'kharjamaat@gmail.com',
        'kharamilsaheb@gmail.com',
        'kharjamaat786@gmail.com',
        'khozemtopiwalla@gmail.com',
        'ybookwala@gmail.com'
      ];
      $adminSub = 'Miqaat Activated: ' . $miqaatName;
      $adminBody = '<p>The miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> scheduled on <strong>' . htmlspecialchars($miqaatDate) . '</strong> has been activated.</p>';
      foreach ($admins as $a) {
        $this->EmailQueueM->enqueue($a, $adminSub, $adminBody, null, 'html');
      }

      // Notify assigned members and group leaders
      if (!empty($miqaat['assignments']) && is_array($miqaat['assignments'])) {
        $assigned_link = base_url('accounts/assigned_miqaats');
        foreach ($miqaat['assignments'] as $ass) {
          if (isset($ass['assign_type']) && $ass['assign_type'] === 'Individual') {
            $memberId = $ass['member_id'];
            $member = $this->AccountM->getUserData($memberId);
            if (!empty($member) && !empty($member['Email'])) {
              $sub = 'Miqaat Activated: submit your Raza for ' . $miqaatName;
              $body = '<p>Baad Afzalus Salaam,</p>';
              $body .= '<p>The miqaat <strong>' . htmlspecialchars($miqaatName) . '</strong> scheduled on <strong>' . htmlspecialchars($miqaatDate) . '</strong> which you were assigned to has now been <strong>activated</strong>.</p>';
              $body .= '<p>You can now submit a Raza for this miqaat. To view your assigned miqaats and submit a Raza, please visit: <a href="' . $assigned_link . '">Assigned Miqaats</a>.</p>';
              $body .= '<p>Click the link above to go to your assigned miqaat and submit the Raza.</p>';
              $body .= '<p>Wasalaam,<br/>Anjuman E Saifee Dawoodi Bohra Jamaat Khar</p>';
              $this->EmailQueueM->enqueue($member['Email'], $sub, $body, null, 'html');
            }
          } elseif (isset($ass['assign_type']) && $ass['assign_type'] === 'Group') {
            $leaderId = $ass['group_leader_id'];
            $leader = $this->AccountM->getUserData($leaderId);
            if (!empty($leader) && !empty($leader['Email'])) {
              $sub = 'Miqaat: ' . $miqaatName . ' activated - Submit Raza for your group';
              $body = '<p>Baad Afzalus Salaam,</p>';
              $body .= '<p>The ' . htmlspecialchars($miqaatName) . ' miqaat on ' . htmlspecialchars($miqaatDate) . ' which your group ' . htmlspecialchars($ass['group_name']) . ' is assigned to has now been <strong>activated</strong>. You can now submit a Raza for this miqaat.</p>';
              $body .= '<p>To view your assigned miqaats and submit Raza, please visit: <a href="' . $assigned_link . '">Assigned Miqaats</a>.</p>';
              $body .= '<p>Click the link above to go to the assigned miqaat and submit the Raza.</p>';
              $body .= '<p>Wasalaam,<br/>Anjuman E Saifee Dawoodi Bohra Jamaat Khar</p>';
              $this->EmailQueueM->enqueue($leader['Email'], $sub, $body, null, 'html');
            }
          }
        }
      }

      $this->session->set_flashdata('success', 'Miqaat activated. Notifications queued.');
    } else {
      $this->session->set_flashdata('error', 'Invalid Miqaat.');
    }
    $from = $_SESSION["from"] ?? '';
    redirect('common/managemiqaat?from=' . $from);
  }

  public function miqaatattendance()
  {
    $this->validateUser($_SESSION["user"]);
    $from = $this->input->get('from');
    $data['from'] = $from;
    $_SESSION["from"] = $from;
    $data["active_controller"] = $from ? base_url($from) : base_url();
    $data['user_name'] = $_SESSION['user']['username'];

    // Fetch all miqaat dates and their attendance
    $miqaats = $this->CommonM->get_miqaats();

    // For each miqaat, fetch attendance records
    $attendance_by_miqaat = [];
    foreach ($miqaats as $miqaat) {
      $attendance = $this->CommonM->get_attendance_by_miqaat($miqaat['miqaat_id']);
      $attendance_by_miqaat[$miqaat['miqaat_id']] = $attendance;
    }

    $data['miqaats'] = $miqaats;
    $data['attendance_by_miqaat'] = $attendance_by_miqaat;

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/MiqaatAttendance', $data);
  }

  public function miqaat_attendance_details($miqaat_id = null)
  {
    $this->validateUser($_SESSION["user"]);
    if (!$miqaat_id) {
      $this->session->set_flashdata('error', 'Invalid Miqaat.');
      redirect('common/miqaatattendance');
    }
    $from = $_SESSION["from"];
    $data['from'] = $from;

    $data["active_controller"] = $from;
    $data['user_name'] = $_SESSION['user']['username'];

    // Fetch miqaat details
    $miqaat = $this->CommonM->get_miqaat_by_id($miqaat_id);
    if (!$miqaat) {
      $this->session->set_flashdata('error', 'Miqaat not found.');
      redirect('common/miqaatattendance');
    }
    $data['miqaat'] = $miqaat;

    // Fetch hijri date and month name for this miqaat
    $hijri = null;
    if (!empty($miqaat['date'])) {
      $hijri = $this->CommonM->get_hijri_date_by_greg_date($miqaat['date']);
    } elseif (!empty($miqaat['miqaat_date'])) {
      $hijri = $this->CommonM->get_hijri_date_by_greg_date($miqaat['miqaat_date']);
    }
    $data['hijri_date'] = $hijri;
    // Fetch attendance records for this miqaat
    $attendance = $this->CommonM->get_members_with_attendance($miqaat_id);
    $data['attendance'] = $attendance;

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/MiqaatAttendanceDetails', $data);
  }

  // ===================== Sector/Sub-sector Wise Thaali Signup Breakdown =====================
  public function thaali_signups_breakdown()
  {
    // ================= AUTH =================
    $user = $_SESSION['user'] ?? null;

    if (empty($user) || !in_array((int) ($user['role'] ?? 0), [1, 2, 3, 16], true)) {
      redirect('/accounts');
    }

    // ================= CONTEXT =================
    $from = $this->input->get('from');
    if ($from) {
      $_SESSION['from'] = $from;
    }

    $data['active_controller'] = $_SESSION['from'] ? base_url($_SESSION['from']) : base_url();
    $data['user_name'] = $user['username'];

    // ================= SECTOR (ROLE 16 ONLY) =================
    $sector = null;
    if ((int) $user['role'] === 16) {
      $sector = $user['username'];
    }

    // ================= INPUTS =================
    $date_raw = $this->input->get_post('date');
    $start_raw = $this->input->get_post('start_date');
    $end_raw = $this->input->get_post('end_date');

    // ================= DATE NORMALIZER =================
    $norm = function ($v) {
      if (empty($v))
        return '';
      if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $v)) {
        [$d, $m, $y] = explode('-', $v);
        return "$y-$m-$d";
      }
      return date('Y-m-d', strtotime($v));
    };

    $start_date = $norm($start_raw);
    $end_date = $norm($end_raw);

    // ================= HIJRI PARAMS =================
    $hijri_year_param = $this->input->get('hijri_year');
    $hijri_month_param = $this->input->get('hijri_month');

    if ($hijri_year_param && $hijri_month_param) {
      $days = $this->HijriCalendar
        ->get_hijri_days_for_month_year((int) $hijri_month_param, $hijri_year_param);

      if (!empty($days)) {
        $start_date = $days[0]['greg_date'];
        $end_date = $days[count($days) - 1]['greg_date'];
        $date_raw = '';
      }
    }

    // ================= DEFAULT RANGE =================
    if (empty($start_date) && empty($end_date) && empty($date_raw)) {
      $today = date('Y-m-d');
      $start_date = date('Y-m-d', strtotime('monday this week', strtotime($today)));
      $end_date = date('Y-m-d', strtotime('sunday this week', strtotime($today)));
    }

    // ======================================================
    // ================= RANGE MODE =========================
    // ======================================================
    if ($start_date || $end_date) {

      // ================= SECTOR INCHARGE =================
      if ($sector) {

        $range = $this->CommonM
          ->get_thaali_signup_breakdown_range_by_sector(
            $start_date,
            $end_date,
            $sector
          );

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['date'] = '';
        $data['breakdown'] = $range['breakdown'];
        $data['totals'] = $range['totals'];

        $dayWise = [];
        $cursor = strtotime($start_date);
        $endTs = strtotime($end_date);
        $hijriByDate = [];

        while ($cursor <= $endTs) {
          $d = date('Y-m-d', $cursor);

          $bd = $this->CommonM
            ->get_thaali_signup_breakdown_by_sector($d, $sector);

          $dayWise[] = [
            'date' => $d,
            'breakdown' => $bd['breakdown'] ?? [],
            'totals' => $bd['totals'] ?? []
          ];

          $hp = $this->HijriCalendar->get_hijri_parts_by_greg_date($d);
          if ($hp) {
            $hijriByDate[$d] = $hp;
          }

          $cursor = strtotime('+1 day', $cursor);
        }

        $data['daily_breakdowns'] = $dayWise;
        $data['hijri_by_date'] = $hijriByDate;
      }

      // ================= ADMIN / JAMAAT =================
      else {

        $range = $this->CommonM
          ->get_thaali_signup_breakdown_range($start_date, $end_date);

        $data['start_date'] = $range['start_date'];
        $data['end_date'] = $range['end_date'];
        $data['date'] = '';
        $data['breakdown'] = $range['breakdown'];
        $data['totals'] = $range['totals'];

        $dayWise = [];
        $cursor = strtotime($range['start_date']);
        $endTs = strtotime($range['end_date']);
        $hijriByDate = [];

        while ($cursor <= $endTs) {
          $d = date('Y-m-d', $cursor);

          $bd = $this->CommonM->get_thaali_signup_breakdown($d);

          $dayWise[] = [
            'date' => $d,
            'breakdown' => $bd['breakdown'] ?? [],
            'totals' => $bd['totals'] ?? []
          ];

          $hp = $this->HijriCalendar->get_hijri_parts_by_greg_date($d);
          if ($hp) {
            $hijriByDate[$d] = $hp;
          }

          $cursor = strtotime('+1 day', $cursor);
        }

        $data['daily_breakdowns'] = $dayWise;
        $data['hijri_by_date'] = $hijriByDate;
      }
    }

    // ======================================================
    // ================= SINGLE DATE ========================
    // ======================================================
    else {

      $date = $date_raw ? $norm($date_raw) : date('Y-m-d');

      if ($sector) {
        $bd = $this->CommonM
          ->get_thaali_signup_breakdown_by_sector($date, $sector);
      } else {
        $bd = $this->CommonM
          ->get_thaali_signup_breakdown($date);
      }

      $data['date'] = $date;
      $data['breakdown'] = $bd['breakdown'];
      $data['totals'] = $bd['totals'];

      $hp = $this->HijriCalendar->get_hijri_parts_by_greg_date($date);
      $data['hijri_by_date'] = $hp ? [$date => $hp] : [];
    }

    // ================= VIEWS =================
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/ThaaliSignupsBreakdown', $data);
  }









  public function rsvp_list()
  {
    $this->validateUser($_SESSION["user"]);
    $from = $this->input->get('from');
    $_SESSION["from"] = $from;
    $data['from'] = $from;
    $data["active_controller"] = $from ? base_url($from) : base_url();
    $data['user_name'] = $_SESSION['user']['username'];

    // Fetch all miqaat dates and their RSVP lists
    $miqaats = $this->CommonM->get_miqaats();

    // Sort miqaats by date ascending (earliest first). Do not filter out past miqaats
    if (!empty($miqaats) && is_array($miqaats)) {
      usort($miqaats, function ($a, $b) {
        $da = isset($a['miqaat_date']) ? $a['miqaat_date'] : (isset($a['date']) ? $a['date'] : '');
        $db = isset($b['miqaat_date']) ? $b['miqaat_date'] : (isset($b['date']) ? $b['date'] : '');
        $ta = $da ? strtotime(substr($da, 0, 10)) : 0;
        $tb = $db ? strtotime(substr($db, 0, 10)) : 0;
        return $ta <=> $tb;
      });
      $miqaats = array_values($miqaats);
    }

    // For each miqaat, fetch RSVP records
    $rsvp_by_miqaat = [];
    foreach ($miqaats as $miqaat) {
      $rsvp = $this->CommonM->get_rsvp_by_miqaat($miqaat['miqaat_id']);
      $rsvp_by_miqaat[$miqaat['miqaat_id']] = $rsvp;
    }

    $data['miqaats'] = $miqaats;
    $data['rsvp_by_miqaat'] = $rsvp_by_miqaat;

    $from = $_SESSION["from"];
    $data["from"] = $from;
    // Total members for RSVP list header (only active members with sector/sub_sector)
    $this->load->model('AdminM');
    $data['total_members'] = $this->AdminM->get_active_member_count();
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/RSVPList', $data);
  }

  public function rsvp_details($miqaat_id = null)
  {
    $this->validateUser($_SESSION["user"]);
    if (!$miqaat_id) {
      $this->session->set_flashdata('error', 'Invalid Miqaat.');
      redirect('common/rsvp_list');
    }

    $data["active_controller"] = $_SESSION["from"];
    $data['user_name'] = $_SESSION['user']['username'];

    // Fetch miqaat details
    $miqaat = $this->CommonM->get_miqaat_by_id($miqaat_id);
    if (!$miqaat) {
      $this->session->set_flashdata('error', 'Miqaat not found.');
      redirect('common/rsvp_list');
    }
    $data['miqaat'] = $miqaat;

    // Fetch hijri date and month name for this miqaat
    $hijri = null;
    if (!empty($miqaat['date'])) {
      $hijri = $this->CommonM->get_hijri_date_by_greg_date($miqaat['date']);
    } elseif (!empty($miqaat['miqaat_date'])) {
      $hijri = $this->CommonM->get_hijri_date_by_greg_date($miqaat['miqaat_date']);
    }
    $data['hijri_date'] = $hijri;
    // Fetch RSVP records for this miqaat
    $rsvp = $this->CommonM->get_members_with_rsvp($miqaat_id);
    $data['rsvp'] = $rsvp;

    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/RSVPDetails', $data);
  }

  // Delivery Person Management
  public function deliverydashboard()
  {
    $this->validateUser($_SESSION["user"]);

    $from = $this->input->get('from');
    if ($from) {
      $_SESSION["from"] = $from;
      $data['from'] = $from;
    }
    $data["active_controller"] = $_SESSION["from"] ? base_url($_SESSION["from"]) : base_url();

    $pendingDaysInYear = $this->HijriCalendar->getPendingDaysInYear();
    $hof_count = $this->CommonM->get_hof_count()["hof_count"];

    if (isset($pendingDaysInYear)) {
      $data["pending_days_in_year"] = $pendingDaysInYear;
      foreach ($data["pending_days_in_year"] as $key => $value) {
        $get_signup_data = $this->CommonM->getsignupcount($value["greg_date"]);
        $data["pending_days_in_year"][$key]["signup_count"] = (int) $get_signup_data["hof_signup_count"];
        $data["pending_days_in_year"][$key]["not_signup_count"] = (int) $hof_count - (int) $get_signup_data["hof_signup_count"];
        $data["pending_days_in_year"][$key]["delivery_person_count"] = (int) $get_signup_data["delivery_person_count"];
      }
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view("Common/Header", $data);
    $this->load->view("Common/DeliveryDashboard", $data);
  }

  public function permanentassignment()
  {
    $this->validateUser($_SESSION["user"]);
    $data["active_controller"] = $_SESSION["from"];
    $all_users = $this->CommonM->get_all_users();
    $data['all_users'] = $all_users;
    $all_dp = $this->CommonM->get_all_dp();
    $data['all_dp'] = $all_dp;
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view("Common/Header", $data);
    $this->load->view("Common/PermanentAssignment", $data);
  }

  public function updatedpmapping()
  {
    $this->validateUser($_SESSION["user"]);

    $user_id = $this->input->post("user_id");
    $dp_id = $this->input->post("dp_id");
    $start_date = date("Y-m-d");

    $data = array(
      "user_id" => $user_id,
      "dp_id" => $dp_id,
      "start_date" => $start_date,
    );

    $result = $this->CommonM->updatedpmapping($data);

    if ($result) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false]);
    }
  }

  public function signupforaday($date)
  {
    $this->validateUser($_SESSION["user"]);
    $data["active_controller"] = $_SESSION["from"];

    $data['date'] = $date;
    $thali_taken = $this->input->post("thali_taken");
    $reg_dp_id = $this->input->post("reg_dp_id");
    $sub_dp_id = $this->input->post("sub_dp_id");

    $data["thali_taken"] = $thali_taken;
    $data["reg_dp_id"] = $reg_dp_id;
    $data["sub_dp_id"] = $sub_dp_id;

    $all_dp = $this->CommonM->get_all_dp();
    $data['all_dp'] = $all_dp;

    $signupforaday = $this->CommonM->getsignupforaday($data);
    if ($signupforaday) {
      $data["signupdata"] = $signupforaday;
    }
    $data["filter_data"] = array(
      "thali_taken" => $thali_taken,
      "reg_dp_id" => $reg_dp_id,
      "sub_dp_id" => $sub_dp_id,
    );
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view("Common/Header", $data);
    $this->load->view("Common/SignUpForDay", $data);
  }

  // Thaali-specific report page (duplicate of signupforaday but with Thaali-specific heading/view)
  public function thaali_signup_report($date)
  {
    $this->validateUser($_SESSION["user"]);

    $user = $_SESSION['user'];
    $role = (int) ($user['role'] ?? 0);
    $username = $user['username'] ?? '';

    $data["active_controller"] = $_SESSION["from"];
    $data['date'] = $date;

    // ===== POST FILTERS =====
    $thali_taken = $this->input->post("thali_taken");
    $reg_dp_id = $this->input->post("reg_dp_id");
    $sub_dp_id = $this->input->post("sub_dp_id");
    $sector = $this->input->post('sector');
    $sub_sector = $this->input->post('sub_sector');

    /**
     * =================================================
     * 🔐 SECTOR INCHARGE RESTRICTION (ROLE = 16)
     * =================================================
     * If logged in as sector incharge:
     * - Force sector = username
     * - Ignore any posted sector value
     */
    if ($role === 16) {
      $sector = $username;
    }

    // ===== ASSIGN TO VIEW =====
    $data["thali_taken"] = $thali_taken;
    $data["reg_dp_id"] = $reg_dp_id;
    $data["sub_dp_id"] = $sub_dp_id;
    $data["sector"] = $sector;
    $data["sub_sector"] = $sub_sector;

    // ===== MASTER DATA =====
    $data['all_dp'] = $this->CommonM->get_all_dp();

    /**
     * Admin/Jamaat need all users to filter
     * Sector incharge technically doesn’t need it,
     * but we keep it to avoid breaking the view
     */
    $data['all_users'] = $this->CommonM->get_all_users();

    // ===== FETCH SIGNUPS FOR DAY =====
    $signupforaday = $this->CommonM->getsignupforaday($data);
    if ($signupforaday) {
      $data["signupdata"] = $signupforaday;
    }

    // ===== FILTER STATE FOR VIEW =====
    $data["filter_data"] = array(
      "thali_taken" => $thali_taken,
      "reg_dp_id" => $reg_dp_id,
      "sub_dp_id" => $sub_dp_id,
      "sector" => $sector,
      "sub_sector" => $sub_sector,
    );

    // ===== VIEW META =====
    $data['user_name'] = $username;
    $data["from"] = $_SESSION["from"];

    // ===== LOAD VIEWS =====
    $this->load->view("Common/Header", $data);
    $this->load->view("Common/ThaaliSignupReportForDay", $data);
  }


  public function managedeliveryperson()
  {
    $this->validateUser($_SESSION["user"]);
    $data["active_controller"] = $_SESSION["from"];
    $all_dp = $this->CommonM->get_all_dp();
    $data['all_dp'] = $all_dp;
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view("Common/Header", $data);
    $this->load->view("Common/ManageDeliveryPerson", $data);
  }
  public function adddeliveryperson()
  {
    $this->validateUser($_SESSION["user"]);

    $dp_name = $this->input->post("delivery_person_name");
    $dp_phone = $this->input->post("delivery_person_phone");

    if (isset($dp_name) && !empty($dp_name)) {
      $dp_data = array(
        "name" => $dp_name,
        "phone" => $dp_phone,
      );
      $result = $this->CommonM->adddeliveryperson($dp_data);
    }

    $data['user_name'] = $_SESSION['user']['username'];

    if ($result) {
      redirect('common/success/managedeliveryperson');
    } else {
      redirect("common/error/managedeliveryperson");
    }
  }
  public function updatedeliveryperson()
  {
    $this->validateUser($_SESSION["user"]);

    $dp_id = $this->input->post("dp_id");
    $name = $this->input->post("name");
    $phone = $this->input->post("phone");
    $status = $this->input->post("status");

    $data = array(
      "dp_id" => $dp_id,
      "name" => $name,
      "phone" => $phone,
      "status" => $status,
    );

    $result = $this->CommonM->updatedeliveryperson($data);
    $data['user_name'] = $_SESSION['user']['username'];

    echo json_encode(["success" => true]);
  }
  public function substitutedeliveryperson()
  {
    $this->validateUser($_SESSION["user"]);

    $data["active_controller"] = $_SESSION["from"];
    $substituteDate = $this->input->post("substitute-date");
    $data["substitute_date"] = $substituteDate;
    $all_users = $this->CommonM->get_all_users();
    $data['all_users'] = $all_users;
    $all_dp = $this->CommonM->get_all_dp();
    $data['all_dp'] = $all_dp;
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view("Common/Header", $data);
    $this->load->view("Common/substituteDeliveryPerson", $data);
  }
  public function currentsubstitutions()
  {
    $this->validateUser($_SESSION["user"]);
    $data["active_controller"] = $_SESSION["from"];
    // Safely fetch inputs; avoid passing null to trim (PHP 8.1+ deprecation)
    $member_name_raw = $this->input->get_post('member_name');
    $dp_name_raw = $this->input->get_post('dp_name');
    $filters = [
      'member_name' => ($member_name_raw !== null) ? trim($member_name_raw) : '',
      'dp_name' => ($dp_name_raw !== null) ? trim($dp_name_raw) : '',
      'start_date' => $this->input->get_post('start_date') ?: '',
      'end_date' => $this->input->get_post('end_date') ?: '',
      'show_all' => $this->input->get_post('show_all') ? true : false,
      'active_on' => $this->input->get_post('active_on') ?: '',
    ];
    $data['filter_values'] = $filters;
    $current_substitutions = $this->CommonM->get_current_substitutions($filters);
    $data['current_substitutions'] = $current_substitutions;
    $data['all_dp'] = $this->CommonM->get_all_dp();
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $_SESSION["from"];
    $data["from"] = $from;
    $this->load->view("Common/Header", $data);
    $this->load->view("Common/CurrentSubstitutions", $data);
  }

  // Lightweight JSON endpoint to fetch substitutions that are active on a single date
  public function substitutions_by_date()
  {
    $this->validateUser($_SESSION['user']);
    $date = $this->input->get_post('date');
    $clean_date = '';
    if ($date) {
      $ts = strtotime($date);
      if ($ts)
        $clean_date = date('Y-m-d', $ts);
    }
    if (empty($clean_date)) {
      echo json_encode(['success' => false, 'message' => 'Invalid date']);
      return;
    }
    $rows = $this->CommonM->get_current_substitutions(['active_on' => $clean_date, 'show_all' => true]);
    echo json_encode(['success' => true, 'date' => $clean_date, 'rows' => $rows]);
  }

  public function update_delivery_override()
  {
    $this->validateUser($_SESSION['user']);
    $id = (int) $this->input->post('id');
    $dp_id = (int) $this->input->post('dp_id');
    $success = false;
    $message = '';
    if ($id && $dp_id) {
      $success = $this->CommonM->update_delivery_override($id, $dp_id);
      $message = $success ? 'Updated successfully' : 'Update failed';
    } else {
      $message = 'Invalid parameters';
    }
    echo json_encode(['success' => $success, 'message' => $message]);
  }

  public function delete_delivery_override()
  {
    $this->validateUser($_SESSION['user']);
    $id = (int) $this->input->post('id');
    $success = false;
    $message = '';
    if ($id) {
      $success = $this->CommonM->delete_delivery_override($id);
      $message = $success ? 'Deleted' : 'Delete failed';
    } else {
      $message = 'Invalid id';
    }
    echo json_encode(['success' => $success, 'message' => $message]);
  }

  public function assigndeliveryperson()
  {
    $this->validateUser($_SESSION["user"]);
    $start_date = $this->input->post("start_date");
    $end_date = $this->input->post("end_date");
    $sub_data = $this->input->post("sub-data");

    $data["start_date"] = $start_date;
    $data["end_date"] = $end_date;
    if ($sub_data) {
      foreach ($sub_data as $key => $value) {
        if ($value) {
          $data["user_id"] = explode("|", $value)[0];
          $data["dp_id"] = explode("|", $value)[1];
          $result = $this->CommonM->substitutedeliveryperson($data);
        }
      }
    }

    redirect("Common/DeliveryDashboard");
  }
  // Delivery Person Management

  // Reports

  // Sabeel Takhmeen

  public function sabeeltakhmeen()
  {
    $this->validateUser($_SESSION["user"]);
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $this->input->get('from');
    if ($from) {
      $data['from'] = $from;
      $_SESSION["from"] = $from;
    }
    $data["active_controller"] = $_SESSION["from"];

    // Determine selected Hijri financial year (start year), defaulting to current financial cycle
    $year_param = $this->input->get_post('hijri_year');
    $year_start = null;
    if (!empty($year_param)) {
      // If param is a range like 1446-47, extract the start year; if numeric, use as-is
      if (preg_match('/^(\d{4})-\d{2}$/', (string) $year_param, $m)) {
        $year_start = (int) $m[1];
      } elseif (is_numeric($year_param)) {
        $year_start = (int) $year_param;
      }
    }
    if ($year_start === null) {
      // Compute from today's Hijri date according to financial boundary (09–12 then 01–08)
      $today = date('Y-m-d');
      $hijri_today = $this->HijriCalendar->get_hijri_date($today);
      if ($hijri_today && isset($hijri_today['hijri_date'])) {
        $parts = explode('-', $hijri_today['hijri_date']);
        $hmon = isset($parts[1]) ? $parts[1] : '';
        $hyr = isset($parts[2]) ? (int) $parts[2] : 0;
        if ($hmon >= '01' && $hmon <= '08') {
          $year_start = $hyr - 1;
        } else {
          $year_start = $hyr;
        }
      }
    }
    // Provide range label for view like 1446-47
    if (!empty($year_start)) {
      $data['selected_hijri_year'] = sprintf('%d-%02d', $year_start, ($year_start + 1) % 100);
    } else {
      $data['selected_hijri_year'] = '';
    }

    // Years for dropdown
    // Prefer distinct years actually present in `sabeel_takhmeen`; fall back to calendar list
    $years = $this->CommonM->get_sabeel_distinct_years();
    if (empty($years)) {
      $years = $this->HijriCalendar->get_distinct_hijri_years();
    }
    $data['hijri_years'] = $years;

    // Allow debug output of allocations/payments when ?debug_alloc=1 is provided
    $debug_alloc = $this->input->get('debug_alloc');
    if ($debug_alloc) {
      $t = $this->CommonM->get_sabeel_takhmeen_reports($year_start, true);
      header('Content-Type: application/json');
      echo json_encode($t['_debug'] ?? $t, JSON_PRETTY_PRINT);
      exit;
    }

    $data["takhmeens"] = $this->CommonM->get_sabeel_takhmeen_reports($year_start);

    $this->load->view("Common/Header", $data);
    $this->load->view("Common/SabeelTakhmeen", $data);
  }

  public function sabeel_users()
  {
    $this->load->model('CommonM');
    $yearParam = $this->input->get_post('hijri_year');
    // Normalize year to numeric start year if a range like 1446-47 is provided
    if (!empty($yearParam) && preg_match('/^(\d{4})-\d{2}$/', (string) $yearParam, $m)) {
      $year = (int) $m[1];
    } elseif (is_numeric($yearParam)) {
      $year = (int) $yearParam;
    } else {
      $year = null;
    }
    $name_filter = $this->input->get_post('name');
    $amount_zero = $this->input->get_post('amount_zero');
    if (!$year) {
      // Force default Hijri financial year start to 1446 as requested
      $year = 1446;
    }
    $data['users'] = $this->CommonM->get_sabeel_user_details($year, null, $name_filter, $amount_zero);
    $data['hijri_years'] = $this->HijriCalendar->get_distinct_hijri_years();
    $data['selected_hijri_year'] = sprintf('%d-%02d', (int) $year, ((int) $year + 1) % 100);
    $data['name_filter'] = $name_filter ?? '';
    $data['amount_zero'] = !empty($amount_zero) ? 1 : 0;
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view("Common/Header", $data);
    $this->load->view('Common/SabeelUsers', $data);
  }

  public function sabeel_user_details($user_id = null)
  {
    if (empty($user_id)) {
      show_404();
      return;
    }
    $this->load->model('CommonM');
    $yearParam = $this->input->get_post('hijri_year');
    // Normalize to numeric start year if range provided
    if (!empty($yearParam) && preg_match('/^(\d{4})-\d{2}$/', (string) $yearParam, $m)) {
      $year = (int) $m[1];
    } elseif (is_numeric($yearParam)) {
      $year = (int) $yearParam;
    } else {
      $year = null;
    }
    $data['user'] = $this->CommonM->get_sabeel_user_details(null, null);
    // find specific user row
    $user_rows = array_filter($data['user'], function ($r) use ($user_id) {
      return $r['user_id'] == $user_id;
    });
    $data['user_summary'] = count($user_rows) ? array_values($user_rows)[0] : null;
    $data['records'] = $this->CommonM->get_sabeel_user_records($user_id, $year);
    $data['payments'] = $this->CommonM->get_sabeel_payments_by_user($user_id);
    $data['selected_hijri_year'] = !empty($year) ? sprintf('%d-%02d', (int) $year, ((int) $year + 1) % 100) : '';
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view("Common/Header", $data);
    $this->load->view('Common/SabeelUserDetails', $data);
  }

  // ===================== FMB (Thaali) Takhmeen Pages =====================
  public function fmbtakhmeen()
  {
    $this->validateUser($_SESSION["user"]);
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $this->input->get('from');
    if ($from) {
      $data['from'] = $from;
      $_SESSION["from"] = $from;
    }
    $data["active_controller"] = $_SESSION["from"];

    $year = $this->input->get_post('hijri_year');
    if (!$year) {
      // Force default Hijri financial year start to 1446 (1446-47)
      $year = 1446;
    }
    $data['selected_hijri_year'] = $year;
    $data['hijri_years'] = $this->HijriCalendar->get_distinct_hijri_years();
    $data['summary'] = $this->CommonM->get_fmb_takhmeen_reports($year);
    $this->load->view("Common/Header", $data);
    $this->load->view('Common/FMBTakhmeen', $data);
  }

  // Sector detail (year + sector) - FMB FIFO-paid allocation
  public function fmb_sector_details()
  {
    $this->validateUser($_SESSION["user"]);
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $this->input->get('from');
    if ($from) {
      $data['from'] = $from;
      $_SESSION['from'] = $from;
    }
    $data['active_controller'] = isset($_SESSION['from']) ? $_SESSION['from'] : '';

    $year = $this->input->get('hijri_year');
    $sector = $this->input->get('sector');
    if (empty($year)) {
      $today = date('Y-m-d');
      $hijri_today = $this->HijriCalendar->get_hijri_date($today);
      $year = $hijri_today && isset($hijri_today['hijri_date']) ? explode('-', $hijri_today['hijri_date'])[2] : '';
    }
    $data['selected_hijri_year'] = $year;
    $data['sector'] = $sector;

    $data['details'] = $this->CommonM->get_fmb_sector_year_details($year, $sector);

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/FMBTakhmeenSectorDetails', $data);
  }

  // ===================== FMB General Contribution (Card + Listing) =====================
  public function fmb_general_contributions()
  {
    $this->validateUser($_SESSION["user"]);
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $this->input->get('from');
    if ($from) {
      $data['from'] = $from;
      $_SESSION["from"] = $from;
    }
    $data["active_controller"] = $_SESSION["from"];

    // Filters
    $filters = [];
    $filters['contri_year'] = $this->input->get_post('contri_year');
    $filters['fmb_type'] = $this->input->get_post('fmb_type');
    $filters['contri_type'] = $this->input->get_post('contri_type');
    $filters['payment_status'] = $this->input->get_post('payment_status');
    $filters['user_id'] = $this->input->get_post('user_id');

    // Defaults: if no year specified, default to current Hijri year
    if (empty($filters['contri_year'])) {
      $today = date('Y-m-d');
      $hijri_today = $this->HijriCalendar->get_hijri_date($today);
      $filters['contri_year'] = $hijri_today && isset($hijri_today['hijri_date']) ? explode('-', $hijri_today['hijri_date'])[2] . '-' . substr(explode('-', $hijri_today['hijri_date'])[2] + 1, -2) : '';
    }

    $data['contri_years'] = $this->CommonM->get_distinct_fmb_contribution_years();
    $data['selected_contri_year'] = $filters['contri_year'];
    $data['contri_types'] = $this->CommonM->get_distinct_fmb_contribution_types(['contri_year' => $filters['contri_year'], 'fmb_type' => $filters['fmb_type']]);
    $data['filters'] = $filters;
    // Summary counts per contribution type (ignore contri_type filter for overview)
    $summary_filters = $filters;
    unset($summary_filters['contri_type']);
    $data['contri_type_counts'] = $this->CommonM->get_fmb_gc_contri_type_counts($summary_filters);
    $data['rows'] = $this->CommonM->get_fmb_general_contributions($filters);
    // Payments summary by contribution
    $contributionIds = array_map(function ($r) {
      return $r['id'];
    }, $data['rows']);
    $data['payments_by_gc'] = !empty($contributionIds) ? $this->CommonM->get_fmb_gc_payments_summary_by_contribution_ids($contributionIds) : [];
    $data['payments_rows_by_gc'] = !empty($contributionIds) ? $this->CommonM->get_fmb_gc_payments_by_contribution_ids($contributionIds) : [];

    $this->load->view("Common/Header", $data);
    $this->load->view('Common/FMBGeneralContributions', $data);
  }

  public function fmb_users()
  {
    $this->validateUser($_SESSION["user"]);
    $data['user_name'] = $_SESSION['user']['username'];
    $year = $this->input->get_post('hijri_year');
    $name_filter = $this->input->get_post('name');
    $amount_zero = $this->input->get_post('amount_zero');
    if (!$year) {
      $today = date('Y-m-d');
      $hijri_today = $this->HijriCalendar->get_hijri_date($today);
      $year = $hijri_today && isset($hijri_today['hijri_date']) ? explode('-', $hijri_today['hijri_date'])[2] : '';
    }
    $data['selected_hijri_year'] = $year;
    $data['hijri_years'] = $this->HijriCalendar->get_distinct_hijri_years();
    $data['users'] = $this->CommonM->get_fmb_user_details($year, $name_filter, $amount_zero);
    $data['name_filter'] = $name_filter ?? '';
    $data['amount_zero'] = !empty($amount_zero) ? 1 : 0;
    $this->load->view("Common/Header", $data);
    $this->load->view('Common/FMBUsers', $data);
  }

  public function fmb_user_details($user_id = null)
  {
    $this->validateUser($_SESSION["user"]);
    if (empty($user_id)) {
      show_404();
      return;
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $year = $this->input->get_post('hijri_year');
    if (!$year) {
      $today = date('Y-m-d');
      $hijri_today = $this->HijriCalendar->get_hijri_date($today);
      $year = $hijri_today && isset($hijri_today['hijri_date']) ? explode('-', $hijri_today['hijri_date'])[2] : '';
    }
    $rows = $this->CommonM->get_fmb_user_details($year);
    $match = array_values(array_filter($rows, function ($r) use ($user_id) {
      return $r['user_id'] == $user_id;
    }));
    $data['user_summary'] = !empty($match) ? $match[0] : null;
    $data['records'] = $this->CommonM->get_fmb_user_records($user_id, $year);
    $data['payments'] = $this->CommonM->get_fmb_payments_by_user($user_id);
    $data['selected_hijri_year'] = $year;
    $this->load->view("Common/Header", $data);
    $this->load->view('Common/FMBUserDetails', $data);
  }

  /**
   * Return per-user RSVP counts for a miqaat as JSON.
   * GET params: miqaat_id
   */
  public function miqaat_rsvp_user_counts()
  {
    $miqaat_id = (int)$this->input->get('miqaat_id');
    $this->output->set_content_type('application/json');
    if (!$miqaat_id) {
      echo json_encode(['success' => false, 'message' => 'miqaat_id required']);
      return;
    }

    // Count of individual users who have RSVP'd for this miqaat
    $sql1 = "SELECT COUNT(DISTINCT gr.user_id) AS will_attend
            FROM general_rsvp gr
            JOIN `user` u ON u.ITS_ID = gr.user_id
            WHERE gr.miqaat_id = ? AND u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''";
    $row1 = $this->db->query($sql1, [$miqaat_id])->row_array();
    $will_attend_members = isset($row1['will_attend']) ? (int)$row1['will_attend'] : 0;

    // Include guest counts submitted via general_guest_rsvp (gents+ladies+children)
    $guest_row = $this->db->query("SELECT COALESCE(SUM(gents),0) AS gents, COALESCE(SUM(ladies),0) AS ladies, COALESCE(SUM(children),0) AS children FROM general_guest_rsvp WHERE miqaat_id = ?", [$miqaat_id])->row_array();
    $guest_total = 0;
    if ($guest_row) {
      $guest_total = (int)($guest_row['gents'] ?? 0) + (int)($guest_row['ladies'] ?? 0) + (int)($guest_row['children'] ?? 0);
    }

    // Final will_attend includes both individual members who RSVP'd and guest counts
    $will_attend = $will_attend_members + $guest_total;

    // Get distinct hof_ids that have at least one RSVP (exclude null/empty)
    $hof_rows = $this->db->query("SELECT DISTINCT hof_id FROM general_rsvp WHERE miqaat_id = ?", [$miqaat_id])->result_array();
    $hof_ids = array_values(array_filter(array_map(function ($r) {
      return isset($r['hof_id']) ? (int)$r['hof_id'] : 0;
    }, $hof_rows), function ($v) {
      return $v > 0;
    }));

    // Also get distinct user_ids who submitted RSVP for this miqaat (cleaned)
    $rsvp_user_rows = $this->db->query("SELECT DISTINCT user_id FROM general_rsvp WHERE miqaat_id = ?", [$miqaat_id])->result_array();
    $rsvp_user_ids = array_values(array_filter(array_map(function ($r) {
      return isset($r['user_id']) ? (int)$r['user_id'] : 0;
    }, $rsvp_user_rows), function ($v) {
      return $v > 0;
    }));

    $will_not_attend = 0;
    $not_submitted = 0;

    // Base user filter: active + sector/subsector set
    $base_where = "u.Inactive_Status IS NULL AND COALESCE(u.Sector,'') <> '' AND COALESCE(u.Sub_Sector,'') <> ''";

    if (!empty($hof_ids)) {
      // Users who did NOT RSVP but their HOF has some RSVP
      $this->db->from('user u')->where($base_where, null, false)->where_in('u.HOF_ID', $hof_ids);
      if (!empty($rsvp_user_ids)) {
        $this->db->where_not_in('u.ITS_ID', $rsvp_user_ids);
      }
      $will_not_attend = (int)$this->db->count_all_results();

      // Users whose family has not submitted RSVP (their HOF is not in hof_ids)
      $this->db->from('user u')->where($base_where, null, false)->where_not_in('u.HOF_ID', $hof_ids);
      $not_submitted = (int)$this->db->count_all_results();
    } else {
      // No family RSVP at all: will_not_attend = 0; not_submitted = total active users
      $this->db->from('user u')->where($base_where, null, false);
      $not_submitted = (int)$this->db->count_all_results();
      $will_not_attend = 0;
    }

    echo json_encode([
      'success' => true,
      'miqaat_id' => $miqaat_id,
      'will_attend' => $will_attend,
      'will_not_attend' => $will_not_attend,
      'rsvp_not_submitted' => $not_submitted
    ]);
  }
}
