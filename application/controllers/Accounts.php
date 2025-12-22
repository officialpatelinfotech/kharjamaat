<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Accounts extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('AccountM');
    $this->load->model('HijriCalendar');
    $this->load->library('email', $this->config->item('email'));
    $this->load->helper('file');
  }
  public function index()
  {
    if (!empty($_SESSION['user'])) {
      $role = isset($_SESSION['user']['role']) ? (int)$_SESSION['user']['role'] : 0;
      if ($role === 1) {
        redirect('/admin');
      } elseif ($role === 2) {
        redirect('/amilsaheb');
      } elseif ($role === 3) {
        redirect('/anjuman');
      } elseif ($role === 16) {
        redirect('/MasoolMusaid');
      } elseif ($role >= 4 && $role <= 15) {
        redirect('/Umoor');
      } else {
        redirect('/accounts/home');
      }
    }
    if (!empty($_SESSION['login_status'])) {
      $data['status'] = $_SESSION['login_status'];
      $this->load->view('Home/Header');
      $this->load->view('Accounts/Login', $data);
    } else {
      $this->load->view('Home/Header');
      $this->load->view('Accounts/Login');
    }
  }
  public function login()
  {
    if (!empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $username = $_POST['username'];
    $password = $_POST['password'];
    $check = $this->AccountM->check_user($username, md5($password));

    $_SESSION['login_status'] = null;

    if (!empty($check)) {
      $user = $this->AccountM->get_user($username);
      $_SESSION['user'] = $check[0];
      if (!empty($user)) {
        $_SESSION['user_data'] = $user[0];
      } else {
        $_SESSION['user_data'] = "";
      }
      // echo print_r($check);
      // die();
      if ($check[0]['role'] == 1) {
        redirect('/admin');
      } elseif ($check[0]['role'] == 2) {
        redirect('/amilsaheb');
      } elseif ($check[0]['role'] == 3) {
        redirect('/anjuman');
      } elseif ($check[0]['role'] == 16) {
        redirect('/MasoolMusaid');
      } elseif ($check[0]['role'] >= 4 && $check[0]['role'] <= 15) {
        redirect('/Umoor');
      } else {
        redirect('/accounts/home');
      }
    } else {
      $_SESSION['login_status'] = true;
      redirect(base_url('/accounts/logout'));
    }
  }
  public function register()
  {
    $this->load->view('Home/Header');
    $this->load->view('Accounts/Register');
  }

  public function logout()
  {
    $_SESSION['user'] = null;
    redirect('/accounts');
  }

  // function gregorian_to_hijri($date = null)
  // {
  //   if (!$date) {
  //     $date = date('Y-m-d');
  //   }

  //   $parts = explode('-', $date); // Y-m-d
  //   $day = $parts[2];
  //   $month = $parts[1];
  //   $year = $parts[0];

  //   $url = "http://api.aladhan.com/v1/gToH?date=$day-$month-$year";

  //   $response = file_get_contents($url);
  //   $data = json_decode($response, true);

  //   if ($data && $data['code'] == 200) {
  //     return $data['data']['hijri']['day'] . ' ' .
  //       $data['data']['hijri']['month']['en'] . ' ' .
  //       $data['data']['hijri']['weekday']['en'] . ' ' .
  //       $data['data']['hijri']['year']; // e.g., "29-01-1446"
  //   }

  //   return false;
  // }

  public function get_formatted_raza_requests($user_id)
  {
    $raza = $this->AccountM->get_raza($user_id);

    if (empty($raza) || !is_array($raza)) {
      return [];
    }

    foreach ($raza as $key => $value) {
      $member_rows = $this->AccountM->get_user($value['user_id']);
      $member_full_name = '';
      if (is_array($member_rows) && !empty($member_rows) && isset($member_rows[0]['Full_Name'])) {
        $member_full_name = $member_rows[0]['Full_Name'];
      }

      $razatype_rows = $this->AccountM->get_razatype_byid($value['razaType']);
      $razatype_name = '';
      $razatype_id = '';
      if (is_array($razatype_rows) && !empty($razatype_rows)) {
        $first = $razatype_rows[0];
        $razatype_name = isset($first['name']) ? $first['name'] : '';
        $razatype_id = isset($first['id']) ? $first['id'] : '';
      }

      $raza[$key]['razaType'] = $razatype_name;
      $raza[$key]['razaType_id'] = $razatype_id;
      $raza[$key]['user_name'] = $member_full_name;

      // Fetch chat count
      $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
      $raza[$key]['chat_count'] = $chatCount;
    }
    return $raza;
  }

  public function get_pending_raza_requests($user_id)
  {
    $raza = $this->AccountM->get_raza($user_id);

    $raza_data = array();

    if (empty($raza) || !is_array($raza)) {
      return $raza_data;
    }

    foreach ($raza as $key => $value) {
      if ($value["status"] == 0 || $value["status"] == 1) {
        $razatype_rows = $this->AccountM->get_razatype_byid($value['razaType']);
        $razatype_name = '';
        if (is_array($razatype_rows) && !empty($razatype_rows) && isset($razatype_rows[0]['name'])) {
          $razatype_name = $razatype_rows[0]['name'];
        }
        $raza_data[$key]['razaType'] = $razatype_name;

        $raza_data[$key]['time-stamp'] = $value['time-stamp'];
        $raza_data[$key]['status'] = $value['status'];
      }
    }
    return $raza_data;
  }

  // Updated by Patel Infotech Services
  public function get_hijri_day_month($greg_date)
  {
    $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($greg_date)));
    $hijri_month_name = $this->HijriCalendar->hijri_month_name($hijri_date["hijri_month_id"]);
    return explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month_name["hijri_month"];
  }

  public function home()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $today = date("Y-m-d");

    $data['hijri_date'] = date("d-m-Y", strtotime($this->HijriCalendar->get_hijri_date($today)["hijri_date"]));

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user']['username'];

    $data['user_data'] = $this->AccountM->getUserData($user_id);
    $hof_id = $data['user_data']['HOF_ID'];
    $data['hof_data'] = $data['user_data']['HOF_ID'];

    $assigned_miqaats_count = $this->AccountM->get_assigned_miqaats_count($user_id);
    $data['assigned_miqaats_count'] = $assigned_miqaats_count;

    $miqaats = $this->AccountM->get_all_upcoming_miqaat();
    $data["miqaats"] = $miqaats;

    $data['raza'] = $this->get_pending_raza_requests($user_id);

    $rsvp_overview = $this->AccountM->get_rsvp_overview($hof_id, $miqaats);
    if ($rsvp_overview) {
      $data["rsvp_overview"] = $this->AccountM->get_rsvp_overview($hof_id, $miqaats)[$hof_id];
    } else {
      $data["rsvp_overview"] = [];
    }

    $data["fmb_takhmeen_details"] = $this->AccountM->get_member_total_fmb_due($user_id);
    $data["sabeel_takhmeen_details"] = $this->AccountM->get_member_total_sabeel_due($user_id);

    // Corpus funds summary for this family (HOF): total per family, assigned, paid, outstanding
    $this->load->model('CorpusFundM');
    $corpusFunds = $this->CorpusFundM->get_funds();
    $totalPerFamily = 0.0; // Sum of base fund amounts
    $assignedTotal = 0.0;  // Sum of assignments for this HOF across all funds
    $paidTotal = 0.0;      // Sum of payments for this HOF across all funds
    $fundsCount = is_array($corpusFunds) ? count($corpusFunds) : 0;
    if (!empty($corpusFunds)) {
      foreach ($corpusFunds as $f) {
        $fid = (int)($f['id'] ?? 0);
        $totalPerFamily += (float)($f['amount'] ?? 0);
        if ($fid > 0 && !empty($hof_id)) {
          // Assigned for this HOF + fund
          $rowA = $this->db->select('COALESCE(SUM(amount_assigned),0) AS total_assigned')
            ->from('corpus_fund_assignment')
            ->where('fund_id', $fid)
            ->where('hof_id', $hof_id)
            ->get()->row_array();
          $assignedTotal += isset($rowA['total_assigned']) ? (float)$rowA['total_assigned'] : 0.0;
          // Paid for this HOF + fund
          $rowP = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')
            ->from('corpus_fund_payment')
            ->where('fund_id', $fid)
            ->where('hof_id', $hof_id)
            ->get()->row_array();
          $paidTotal += isset($rowP['total_paid']) ? (float)$rowP['total_paid'] : 0.0;
        }
      }
    }
    $data['corpus_summary'] = [
      'total_per_family' => $totalPerFamily,
      'assigned_total' => $assignedTotal,
      'paid_total' => $paidTotal,
      'outstanding' => max(0, $assignedTotal - $paidTotal),
      'funds_count' => $fundsCount,
    ];

    // Monthly signup overview using current Hijri month
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    if ($h && isset($h['hijri_date'])) {
      $parts = explode('-', $h['hijri_date']); // d-m-Y
      $hm = $parts[1];
      $hy = $parts[2];
      $days = $this->HijriCalendar->get_hijri_days_for_month_year($hm, $hy);
      if (!empty($days)) {
        $firstDay = $days[0]['greg_date'];
        $lastDay  = $days[count($days) - 1]['greg_date'];
        $data["signup_days"] = $this->AccountM->get_fmb_signup_days_between($firstDay, $lastDay);
        $data["signup_data"] = $this->AccountM->get_fmb_signup_data_between($user_id, $firstDay, $lastDay);
        // Month feedback summary (range + counts)
        $month_feedback_signed = 0;
        $month_feedback_given = 0;
        foreach ($data['signup_data'] as $sdRow) {
          if (isset($sdRow['want_thali']) && (string)$sdRow['want_thali'] === '1') {
            $month_feedback_signed++;
            if (isset($sdRow['status']) && (int)$sdRow['status'] === 1) {
              $month_feedback_given++;
            }
          }
        }
        $month_feedback_status_class = 'secondary';
        $month_feedback_status_text  = 'No Sign Ups';
        if ($month_feedback_signed > 0) {
          if ($month_feedback_given === 0) {
            $month_feedback_status_class = 'primary';
            $month_feedback_status_text  = 'Not Given';
          } elseif ($month_feedback_given < $month_feedback_signed) {
            $month_feedback_status_class = 'warning';
            $month_feedback_status_text  = 'Partially Given';
          } else {
            $month_feedback_status_class = 'success';
            $month_feedback_status_text  = 'Given';
          }
        }
        $data['month_feedback_range'] = date('d-m-Y', strtotime($firstDay)) . ' - ' . date('d-m-Y', strtotime($lastDay));
        $data['month_feedback_signed'] = $month_feedback_signed;
        $data['month_feedback_given']  = $month_feedback_given;
        $data['month_feedback_status_class'] = $month_feedback_status_class;
        $data['month_feedback_status_text']  = $month_feedback_status_text;
      } else {
        $data["signup_days"] = [];
        $data["signup_data"] = [];
        $data['month_feedback_range'] = '';
        $data['month_feedback_signed'] = 0;
        $data['month_feedback_given']  = 0;
        $data['month_feedback_status_class'] = 'secondary';
        $data['month_feedback_status_text']  = 'No Sign Ups';
      }
      $month_row = $this->HijriCalendar->hijri_month_name((int)$parts[1]);
      $data['current_hijri_month_label'] = ($month_row ? $month_row['hijri_month'] : $parts[1]) . ' ' . $parts[2];
    } else {
      $data["signup_days"] = [];
      $data["signup_data"] = [];
      $data['current_hijri_month_label'] = '';
      $data['month_feedback_range'] = '';
      $data['month_feedback_signed'] = 0;
      $data['month_feedback_given']  = 0;
      $data['month_feedback_status_class'] = 'secondary';
      $data['month_feedback_status_text']  = 'No Sign Ups';
    }

    // Today signup status (only today's day)
    $todayGreg = date('Y-m-d');
    $nowTime = date('H:i:s');
    $cutoff = '18:00:00';
    $menuToday = $this->AccountM->get_menu_by_date($todayGreg);
    // Map signup data by date for quick lookup
    $signupByDate = [];
    if (isset($data['signup_data']) && is_array($data['signup_data'])) {
      foreach ($data['signup_data'] as $sd) {
        if (isset($sd['signup_date'])) {
          $signupByDate[$sd['signup_date']] = $sd;
        }
      }
    }
    $todaySigned = isset($signupByDate[$todayGreg]) && (string)$signupByDate[$todayGreg]['want_thali'] === '1';
    $todayClosed = ($nowTime >= $cutoff);
    $badgeClass = 'secondary';
    $badgeText = 'No Thaali Today';
    if ($menuToday) {
      if ($todaySigned) {
        $badgeClass = 'success';
        $badgeText = 'Signed Up Today';
      } else if ($todayClosed) {
        $badgeClass = 'danger';
        $badgeText = 'Sign-up Closed';
      } else {
        $badgeClass = 'warning text-dark';
        $badgeText = 'Not Signed Today';
      }
    }
    $data['fmb_today_status'] = [
      'badge_class' => $badgeClass,
      'badge_text'  => $badgeText,
      'menu_items'  => $menuToday ? $menuToday['items'] : []
    ];
    $data["feedback_data"] = $this->AccountM->get_fmb_feedback_data($user_id);

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Home');
  }

  public function assigned_miqaats()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user_data']['ITS_ID'];

    $data['user_data'] = $this->AccountM->getUserData($_SESSION['user']['username']);
    $data['hof_data'] = $data['user_data']['HOF_ID'];

    $data["miqaats"] = $this->AccountM->get_assigned_miqaats($user_id);

    foreach ($data["miqaats"] as $key => $miqaat) {
      $hijri_date = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($miqaat["date"])))["hijri_date"]);
      $hijri_month = $this->HijriCalendar->hijri_month_name($this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($miqaat["date"])))["hijri_month_id"])["hijri_month"];
      $data["miqaats"][$key]["hijri_date"] = $hijri_date[0] . " " . $hijri_month . " " . $hijri_date[2];

      $data["miqaats"][$key]["raza"] = $this->AccountM->get_raza_by_miqaat($miqaat["id"], $user_id);
      $data["miqaats"][$key]["invoice_status"] = $this->AccountM->get_miqaat_invoice_status($miqaat["id"]);
    }

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/AssignedMiqaats', $data);
  }

  public function submit_miqaat_raza($miqaat_id)
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $user_id = $_SESSION['user_data']['ITS_ID'];

    $raza_data = '{"miqaat_id":"' . $miqaat_id . '"}';

    $data = array(
      'user_id' => $user_id,
      'razaType' => 2,
      'razadata' => $raza_data,
      'miqaat_id' => $miqaat_id,
      'sabil' => 0,
      'fmb' => 0,
    );

    $result = $this->AccountM->submit_miqaat_raza($data);

    $hijri_year = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"])[2];

    $raza_id = $this->AccountM->generate_raza_id($hijri_year);
    $this->AccountM->update_raza_by_id($result, array("raza_id" => $raza_id));

    if ($result > 0) {
      $this->session->set_flashdata('success', "Miqaat Raza submitted successfully.");
    } else if ($result < 0) {
      $this->session->set_flashdata('warning', "Miqaat Raza already submitted.");
    } else {
      $this->session->set_flashdata('error', "Failed to submit Miqaat Raza. Please try again.");
    }
    redirect('/accounts/assigned_miqaats');
  }

  public function rsvp_list()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user_data']['ITS_ID'];

    $data['user_data'] = $this->AccountM->getUserData($_SESSION['user']['username']);
    $hof_id = $data['user_data']['HOF_ID'];
    $data['hof_data'] = $hof_id;

    $miqaats = $this->AccountM->get_all_upcoming_miqaat();
    $data["miqaats"] = $miqaats;

    foreach ($miqaats as $key => $miqaat) {
      $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($miqaat["date"])));
      $hijri_month = $this->HijriCalendar->hijri_month_name($hijri_date["hijri_month_id"])["hijri_month"];
      $data["miqaats"][$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];
      $data["miqaats"][$key]["raza_status"] = $this->AccountM->get_miqaat_raza_status($miqaat["id"]);
    }

    $rsvp_overview = $this->AccountM->get_rsvp_overview($hof_id, $miqaats);
    if (!empty($rsvp_overview) && isset($rsvp_overview[$hof_id])) {
      $data["rsvp_overview"] = $rsvp_overview[$hof_id];
    } else {
      $data["rsvp_overview"] = [];
    }

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/RSVP/Home', $data);
  }

  public function corpusfunds_details()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user']['username'];
    $data['user_data'] = $this->AccountM->getUserData($user_id);
    $hof_id = $data['user_data']['HOF_ID'];

    $this->load->model('CorpusFundM');
    // Fetch assignments with paid/due per fund for this HOF
    $rows = $this->db->query(
      "SELECT a.fund_id, f.title, a.amount_assigned,
              COALESCE(paid.total_paid, 0) AS amount_paid,
              GREATEST(a.amount_assigned - COALESCE(paid.total_paid,0), 0) AS amount_due
         FROM corpus_fund_assignment a
         INNER JOIN corpus_fund f ON f.id = a.fund_id
         LEFT JOIN (
            SELECT fund_id, hof_id, SUM(amount_paid) AS total_paid
              FROM corpus_fund_payment
             WHERE hof_id = ?
             GROUP BY fund_id, hof_id
         ) paid ON paid.fund_id = a.fund_id AND paid.hof_id = a.hof_id
        WHERE a.hof_id = ?
        ORDER BY f.title",
      [$hof_id, $hof_id]
    )->result_array();

    // Totals
    $tot_assigned = 0.0;
    $tot_paid = 0.0;
    $tot_due = 0.0;
    foreach ($rows as $r) {
      $tot_assigned += (float)($r['amount_assigned'] ?? 0);
      $tot_paid     += (float)($r['amount_paid'] ?? 0);
      $tot_due      += (float)($r['amount_due'] ?? 0);
    }
    $data['corpus_details'] = [
      'rows' => $rows,
      'tot_assigned' => $tot_assigned,
      'tot_paid' => $tot_paid,
      'tot_due' => $tot_due,
    ];

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/CorpusFundsDetails', $data);
  }

  public function general_rsvp($miqaat_id)
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user_data']['ITS_ID'];

    $data['user_data'] = $this->AccountM->getUserData($_SESSION['user']['username']);
    $hof_id = $data['user_data']['HOF_ID'];
    $data['hof_data'] = $data['user_data']['HOF_ID'];

    $family = $this->AccountM->get_all_family_member($data['hof_data']);
    $data['family'] = $family;

    $data['miqaat'] = $this->AccountM->get_miqaat_by_id($miqaat_id);

    $data["rsvp_by_miqaat_id"] = $this->AccountM->get_rsvp_by_miqaat_id($hof_id, $miqaat_id);
    $data["rsvp_miqaat_ids"] = array_column($data["rsvp_by_miqaat_id"], 'user_id');

    // Load any saved guest counts for this hof + miqaat so view can prefill
    $data['guest_rsvp'] = $this->AccountM->get_guest_rsvp($hof_id, $miqaat_id);

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/RSVP/GeneralRSVP', $data);
  }

  public function submit_general_rsvp()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $user_id = $_SESSION['user_data']['ITS_ID'];
    $hof_id = $_SESSION['user_data']['HOF_ID'];
    $miqaat_id = $this->input->post('miqaat_id');
    $rsvp_members = $this->input->post('rsvp_members');
    $guest_gents = $this->input->post('guest_gents');
    $guest_ladies = $this->input->post('guest_ladies');
    $guest_children = $this->input->post('guest_children');

    if (empty($rsvp_members)) {
      $this->session->set_flashdata('error', "Please select at least one family member to RSVP.");
      redirect('/accounts/general_rsvp/' . $miqaat_id);
    }

    // Clear existing RSVPs for the selected miqaat and hof_id
    $this->AccountM->clear_existing_rsvps($hof_id, $miqaat_id);

    // Insert new RSVPs
    foreach ($rsvp_members as $member_id) {
      $data = array(
        'hof_id' => $hof_id,
        'miqaat_id' => $miqaat_id,
        'user_id' => $member_id
      );
      $this->AccountM->insert_rsvp($data);
    }

    // Handle guest RSVP counts: clear previous and insert new if provided
    $guest_flag = $this->input->post('guest_rsvp');
    // Always clear any existing guest record for this hof+miqaat to keep single source of truth
    $this->AccountM->clear_existing_guest_rsvp($hof_id, $miqaat_id);
    if ($guest_flag) {
      $gents = is_numeric($guest_gents) ? max(0, (int)$guest_gents) : 0;
      $ladies = is_numeric($guest_ladies) ? max(0, (int)$guest_ladies) : 0;
      $children = is_numeric($guest_children) ? max(0, (int)$guest_children) : 0;
      $guestData = [
        'hof_id' => $hof_id,
        'miqaat_id' => $miqaat_id,
        'gents' => $gents,
        'ladies' => $ladies,
        'children' => $children
      ];
      $this->AccountM->insert_guest_rsvp($guestData);
    }

    $this->session->set_flashdata('success', "RSVP submitted successfully.");
    redirect('/accounts/rsvp_list');
  }

  public function fmbweeklysignup()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user_data']['ITS_ID'];

    $data['user_data'] = $this->AccountM->getUserData($_SESSION['user']['username']);
    $data['hof_data'] = $data['user_data']['HOF_ID'];

    // Monthly signup (Hijri): accept optional GET param hijri=YYYY-MM
    $hijri = $this->input->get('hijri');
    $todayGreg = date('Y-m-d');
    $todayHijri = $this->HijriCalendar->get_hijri_date($todayGreg);
    $defParts = $todayHijri && isset($todayHijri['hijri_date']) ? explode('-', $todayHijri['hijri_date']) : null; // d-m-Y
    $defMonth = $defParts ? $defParts[1] : date('m');
    $defYear  = $defParts ? $defParts[2] : date('Y');

    if (preg_match('/^\d{4}-\d{2}$/', (string)$hijri)) {
      list($hy, $hm) = explode('-', $hijri);
    } else {
      $hy = $defYear;
      $hm = $defMonth;
      $hijri = $hy . '-' . $hm;
    }

    $days = $this->HijriCalendar->get_hijri_days_for_month_year($hm, $hy);
    if (!empty($days)) {
      $firstDay = $days[0]['greg_date'];
      $lastDay  = $days[count($days) - 1]['greg_date'];
      $data["menu"] = $this->AccountM->get_menus_between($firstDay, $lastDay);
      $data["signup_days"] = $this->AccountM->get_fmb_signup_days_between($firstDay, $lastDay);
      $data["signup_data"] = $this->AccountM->get_fmb_signup_data_between($user_id, $firstDay, $lastDay);
    } else {
      $data["menu"] = [];
      $data["signup_days"] = [];
      $data["signup_data"] = [];
    }

    // Decorate with hijri labels
    foreach ($data["menu"] as $key => $value) {
      $data['menu'][$key]['hijri_date'] = $this->get_hijri_day_month($value["date"]);
    }

    // Build all days of the Hijri month with merged menu + signup info
    $menuByDate = [];
    foreach ($data['menu'] as $m) {
      $menuByDate[$m['date']] = $m;
    }
    $signupByDate = [];
    if (isset($data['signup_data']) && is_array($data['signup_data'])) {
      foreach ($data['signup_data'] as $sd) {
        if (isset($sd['signup_date'])) {
          $signupByDate[$sd['signup_date']] = $sd;
        }
      }
    }
    $all_days = [];
    foreach ($days as $d) {
      $greg = $d['greg_date'];
      $weekday = date('l', strtotime($greg));
      $hijri_full = isset($d['hijri_date']) ? $d['hijri_date'] : '';
      $menu_items = [];
      if (isset($menuByDate[$greg]) && isset($menuByDate[$greg]['items'])) {
        $menu_items = $menuByDate[$greg]['items'];
      }
      $signup_row = isset($signupByDate[$greg]) ? $signupByDate[$greg] : [];
      $all_days[] = [
        'greg_date'   => $greg,
        'weekday'     => $weekday,
        'hijri_date'  => $hijri_full, // format d-m-Y
        'menu_items'  => $menu_items,
        'want_thali'  => isset($signup_row['want_thali']) ? $signup_row['want_thali'] : null,
        'thali_size'  => isset($signup_row['thali_size']) ? $signup_row['thali_size'] : null,
        'menu_id'     => isset($menuByDate[$greg]['id']) ? $menuByDate[$greg]['id'] : null
      ];
    }
    $data['all_days'] = $all_days;

    // Month label and navigation
    $data['selected_hijri'] = $hijri;
    $data['hijri_year'] = $hy;
    $data['hijri_month'] = $hm;
    $month_row = $this->HijriCalendar->hijri_month_name((int)$hm);
    $data['hijri_month_name'] = $month_row ? $month_row['hijri_month'] : $hm;
    $prevMonth = (int)$hm === 1 ? 12 : ((int)$hm - 1);
    $prevYear  = (int)$hm === 1 ? ((int)$hy - 1) : (int)$hy;
    $nextMonth = (int)$hm === 12 ? 1 : ((int)$hm + 1);
    $nextYear  = (int)$hm === 12 ? ((int)$hy + 1) : (int)$hy;
    $data['prev_hijri'] = sprintf('%04d-%02d', $prevYear, $prevMonth);
    $data['next_hijri'] = sprintf('%04d-%02d', $nextYear, $nextMonth);

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/FMB/WeeklySignup', $data);
  }

  public function savefmbsignup()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $user_id = $_SESSION['user_data']['ITS_ID'];
    $signup_dates = $this->input->post('date');
    $want_thali = $this->input->post('want-thali');
    $thali_size = $this->input->post('thali_size');

    $today = date('Y-m-d');
    $nowTime = date('H:i:s');
    $cutoff = '18:00:00';

    $saved = 0;
    $skipped = 0;

    foreach ($signup_dates as $key => $date) {
      $data = array(
        'user_id' => $user_id,
        'signup_date' => $date,
        'want_thali' => isset($want_thali[$key]) ? $want_thali[$key] : 0,
        'thali_size' => isset($thali_size[$key]) ? $thali_size[$key] : ''
      );
      if ($this->AccountM->save_fmb_signup($data)) {
        $saved++;
      } else {
        $skipped++;
      }
    }

    $msg = 'Thaali Sign Up saved successfully';
    if ($skipped > 0) {
      $msg .= " (" . $skipped . " date(s) unchanged)";
    }
    $this->session->set_flashdata('success', $msg);
    redirect('/accounts/fmbweeklysignup');
  }

  public function FMBFeedback()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user_data']['ITS_ID'];

    $data['user_data'] = $this->AccountM->getUserData($_SESSION['user']['username']);
    $data['hof_data'] = $data['user_data']['HOF_ID'];

    // Determine selected Hijri month/year (from GET or default to current Hijri)
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $default_parts = explode('-', $today_hijri['hijri_date']); // d-m-Y
    $default_month = isset($default_parts[1]) ? (int)$default_parts[1] : null;
    $default_year  = isset($default_parts[2]) ? (int)$default_parts[2] : null;
    $sel_month = $this->input->get('hijri_month') ? (int)$this->input->get('hijri_month') : $default_month;
    $sel_year  = $this->input->get('hijri_year') ? (int)$this->input->get('hijri_year') : $default_year;

    // Build month navigation labels
    $months_this_year = $this->HijriCalendar->get_hijri_months_for_year($sel_year);
    $current_month_name = '';
    foreach ($months_this_year as $m) {
      if ((int)$m['id'] === (int)$sel_month) {
        $current_month_name = $m['name'];
        break;
      }
    }
    $data['hijri_nav'] = [
      'sel_month' => $sel_month,
      'sel_year' => $sel_year,
      'current_month_name' => $current_month_name,
      'months_this_year' => $months_this_year,
    ];

    // Fetch all Hijri days for the selected month and year
    $days = $this->HijriCalendar->get_hijri_days_for_month_year($sel_month, $sel_year);
    $data['days'] = $days;

    // Fetch menus between first and last Gregorian date covering these days
    $data['menu'] = [];
    $data['signup_data'] = [];
    if (!empty($days)) {
      $first_greg = $days[0]['greg_date'];
      $last_greg  = $days[count($days) - 1]['greg_date'];
      $menus_between = $this->AccountM->get_menus_between($first_greg, $last_greg);
      // Fetch user signup entries for the period
      $data['signup_data'] = $this->AccountM->get_fmb_signup_data_between($user_id, $first_greg, $last_greg);
      // Map signup by greg date for quick lookup
      $signupByDate = [];
      foreach ($data['signup_data'] as $sd) {
        if (isset($sd['signup_date'])) {
          $signupByDate[$sd['signup_date']] = $sd;
        }
      }
      // Map menus to view-friendly structure
      foreach ($menus_between as $mm) {
        $greg = isset($mm['date']) ? $mm['date'] : null;
        if (!$greg) continue;
        // Find corresponding hijri day from $days
        $hijri_date = '';
        foreach ($days as $d) {
          if ($d['greg_date'] === $greg) {
            $hijri_date = $d['hijri_date'];
            break;
          }
        }
        $data['menu'][] = [
          // signup_id used for feedback reference (fwsid legacy name in view)
          'signup_id' => isset($signupByDate[$greg]['id']) ? $signupByDate[$greg]['id'] : null,
          'item_names' => !empty($mm['items']) ? implode(', ', $mm['items']) : '',
          'hijri_date' => $hijri_date,
          'greg_date' => $greg,
          'want_thali' => 0, // will be determined in view from signup_data
          'thali_size' => isset($signupByDate[$greg]['thali_size']) ? $signupByDate[$greg]['thali_size'] : '',
          'status' => isset($signupByDate[$greg]['status']) ? (int)$signupByDate[$greg]['status'] : 0,
        ];
      }
    }

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/FMB/Feedback', $data);
  }

  public function getFMBFeedbackData()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $fwsid = $this->input->post("fwsid");
    $get_feedback_data = $this->AccountM->get_feedback_data($fwsid);
    echo json_encode($get_feedback_data);
  }

  public function UpdateFMBFeedback()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $feedback_id = $this->input->post("feedback_id");
    $delivery_time = $this->input->post("delivery_time");
    $quality = $this->input->post('quality');
    $freshness = $this->input->post('freshness');
    $quantity = $this->input->post('quantity');
    $feedback_remark = $this->input->post('feedback_remark');

    $data = array(
      'feedback_id' => $feedback_id,
      'delivery_time' => $delivery_time,
      'quality' => $quality,
      'freshness' => $freshness,
      'quantity' => $quantity,
      'feedback_remark' => $feedback_remark
    );
    $result = $this->AccountM->update_fmb_feedback($data);
    $success = $result ? true : false;
    $this->output->set_content_type('application/json')->set_output(json_encode([
      'success' => $success,
      'feedback_id' => $feedback_id,
    ]));
  }

  public function viewmenu()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    // Optional Hijri month navigation (?hijri=YYYY-MM)
    $hijriParam = $this->input->get('hijri');
    $todayGreg = date('Y-m-d');
    $todayHijri = $this->HijriCalendar->get_hijri_date($todayGreg);
    $parts = $todayHijri && isset($todayHijri['hijri_date']) ? explode('-', $todayHijri['hijri_date']) : [];
    $currentHy = !empty($parts) ? $parts[2] : date('Y');
    $currentHm = !empty($parts) ? $parts[1] : date('m');
    if (preg_match('/^\d{4}-\d{2}$/', (string)$hijriParam)) {
      list($hy, $hm) = explode('-', $hijriParam);
    } else {
      $hy = $currentHy;
      $hm = $currentHm;
      $hijriParam = $hy . '-' . $hm;
    }
    $data['selected_hijri'] = $hijriParam;
    $data['hijri_year'] = $hy;
    $data['hijri_month'] = $hm;
    $monthRow = $this->HijriCalendar->hijri_month_name((int)$hm);
    $data['hijri_month_name'] = $monthRow ? $monthRow['hijri_month'] : $hm;
    // Prev / Next Hijri month
    $prevMonth = (int)$hm === 1 ? 12 : ((int)$hm - 1);
    $prevYear  = (int)$hm === 1 ? ((int)$hy - 1) : (int)$hy;
    $nextMonth = (int)$hm === 12 ? 1 : ((int)$hm + 1);
    $nextYear  = (int)$hm === 12 ? ((int)$hy + 1) : (int)$hy;
    $data['prev_hijri'] = sprintf('%04d-%02d', $prevYear, $prevMonth);
    $data['next_hijri'] = sprintf('%04d-%02d', $nextYear, $nextMonth);

    $data['menus'] = $this->AccountM->get_hijri_month_menu($hy, $hm);
    // Provide debug meta if empty for troubleshooting
    if (empty($data['menus'])) {
      $data['menus_debug'] = [
        'selected_hijri' => $hijriParam,
        'year' => $hy,
        'month' => $hm,
        'reason' => 'No records found for provided Hijri month (check hijri_calendar and menu entries)'
      ];
    }
    foreach ($data['menus'] as $key => $value) {
      $data['menus'][$key]['hijri_date'] = $this->get_hijri_day_month($value['date']);
    }

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/FMB/ViewMenu', $data);
  }

  public function viewfmbtakhmeen()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user_data']['ITS_ID'];
    $data["fmb_takhmeen_details"] = $this->AccountM->viewfmbtakhmeen($user_id);
    // Add Miqaat invoices listing for user with paid/due breakdown
    $data['miqaat_invoices'] = $this->AccountM->get_user_miqaat_invoices($user_id);

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/FMB/ViewTakhmeen', $data);
  }

  /**
   * AJAX: Return miqaat invoice payment history for the logged-in user.
   * POST: invoice_id
   * Response: { success, invoice, payments, message? }
   */
  public function miqaat_invoice_history()
  {
    if (empty($_SESSION['user'])) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
      return;
    }
    $invoice_id = (int)$this->input->post('invoice_id');
    if (!$invoice_id) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Missing invoice id']));
      return;
    }
    $user_id = $_SESSION['user_data']['ITS_ID'];
    $res = $this->AccountM->get_user_miqaat_invoice_history($user_id, $invoice_id);
    if (!$res['invoice']) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Invoice not found']));
      return;
    }
    $this->output->set_content_type('application/json')->set_output(json_encode([
      'success' => true,
      'invoice' => $res['invoice'],
      'payments' => $res['payments']
    ]));
  }

  /**
   * AJAX: Return payment history for a General Contribution invoice belonging to logged-in user.
   * POST: fmbgc_id
   * Response: JSON { success, invoice, payments, total_received, balance_due, message? }
   */
  public function gc_payment_history()
  {
    if (empty($_SESSION['user'])) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
      return;
    }
    $fmbgc_id = (int)$this->input->post('fmbgc_id');
    if (!$fmbgc_id) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Missing invoice id']));
      return;
    }
    $user_id = $_SESSION['user_data']['ITS_ID'];
    $res = $this->AccountM->get_user_gc_payment_history($user_id, $fmbgc_id);
    $this->output->set_content_type('application/json')->set_output(json_encode($res));
  }

  public function ViewSabeelTakhmeen()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $user_id = $_SESSION['user_data']['ITS_ID'];
    $data["sabeel_takhmeen_details"] = $this->AccountM->viewSabeelTakhmeen($user_id);

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Sabeel/ViewTakhmeen', $data);
  }

  // Updated by Patel Infotech Services

  public function MyRazaRequest()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $user_id = $_SESSION['user_data']['ITS_ID'];
    $data['raza'] = $this->get_formatted_raza_requests($user_id);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/MyRaza/Home', $data);
  }
  public function Umoor()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    // Load the model
    $this->load->model('AccountM');

    // Get the data from model
    $data['raza_types'] = $this->AccountM->RazaTypesDetails();
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/12_Umoor/Home', $data);
  }
  public function NewRaza()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['razatype'] = $this->AccountM->get_razatype();
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/MyRaza/NewRaza3', $data);
  }
  public function updateraza($id)
  {
    $this->email->from('admin@kharjamaat.in', 'Raza Update');
    $this->email->to($_SESSION['user_data']['Email']);
    $this->email->subject('Raza Status');
    $this->email->message('Your Raza has been updated');
    $this->email->send();

    unset($_POST['raza-type']);
    $data = json_encode($_POST);
    $flag = $this->AccountM->update_raza($id, $data);
    if ($flag) {
      redirect('/accounts/success/MyRazaRequest');
    } else {
      redirect('/accounts/error/MyRazaRequest');
    }
  }
  public function updateVasanReq($id)
  {
    $data['vasan'] = $this->AccountM->get_vasanreq_byid($id)[0];
    $data['vasan_type'] = $this->AccountM->get_vasantype();
    $otherInfo = json_decode($data['vasan']['utensils'], true);

    unset($data['vasan']['utensils']);

    foreach ($otherInfo as $key => $value) {
      $data['vasan']['utensils'][$key] = $value;
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Miqaat/updateVasan', $data);
  }
  public function editVasanReq($id)
  {
    $reason = $_POST['reason'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $utensil_name = $_POST['form-utensil'];
    $utensil_quantity = $_POST['form-quantity'];

    $utensils = [];
    foreach ($utensil_name as $key => $value) {
      $utensils[$key] = [
        'name' => $value,
        'quantity' => $utensil_quantity[$key],
      ];
    }
    $utensil = json_encode($utensils);
    $flag = $this->AccountM->update_vasan_req($id, $reason, $from_date, $to_date, $utensil);
    if ($flag) {
      redirect('/accounts/success/miqaat');
    } else {
      redirect('/accounts/error/miqaat');
    }
  }
  public function deleteVasanReq($id)
  {
    $flag = $this->AccountM->delete_vasan_req($id);
    if ($flag) {
      redirect('/accounts/success/miqaat');
    } else {
      redirect('/accounts/error/miqaat');
    }
  }
  public function DeleteRaza($id)
  {
    $flag = $this->AccountM->delete_raza($id);
    if ($flag) {
      redirect('/accounts/success/MyRazaRequest');
    } else {
      redirect('/accounts/error/MyRazaRequest');
    }
  }
  public function miqaat()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $userId = $_SESSION['user_data']['ITS_ID'];
    $data['rsvp_list'] = $this->AccountM->get_rsvp();
    $data['vasanreq_list'] = $this->AccountM->get_vasan_request($userId);
    $userHof = $_SESSION['user_data']['HOF_ID'];
    foreach ($data['rsvp_list'] as $key => $r) {
      $family = $this->AccountM->get_all_family_member($userHof);
      $attend = true;
      foreach ($family as $f) {
        $isattended = $this->AccountM->get_rsvp_attendance($r['id'], $f['id']);
        if (!$isattended) {
          $attend = false;
          break;
        }
      }
      $data['rsvp_list'][$key]['attend'] = $attend;
    }

    foreach ($data['vasanreq_list'] as $key => $vr) {
      $str = "";
      $value = json_decode($vr['utensils'], true);
      foreach ($value as $v) {
        $nameofvasan = $this->AccountM->get_vasan_byid($v['name'])[0];
        $str .= $nameofvasan['name'] . '-' . $v['quantity'] . '<br/>';
      }
      $data['vasanreq_list'][$key]['utensils'] = $str;
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Miqaat/Home', $data);
  }
  public function rsvp($id)
  {
    if (empty($_SESSION['user'])) {
      $_SESSION['redirect_to_url'] = 'accounts/rsvp/' . $id;
      redirect('/accounts/loggin');
    }
    $data['rsvp'] = $this->AccountM->get_rsvp_byid($id)[0];
    $userId = $_SESSION['user_data']['HOF_ID'];
    $family = $this->AccountM->get_all_family_member($userId);
    foreach ($family as $key => $f) {
      $family[$key]['attend'] = $this->AccountM->get_rsvp_attendance_present($id, $f['id']);
    }
    $data['family'] = $family;
    $data['guest'] = $this->AccountM->get_guset_rsvp($userId, $id);

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Miqaat/Rsvp', $data);
  }

  public function submit_rsvp($id)
  {
    try {
      $hofid = $_SESSION['user_data']['HOF_ID'];
      $family = $this->AccountM->get_all_family_member($hofid);
      $guset_male = $_POST['guest_male'];
      $guset_female = $_POST['guest_female'];
      $this->AccountM->insert_rsvp_attendance_guest($hofid, $guset_male, $guset_female, $id);

      foreach ($family as $f) {
        if (!empty($_POST[$f['id']])) {
          //mark present
          $alreadypresent = $this->AccountM->get_rsvp_attendance($id, $f['id']);
          if (!$alreadypresent) {
            $this->AccountM->insert_rsvp_attendance($id, $f['id'], 1);
          } else {
            $this->AccountM->update_rsvp_attendance($id, $f['id'], 1);
          }
        } else {
          //mark absent
          $alreadypresent = $this->AccountM->get_rsvp_attendance($id, $f['id']);
          if ($alreadypresent) {
            $this->AccountM->update_rsvp_attendance($id, $f['id'], 0);
          } else {
            $this->AccountM->insert_rsvp_attendance($id, $f['id'], 0);
          }
        }
      }
      redirect('/accounts/success/miqaat');
    } catch (Exception $e) {
      log_message('error', 'Exception in submit_rsvp: ' . $e->getMessage());
      redirect('/accounts/error/miqaat');
    }
  }

  public function newVasanReq()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['vasan_type'] = $this->AccountM->get_vasantype();
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Miqaat/NewVasanRequest.php', $data);
  }
  public function submit_vasanrequest()
  {
    if ($_POST) {
      $userId = $_SESSION['user_data']['ITS_ID'];
      $reason = $_POST['reason'];
      $from_date = $_POST['from_date'];
      $to_date = $_POST['to_date'];
      $utensil_name = $_POST['form-utensil'];
      $utensil_quantity = $_POST['form-quantity'];

      $utensils = [];
      foreach ($utensil_name as $key => $value) {
        $utensils[$key] = [
          'name' => $value,
          'quantity' => $utensil_quantity[$key],
        ];
      }
      $utensil = json_encode($utensils);
      $flag = $this->AccountM->insert_vasan_req($userId, $reason, $from_date, $to_date, $utensil);
      if ($flag) {
        redirect('/accounts/success/miqaat');
      } else {
        redirect('/accounts/error/miqaat');
      }
    }
  }
  public function success($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $data['redirect'] = $redirectto;
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Success.php', $data);
  }
  public function error($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $data['redirect'] = $redirectto;
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Error.php', $data);
  }
  public function changepassword()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/ResetPassword');
  }
  public function submitchangepassword()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $id = $_SESSION['user_data']['ITS_ID'];
    $role = $_SESSION['user']['role'];
    $password = trim($_POST['confirm_password']);
    $flag = $this->AccountM->change_password($id, md5($password));
    if ($flag) {
      switch ($role) {
        case 0:
          redirect('/accounts/success/home');
          break;
        case 1:
          redirect('/accounts/success/admin');
          break;
        case 2:
          redirect('/accounts/success/amilsaheb');
          break;

        default:
          redirect('/accounts/logout');
          break;
      }
    } else {
      redirect('/accounts/error/logout');
    }
  }
  public function submit_raza()
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $razatypeid = $_POST['raza-type'];
    $sabil = isset($_POST['sabil']) ? $_POST['sabil'] : null;
    $fmb = isset($_POST['fmb']) ? $_POST['fmb'] : null;

    unset($_POST['sabil']);
    unset($_POST['fmb']);
    $razatype = $this->AccountM->get_razatype_byid($razatypeid)[0];
    $razafields = json_decode($razatype['fields'], true);

    $table = '';
    foreach ($razafields['fields'] as $value) {
      $result = preg_replace(
        ['/[\s]/', '/[()]/', '/[\/?]/'],
        ['-', '_', '-'],
        strtolower($value['name'])
      );
      $v = "";
      if ($value['type'] == "select" && isset($_POST[$result])) {
        $v = htmlspecialchars($value['options'][$_POST[$result]]['name']);
      } else {
        $v = isset($_POST[$result]) ? htmlspecialchars($_POST[$result]) : '';
      }
      $table .= '<tr>
                  <td align="center" style="border: 1px solid black;width: 50%;">
                    <p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: bold; font-family: Roboto, arial, sans-serif;">' . htmlspecialchars($value['name']) . '</p>
                  </td>
                  <td align="center" style="border: 1px solid black;width: 50%;">
                    <p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: normal; font-family: Roboto, arial, sans-serif;">' . $v . '</p>
                  </td>
                </tr>';
    }

    $user_data = $_SESSION['user_data'];

    $weekDateTime = date('l, j M Y, h:i:s A');

    $file_path = 'assets/email.php';
    $email_template = file_get_contents($file_path);

    $dynamic_data = array(
      'todayDate' => $weekDateTime,
      'name' => $user_data['Full_Name'],
      'its' => $user_data['ITS_ID'],
      'table' => $table,
      'razaname' => $razatype['name']
    );

    foreach ($dynamic_data as $key => $value) {
      $placeholder = '{%' . $key . '%}';
      $email_template = str_replace($placeholder, $value, $email_template);
    }

    // Send single email with BCC to all admin recipients to reduce SMTP handshakes
    $this->load->library('email');
    $to = $_SESSION['user_data']['Email'];
    $bcc = [
      'anjuman@kharjamaat.in',
      'amilsaheb@kharjamaat.in',
      '3042@carmelnmh.in',
      'kharjamaat@gmail.com',
      'kharamilsaheb@gmail.com',
      'kharjamaat786@gmail.com'
    ];

    $this->email->from('admin@kharjamaat.in', 'New Raza');
    $this->email->to($to);
    $this->email->bcc($bcc);
    $this->email->subject('New Raza');
    $this->email->message($email_template);
    $this->email->send();

    $userId = $_SESSION['user_data']['ITS_ID'];
    unset($_POST['raza-type']);
    $data = json_encode($_POST);
    if (isset($_POST["miqaat_id"])) {
      $miqaat_id = $_POST["miqaat_id"];
    } else {
      $miqaat_id = null;
    }
    $check = $this->AccountM->insert_raza($userId, $razatypeid, $data, $miqaat_id, $sabil, $fmb);
    if ($check) {
      redirect('/accounts/success/myrazarequest');
    } else {
      redirect('/accounts/error/myrazarequest');
    }
  }

  public function edit_raza($id)
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['razatype'] = $this->AccountM->get_razatype();
    $data['raza'] = $this->AccountM->get_raza_byid($id)[0];

    // Join miqaat table if miqaat_id is present
    $data['raza_miqaat'] = null;
    if (!empty($data['raza']['miqaat_id'])) {
      $data['raza_miqaat'] = $this->AccountM->get_raza_miqaat_details($data['raza']['miqaat_id'], $id);
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/MyRaza/UpdateRaza', $data);
  }
  public function forgetpassword()
  {
    $this->load->view('Home/Header');
    $this->load->view('Accounts/ForgotPassword');
  }
  public function submitForgotPassword()
  {
    if (!empty($_POST['username'])) {
      $check_user = $this->AccountM->check_user_exist($_POST['username'])[0];
      $microtime = microtime(true);
      $microseconds = (int) ($microtime * 1000000);
      $uniqueNumber = $microseconds % 1000;
      $uniqueNumber = sprintf("%03d", $uniqueNumber);
      if (!empty($check_user)) {
        $new_password = $this->AccountM->change_password_to_default($check_user['ITS_ID'], $check_user['ITS_ID'] . $uniqueNumber);
        if ($new_password) {
          $msg = 'Your Password Has been Reset.<br/> New Password is ' . $check_user['ITS_ID'] . $uniqueNumber . '.<br/> To Maintain Your Privacy Please Change Your Password After Login';
          $this->email->from('admin@kharjamaat.in', 'Admin');
          $this->email->to($check_user['Email']);
          $this->email->subject('Password Reset Request');
          $this->email->message($msg);
          $email = $this->email->send();
          $data['user_email'] = $check_user['Email'];
          if (!empty($email)) {
            $this->load->view('Accounts/PasswordResetConfirm', $data);
          }
        } else {
          echo 'Error Please Try Again Later';
        }
      } else {
        echo 'User Not Found';
      }
    }
  }
  public function loggin()
  {
    $this->load->view('Home/Header');
    $this->load->view('Accounts/Redirect');
  }
  public function redirect()
  {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $check = $this->AccountM->check_user($username, md5($password));

    $_SESSION['login_status'] = null;

    if (!empty($check)) {
      $user = $this->AccountM->get_user($username);
      $_SESSION['user'] = $check[0];
      if (!empty($user)) {
        $_SESSION['user_data'] = $user[0];
      }
      redirect($_SESSION['redirect_to_url']);
    } else {
      $_SESSION['login_status'] = true;
      redirect(base_url('/accounts/logout'));
    }
  }
  public function profile()
  {
    // Get user ID from session
    $user_id = $_SESSION['user_data']['ITS_ID'];

    // Fetch user data from the database
    $data['user_data'] = $this->AccountM->getUserData($user_id);

    // Fetch Father's data based on Father_ITS_ID
    $father_its_id = $data['user_data']['Father_ITS_ID'];
    $data['father_data'] = $this->AccountM->getFatherData($father_its_id);

    // Fetch Mother's data based on Mother_ITS_ID
    $mother_its_id = $data['user_data']['Mother_ITS_ID'];
    $data['mother_data'] = $this->AccountM->getMotherData($mother_its_id);

    // Fetch HOF_ID
    $hof_id = $data['user_data']['HOF_ID'];
    $data['hof_data'] = $this->AccountM->getHOFData($hof_id);

    // Fetch Family Members based on HOF_ID
    $hof_id = $data['user_data']['HOF_ID'];
    $data['family_members'] = $this->AccountM->getFamilyMembers($hof_id);

    $data['incharge_data'] = $this->AccountM->getInchargeDetails($user_id);

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $this->load->view('Accounts/Header', $data);
    // Load the view with inline CSS
    $this->load->view('Accounts/profile', $data);
  }
  public function appointment()
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    // Get the dates
    $data['dates'] = $this->AccountM->get_dates();
    $data['user_appointments'] = $this->AccountM->get_user_appointments($data['user_name']);


    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Appointment/Home', $data);
  }


  public function time_slots()
  {
    $date = $this->input->get('date');
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];

    $data['time_slots'] = $this->AccountM->get_available_time_slots($date);

    $data['date'] = $date;

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Appointment/TimeSlot', $data);
  }

  public function book_slot()
  {
    // Retrieve data from the URL parameters
    $slot_id = $this->input->get('slot_id');
    $time = $this->input->get('time');
    $purpose = $this->input->get('purpose');
    $details = $this->input->get('details');

    // Retrieve user_id from the session
    $user_id = $_SESSION['user']['username'];

    // Load the model

    // Retrieve ITS_ID and Full_Name from the user table based on user_id
    $user_info = $this->AccountM->get_user_info($user_id);

    if ($user_info) {
      // Store the appointment in the appointments table (include purpose and other details)
      $this->AccountM->book_slot($slot_id, $user_info->ITS_ID, $user_info->Full_Name, $purpose, $details);

      // Add any additional logic or redirects as needed
      // Send confirmation email to the user and notify amilsaheb
      try {
        $this->load->library('email');
        $user_data = $this->AccountM->getUserData($user_id);
        // Fetch slot date for inclusion in emails
        $slot_info = $this->AccountM->get_slot_info($slot_id);
        $slot_date = isset($slot_info->date) ? $slot_info->date : '';
        $user_email = isset($user_data['Email']) ? $user_data['Email'] : null;
        $user_name = $user_info->Full_Name;
        $msg = "Dear " . $user_name . ",<br/><br/>Your appointment has been booked for <b>" . htmlspecialchars($time) . "</b> on <b>" . htmlspecialchars(date("d-m-Y", strtotime($slot_date))) . "</b>.<br/>";
        if (!empty($purpose)) $msg .= "Purpose: " . htmlspecialchars($purpose) . "<br/>";
        $msg .= "<br/>";
        if (!empty($details)) $msg .= " Details: " . htmlspecialchars($details) . "<br/>";
        $msg .= "<br/>Thank you,<br/>Khar Jamaat";

        if ($user_email) {
          $this->email->from('admin@kharjamaat.in', 'Khar Jamaat - Appointment');
          $this->email->to($user_email);
          $this->email->subject('Appointment Confirmation');
          $this->email->message($msg);
          $this->email->send();
        }

        // Notify amilsaheb as well
        $this->email->from('admin@kharjamaat.in', 'Khar Jamaat - Appointment');
        $this->email->to('amilsaheb@kharjamaat.in');
        $this->email->to('kharamilsaheb@gmail.com');
        $this->email->subject('New Appointment Booked');
        $adminMsg = "Hello, <br/><br/>A new appointment has been booked by <b>" . htmlspecialchars($user_name) . "</b> for <b>" . htmlspecialchars($time) . "</b> on <b>" . htmlspecialchars(date("d-m-Y", strtotime($slot_date))) . "</b>.<br/>";
        if (!empty($purpose)) $adminMsg .= "<b>Purpose:</b> " . htmlspecialchars($purpose) . "<br/>";
        if (!empty($details)) $adminMsg .= "<b>Details:</b> " . htmlspecialchars($details) . "<br/>";
        $this->email->message($adminMsg);
        $this->email->send();
      } catch (Exception $e) {
        log_message('error', 'Error sending appointment email: ' . $e->getMessage());
      }

      redirect('accounts/appointment');
    } else {
      // Handle the case where user info is not found
      echo 'User information not found.';
    }
  }
  public function delete_appointment($appointment_id)
  {
    // Load the model

    // Delete the appointment
    $this->AccountM->delete_appointment($appointment_id);

    // Redirect back to the appointments page
    redirect('accounts/appointment');
  }

  public function chat($id, $from = null)
  {
    $data['user_name'] = $_SESSION['user']['username'] ?? "";
    if ($_SESSION['user_data'] != "") {
      $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
      $data['sector'] = $_SESSION['user_data']['Sector'];
    }

    $data['id'] = $id;
    $data['from'] = $from;

    // Fetch chat data from the model
    $data['chat'] = $this->AccountM->get_chat_by_raza_id($id);

    // Fetch status data from the model
    $data['status'] = $this->AccountM->get_status_by_raza_id($id);

    // Load views
    if (isset($from)) {
      $this->load->view("$from/Header", $data);
    } else {
      $this->load->view('Accounts/Header', $data);
    }
    $this->load->view('Accounts/Chat', $data);
  }

  public function send_message()
  {
    // Load the model
    $this->load->model('AccountM');

    // Get data from the POST request
    $raza_id = $this->input->post('raza_id');
    $user = $this->input->post('user');
    $message = $this->input->post('message');

    // Prepare data to be inserted into the database
    $data = array(
      'raza_id' => $raza_id,
      'user' => $user,
      'message' => $message
    );

    // Call the model function to insert the message into the database
    $insert_id = $this->AccountM->insert_message($data);

    // Check if the insertion was successful
    if ($insert_id) {
      // If successful, redirect back to the chat page
      redirect("accounts/chat/$raza_id");
      exit();
    } else {
      // If unsuccessful, return an error response
      $response = array('status' => 'error', 'message' => 'Failed to send message.');
      echo json_encode($response);
    }
  }

  public function deleteMessage($message_id)
  {

    $result = $this->AccountM->deleteMessage($message_id);

    // Redirect back to the chat page with a success or error message
    if ($result) {
      // Deleted successfully
      echo '<script>window.history.go(-1);</script>';
    } else {
      // Error occurred
      echo "Failed to delete message!";
    }
  }
}
