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
      // If a redirect target was saved before login, validate and use it
      $savedRedirect = isset($_SESSION['redirect_to_url']) ? $_SESSION['redirect_to_url'] : null;
      if (!empty($savedRedirect)) {
        unset($_SESSION['redirect_to_url']);
        // only allow internal paths (no scheme/host)
        $parts = parse_url($savedRedirect);
        if (empty($parts['host']) && empty($parts['scheme'])) {
          $path = ltrim($savedRedirect, '/');
          redirect(base_url($path));
        }
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
    $_SESSION['user_data'] = null;
    $_SESSION['login_status'] = null;

    if (isset($this->session)) {
      $this->session->sess_destroy();
    }
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
    $user_id = $_SESSION['user_data']['ITS_ID'] ?? $_SESSION['user']['username'];

    $data['user_data'] = $this->AccountM->getUserData($user_id);
    // Resolve HOF ITS for this login (HOF or child)
    $hof_id = $this->AccountM->get_hof_id_for_member($user_id);
    $data['hof_data'] = $hof_id;

    // Build family member list (HOF + children)
    $memberIds = [(string)$hof_id];
    $familyRows = $this->AccountM->get_all_family_member($hof_id);
    if (!empty($familyRows)) {
      foreach ($familyRows as $r) {
        if (!empty($r['ITS_ID'])) {
          $memberIds[] = (string)$r['ITS_ID'];
        }
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    // Madresa fees/dues summary (students age <= 15 from family)
    $this->load->model('MadresaM');
    $studentRows = $this->MadresaM->get_students_details($memberIds);
    $studentItsIds = [];
    if (!empty($studentRows)) {
      foreach ($studentRows as $sr) {
        if (!empty($sr['ITS_ID'])) {
          $studentItsIds[] = (string)$sr['ITS_ID'];
        }
      }
    }
    $studentItsIds = array_values(array_unique($studentItsIds));
    $data['madresa_summary'] = $this->MadresaM->get_students_financials_summary($studentItsIds);

    // Pending assigned miqaats count is tracked against HOF submission (family-wide)
    $data['assigned_miqaats_count'] = $this->AccountM->get_assigned_miqaats_count($user_id);

    $miqaats = $this->AccountM->get_all_upcoming_miqaat();
    $data["miqaats"] = $miqaats;

    $data['raza'] = $this->get_pending_raza_requests($user_id);

    $rsvp_overview = $this->AccountM->get_rsvp_overview($hof_id, $miqaats);
    if ($rsvp_overview) {
      $data["rsvp_overview"] = $this->AccountM->get_rsvp_overview($hof_id, $miqaats)[$hof_id];
    } else {
      $data["rsvp_overview"] = [];
    }

    // Family-wise dues for dashboard cards
    $family_fmb_due = 0.0;
    $family_sabeel_due = 0.0;
    $family_sabeel_cy_total = 0.0;
    $family_sabeel_cy_paid = 0.0;
    $family_sabeel_cy_due = 0.0;
    $family_sabeel_current_year = '';
    foreach ($memberIds as $mid) {
      $f = $this->AccountM->get_member_total_fmb_due($mid);
      $family_fmb_due += is_array($f) && isset($f['total_due']) ? (float)$f['total_due'] : 0.0;

      $s = $this->AccountM->get_member_total_sabeel_due($mid);
      if (is_array($s)) {
        $family_sabeel_due += (float)($s['total_due'] ?? 0);
        if ($family_sabeel_current_year === '' && !empty($s['current_year'])) {
          $family_sabeel_current_year = (string)$s['current_year'];
        }
        $family_sabeel_cy_total += (float)($s['current_year_total'] ?? 0);
        $family_sabeel_cy_paid  += (float)($s['current_year_paid'] ?? 0);
        $family_sabeel_cy_due   += (float)($s['current_year_due'] ?? max(0, (float)($s['current_year_total'] ?? 0) - (float)($s['current_year_paid'] ?? 0)));
      }
    }

    $data["fmb_takhmeen_details"] = [
      'total_due' => $family_fmb_due,
    ];
    $data["sabeel_takhmeen_details"] = [
      'total_due' => $family_sabeel_due,
      'current_year' => $family_sabeel_current_year,
      'current_year_total' => $family_sabeel_cy_total,
      'current_year_paid' => $family_sabeel_cy_paid,
      'current_year_due' => $family_sabeel_cy_due,
    ];

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

    // Ekram summary for this family (HOF): total per family, assigned, paid, outstanding
    $this->load->model('EkramFundM');
    $ekramFunds = $this->EkramFundM->get_funds();
    $ef_total_per_family = 0.0;
    $ef_assigned_total = 0.0;
    $ef_paid_total = 0.0;
    $ef_funds_count = is_array($ekramFunds) ? count($ekramFunds) : 0;
    if (!empty($ekramFunds)) {
      foreach ($ekramFunds as $f) {
        $fid = (int)($f['id'] ?? 0);
        $ef_total_per_family += (float)($f['amount'] ?? 0);
        if ($fid > 0 && !empty($hof_id)) {
          $rowA = $this->db->select('COALESCE(SUM(amount_assigned),0) AS total_assigned')
            ->from('ekram_fund_assignment')
            ->where('fund_id', $fid)
            ->where('hof_id', $hof_id)
            ->get()->row_array();
          $ef_assigned_total += isset($rowA['total_assigned']) ? (float)$rowA['total_assigned'] : 0.0;
          $rowP = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')
            ->from('ekram_fund_payment')
            ->where('fund_id', $fid)
            ->where('hof_id', $hof_id)
            ->get()->row_array();
          $ef_paid_total += isset($rowP['total_paid']) ? (float)$rowP['total_paid'] : 0.0;
        }
      }
    }
    $data['ekram_summary'] = [
      'total_per_family' => $ef_total_per_family,
      'assigned_total' => $ef_assigned_total,
      'paid_total' => $ef_paid_total,
      'outstanding' => max(0, $ef_assigned_total - $ef_paid_total),
      'funds_count' => $ef_funds_count,
    ];

    // Wajebaat summary for family (used on Home page)
    $this->load->model('WajebaatM');
    $waj_total_amount = 0.0;
    $waj_total_due = 0.0;
    $waj_last = '';
    foreach ($memberIds as $mid) {
      $row = $this->WajebaatM->get_by_its($mid);
      if (!empty($row) && is_array($row)) {
        $waj_total_amount += (float)($row['amount'] ?? 0);
        $waj_total_due += (float)($row['due'] ?? 0);
        $dt = '';
        if (!empty($row['updated_at'])) $dt = $row['updated_at'];
        elseif (!empty($row['created_at'])) $dt = $row['created_at'];
        if ($dt && ($waj_last === '' || strtotime($dt) > strtotime($waj_last))) {
          $waj_last = $dt;
        }
      }
    }
    $data['wajebaat'] = [
      'amount' => $waj_total_amount,
      'due' => $waj_total_due,
      'updated_at' => $waj_last,
    ];

    // Qardan Hasana summary for family (used on Home page)
    // Note: scheme tables store deposits/collections; a separate due table may not exist.
    $this->load->model('QardanHasanaM');
    $qh_taher_total = $this->QardanHasanaM->get_scheme_total_amount_for_its('taher', $memberIds);
    $qh_husain_total = $this->QardanHasanaM->get_scheme_total_amount_for_its('husain', $memberIds);
    $qh_due_total = 0.0;
    // Backward-compatible: if legacy table exists (some deployments), show its due.
    if ($this->db->table_exists('qardan_hasana') && $this->db->field_exists('due', 'qardan_hasana')) {
      $rowQhDue = $this->db
        ->select('COALESCE(SUM(due),0) AS total_due', false)
        ->from('qardan_hasana')
        ->where_in('ITS_ID', $memberIds)
        ->get()->row_array();
      $qh_due_total = isset($rowQhDue['total_due']) ? (float)$rowQhDue['total_due'] : 0.0;
    }
    $data['qardan_summary'] = [
      'taher' => (float)$qh_taher_total,
      'husain' => (float)$qh_husain_total,
      'total' => (float)$qh_taher_total + (float)$qh_husain_total,
      'due' => (float)$qh_due_total,
      'schemes_count' => 2,
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
    $this->load->view('Accounts/Home', $data);
  }

  public function madresa()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];

    $member_its = $_SESSION['user_data']['ITS_ID'] ?? $_SESSION['user']['username'];
    $hof_id = $this->AccountM->get_hof_id_for_member($member_its);

    // Build family member list (HOF + children)
    $memberIds = [(string)$hof_id];
    $familyRows = $this->AccountM->get_all_family_member($hof_id);
    if (!empty($familyRows)) {
      foreach ($familyRows as $r) {
        if (!empty($r['ITS_ID'])) {
          $memberIds[] = (string)$r['ITS_ID'];
        }
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    // Fetch overall fees/dues from admin-created Madresa classes (same as Madresa Classes screen)
    $this->load->model('MadresaM');
    $this->load->model('HijriCalendar');
    $todayHijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $parts = $todayHijri && isset($todayHijri['hijri_date']) ? explode('-', (string)$todayHijri['hijri_date']) : [];
    $currentHy = !empty($parts) && !empty($parts[2]) ? (int)$parts[2] : (int)date('Y');

    $data['selected_hijri_year'] = $currentHy;
    $data['madresa_classes'] = $this->MadresaM->list_classes_by_year($currentHy, false);

    $totalFees = 0.0;
    $totalDues = 0.0;
    if (!empty($data['madresa_classes'])) {
      foreach ($data['madresa_classes'] as $c) {
        $totalFees += (float)($c['amount_to_collect'] ?? 0);
        $totalDues += (float)($c['amount_due'] ?? 0);
      }
    }
    $data['madresa_totals'] = [
      'total_fees' => $totalFees,
      'total_dues' => $totalDues,
    ];

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Madresa/Home', $data);
  }

  public function qardanhasana($scheme = null)
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
      return;
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = ($_SESSION['user_data']['First_Name'] ?? '') . " " . ($_SESSION['user_data']['Surname'] ?? '');
    $data['sector'] = $_SESSION['user_data']['Sector'] ?? '';

    $memberIts = $_SESSION['user_data']['ITS_ID'] ?? $_SESSION['user']['username'];
    $memberIts = trim((string)$memberIts);

    // Build family ITS list (HOF + family members) so members can view admin-imported records tied to HOF/family.
    $familyIts = [];
    $hofId = $this->AccountM->get_hof_id_for_member($memberIts);
    if (!empty($hofId)) {
      $familyIts[] = (string)$hofId;
      $familyRows = $this->AccountM->get_all_family_member($hofId);
      if (!empty($familyRows) && is_array($familyRows)) {
        foreach ($familyRows as $r) {
          if (!empty($r['ITS_ID'])) {
            $familyIts[] = (string)$r['ITS_ID'];
          }
        }
      }
    } else {
      $familyIts[] = $memberIts;
    }
    $familyIts = array_values(array_unique(array_filter(array_map('trim', $familyIts))));

    // Optional narrowing: if user selects a specific ITS, use it only if it is within family list.
    $itsNarrow = trim((string)$this->input->get('its'));
    if ($itsNarrow !== '' && !in_array($itsNarrow, $familyIts, true)) {
      $itsNarrow = '';
    }

    $data['qh_prefix'] = 'accounts';
    $data['can_manage'] = false;
    $data['can_import'] = false;

    $allowedSchemes = ['taher', 'husain'];

    if ($scheme === null || trim((string)$scheme) === '') {
      $data['qh_schemes'] = $allowedSchemes;

      $this->load->model('QardanHasanaM');
      $data['qh_scheme_totals'] = [
        'taher' => $this->QardanHasanaM->get_scheme_total_amount_for_its('taher', $familyIts),
        'husain' => $this->QardanHasanaM->get_scheme_total_amount_for_its('husain', $familyIts),
      ];
      $data['qh_total_all'] = (float)($data['qh_scheme_totals']['taher'] ?? 0) + (float)($data['qh_scheme_totals']['husain'] ?? 0);

      $this->load->view('Accounts/Header', $data);
      $this->load->view('Admin/QardanHasana', $data);
      return;
    }

    $scheme = strtolower(trim((string)$scheme));
    if (!in_array($scheme, $allowedSchemes, true)) {
      redirect('accounts/qardanhasana');
      return;
    }

    $this->load->model('QardanHasanaM');

    // Miqaat list for filters (mirrors Admin)
    $data['miqaats'] = $this->db
      ->select('m.id, m.name, m.date, hc.hijri_date')
      ->from('miqaat m')
      ->join('hijri_calendar hc', 'hc.greg_date = m.date', 'left')
      ->order_by('m.date', 'DESC')
      ->get()->result_array();

    // Resolve miqaat_id -> miqaat_name (scheme tables store miqaat_name as text)
    $miqaatNameFilter = '';
    $miqaatId = trim((string)$this->input->get('miqaat_id'));
    if ($miqaatId !== '') {
      $row = $this->db->select('name')->from('miqaat')->where('id', (int)$miqaatId)->get()->row_array();
      $miqaatNameFilter = isset($row['name']) ? (string)$row['name'] : '';
    }

    $data['scheme_key'] = $scheme;
    $data['scheme_title'] = ucfirst($scheme) . ' Scheme';
    $data['filters'] = [
      'miqaat_id' => $this->input->get('miqaat_id'),
      'hijri_date' => $this->input->get('hijri_date'),
      'greg_date' => $this->input->get('greg_date'),
      'deposit_date' => $this->input->get('deposit_date'),
      'maturity_date' => $this->input->get('maturity_date'),
      'duration' => $this->input->get('duration'),
      'its' => $itsNarrow,
      'member_name' => ''
    ];

    $data['records'] = [];
    $data['total_amount'] = 0.0;

    if ($scheme === 'mohammedi') {
      $data['records'] = $this->QardanHasanaM->get_mohammedi_records([
        'miqaat_name' => $miqaatNameFilter,
        'hijri_date' => trim((string)$this->input->get('hijri_date')),
        'eng_date' => trim((string)$this->input->get('greg_date')),
      ]);
    } elseif ($scheme === 'taher') {
      $data['records'] = $this->QardanHasanaM->get_taher_records([
        'miqaat_name' => $miqaatNameFilter,
        'ITS' => ($itsNarrow !== '' ? $itsNarrow : $familyIts),
        'member_name' => ''
      ], 500);
    } elseif ($scheme === 'husain') {
      $depositDate = trim((string)$this->input->get('deposit_date'));
      if ($depositDate === '') {
        $depositDate = trim((string)$this->input->get('greg_date'));
      }
      $data['records'] = $this->QardanHasanaM->get_husain_records([
        'deposit_date' => $depositDate,
        'maturity_date' => trim((string)$this->input->get('maturity_date')),
        'duration' => trim((string)$this->input->get('duration')),
        'ITS' => ($itsNarrow !== '' ? $itsNarrow : $familyIts),
        'member_name' => ''
      ], 500);
    }

    // Total amount for header display (sum of fetched records)
    $total = 0.0;
    if (!empty($data['records']) && is_array($data['records'])) {
      foreach ($data['records'] as $row) {
        if ($scheme === 'husain') {
          $total += (float)($row['amount'] ?? 0);
        } else {
          if (isset($row['collection_amount'])) {
            $total += (float)$row['collection_amount'];
          } else {
            $unit = (float)($row['unit'] ?? 0);
            $units = (int)($row['units'] ?? 0);
            if ($unit > 0 && $units > 0) {
              $total += $unit * $units;
            }
          }
        }
      }
    }
    $data['total_amount'] = $total;

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Admin/QardanHasanaScheme', $data);
  }

  public function madresa_payment_history($classId = null)
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];

    $classId = (int)$classId;
    if ($classId <= 0) {
      redirect('/accounts/madresa');
    }

    $this->load->model('MadresaM');
    $class = $this->MadresaM->get_class($classId);
    if (empty($class)) {
      show_404();
    }

    $financials = $this->MadresaM->get_class_financials($classId);
    $payments = $this->MadresaM->list_class_payments($classId);

    $totalPaid = 0.0;
    if (!empty($payments)) {
      foreach ($payments as $p) {
        $totalPaid += (float)($p['amount'] ?? 0);
      }
    }

    $data['madresa_class'] = $class;
    $data['madresa_financials'] = $financials;
    $data['madresa_payments'] = $payments;
    $data['madresa_total_paid'] = $totalPaid;

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Madresa/PaymentHistory', $data);
  }

  public function wajebaat()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $this->load->model('AccountM');
    $this->load->model('WajebaatM');
    $username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';
    $data['user_name'] = $username;

    $member_its = $_SESSION['user_data']['ITS_ID'] ?? $username;
    $hof_id = $this->AccountM->get_hof_id_for_member($member_its);
    $memberIds = [(string)$hof_id];
    $family = $this->AccountM->get_all_family_member($hof_id);
    if (!empty($family)) {
      foreach ($family as $f) {
        if (!empty($f['ITS_ID'])) {
          $memberIds[] = (string)$f['ITS_ID'];
        }
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    $total_amount = 0.0;
    $total_due = 0.0;
    $lastUpdated = '';
    foreach ($memberIds as $mid) {
      $row = $this->WajebaatM->get_by_its($mid);
      if (!empty($row) && is_array($row)) {
        $total_amount += (float)($row['amount'] ?? 0);
        $total_due += (float)($row['due'] ?? 0);
        foreach (['updated_at', 'updated_on', 'last_updated', 'modified_at', 'modified_on', 'created_at', 'created_on', 'created_date', 'date', 'created'] as $k) {
          if (!empty($row[$k])) {
            $dt = (string)$row[$k];
            if ($lastUpdated === '' || strtotime($dt) > strtotime($lastUpdated)) {
              $lastUpdated = $dt;
            }
            break;
          }
        }
      }
    }

    $data['wajebaat'] = [
      'amount' => $total_amount,
      'due' => $total_due,
      'updated_at' => $lastUpdated,
    ];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Wajebaat', $data);
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

      // family-wide raza (so button/status is same for all family members)
      $data["miqaats"][$key]["raza"] = $this->AccountM->get_family_raza_by_miqaat($miqaat["id"], $user_id);

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

    $member_id = $_SESSION['user_data']['ITS_ID'];
    // Submit under the ITS of the assignee (within this member's family)
    $user_id = $this->AccountM->get_assignee_its_for_miqaat_in_family($miqaat_id, $member_id);

    // If any family member already submitted for this miqaat, block further submissions
    $existing_family_raza = $this->AccountM->get_family_raza_by_miqaat($miqaat_id, $member_id);
    if (!empty($existing_family_raza)) {
      $msg = 'Miqaat Raza is already submitted';
      $msg .= '.';
      $this->session->set_flashdata('warning', $msg);
      redirect('accounts/assigned_miqaats');
      return;
    }

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
      // enqueue notifications for admins about this miqaat raza submission
      $this->load->model('NotificationM');
      $this->load->helper('email_template');
      $user_row = $this->AccountM->getUserData($user_id);
      $user_full = !empty($user_row['Full_Name']) ? $user_row['Full_Name'] : $user_id;

      // prepare miqaat details for inclusion in emails
      $miqaatNameRaw = '';
      $miqaatPublicId = '';
      $miqaatType = '';
      $miqaatDate = '';
      $assignmentLabel = '';
      $assignmentGroupName = '';
      if (!empty($miqaat_id)) {
        $miqaatRow = $this->AccountM->get_miqaat_by_id($miqaat_id);
        if (!empty($miqaatRow)) {
          $miqaatNameRaw = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
          $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$miqaat_id;
          $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
          $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
        }

        $ass = $this->AccountM->get_miqaat_assignment_for_member($miqaat_id, $user_id);
        if (!empty($ass)) {
          $assignmentLabel = isset($ass['assign_type']) ? (string)$ass['assign_type'] : '';
          $assignmentGroupName = isset($ass['group_name']) ? (string)$ass['group_name'] : '';

          $al = strtolower(trim($assignmentLabel));
          if ($al === 'group') {
            $assignmentLabel = 'Group';
          } elseif ($al === 'individual') {
            $assignmentLabel = 'Individual';
          }
        }
      }

      $miqaatNameEsc = htmlspecialchars($miqaatNameRaw);
      $miqaatInfo = '';
      if ($miqaatNameRaw !== '') {
        $miqaatInfo = $miqaatNameEsc . ($miqaatDate !== '' ? (' (' . htmlspecialchars($miqaatDate) . ')') : '');
      }

      $admins = [
        'amilsaheb@kharjamaat.in',
        '3042@carmelnmh.in',
        'kharjamaat@gmail.com',
        'kharamilsaheb@gmail.com',
        'kharjamaat786@gmail.com',
        'khozemtopiwalla@gmail.com',
        'ybookwala@gmail.com'
      ];

      // Admin notification: include submitter, raza id and miqaat details
      $adminSubject = 'New Miqaat Raza submitted by ' . $user_full;
      $adminDetails = [
        'Submitted By' => $user_full,
        'Raza ID' => (string)$raza_id,
      ];
      if ($miqaatNameRaw !== '') $adminDetails['Miqaat'] = $miqaatNameRaw;
      if ($miqaatPublicId !== '') $adminDetails['Miqaat ID'] = $miqaatPublicId;
      if ($miqaatType !== '') $adminDetails['Type'] = $miqaatType;
      if ($miqaatDate !== '') $adminDetails['Date'] = $miqaatDate;
      if ($assignmentLabel !== '') $adminDetails['Assignment'] = $assignmentLabel;
      if ($assignmentGroupName !== '') $adminDetails['Group'] = $assignmentGroupName;

      $adminBody = render_generic_email_html([
        'title' => $adminSubject,
        'todayDate' => date('l, j M Y, h:i:s A'),
        'greeting' => 'Baad Afzalus Salaam,',
        'cardTitle' => '',
        'details' => $adminDetails,
        'body' => 'View submissions in the admin panel.',
        'ctaUrl' => base_url('admin'),
        'ctaText' => 'Login to your account',
      ]);
      foreach ($admins as $a) {
        $this->NotificationM->insert_notification([
          'channel' => 'email',
          'recipient' => $a,
          'recipient_type' => 'admin',
          'subject' => $adminSubject,
          'body' => $adminBody,
          'scheduled_at' => null
        ]);
      }

      // Member notification: acknowledgement with miqaat details and raza id
      $userEmail = isset($user_row['Email']) ? $user_row['Email'] : null;
      if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $memberSubject = 'Your Miqaat Raza has been submitted' . ($miqaatNameRaw !== '' ? (' - ' . $miqaatNameRaw) : '');
        $assigned_link = base_url('accounts/assigned_miqaats');

        $memberDetails = [
          'Raza ID' => (string)$raza_id,
        ];
        if ($miqaatNameRaw !== '') $memberDetails['Miqaat'] = $miqaatNameRaw;
        if ($miqaatPublicId !== '') $memberDetails['Miqaat ID'] = $miqaatPublicId;
        if ($miqaatType !== '') $memberDetails['Type'] = $miqaatType;
        if ($miqaatDate !== '') $memberDetails['Date'] = $miqaatDate;
        if ($assignmentLabel !== '') $memberDetails['Assignment'] = $assignmentLabel;
        if ($assignmentGroupName !== '') $memberDetails['Group'] = $assignmentGroupName;

        $memberBody = render_generic_email_html([
          'title' => $memberSubject,
          'todayDate' => date('l, j M Y, h:i:s A'),
          'greeting' => 'Baad Afzalus Salaam,',
          'name' => $user_full,
          'its' => (string)$user_id,
          'cardTitle' => '',
          'details' => $memberDetails,
          'body' => 'You can view your submission in the Assigned Miqaats page. To view your assigned miqaats and submit a Raza, please visit: <a href="' . $assigned_link . '">Assigned Miqaats</a>.',
          'ctaUrl' => $assigned_link,
          'ctaText' => 'Assigned Miqaats',
        ]);
        $this->NotificationM->insert_notification([
          'channel' => 'email',
          'recipient' => $userEmail,
          'recipient_type' => 'user',
          'subject' => $memberSubject,
          'body' => $memberBody,
          'scheduled_at' => null
        ]);
      }
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
      $qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
      $_SESSION['redirect_to_url'] = 'accounts/rsvp_list' . $qs;
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

  /**
   * AJAX endpoint: search upcoming miqaats and return rendered cards
   */
  public function rsvp_search()
  {
    if (empty($_SESSION['user'])) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
      return;
    }
    $q = $this->input->get('q', true);

    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    $data['user_data'] = $this->AccountM->getUserData($_SESSION['user']['username']);
    $hof_id = $data['user_data']['HOF_ID'];

    $miqaats = $this->AccountM->search_upcoming_miqaat($q);
    foreach ($miqaats as $key => $miqaat) {
      $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($miqaat["date"])));
      $hijri_month = $this->HijriCalendar->hijri_month_name($hijri_date["hijri_month_id"])['hijri_month'];
      $miqaats[$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];
      $miqaats[$key]["raza_status"] = $this->AccountM->get_miqaat_raza_status($miqaat["id"]);
    }

    $rsvp_overview = $this->AccountM->get_rsvp_overview($hof_id, $miqaats);
    if (!empty($rsvp_overview) && isset($rsvp_overview[$hof_id])) {
      $data['rsvp_overview'] = $rsvp_overview[$hof_id];
    } else {
      $data['rsvp_overview'] = [];
    }

    $data['miqaats'] = $miqaats;
    $html = $this->load->view('Accounts/RSVP/_miqaat_cards', $data, true);

    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'html' => $html]));
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
  public function ekramfunds()
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

    $this->load->model('EkramFundM');
    $rows = $this->EkramFundM->get_assignments_with_payments_by_hof($hof_id);

    // Ensure rows are ordered by year descending for display (prefer numeric hijri_year)
    if (!empty($rows) && is_array($rows)) {
      usort($rows, function ($a, $b) {
        $getYear = function ($r) {
          if (!empty($r['hijri_year']) && is_numeric($r['hijri_year'])) return (int)$r['hijri_year'];
          // try to extract year-like number from title
          if (!empty($r['title']) && preg_match('/(\d{3,4})/', $r['title'], $m)) return (int)$m[1];
          return null;
        };
        $ya = $getYear($a);
        $yb = $getYear($b);
        if ($ya !== null && $yb !== null) return $yb <=> $ya; // both numeric: desc
        if ($ya !== null) return -1; // numeric should come before non-numeric
        if ($yb !== null) return 1;
        // fallback alphabetical desc by title
        $ta = isset($a['title']) ? (string)$a['title'] : '';
        $tb = isset($b['title']) ? (string)$b['title'] : '';
        return strcasecmp($tb, $ta);
      });
    }

    // Totals: compute from assignments/payments tables to ensure accurate aggregates
    $tot_assigned_row = $this->db->select('COALESCE(SUM(amount_assigned),0) AS total_assigned')
      ->from('ekram_fund_assignment')
      ->where('hof_id', $hof_id)
      ->get()->row_array();
    $tot_paid_row = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')
      ->from('ekram_fund_payment')
      ->where('hof_id', $hof_id)
      ->get()->row_array();
    $tot_assigned = isset($tot_assigned_row['total_assigned']) ? (float)$tot_assigned_row['total_assigned'] : 0.0;
    $tot_paid = isset($tot_paid_row['total_paid']) ? (float)$tot_paid_row['total_paid'] : 0.0;
    $tot_due = max(0, $tot_assigned - $tot_paid);
    $data['ekram_details'] = [
      'rows' => $rows,
      'tot_assigned' => $tot_assigned,
      'tot_paid' => $tot_paid,
      'tot_due' => $tot_due,
    ];

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/EkramFundsDetails', $data);
  }
  // wajebaat dashboard logic removed

  public function general_rsvp($miqaat_id)
  {
    if (empty($_SESSION['user'])) {
      $qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
      $_SESSION['redirect_to_url'] = 'accounts/general_rsvp/' . $miqaat_id . $qs;
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
      // Save intended URL and send to login
      $qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
      $_SESSION['redirect_to_url'] = 'accounts/fmbweeklysignup' . $qs;
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
      $qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
      $_SESSION['redirect_to_url'] = 'accounts/FMBFeedback' . $qs;
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
      $_SESSION['redirect_to_url'] = 'accounts/FMBFeedback';
      redirect('/accounts');
    }

    $fwsid = $this->input->post("fwsid");
    $get_feedback_data = $this->AccountM->get_feedback_data($fwsid);
    echo json_encode($get_feedback_data);
  }

  public function UpdateFMBFeedback()
  {
    if (empty($_SESSION['user'])) {
      $_SESSION['redirect_to_url'] = 'accounts/FMBFeedback';
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
    $member_id = $_SESSION['user_data']['ITS_ID'];

    // Family-wise: HOF + all members linked to HOF
    $hof_id = $this->AccountM->get_hof_id_for_member($member_id);
    $memberIds = [$hof_id];
    $family = $this->AccountM->get_all_family_member($hof_id);
    if (!empty($family)) {
      foreach ($family as $f) {
        if (!empty($f['ITS_ID'])) {
          $memberIds[] = (int)$f['ITS_ID'];
        }
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    // Aggregate per-member FMB takhmeen details into a family-wide structure matching the view contract.
    $overall_amount = 0.0;
    $overall_paid = 0.0;
    $overall_excess = 0.0;
    $current_year_amount = 0.0;
    $current_year_paid = 0.0;
    $current_year_excess = 0.0;
    $current_year_label = null;
    $current_hijri_year = null;
    $all_takhmeen_by_year = [];
    $all_payments = [];
    $general_contributions = [];

    foreach ($memberIds as $mid) {
      $details = $this->AccountM->viewfmbtakhmeen($mid);
      if (!is_array($details) || empty($details)) {
        continue;
      }

      $oa = (float)($details['overall']['total_amount'] ?? 0);
      $op = (float)($details['overall']['total_paid'] ?? 0);
      $overall_amount += $oa;
      $overall_paid += min($op, $oa);
      $overall_excess += (float)($details['overall']['excess_paid'] ?? 0);

      if (isset($details['current_hijri_year']) && $current_hijri_year === null) {
        $current_hijri_year = $details['current_hijri_year'];
      }

      if (isset($details['current_year']) && is_array($details['current_year']) && !empty($details['current_year']['year'])) {
        if ($current_year_label === null) {
          $current_year_label = $details['current_year']['year'];
        }
        $cya = (float)($details['current_year']['total_amount'] ?? 0);
        $cyp = (float)($details['current_year']['total_paid'] ?? 0);
        $current_year_amount += $cya;
        $current_year_paid += min($cyp, $cya);
        $current_year_excess += (float)($details['current_year']['excess_paid'] ?? 0);
      }

      if (!empty($details['all_takhmeen']) && is_array($details['all_takhmeen'])) {
        foreach ($details['all_takhmeen'] as $tr) {
          $y = $tr['year'] ?? null;
          if ($y === null || $y === '') {
            continue;
          }
          if (!isset($all_takhmeen_by_year[$y])) {
            $all_takhmeen_by_year[$y] = 0.0;
          }
          $all_takhmeen_by_year[$y] += (float)($tr['total_amount'] ?? 0);
        }
      }

      if (!empty($details['all_payments']) && is_array($details['all_payments'])) {
        foreach ($details['all_payments'] as $pr) {
          $pr['user_id'] = $mid;
          $all_payments[] = $pr;
        }
      }

      if (!empty($details['general_contributions']) && is_array($details['general_contributions'])) {
        foreach ($details['general_contributions'] as $gcr) {
          $gcr['user_id'] = $mid;
          $general_contributions[] = $gcr;
        }
      }
    }

    // Build all_takhmeen list in DESC year order
    $all_takhmeen = [];
    if (!empty($all_takhmeen_by_year)) {
      krsort($all_takhmeen_by_year);
      foreach ($all_takhmeen_by_year as $y => $amt) {
        $all_takhmeen[] = ['year' => $y, 'total_amount' => $amt];
      }
    }
    $latest = !empty($all_takhmeen) ? $all_takhmeen[0] : null;

    // Normalize overall & current-year dues (avoid negative)
    $overall_due = $overall_amount - $overall_paid;
    if ($overall_due < 0) $overall_due = 0.0;
    $overall = [
      'total_amount' => $overall_amount,
      'total_paid' => $overall_paid,
      'total_due' => $overall_due,
    ];
    if ($overall_excess > 0) {
      $overall['excess_paid'] = $overall_excess;
    }

    $current_year = null;
    if ($current_year_label !== null) {
      $cy_due = $current_year_amount - $current_year_paid;
      if ($cy_due < 0) $cy_due = 0.0;
      $current_year = [
        'year' => $current_year_label,
        'total_amount' => $current_year_amount,
        'total_paid' => $current_year_paid,
        'total_due' => $cy_due,
      ];
      if ($current_year_excess > 0) {
        $current_year['excess_paid'] = $current_year_excess;
      }
    }

    $data['fmb_takhmeen_details'] = [
      'all_takhmeen' => $all_takhmeen,
      'all_payments' => $all_payments,
      'latest' => $latest,
      'overall' => $overall,
      'current_year' => $current_year,
      'current_hijri_year' => $current_hijri_year,
      'general_contributions' => $general_contributions,
    ];

    // Family-wise Miqaat invoices listing
    $miqaat_invoices = [];
    foreach ($memberIds as $mid) {
      $inv = $this->AccountM->get_user_miqaat_invoices($mid);
      if (!empty($inv)) {
        foreach ($inv as $r) {
          $r['user_id'] = $mid;
          $miqaat_invoices[] = $r;
        }
      }
    }
    if (!empty($miqaat_invoices)) {
      usort($miqaat_invoices, function ($a, $b) {
        $ad = $a['invoice_date'] ?? '';
        $bd = $b['invoice_date'] ?? '';
        if ($ad === $bd) {
          return ((int)($b['id'] ?? 0)) <=> ((int)($a['id'] ?? 0));
        }
        return strcmp((string)$bd, (string)$ad);
      });
    }
    $data['miqaat_invoices'] = $miqaat_invoices;

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
    $member_id = $_SESSION['user_data']['ITS_ID'];
    $hof_id = $this->AccountM->get_hof_id_for_member($member_id);
    $memberIds = [$hof_id];
    $family = $this->AccountM->get_all_family_member($hof_id);
    if (!empty($family)) {
      foreach ($family as $f) {
        if (!empty($f['ITS_ID'])) $memberIds[] = (int)$f['ITS_ID'];
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    $res = ['invoice' => null, 'payments' => []];
    foreach ($memberIds as $mid) {
      $try = $this->AccountM->get_user_miqaat_invoice_history($mid, $invoice_id);
      if (!empty($try['invoice'])) {
        $res = $try;
        break;
      }
    }

    if (empty($res['invoice'])) {
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
    $member_id = $_SESSION['user_data']['ITS_ID'];
    $hof_id = $this->AccountM->get_hof_id_for_member($member_id);
    $memberIds = [$hof_id];
    $family = $this->AccountM->get_all_family_member($hof_id);
    if (!empty($family)) {
      foreach ($family as $f) {
        if (!empty($f['ITS_ID'])) $memberIds[] = (int)$f['ITS_ID'];
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    $res = null;
    foreach ($memberIds as $mid) {
      $try = $this->AccountM->get_user_gc_payment_history($mid, $fmbgc_id);
      if (!empty($try['success'])) {
        $res = $try;
        break;
      }
    }
    if ($res === null) {
      $res = ['success' => false, 'message' => 'Invoice not found'];
    }
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
    $member_id = $_SESSION['user_data']['ITS_ID'];

    // Family-wise: HOF + all members linked to HOF
    $hof_id = $this->AccountM->get_hof_id_for_member($member_id);
    $memberIds = [$hof_id];
    $family = $this->AccountM->get_all_family_member($hof_id);
    if (!empty($family)) {
      foreach ($family as $f) {
        if (!empty($f['ITS_ID'])) $memberIds[] = (int)$f['ITS_ID'];
      }
    }
    $memberIds = array_values(array_unique($memberIds));

    // Aggregate Sabeel details per year to preserve existing view calculations (current-year card expects one row per year).
    $e_by_year = [];
    $r_by_year = [];
    $all_payments = [];
    $est_total = 0.0;
    $res_total = 0.0;
    $est_paid = 0.0;
    $res_paid = 0.0;

    foreach ($memberIds as $mid) {
      $details = $this->AccountM->viewSabeelTakhmeen($mid);
      if (!is_array($details) || empty($details)) {
        continue;
      }

      $ov = isset($details['overall']) && is_array($details['overall']) ? $details['overall'] : [];
      $est_total += (float)($ov['establishment_total'] ?? 0);
      $res_total += (float)($ov['residential_total'] ?? 0);
      $est_paid  += (float)($ov['establishment_paid'] ?? 0);
      $res_paid  += (float)($ov['residential_paid'] ?? 0);

      if (!empty($details['e_takhmeen']) && is_array($details['e_takhmeen'])) {
        foreach ($details['e_takhmeen'] as $row) {
          $y = $row['year'] ?? null;
          if ($y === null || $y === '') continue;
          if (!isset($e_by_year[$y])) {
            $e_by_year[$y] = ['year' => $y, 'grade' => '—', 'total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
          }
          $e_by_year[$y]['total'] += (float)($row['total'] ?? 0);
          $e_by_year[$y]['paid']  += (float)($row['paid'] ?? 0);
          $e_by_year[$y]['due']   += (float)($row['due'] ?? max(0, ((float)($row['total'] ?? 0)) - (float)($row['paid'] ?? 0)));
        }
      }

      if (!empty($details['r_takhmeen']) && is_array($details['r_takhmeen'])) {
        foreach ($details['r_takhmeen'] as $row) {
          $y = $row['year'] ?? null;
          if ($y === null || $y === '') continue;
          if (!isset($r_by_year[$y])) {
            $r_by_year[$y] = ['year' => $y, 'grade' => '—', 'total' => 0.0, 'paid' => 0.0, 'due' => 0.0];
          }
          $r_by_year[$y]['total'] += (float)($row['total'] ?? 0);
          $r_by_year[$y]['paid']  += (float)($row['paid'] ?? 0);
          $r_by_year[$y]['due']   += (float)($row['due'] ?? max(0, ((float)($row['total'] ?? 0)) - (float)($row['paid'] ?? 0)));
        }
      }

      if (!empty($details['all_payments']) && is_array($details['all_payments'])) {
        foreach ($details['all_payments'] as $pr) {
          $pr['user_id'] = $mid;
          $all_payments[] = $pr;
        }
      }
    }

    $est_paid_capped = min($est_paid, $est_total);
    $res_paid_capped = min($res_paid, $res_total);
    $overall = [
      'establishment_total' => $est_total,
      'residential_total' => $res_total,
      'total_amount' => $est_total + $res_total,
      'establishment_paid' => $est_paid_capped,
      'residential_paid' => $res_paid_capped,
      'total_paid' => $est_paid_capped + $res_paid_capped,
    ];
    $overall['establishment_due'] = max(0, $overall['establishment_total'] - $overall['establishment_paid']);
    $overall['residential_due'] = max(0, $overall['residential_total'] - $overall['residential_paid']);
    $overall['total_due'] = max(0, $overall['total_amount'] - $overall['total_paid']);

    // Convert year maps to latest-first arrays
    if (!empty($e_by_year)) {
      uksort($e_by_year, function ($a, $b) {
        return strcmp((string)$b, (string)$a);
      });
    }
    if (!empty($r_by_year)) {
      uksort($r_by_year, function ($a, $b) {
        return strcmp((string)$b, (string)$a);
      });
    }

    $data['sabeel_takhmeen_details'] = [
      'all_takhmeen' => [],
      'e_takhmeen' => array_values($e_by_year),
      'r_takhmeen' => array_values($r_by_year),
      'all_payments' => $all_payments,
      'latest' => null,
      'overall' => $overall,
    ];

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

  /**
   * AJAX: return member financial dues as JSON for current logged-in user
   */
  public function get_member_dues()
  {
    if (empty($_SESSION['user'])) {
      return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
    }
    $this->load->model('AccountM');
    $this->load->model('CorpusFundM');
    $this->load->model('EkramFundM');

    // current user identifier (prefer ITS_ID from session user_data)
    $user_id = $_SESSION['user_data']['ITS_ID'] ?? $_SESSION['user']['username'];

    // Build family member list (self + HOF family members)
    $memberIds = [$user_id];
    if (!empty($_SESSION['user_data']['HOF_ID'])) {
      $hof = $_SESSION['user_data']['HOF_ID'];
      $rows = $this->db->query("SELECT ITS_ID FROM user WHERE HOF_ID = ?", [$hof])->result_array();
      foreach ($rows as $r) {
        if (!empty($r['ITS_ID'])) $memberIds[] = (string)$r['ITS_ID'];
      }
      if (!in_array((string)$hof, $memberIds, true)) $memberIds[] = (string)$hof;
    }
    $memberIds = array_values(array_unique($memberIds));

    // Family-level FMB and Sabeel dues
    $family_fmb = 0.0;
    $family_sabeel = 0.0;
    foreach ($memberIds as $m) {
      $f = $this->AccountM->get_member_total_fmb_due($m);
      $family_fmb += is_array($f) && isset($f['total_due']) ? (float)$f['total_due'] : 0.0;
      $s = $this->AccountM->get_member_total_sabeel_due($m);
      $family_sabeel += is_array($s) && isset($s['total_due']) ? (float)$s['total_due'] : 0.0;
    }

    // General contributions (GC) for family
    $gc_due = 0.0;
    if (!empty($memberIds)) {
      $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
      $gcRow = $this->db->query("SELECT COALESCE(SUM(gc.amount - COALESCE(p.total_received,0)),0) AS total_due FROM fmb_general_contribution gc LEFT JOIN (SELECT fmbgc_id, SUM(amount) AS total_received FROM fmb_general_contribution_payments GROUP BY fmbgc_id) p ON p.fmbgc_id = gc.id WHERE gc.user_id IN ($placeholders)", $memberIds)->row_array();
      $gc_due = isset($gcRow['total_due']) ? (float)$gcRow['total_due'] : 0.0;
    }

    // Miqaat invoices due for family
    $miq_due = 0.0;
    $miq_list = [];
    if (!empty($memberIds)) {
      $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
      $sql = "SELECT i.id, i.miqaat_id, m.name AS miqaat_name, i.date AS invoice_date, i.amount, i.description, i.user_id, u.Full_Name as owner_name, COALESCE(SUM(p.amount),0) AS paid_amount, (i.amount - COALESCE(SUM(p.amount),0)) AS due_amount
              FROM miqaat_invoice i
              LEFT JOIN miqaat_payment p ON p.miqaat_invoice_id = i.id
              LEFT JOIN miqaat m ON m.id = i.miqaat_id
              LEFT JOIN user u ON u.ITS_ID = i.user_id
              WHERE i.user_id IN ($placeholders)
              GROUP BY i.id, i.miqaat_id, m.name, i.date, i.amount, i.description, i.user_id, u.Full_Name
              ORDER BY i.date DESC, i.id DESC";
      $miq_rows = $this->db->query($sql, $memberIds)->result_array();
      foreach ($miq_rows as $mi) {
        $miq_due += (float)($mi['due_amount'] ?? 0);
        $miq_list[] = $mi;
      }
    }

    // Corpus fund outstanding for this HOF (use hof_id from user_data if available)
    $hof_id = null;
    if (isset($_SESSION['user_data']['HOF_ID'])) $hof_id = $_SESSION['user_data']['HOF_ID'];
    $corpus_due = 0.0;
    if (!empty($hof_id)) {
      $assigns = $this->CorpusFundM->get_assignments_with_payments_by_hof($hof_id);
      if (is_array($assigns)) {
        foreach ($assigns as $a) {
          $assigned = (float)($a['amount_assigned'] ?? 0);
          $paid = (float)($a['amount_paid'] ?? 0);
          $corpus_due += max(0, $assigned - $paid);
        }
      }
    }

    // Ekram fund outstanding for this HOF
    $ekram_due = 0.0;
    if (!empty($hof_id)) {
      $eassigns = $this->EkramFundM->get_assignments_with_payments_by_hof($hof_id);
      if (is_array($eassigns)) {
        foreach ($eassigns as $ea) {
          $assigned = (float)($ea['amount_assigned'] ?? 0);
          $paid = (float)($ea['amount_paid'] ?? 0);
          $ekram_due += max(0, $assigned - $paid);
        }
      }
    }


    // Wajebaat outstanding for family
    $wajebaat_due = 0.0;
    $this->load->model('WajebaatM');
    foreach ($memberIds as $m) {
      $waj_row = $this->WajebaatM->get_by_its($m);
      if (!empty($waj_row) && is_array($waj_row)) {
        $wajebaat_due += (float)($waj_row['due'] ?? 0);
      }
    }

    $total_due = $family_fmb + $family_sabeel + $gc_due + $miq_due + $corpus_due + $ekram_due + $wajebaat_due;

    $payload = [
      'success' => true,
      'dues' => [
        'fmb_due' => round($family_fmb, 2),
        'sabeel_due' => round($family_sabeel, 2),
        'gc_due' => round($gc_due, 2),
        'miqaat_due' => round($miq_due, 2),
        'corpus_due' => round($corpus_due, 2),
        'ekram_due' => round($ekram_due, 2),
        'wajebaat_due' => round($wajebaat_due, 2),
        'total_due' => round($total_due, 2)
      ],
      'miqaat_invoices' => isset($miq_list) ? $miq_list : []
    ];

    return $this->output->set_content_type('application/json')->set_output(json_encode($payload));
  }

  /**
   * Send dues notification emails to members related to current user (HOF family + self)
   * Sends per-member miqaat invoice dues list if any.
   */
  public function send_dues_email()
  {
    if (empty($_SESSION['user'])) {
      return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
    }
    $this->load->model('AccountM');
    $this->load->model('CorpusFundM');
    $this->load->model('EkramFundM');

    $user_id = $_SESSION['user_data']['ITS_ID'] ?? $_SESSION['user']['username'];

    // Build member list (self + family by HOF)
    $memberIds = [$user_id];
    if (!empty($_SESSION['user_data']['HOF_ID'])) {
      $hof = $_SESSION['user_data']['HOF_ID'];
      $rows = $this->db->query("SELECT ITS_ID, Email, Full_Name FROM user WHERE HOF_ID = ?", [$hof])->result_array();
      foreach ($rows as $r) {
        if (!empty($r['ITS_ID'])) $memberIds[] = (string)$r['ITS_ID'];
      }
      if (!in_array((string)$hof, $memberIds, true)) $memberIds[] = (string)$hof;
    }
    $memberIds = array_values(array_unique($memberIds));

    // Aggregate family-level dues
    $family_fmb = 0.0;
    $family_sabeel = 0.0;
    foreach ($memberIds as $m) {
      $f = $this->AccountM->get_member_total_fmb_due($m);
      $family_fmb += is_array($f) && isset($f['total_due']) ? (float)$f['total_due'] : 0.0;
      $s = $this->AccountM->get_member_total_sabeel_due($m);
      $family_sabeel += is_array($s) && isset($s['total_due']) ? (float)$s['total_due'] : 0.0;
    }

    // General contributions for family
    $gc_due = 0.0;
    if (!empty($memberIds)) {
      $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
      $gcRow = $this->db->query("SELECT COALESCE(SUM(gc.amount - COALESCE(p.total_received,0)),0) AS total_due FROM fmb_general_contribution gc LEFT JOIN (SELECT fmbgc_id, SUM(amount) AS total_received FROM fmb_general_contribution_payments GROUP BY fmbgc_id) p ON p.fmbgc_id = gc.id WHERE gc.user_id IN ($placeholders)", $memberIds)->row_array();
      $gc_due = isset($gcRow['total_due']) ? (float)$gcRow['total_due'] : 0.0;
    }

    // Miqaat invoices for family
    $miq_due = 0.0;
    $miq_list = [];
    if (!empty($memberIds)) {
      $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
      $sql = "SELECT i.id, i.miqaat_id, m.name AS miqaat_name, i.date AS invoice_date, i.amount, i.description, i.user_id, u.Full_Name as owner_name, COALESCE(SUM(p.amount),0) AS paid_amount, (i.amount - COALESCE(SUM(p.amount),0)) AS due_amount
              FROM miqaat_invoice i
              LEFT JOIN miqaat_payment p ON p.miqaat_invoice_id = i.id
              LEFT JOIN miqaat m ON m.id = i.miqaat_id
              LEFT JOIN user u ON u.ITS_ID = i.user_id
              WHERE i.user_id IN ($placeholders)
              GROUP BY i.id, i.miqaat_id, m.name, i.date, i.amount, i.description, i.user_id, u.Full_Name
              ORDER BY i.date DESC, i.id DESC";
      $miq_rows = $this->db->query($sql, $memberIds)->result_array();
      foreach ($miq_rows as $mi) {
        $miq_due += (float)($mi['due_amount'] ?? 0);
        $miq_list[] = $mi;
      }
    }

    // Corpus for HOF
    $corpus_due = 0.0;
    $hof_id = (!empty($_SESSION['user_data']['HOF_ID'])) ? $_SESSION['user_data']['HOF_ID'] : null;
    if (!empty($hof_id)) {
      $assigns = $this->CorpusFundM->get_assignments_with_payments_by_hof($hof_id);
      if (is_array($assigns)) {
        foreach ($assigns as $a) {
          $assigned = (float)($a['amount_assigned'] ?? 0);
          $paid = (float)($a['amount_paid'] ?? 0);
          $corpus_due += max(0, $assigned - $paid);
        }
      }
    }

    // Ekram for HOF
    $ekram_due = 0.0;
    if (!empty($hof_id)) {
      $eassigns = $this->EkramFundM->get_assignments_with_payments_by_hof($hof_id);
      if (is_array($eassigns)) {
        foreach ($eassigns as $ea) {
          $assigned = (float)($ea['amount_assigned'] ?? 0);
          $paid = (float)($ea['amount_paid'] ?? 0);
          $ekram_due += max(0, $assigned - $paid);
        }
      }
    }

    // Wajebaat outstanding for family
    $wajebaat_due = 0.0;
    $this->load->model('WajebaatM');
    foreach ($memberIds as $m) {
      $waj_row = $this->WajebaatM->get_by_its($m);
      if (!empty($waj_row) && is_array($waj_row)) {
        $wajebaat_due += (float)($waj_row['due'] ?? 0);
      }
    }

    $family_total = $family_fmb + $family_sabeel + $gc_due + $miq_due + $corpus_due + $ekram_due + $wajebaat_due;

    // prepare recipients: submitter and HOF (if email present)
    $submitterEmail = $_SESSION['user_data']['Email'] ?? null;
    $submitterName = $_SESSION['user_data']['First_Name'] . ' ' . $_SESSION['user_data']['Surname'];
    $hofEmail = null;
    $hofName = null;
    if (!empty($hof_id)) {
      $hofRow = $this->db->query('SELECT Email, Full_Name FROM user WHERE ITS_ID = ? LIMIT 1', [$hof_id])->row_array();
      if (!empty($hofRow)) {
        $hofEmail = $hofRow['Email'] ?? null;
        $hofName = $hofRow['Full_Name'] ?? null;
      }
    }

    // Build HTML body with improved layout and red styling for positive dues
    $subject = 'Pending Family Dues Summary';
    // small cleaning helper to remove control/format characters and collapse whitespace
    $clean = function ($s) {
      if ($s === null) return '';
      if (!is_string($s)) $s = (string)$s;
      // decode any entities
      $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
      // remove control & format characters and zero-width joiners
      $s = preg_replace('/[\p{C}\x{200B}\x{200C}\x{200D}\x{2060}]+/u', '', $s);
      // collapse multiple whitespace
      $s = preg_replace('/\s{2,}/u', ' ', $s);
      return trim($s);
    };

    $submitterNameClean = $clean($submitterName);
    $hofNameClean = $clean($hofName);

    $loginUrl = htmlspecialchars($clean(base_url('accounts/login')));
    $hofLabel = htmlspecialchars($clean($hof_id ?? '')) . ($hofNameClean ? ' - ' . htmlspecialchars($hofNameClean) : '');
    $submitter = htmlspecialchars($clean($submitterNameClean));
    $subject = $clean($subject);
    $logoUrl = htmlspecialchars($clean(base_url('assets/logo.png')));
    $title = htmlspecialchars($clean('Pending Dues — Family Summary'));
    $orgName = htmlspecialchars($clean('Khar Jamaat'));

    $body = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    $body .= '<style>
      body{font-family:Arial,Helvetica,sans-serif;color:#222;margin:0;padding:0;background:#f6f6f6}
      .container{max-width:640px;margin:18px auto;background:#fff;border-radius:6px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.06)}
      .header{background:#0b5;color:#fff;padding:14px 18px}
      .header h1{margin:0;font-size:18px;font-weight:700}
      .content{padding:18px}
      .muted{color:#666;font-size:13px}
      table.totals{width:100%;border-collapse:collapse;margin:12px 0;border:1px solid #e6e6e6}
      table.totals th, table.totals td{padding:10px;border:1px solid #e6e6e6}
      table.totals tr.total td{font-weight:700;border-top:2px solid #ddd;background:#fafafa}
      .amount{ text-align:right; white-space:nowrap; font-variant-numeric:tabular-nums }
      .amount.positive{ color:#c00; font-weight:700 }
      .details{width:100%;border-collapse:collapse;margin-top:12px;border:1px solid #e6e6e6}
      .details th, .details td{padding:8px;border:1px solid #e6e6e6;text-align:left;font-size:13px}
      .details th{background:#fafafa;font-weight:600}
      .cta{display:inline-block;margin-top:14px;padding:10px 14px;background:#0b5;color:#fff;text-decoration:none;border-radius:4px}
      @media (max-width:520px){ .header h1{font-size:16px} .details th,.details td{font-size:12px} }
      </style></head><body>';
    // logo removed from email per request
    $body .= '<div class="container">';
    $body .= '<div class="header"><h1>' . $title . '</h1></div>';
    $body .= '<div class="content">';
    $body .= '<p class="muted">Baad Afzalus Salaam,<br> ' . $submitter . ',</p>';
    $body .= '<p class="muted">Below is the summary of pending dues for your family (HOF: ' . $hofLabel . ').</p>';

    // summary totals
    $body .= '<table class="totals">';
    $fmb_fmt = htmlspecialchars($clean(number_format($family_fmb)));
    $sabeel_fmt = htmlspecialchars($clean(number_format($family_sabeel)));
    $gc_fmt = htmlspecialchars($clean(number_format($gc_due)));
    $corpus_fmt = htmlspecialchars($clean(number_format($corpus_due)));
    $ekram_fmt = htmlspecialchars($clean(number_format($ekram_due)));
    $miq_fmt = htmlspecialchars($clean(number_format($miq_due)));
    $waj_fmt = htmlspecialchars($clean(number_format($wajebaat_due)));
    $total_fmt = htmlspecialchars($clean(number_format($family_total)));

    $body .= '<tr><td>FMB Takhmeen</td><td class="amount ' . ($family_fmb > 0 ? 'positive' : '') . '">₹ ' . $fmb_fmt . '</td></tr>';
    $body .= '<tr><td>Sabeel Takhmeen</td><td class="amount ' . ($family_sabeel > 0 ? 'positive' : '') . '">₹ ' . $sabeel_fmt . '</td></tr>';
    $body .= '<tr><td>General Contributions</td><td class="amount ' . ($gc_due > 0 ? 'positive' : '') . '">₹ ' . $gc_fmt . '</td></tr>';
    $body .= '<tr><td>Corpus Fund</td><td class="amount ' . ($corpus_due > 0 ? 'positive' : '') . '">₹ ' . $corpus_fmt . '</td></tr>';
    $body .= '<tr><td>Ekram Fund</td><td class="amount ' . ($ekram_due > 0 ? 'positive' : '') . '">₹ ' . $ekram_fmt . '</td></tr>';
    $body .= '<tr><td>Miqaat Invoices</td><td class="amount ' . ($miq_due > 0 ? 'positive' : '') . '">₹ ' . $miq_fmt . '</td></tr>';
    $body .= '<tr><td>Wajebaat</td><td class="amount ' . ($wajebaat_due > 0 ? 'positive' : '') . '">₹ ' . $waj_fmt . '</td></tr>';
    $body .= '<tr class="total"><td>Total</td><td class="amount ' . ($family_total > 0 ? 'positive' : '') . '">₹ ' . $total_fmt . '</td></tr>';
    $body .= '</table>';

    // details table
    if (!empty($miq_list)) {
      $body .= '<h3 style="margin:12px 0 6px 0;font-size:15px">Miqaat / Invoice Details</h3>';
      $body .= '<table class="details">';
      $body .= '<thead><tr>'
        . '<th>Assigned to</th>'
        . '<th>Event</th>'
        . '<th style="text-align:right">Amount (₹)</th>'
        . '<th style="text-align:right">Paid (₹)</th>'
        . '<th style="text-align:right">Due (₹)</th>'
        . '</tr></thead><tbody>';
      foreach ($miq_list as $mi) {
        $owner = htmlspecialchars($clean($mi['owner_name'] ?? $mi['user_id'] ?? ''));
        $event = htmlspecialchars($clean($mi['miqaat_name'] ?? ('#' . ($mi['miqaat_id'] ?? ''))));
        $amt = htmlspecialchars($clean(number_format($mi['amount'] ?? 0)));
        $paid = htmlspecialchars($clean(number_format($mi['paid_amount'] ?? 0)));
        $dueVal = (float)($mi['due_amount'] ?? 0);
        $dueFmt = htmlspecialchars($clean(number_format($dueVal)));
        $dueHtml = $dueVal > 0 ? '<span class="amount positive">₹ ' . $dueFmt . '</span>' : '<span class="amount">₹ ' . $dueFmt . '</span>';
        $body .= '<tr>'
          . '<td>' . $owner . '</td>'
          . '<td>' . $event . '</td>'
          . '<td style="text-align:right">₹ ' . $amt . '</td>'
          . '<td style="text-align:right">₹ ' . $paid . '</td>'
          . '<td style="text-align:right">' . $dueHtml . '</td>'
          . '</tr>';
      }
      $body .= '</tbody></table>';
    }

    $body .= '<p style="margin-top:14px">Please <a class="cta" href="' . $loginUrl . '">Login to your account</a> to view invoices and make payments, or contact the office for assistance.</p>';
    $body .= '<p class="muted" style="margin-top:18px">Regards,<br>' . $orgName . '</p>';
    $body .= '</div></div></body></html>';

    // final cleanup: decode any double-encoded entities, then strip control/format characters
    $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $body = preg_replace('/[\p{Cc}\p{Cf}]+/u', '', $body);
    // collapse incidental whitespace between tags
    $body = preg_replace('/>\s+</', '><', $body);
    $body = trim($body);

    // Send to submitter and HOF (if present) — enqueue for background worker
    $recipients = [];
    if (!empty($submitterEmail)) $recipients[] = $submitterEmail;
    if (!empty($hofEmail) && $hofEmail !== $submitterEmail) $recipients[] = $hofEmail;

    // release PHP session lock early so other requests aren't blocked
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_write_close();
    }

    $this->load->model('EmailQueueM');
    $enqueued = 0;
    if (!empty($recipients)) {
      foreach ($recipients as $rcp) {
        $this->EmailQueueM->enqueue($rcp, $subject, $body, null, 'html');
        $enqueued++;
      }
    }

    return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'enqueued' => $enqueued]));
  }

  public function updateraza($id)
  {
    unset($_POST['raza-type']);
    $data = json_encode($_POST);
    $flag = $this->AccountM->update_raza($id, $data);
    if ($flag) {
      // enqueue notification for user and admins that raza was updated
      $this->load->model('NotificationM');
      $userEmail = isset($_SESSION['user_data']['Email']) ? $_SESSION['user_data']['Email'] : null;
      $userName = isset($_SESSION['user_data']['Full_Name']) ? $_SESSION['user_data']['Full_Name'] : '';

      // Member notification
      if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $this->NotificationM->insert_notification([
          'channel' => 'email',
          'recipient' => $userEmail,
          'recipient_type' => 'user',
          'subject' => 'Your Raza has been updated',
          'body' => 'Baad Afzalus Salaam, <br /><br /> ' . $userName . ',<br /><br />Your Raza has been updated. Please login to view details.',
          'scheduled_at' => null
        ]);
      }

      // Admin notifications (enqueue to full admin list)
      $admins = [
        'amilsaheb@kharjamaat.in',
        '3042@carmelnmh.in',
        'kharjamaat@gmail.com',
        'kharamilsaheb@gmail.com',
        'kharjamaat786@gmail.com',
        'khozemtopiwalla@gmail.com',
        'ybookwala@gmail.com'
      ];
      foreach ($admins as $a) {
        $this->NotificationM->insert_notification([
          'channel' => 'email',
          'recipient' => $a,
          'recipient_type' => 'admin',
          'subject' => 'Raza updated by ' . $userName,
          'body' => htmlspecialchars($userName) . ' updated a Raza. View in admin panel.',
          'scheduled_at' => null
        ]);
      }
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
      redirect('/accounts');
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
    if (empty($_SESSION['user'])) {
      $_SESSION['redirect_to_url'] = 'accounts/rsvp/' . $id;
      redirect('/accounts');
    }

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

      // PHP converts dots in form field names to underscores in $_POST keys.
      // Example: input name "mobile-no." becomes $_POST['mobile-no_'].
      $postKey = $result;
      if (!isset($_POST[$postKey]) && strpos($postKey, '.') !== false) {
        $postKey = str_replace('.', '_', $postKey);
      }

      $v = "";
      if ($value['type'] == "select" && isset($_POST[$postKey])) {
        $selectedIndex = $_POST[$postKey];
        if (isset($value['options'][$selectedIndex]['name'])) {
          $v = htmlspecialchars($value['options'][$selectedIndex]['name']);
        }
      } else {
        $v = isset($_POST[$postKey]) ? htmlspecialchars($_POST[$postKey]) : '';
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

    $this->load->model('EmailQueueM');
    // determine member email (validate, fallback to DB lookup if missing)
    $memberEmail = isset($user_data['Email']) && !empty($user_data['Email']) ? trim($user_data['Email']) : null;
    if (empty($memberEmail) || !filter_var($memberEmail, FILTER_VALIDATE_EMAIL)) {
      $row = $this->db->query('SELECT Email FROM user WHERE ITS_ID = ? LIMIT 1', [$_SESSION['user_data']['ITS_ID']])->row_array();
      if (!empty($row['Email']) && filter_var($row['Email'], FILTER_VALIDATE_EMAIL)) {
        $memberEmail = $row['Email'];
      } else {
        $memberEmail = null;
      }
    }
    // Enqueue personal copy to member only if we have a valid email
    if (!empty($memberEmail)) {
      $this->EmailQueueM->enqueue($memberEmail, 'Raza Submission Successful', $email_template, null, 'html');
    }

    $admins = [
      'amilsaheb@kharjamaat.in',
      '3042@carmelnmh.in',
      'kharjamaat@gmail.com',
      'kharamilsaheb@gmail.com',
      'kharjamaat786@gmail.com',
      'khozemtopiwalla@gmail.com',
      'ybookwala@gmail.com'
    ];

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
      // $check is the inserted row id (int). Generate raza_id and update row so admins can see it.
      $hijri_year = explode("-", $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"])[2];
      $generated_raza_id = $this->AccountM->generate_raza_id($hijri_year);
      $this->AccountM->update_raza_by_id($check, array("raza_id" => $generated_raza_id));

      // Prepare miqaat details if present
      $miqaatInfoHtml = '';
      if (!empty($miqaat_id)) {
        $miqaatRow = $this->AccountM->get_miqaat_by_id($miqaat_id);
        if (!empty($miqaatRow)) {
          $miqaatName = htmlspecialchars($miqaatRow['name']);
          $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
          $miqaatInfoHtml = '<p><strong>Miqaat:</strong> ' . $miqaatName . ' (' . $miqaatDate . ')</p>';
        }
      }

      // Notify admins with raza id and miqaat details (use branded template)
      $this->load->helper('email_template');
      $adminSubject = htmlspecialchars($user_data['Full_Name']) . ' has submitted a new Kaaraj Raza';

      // prepare details array for the branded email
      $adminDetails = [
        'Submitted By' => isset($user_data['Full_Name']) ? (string)$user_data['Full_Name'] : (string)$userId,
        'ITS' => isset($user_data['ITS_ID']) ? (string)$user_data['ITS_ID'] : (string)$userId,
        'Raza ID' => (string)$generated_raza_id,
      ];

      if (!empty($miqaatRow)) {
        if (!empty($miqaatRow['name'])) $adminDetails['Miqaat'] = (string)$miqaatRow['name'];
        if (!empty($miqaatRow['miqaat_id'])) $adminDetails['Miqaat ID'] = (string)$miqaatRow['miqaat_id'];
        if (!empty($miqaatRow['type'])) $adminDetails['Type'] = (string)$miqaatRow['type'];
        if (!empty($miqaatRow['date'])) $adminDetails['Date'] = date('d-m-Y', strtotime($miqaatRow['date']));
      }

      // Try to fetch assignment info for the submitting member
      $assignmentLabel = '';
      $assignmentGroupName = '';
      if (!empty($miqaat_id)) {
        $ass = $this->AccountM->get_miqaat_assignment_for_member($miqaat_id, $userId);
        if (!empty($ass)) {
          $assignmentLabel = isset($ass['assign_type']) ? (string)$ass['assign_type'] : '';
          $assignmentGroupName = isset($ass['group_name']) ? (string)$ass['group_name'] : '';
          $al = strtolower(trim($assignmentLabel));
          if ($al === 'group') $assignmentLabel = 'Group';
          elseif ($al === 'individual') $assignmentLabel = 'Individual';
        }
      }
      if ($assignmentLabel !== '') $adminDetails['Assignment'] = $assignmentLabel;
      if ($assignmentGroupName !== '') $adminDetails['Group'] = $assignmentGroupName;

      // Build admin details table HTML and full raza details table
      $this->load->helper('raza_details');
      // Prepare key/value rows for admin summary
      $adminRows = [];
      foreach ($adminDetails as $k => $v) {
        $adminRows[] = ['label' => (string)$k, 'value' => (string)$v];
      }
      $adminSummaryTable = function_exists('email_kv_details_table_html') ? email_kv_details_table_html($adminRows) : '';

      // Build raza details table using the raza helper (matches screenshot style)
      $razadataDecoded = json_decode($data, true);
      $razaDetailsTable = function_exists('render_raza_details_table_html') ? render_raza_details_table_html($razatype['name'], $razafields, $razadataDecoded) : '';

      $bodyHtml = $adminSummaryTable . ($razaDetailsTable !== '' ? '<div style="margin-top:12px">' . $razaDetailsTable . '</div>' : '');

      $adminBody = render_generic_email_html([
        'title' => $adminSubject,
        'todayDate' => date('l, j M Y, h:i:s A'),
        'greeting' => 'Baad Afzalus Salaam,',
        'cardTitle' => 'All Details',
        'body' => $bodyHtml,
        'ctaUrl' => base_url('admin'),
        'ctaText' => 'Login to Admin',
      ]);

      foreach ($admins as $adminEmail) {
        $this->EmailQueueM->enqueue($adminEmail, $adminSubject, $adminBody, null, 'html');
      }

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

  public function update_profile_contact()
  {
    header('Content-Type: application/json');
    if (empty($_SESSION['user'])) {
      http_response_code(403);
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }

    $its = $_SESSION['user_data']['ITS_ID'];
    $mobile = $this->input->post('mobile');
    $email = $this->input->post('email');
    $family_mobile = $this->input->post('registered_family_mobile');

    $data = [];
    if ($mobile !== null) $data['Mobile'] = trim($mobile);
    if ($email !== null) $data['Email'] = trim($email);
    if ($family_mobile !== null) $data['Registered_Family_Mobile'] = trim($family_mobile);

    if (empty($data)) {
      http_response_code(400);
      echo json_encode(['success' => false, 'error' => 'No data to update']);
      return;
    }

    $this->load->model('AmilsahebM');
    $flag = $this->AmilsahebM->update_user_by_its_id($its, $data);

    if ($flag) {
      // Update session cache
      foreach ($data as $k => $v) {
        $_SESSION['user_data'][$k] = $v;
      }
      echo json_encode(['success' => true, 'data' => $data]);
    } else {
      http_response_code(500);
      echo json_encode(['success' => false, 'error' => 'Failed to update']);
    }
  }
  public function appointment()
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $_SESSION['user_data']['First_Name'] . " " . $_SESSION['user_data']['Surname'];
    $data['sector'] = $_SESSION['user_data']['Sector'];
    // Get the dates
    $data['dates'] = $this->AccountM->get_dates();
    $data['user_appointments'] = $this->AccountM->get_user_appointments($data['user_name']);

    // For month navigation fetches, return only the calendar markup
    if ($this->input->get('partial') == '1') {
      $this->load->view('Accounts/Appointment/Home', $data);
      return;
    }

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
    $time    = $this->input->get('time');
    $purpose = $this->input->get('purpose');
    $details = $this->input->get('details');

    // Retrieve user_id from the session
    $user_id = $_SESSION['user']['username'] ?? null;
    if (empty($user_id)) {
      redirect('login');
      return;
    }

    // Load required models/helpers
    $this->load->model('AccountM');
    $this->load->model('NotificationM');
    $this->load->helper('email_template');

    // Retrieve ITS_ID and Full_Name
    $user_info = $this->AccountM->get_user_info($user_id);
    if (!$user_info) {
      echo 'User information not found.';
      return;
    }

    // Book the appointment
    $this->AccountM->book_slot(
      $slot_id,
      $user_info->ITS_ID,
      $user_info->Full_Name,
      $purpose,
      $details
    );

    try {

      // Fetch additional data
      $user_data = $this->AccountM->getUserData($user_id);
      $slot_info = $this->AccountM->get_slot_info($slot_id);

      $slot_date = isset($slot_info->date) ? $slot_info->date : '';
      $user_email = $user_data['Email'] ?? null;
      $user_name  = $user_info->Full_Name;

      /* ==========================================================
       MEMBER EMAIL (HTML BODY)
       ========================================================== */

      $memberBody = '
    <p>Your appointment has been successfully booked with the following details:</p>

    <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
      <tr>
        <td><strong>Date</strong></td>
        <td>' . htmlspecialchars(date('d M Y', strtotime($slot_date))) . '</td>
      </tr>
      <tr>
        <td><strong>Time</strong></td>
        <td>' . htmlspecialchars($time) . '</td>
      </tr>';

      if (!empty($purpose)) {
        $memberBody .= '
      <tr>
        <td><strong>Purpose</strong></td>
        <td>' . htmlspecialchars($purpose) . '</td>
      </tr>';
      }

      if (!empty($details)) {
        $memberBody .= '
      <tr>
        <td><strong>Details</strong></td>
        <td>' . nl2br(htmlspecialchars($details)) . '</td>
      </tr>';
      }

      $memberBody .= '</table>';

      $memberEmailHtml = render_generic_email_html([
        'title'       => 'Appointment Confirmation',
        'todayDate'   => date('l, j M Y, h:i A'),
        'greeting'    => 'Dear ' . htmlspecialchars($user_name) . ',',
        'cardTitle'   => 'Appointment Booked',
        'body'        => $memberBody,
        'auto_table'  => false,
        'ctaUrl'      => base_url('accounts/appointment'),
        'ctaText'     => 'View Appointment',
      ]);

      /* ==========================================================
       ADMIN EMAIL (HTML BODY)
       ========================================================== */

      $adminBody = '
    <p>A new appointment has been booked with the following details:</p>

    <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
      <tr>
        <td><strong>Member</strong></td>
        <td>' . htmlspecialchars($user_name) . ' (' . htmlspecialchars($user_info->ITS_ID) . ')</td>
      </tr>
      <tr>
        <td><strong>Date</strong></td>
        <td>' . htmlspecialchars(date('d M Y', strtotime($slot_date))) . '</td>
      </tr>
      <tr>
        <td><strong>Time</strong></td>
        <td>' . htmlspecialchars($time) . '</td>
      </tr>';

      if (!empty($purpose)) {
        $adminBody .= '
      <tr>
        <td><strong>Purpose</strong></td>
        <td>' . htmlspecialchars($purpose) . '</td>
      </tr>';
      }

      if (!empty($details)) {
        $adminBody .= '
      <tr>
        <td><strong>Details</strong></td>
        <td>' . nl2br(htmlspecialchars($details)) . '</td>
      </tr>';
      }

      $adminBody .= '</table>';

      $adminEmailHtml = render_generic_email_html([
        'title'       => 'New Appointment Booked',
        'todayDate'   => date('l, j M Y, h:i A'),
        'greeting'    => 'Baad Afzalus Salaam,',
        'cardTitle'   => 'Appointment Notification',
        'body'        => $adminBody,
        'auto_table'  => false,
        'ctaUrl'      => base_url('amilsaheb/manage_appointment'),
        'ctaText'     => 'Manage Appointments',
      ]);

      /* ==========================================================
       QUEUE NOTIFICATIONS
       ========================================================== */

      // Member email
      if (!empty($user_email)) {
        $this->NotificationM->insert_notification([
          'channel'        => 'email',
          'recipient'      => $user_email,
          'recipient_type' => 'user',
          'subject'        => 'Appointment Confirmation',
          'body'           => $memberEmailHtml,
          'scheduled_at'   => null
        ]);
      }

      // Member WhatsApp (plain text)
      $user_mobile = $user_data['Registered_Family_Mobile']
        ?? $user_data['Mobile']
        ?? '';
      $user_mobile = preg_replace('/[^0-9+]/', '', (string)$user_mobile);

      if ($user_mobile !== '') {
        $plainText = "Your appointment has been booked.\n"
          . "Date: " . date('d M Y', strtotime($slot_date)) . "\n"
          . "Time: {$time}\n"
          . (!empty($purpose) ? "Purpose: {$purpose}\n" : '')
          . (!empty($details) ? "Details: {$details}\n" : '');

        $this->NotificationM->insert_notification([
          'channel'        => 'whatsapp',
          'recipient'      => $user_mobile,
          'recipient_type' => 'user',
          'subject'        => null,
          'body'           => $plainText,
          'scheduled_at'   => null
        ]);
      }

      // Admin emails
      $admins = [
        'amilsaheb@kharjamaat.in',
        'kharamilsaheb@gmail.com',
      ];

      foreach ($admins as $adminEmail) {
        $this->NotificationM->insert_notification([
          'channel'        => 'email',
          'recipient'      => $adminEmail,
          'recipient_type' => 'admin',
          'subject'        => 'New Appointment Booked',
          'body'           => $adminEmailHtml,
          'scheduled_at'   => null
        ]);
      }
    } catch (Exception $e) {
      log_message('error', 'Appointment booking notification error: ' . $e->getMessage());
    }

    redirect('accounts/appointment');
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
