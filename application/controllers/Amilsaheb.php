<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Amilsaheb extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('AccountM');
    $this->load->model('AdminM');
    $this->load->model('AmilsahebM');
    $this->load->model('MasoolMusaidM');
    $this->load->model('CommonM');
    $this->load->model('HijriCalendar');
    // Anjuman model provides takhmeen helpers we reuse here
    $this->load->model('AnjumanM');
    $this->load->library('email', $this->config->item('email'));
  }
  public function index()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];

    $users = $this->AmilsahebM->get_all_ashara();
    // Resident-only sector stats for dashboard cards
    $sectorsData = $this->AmilsahebM->get_resident_sector_stats();
    $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats();

    // Build resident-only overview counts for dashboard cards
    $residentOverview = $this->AmilsahebM->get_resident_overview_counts();
    $stats = [
      'HOF' => (int)($residentOverview['hof'] ?? 0),
      'FM' => (int)($residentOverview['fm'] ?? 0),
      'Mardo' => (int)($residentOverview['male'] ?? 0),
      'Bairo' => (int)($residentOverview['female'] ?? 0),
      'Age_0_4' => (int)($residentOverview['age_0_4'] ?? 0),
      'Age_5_15' => (int)($residentOverview['age_5_15'] ?? 0),
      'Buzurgo' => (int)($residentOverview['seniors'] ?? 0),
      'LeaveStatus' => [],
      'Sectors' => $sectorsData,
      'SubSectors' => $subSectorsData
    ];

    $data = [
      'user_name' => $data['user_name'],
      'users' => $users,
      'stats' => $stats,
      'current_sector' => '',
      'current_sub_sector' => ''
    ];

    // Add current Hijri year's Miqaat/Thaali/Holiday day counts for display on home
    $data['year_daytype_stats'] = $this->CommonM->get_year_calendar_daytypes();

    // --- Build FMB & Sabeel takhmeen sector summaries (reuse logic from Anjuman controller) ---
    // Compute current Hijri financial year string (same algorithm as Anjuman)
    $current_hijri = $this->HijriCalendar->get_hijri_date(date("Y-m-d"));
    $takhmeen_year_current = null;
    if (!empty($current_hijri) && !empty($current_hijri['hijri_date'])) {
      $parts = explode('-', $current_hijri['hijri_date']); // d-m-Y
      if (count($parts) === 3) {
        $m = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
        $y = $parts[2];
        if ($m >= '01' && $m <= '08') {
          $y1 = intval($y) - 1;
          $y2 = substr($y, -2);
          $takhmeen_year_current = sprintf('%d-%s', $y1, $y2);
        } else {
          $y1 = intval($y);
          $y2 = substr(strval($y + 1), -2);
          $takhmeen_year_current = sprintf('%d-%s', $y1, $y2);
        }
      }
    }

    // Fallback to max year if detection fails
    if (empty($takhmeen_year_current)) {
      $row = $this->db->query("SELECT MAX(year) AS y FROM fmb_takhmeen")->row_array();
      $takhmeen_year_current = $row && isset($row['y']) ? $row['y'] : null;
    }

    // FMB: per-user takhmeen details (model already does oldest-first allocation)
    $fmb_users = $this->AnjumanM->get_user_takhmeen_details();
    $fmb_agg = [];
    foreach ($fmb_users as $u) {
      $sector = isset($u['Sector']) ? trim($u['Sector']) : '';
      if ($sector === '') $sector = 'Unassigned';
      $total_year = 0.0;
      $paid_year = 0.0;
      if (!empty($u['all_takhmeen']) && is_array($u['all_takhmeen'])) {
        foreach ($u['all_takhmeen'] as $yr) {
          if (isset($yr['year']) && $yr['year'] == $takhmeen_year_current) {
            $total_year = (float)($yr['total_amount'] ?? 0);
            $paid_year  = (float)($yr['total_paid'] ?? 0);
            break;
          }
        }
      }
      if (!isset($fmb_agg[$sector])) {
        $fmb_agg[$sector] = [
          'sector' => $sector,
          'total_takhmeen' => 0.0,
          'total_paid' => 0.0,
          'members' => 0,
        ];
      }
      $fmb_agg[$sector]['total_takhmeen'] += $total_year;
      $fmb_agg[$sector]['total_paid'] += $paid_year;
      if ($total_year > 0) $fmb_agg[$sector]['members'] += 1;
    }
    $fmb_rows = array_values(array_map(function ($r) {
      $total = (float)($r['total_takhmeen'] ?? 0);
      $paid = (float)($r['total_paid'] ?? 0);
      $r['outstanding'] = max(0, $total - $paid);
      return $r;
    }, $fmb_agg));

    // Sabeel: reuse model that supports allocation order
    $sabeel_users = $this->AnjumanM->get_user_sabeel_takhmeen_details([
      'allocation_order' => 'oldest-first',
      'year' => $takhmeen_year_current,
    ]);
    $sabeel_agg = [];
    foreach ($sabeel_users as $u) {
      $sector = isset($u['Sector']) ? trim($u['Sector']) : '';
      if ($sector === '') $sector = 'Unassigned';
      $ct = isset($u['current_year_takhmeen']) ? $u['current_year_takhmeen'] : null;
      $total_year = 0.0;
      $paid_year = 0.0;
      if (!empty($ct)) {
        $estY = (float)($ct['establishment']['yearly'] ?? 0);
        $resY = (float)($ct['residential']['yearly'] ?? 0);
        $estP = (float)($ct['establishment']['paid'] ?? 0);
        $resP = (float)($ct['residential']['paid'] ?? 0);
        $total_year = $estY + $resY;
        $paid_year = $estP + $resP;
      }
      if (!isset($sabeel_agg[$sector])) {
        $sabeel_agg[$sector] = [
          'sector' => $sector,
          'total_takhmeen' => 0.0,
          'total_paid' => 0.0,
          'members' => 0,
        ];
      }
      $sabeel_agg[$sector]['total_takhmeen'] += $total_year;
      $sabeel_agg[$sector]['total_paid'] += $paid_year;
      if ($total_year > 0) $sabeel_agg[$sector]['members'] += 1;
    }
    $sabeel_rows = array_values(array_map(function ($r) {
      $total = (float)($r['total_takhmeen'] ?? 0);
      $paid = (float)($r['total_paid'] ?? 0);
      $r['outstanding'] = max(0, $total - $paid);
      return $r;
    }, $sabeel_agg));

    // Raza summary to match Anjuman dashboard
    $rz_row = $this->db->query(
      "SELECT 
          SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pending,
          SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS approved,
          SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS rejected
        FROM raza
        WHERE active = 1"
    )->row_array();
    $raza_summary = [
      'pending' => (int)($rz_row['pending'] ?? 0),
      'approved' => (int)($rz_row['approved'] ?? 0),
      'rejected' => (int)($rz_row['rejected'] ?? 0),
    ];

    // Build dashboard_data with monthly and weekly summaries
    $dd = [
      'fmb_takhmeen_sector' => $fmb_rows,
      'fmb_takhmeen_year' => $takhmeen_year_current,
      'sabeel_takhmeen_sector' => $sabeel_rows,
      'sabeel_takhmeen_year' => $takhmeen_year_current,
      'raza_summary' => $raza_summary,
      // Weekly thaali signup averages (HOF signups) identical to Jamaat dashboard logic
      'this_week_sector_signup_avg' => $this->get_this_week_sector_signup_avg(),
      // Families (HOF) with zero thaali signups any day in current week
      'no_thaali_families' => $this->get_no_thaali_families_this_week(),
      // Initialize monthly placeholders (will populate below)
      'this_month_families_signed_up' => 0,
      'no_thaali_families_month' => [],
    ];
    $data['dashboard_data'] = $dd;

    // Populate Hijri-month monthly stats (families signed up this month, and no-thaali list)
    $today_parts = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
    if ($today_parts && isset($today_parts['hijri_month']) && isset($today_parts['hijri_year'])) {
      $mstats = $this->CommonM->get_monthly_thaali_stats((int)$today_parts['hijri_month'], (int)$today_parts['hijri_year']);
      $data['dashboard_data']['this_month_families_signed_up'] = isset($mstats['families_signed_up']) ? (int)$mstats['families_signed_up'] : 0;
      $data['dashboard_data']['no_thaali_families_month'] = isset($mstats['no_thaali_list']) ? $mstats['no_thaali_list'] : [];
    }

    // Provide miqaat RSVP payload and upcoming miqaats for the RSVP container in the view
    $data['dashboard_data']['miqaat_rsvp'] = $this->CommonM->get_next_miqaat_rsvp_stats();
    // Fetch next upcoming miqaats (limit 5) — include all upcoming miqaats regardless of raza approval
    $limit = 5;
    $sql = "SELECT m.id, m.name, m.type, m.date, m.assigned_to
      FROM miqaat m
      WHERE m.date >= CURDATE()
      ORDER BY m.date ASC
      LIMIT " . (int)$limit;
    $upcoming_miqaats = $this->db->query($sql)->result_array();
    if (!empty($upcoming_miqaats)) {
      foreach ($upcoming_miqaats as &$um) {
        $um_date = isset($um['date']) ? $um['date'] : null;
        $hparts = null;
        if ($um_date) {
          $hparts = $this->HijriCalendar->get_hijri_parts_by_greg_date($um_date);
        }
        if ($hparts && isset($hparts['hijri_day'])) {
          $um['hijri_label'] = trim((($hparts['hijri_day'] ?? '')) . ' ' . (($hparts['hijri_month_name'] ?? $hparts['hijri_month'] ?? '')) . ' ' . (($hparts['hijri_year'] ?? '')));
        } else {
          $um['hijri_label'] = '';
        }
        $um['hijri_parts'] = $hparts;
      }
      unset($um);
    }
    $data['dashboard_data']['upcoming_miqaats'] = $upcoming_miqaats;

    // Member type distribution for dashboard
    $data['member_type_counts'] = $this->AmilsahebM->get_member_type_distribution();

    // Marital status distribution (active members only)
    $ms_rows = $this->db->select("COALESCE(NULLIF(TRIM(Marital_Status),''),'Unknown') AS ms, COUNT(*) AS cnt")
      ->from('user')
      ->where('inactive_status IS NULL')
      ->group_by('ms')
      ->get()
      ->result_array();
    $marital_status_counts = [];
    foreach ($ms_rows as $r) {
      $marital_status_counts[$r['ms']] = (int)($r['cnt'] ?? 0);
    }
    $data['marital_status_counts'] = $marital_status_counts;

    // Corpus funds overview (parity with Anjuman)
    $this->load->model('CorpusFundM');
    $funds = $this->CorpusFundM->get_funds();
    $corpus_funds = [];
    foreach ($funds as $f) {
      $fid = (int)($f['id'] ?? 0);
      $amount = (float)($f['amount'] ?? 0);
      $assignedTotal = 0.0;
      $paidTotal = 0.0;
      $assignments = [];
      if ($fid > 0) {
        $assignments = $this->CorpusFundM->get_assignments($fid);
        foreach ($assignments as $a) {
          $assignedAmt = (float)($a['amount_assigned'] ?? 0);
          $assignedTotal += $assignedAmt;
          $hofId = (int)($a['hof_id'] ?? $a['HOF_ID'] ?? 0);
          // Sum payments for this assignment (fund + HOF)
          if (method_exists($this->CorpusFundM, 'get_payments_for_assignment')) {
            $plist = $this->CorpusFundM->get_payments_for_assignment($fid, $hofId);
            foreach ($plist as $p) {
              $paidTotal += (float)($p['amount_paid'] ?? 0);
            }
          }
        }
      }
      // Outstanding should reflect unpaid amount on assigned totals
      $outstanding = max(0, $assignedTotal - $paidTotal);
      $f['assigned_total'] = $assignedTotal;
      $f['paid_total'] = $paidTotal;
      $f['outstanding'] = $outstanding;
      $f['assignments'] = $assignments;
      $corpus_funds[] = $f;
    }
    $data['corpus_funds'] = $corpus_funds;

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Home', $data);
  }
  /**
   * Compute sector-wise aggregate total and average daily thaali signups (HOF) for current Mon-Sun week.
   * Mirrors Anjuman::get_this_week_sector_signup_avg to keep dashboard parity.
   */
  private function get_this_week_sector_signup_avg()
  {
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));
    $start = $monday;
    $end = $sunday;
    $dates = [];
    $cursor = strtotime($start);
    $endTs = strtotime($end);
    while ($cursor <= $endTs) {
      $dates[] = date('Y-m-d', $cursor);
      $cursor = strtotime('+1 day', $cursor);
    }
    $days = count($dates);
    if ($days <= 0) return ['start' => $start, 'end' => $end, 'days' => 0, 'sectors' => []];
    $agg = [];
    foreach ($dates as $d) {
      $rows = $this->CommonM->getsignupcount_by_sector($d);
      foreach ($rows as $r) {
        $sector = isset($r['Sector']) ? trim($r['Sector']) : '';
        if ($sector === '' || strtolower($sector) === 'unassigned') continue;
        $cnt = (int)($r['hof_signup_count'] ?? 0);
        if (!isset($agg[$sector])) $agg[$sector] = 0;
        $agg[$sector] += $cnt;
      }
    }
    $sectors = [];
    foreach ($agg as $sector => $total) {
      $sectors[] = [
        'sector' => $sector,
        'total' => (int)$total,
        'avg' => $days > 0 ? round($total / $days, 2) : 0,
      ];
    }
    usort($sectors, function ($a, $b) {
      if ($a['avg'] == $b['avg']) return strcmp($a['sector'], $b['sector']);
      return ($a['avg'] < $b['avg']) ? 1 : -1;
    });
    return ['start' => $start, 'end' => $end, 'days' => $days, 'sectors' => $sectors];
  }
  /**
   * Return list of HOF family rows (from user table) who have no thaali signup on any day of current week.
   * Parity with Anjuman dashboard logic.
   */
  private function get_no_thaali_families_this_week()
  {
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));
    $dates = [];
    $cursor = strtotime($monday);
    $endTs = strtotime($sunday);
    while ($cursor <= $endTs) {
      $dates[] = date('Y-m-d', $cursor);
      $cursor = strtotime('+1 day', $cursor);
    }
    $signedHofs = [];
    foreach ($dates as $d) {
      $rows = $this->CommonM->getsignupforaday_aggregated(['date' => $d, 'thali_taken' => 1]);
      foreach ($rows as $r) {
        $hofId = $r['ITS_ID'] ?? ($r['HOF_ID'] ?? null);
        if ($hofId) $signedHofs[$hofId] = true;
      }
    }
    $allHofs = $this->CommonM->get_all_users(); // includes HOF & FM; filter HOF only
    $no = [];
    foreach ($allHofs as $h) {
      if (($h['HOF_FM_TYPE'] ?? '') !== 'HOF') continue;
      $its = $h['ITS_ID'] ?? null;
      if (!$its) continue;
      if (!isset($signedHofs[$its])) $no[] = $h;
    }
    return $no;
  }
  public function EventRazaRequest()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $event_type = $this->input->get('event_type');
    // Fetch counts for Janab-status=0 and Janab-status=1
    $janabStatus0Count = $this->AmilsahebM->get_raza_count_event(['Janab-status' => 0], $event_type, true);
    $janabStatus1Count = $this->AmilsahebM->get_raza_count_event(['Janab-status' => 1], $event_type);

    // Fetch count for coordinator-status=0
    $coordinatorStatus0Count = $this->AmilsahebM->get_raza_count_event(['coordinator-status' => 0], $event_type, true);

    if ($event_type == 1) {
      $data['umoor'] = "Miqaat Request";
    } elseif ($event_type == 2) {
      $data['umoor'] = "Kaaraj Request";
    }
    $data['raza'] = $this->AmilsahebM->get_raza_event($event_type);
    $data['razatype'] = $this->AdminM->get_eventrazatype();

    foreach ($data['raza'] as $key => $value) {
      $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
      $data['raza'][$key]['chat_count'] = $chatCount;

      $usernameRows = $this->AccountM->get_user($value['user_id']);
      $usernameRow = is_array($usernameRows) && !empty($usernameRows) ? $usernameRows[0] : [];
      $razatypeRows = $this->AdminM->get_razatype_byid($value['razaType']);
      $razatype = is_array($razatypeRows) && !empty($razatypeRows) ? $razatypeRows[0] : [];
      $data['raza'][$key]['razaType'] = $razatype['name'] ?? '';
      $data['raza'][$key]['razaType_id'] = $razatype['id'] ?? null;
      $data['raza'][$key]['razafields'] = $razatype['fields'] ?? '';
      $data['raza'][$key]['umoor'] = $razatype['umoor'] ?? '';
      $data['raza'][$key]['user_name'] = $usernameRow['Full_Name'] ?? '';
      $data['raza'][$key]['miqaat_id'] = $value['miqaat_id'];
      if (!empty($value['miqaat_id'])) {
        $this->load->model('AnjumanM');
        $data['raza'][$key]['miqaat_details'] = json_encode($this->AnjumanM->get_miqaat_by_id($value['miqaat_id']));
      } else {
        $data['raza'][$key]['miqaat_details'] = "";
      }
    }

    $data['event_type'] = $event_type;
    $data['user_name'] = $_SESSION['user']['username'];
    $data['janab_status_0_count'] = $janabStatus0Count;
    $data['janab_status_1_count'] = $janabStatus1Count;
    $data['coordinator_status_0_count'] = $coordinatorStatus0Count;

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/RazaRequest', $data);
  }

  public function UmoorRazaRequest()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['umoor'] = "12 Umoor Raza Applications";
    $data['raza'] = $this->AmilsahebM->get_raza_umoor();
    $data['razatype'] = $this->AdminM->get_umoorrazatype();
    // $data['umoortype'] = $this->AdminM->get_umoortype();

    // Fetch counts for Janab-status=0 and Janab-status=1
    $janabStatus0Count = $this->AmilsahebM->get_raza_count_umoor(['Janab-status' => 0], true);
    $janabStatus1Count = $this->AmilsahebM->get_raza_count_umoor(['Janab-status' => 1]);

    // Fetch count for coordinator-status=0
    $coordinatorStatus0Count = $this->AmilsahebM->get_raza_count_umoor(['coordinator-status' => 0], true);

    foreach ($data['raza'] as $key => $value) {
      $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
      $data['raza'][$key]['chat_count'] = $chatCount;
    }

    foreach ($data['raza'] as $key => $value) {
      $usernameRows = $this->AccountM->get_user($value['user_id']);
      $usernameRow = is_array($usernameRows) && !empty($usernameRows) ? $usernameRows[0] : [];
      $razatypeRows = $this->AdminM->get_razatype_byid($value['razaType']);
      $razatype = is_array($razatypeRows) && !empty($razatypeRows) ? $razatypeRows[0] : [];
      $data['raza'][$key]['razaType'] = $razatype['name'] ?? '';
      $data['raza'][$key]['razafields'] = $razatype['fields'] ?? '';
      $data['raza'][$key]['umoor'] = $razatype['umoor'] ?? '';
      $data['raza'][$key]['user_name'] = $usernameRow['Full_Name'] ?? '';
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['janab_status_0_count'] = $janabStatus0Count;
    $data['janab_status_1_count'] = $janabStatus1Count;
    $data['coordinator_status_0_count'] = $coordinatorStatus0Count;
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/RazaRequest', $data);
  }

  public function corpusfunds_details()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->model('CorpusFundM');
    // Build overview like on Home
    $funds = $this->CorpusFundM->get_funds();
    $details = [];
    foreach ($funds as $f) {
      $fid = (int)($f['id'] ?? 0);
      if ($fid <= 0) continue;
      $assignments = $this->CorpusFundM->get_assignments_with_payments_by_hof(null); // fallback if method not available
      // If specific method exists to get per fund assignments
      $assignments = $this->CorpusFundM->get_assignments($fid);
      $rows = [];
      $assignedTotal = 0.0;
      $paidTotal = 0.0;
      $outstandingTotal = 0.0;
      foreach ($assignments as $a) {
        $hof = (int)($a['hof_id'] ?? $a['HOF_ID'] ?? 0);
        // Fetch authoritative member details from user table by HOF ITS_ID
        $ud = $hof > 0 ? $this->db->query("SELECT Full_Name, Sector, Sub_Sector FROM user WHERE ITS_ID = ? LIMIT 1", [$hof])->row_array() : null;
        $name = ($ud['Full_Name'] ?? '') ?: ($a['Full_Name'] ?? ($a['member_name'] ?? ''));
        $sector = ($ud['Sector'] ?? '') ?: ($a['Sector'] ?? '');
        $subsector = ($ud['Sub_Sector'] ?? '') ?: ($a['Sub_Sector'] ?? '');
        $assigned = (float)($a['amount_assigned'] ?? ($a['amount'] ?? 0));
        $paid = (float)($a['paid'] ?? 0);
        if ($paid === 0.0) {
          // Try compute paid via payments aggregate helper if available
          $plist = method_exists($this->CorpusFundM, 'get_payments_for_assignment') ? $this->CorpusFundM->get_payments_for_assignment($fid, $hof) : [];
          foreach ($plist as $p) {
            $paid += (float)($p['amount_paid'] ?? 0);
          }
        }
        $due = max(0, $assigned - $paid);
        $assignedTotal += $assigned;
        $paidTotal += $paid;
        $outstandingTotal += $due;
        $rows[] = [
          'hof_id' => $hof,
          'name' => $name,
          'sector' => $sector,
          'subsector' => $subsector,
          'assigned' => $assigned,
          'paid' => $paid,
          'due' => $due
        ];
      }
      $details[] = [
        'fund' => $f,
        'rows' => $rows,
        'assigned_total' => $assignedTotal,
        'paid_total' => $paidTotal,
        'outstanding_total' => $outstandingTotal,
      ];
    }
    $data['corpus_details'] = $details;
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/CorpusFundsDetails', $data);
  }
  public function miqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Miqaat/Home', $data);
  }

  public function approveRaza()
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $remark = trim($_POST['remark']);
    $raza_id = $_POST['raza_id'];
    $flag = $this->AmilsahebM->approve_raza($raza_id, $remark);
    $user = $this->AdminM->get_user_by_raza_id($raza_id);

    $this->email->from('info@kharjamaat.in', 'Admin');
    $this->email->to($user['Email']);
    $this->email->subject('Raza Status');
    $this->email->message('Mubarak. Your Raza has been Approved by Amil Saheb');
    $this->email->send();

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('amilsaheb@kharjamaat.in');
    $this->email->subject('Raza Approved');
    $this->email->message('Mubarak. Your Raza has been Approved by Amil Saheb');
    $this->email->send();

    $msg = $user['Full_Name'] . ' (' . $user['ITS_ID'] . ').Raza has been Approved by Amil Saheb';

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('kharjamaat@gmail.com');
    $this->email->subject('Raza Approved');
    $this->email->message($msg);
    $this->email->send();

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('3042@carmelnmh.in');
    $this->email->subject('Raza Approved');
    $this->email->message($msg);
    $this->email->send();

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('anjuman@kharjamaat.in');
    $this->email->subject('Raza Approved');
    $this->email->message($msg);
    $this->email->send();

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }
  public function DeleteRaza($id)
  {
    // Retrieve the value of $umoor from the URL parameters
    $umoor = $this->input->get('umoor');

    $flag = $this->AccountM->delete_raza($id);

    if ($flag) {
      // Check the value of $umoor and redirect accordingly
      if ($umoor == 'Event Raza Applications') {
        redirect('/amilsaheb/success/EventRazaRequest');
      } else {
        redirect('/amilsaheb/success/UmoorRazaRequest');
      }
    } else {
      // Check the value of $umoor and redirect to the appropriate error URL
      if ($umoor == 'Event Raza Applications') {
        redirect('/amilsaheb/error/EventRazaRequest');
      } else {
        redirect('/amilsaheb/error/UmoorRazaRequest');
      }
    }
  }
  public function success($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('amilsaheb/Header', $data);
    $this->load->view('amilsaheb/Success.php', $data);
  }
  public function error($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('amilsaheb/Header', $data);
    $this->load->view('amilsaheb/Error.php', $data);
  }
  public function rejectRaza()
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $remark = trim($_POST['remark']);
    $raza_id = $_POST['raza_id'];
    $flag = $this->AmilsahebM->reject_raza($raza_id, $remark);
    $user = $this->AdminM->get_user_by_raza_id($raza_id);

    $this->email->from('info@kharjamaat.in', 'Admin');
    $this->email->to($user['Email']);
    $this->email->subject('Raza Status');
    $this->email->message('Sorry. Your Raza has been Rejected by Amil Saheb. Contact jamaat office for further assistance');
    $this->email->send();

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('amilsaheb@kharjamaat.in');
    $this->email->subject('Raza Recommended');
    $this->email->message('Sorry. Your Raza has been Rejected by Amil Saheb. Contact jamaat office for further assistance');
    $this->email->send();

    $msg = $user['Full_Name'] . ' (' . $user['ITS_ID'] . ').Raza has been Rejected by Amil Saheb';

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('kharjamaat@gmail.com');
    $this->email->subject('Raza Rejected');
    $this->email->message($msg);
    $this->email->send();

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('3042@carmelnmh.in');
    $this->email->subject('Raza Rejected');
    $this->email->message($msg);
    $this->email->send();

    $this->email->from('admin@kharjamaat.in', 'Admin');
    $this->email->to('anjuman@kharjamaat.in');
    $this->email->subject('Raza Rejected');
    $this->email->message($msg);
    $this->email->send();

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }
  public function managemiqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Miqaat/CreateMiqaat', $data);
  }
  public function addmiqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Miqaat/AddMiqaat');
  }
  public function submitmiqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $miqaatname = $this->input->post('miqaatname');
    $miqaatdesc = $this->input->post('miqaatdesc');
    $miqaatdate = $this->input->post('miqaatdate');
    $miqaattime = $this->input->post('miqaattime');
    $miqaathijridate = $this->input->post('miqaathijridate');
    $miqaatexpired = $this->input->post('miqaatexpired');
    $data = array(
      'name' => $miqaatname,
      'description' => $miqaatdesc,
      'date' => $miqaatdate,
      'time' => $miqaattime,
      'hijri_date' => $miqaathijridate,
      'expired' => $miqaatexpired,
    );
    $check = $this->AdminM->insert_miqaat($data);
    if ($check) {
      redirect('/amilsaheb/success/managemiqaat');
    } else {
      redirect('/amilsaheb/error/managemiqaat');
    }
  }
  public function modifymiqaat($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Miqaat/ModifyMiqaat', $data);
  }
  public function submitmodifymiqaat($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $miqaatname = $this->input->post('miqaatname');
    $miqaatdesc = $this->input->post('miqaatdesc');
    $miqaatdate = $this->input->post('miqaatdate');
    $miqaattime = $this->input->post('miqaattime');
    $miqaathijridate = $this->input->post('miqaathijridate');
    $miqaatexpired = $this->input->post('miqaatexpired');
    $data = array(
      'name' => $miqaatname,
      'description' => $miqaatdesc,
      'date' => $miqaatdate,
      'time' => $miqaattime,
      'hijri_date' => $miqaathijridate,
      'expired' => $miqaatexpired,
    );
    $check = $this->AdminM->modify_miqaat($data, $id);
    if ($check) {
      redirect('/amilsaheb/success/managemiqaat');
    } else {
      redirect('/amilsaheb/error/managemiqaat');
    }
  }
  function deletemiqaat($id)
  {
    $check = $this->AdminM->delete_miqaat($id);
    if ($check) {
      redirect('/amilsaheb/success/managemiqaat');
    } else {
      redirect('/amilsaheb/error/managemiqaat');
    }
  }

  public function miqaatattendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp_list'] = $this->AdminM->get_all_rsvp();
    foreach ($data['rsvp_list'] as $key => $rv) {
      $data['rsvp_list'][$key]['total_marked_attendance'] = $this->AdminM->get_rsvp_attendance($rv['id']);
      $data['rsvp_list'][$key]['total_present_attendance'] = $this->AdminM->get_rsvp_attendance_present($rv['id']);
      $data['rsvp_list'][$key]['total_absent_attendance'] = $data['rsvp_list'][$key]['total_marked_attendance'] - $this->AdminM->get_rsvp_attendance_present($rv['id']);
      $data['rsvp_list'][$key]['total_unmarked_attendance'] = $this->AdminM->get_user_count() - $data['rsvp_list'][$key]['total_marked_attendance'];
      $temp = $this->AdminM->get_rsvp_attendance_present_gender($rv['id']);
      $data['rsvp_list'][$key]['total_present_attendance_gents'] = $temp['male_count'];
      $data['rsvp_list'][$key]['total_present_attendance_ladies'] = $temp['female_count'];
      $guest = $this->AdminM->get_rsvp_attendance_guest($rv['id']);
      $data['rsvp_list'][$key]['total_present_guest_male'] = $guest['male_count'];
      $data['rsvp_list'][$key]['total_present_guest_female'] = $guest['female_count'];
    }
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Miqaat/MiqaatAttendance', $data);
  }

  public function mumineendirectory()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    // If GET filters are present, use server-side filtering so initial payload is filtered
    $get = $this->input->get();
    if (!empty($get)) {
      $data['users'] = $this->AmilsahebM->get_users_filtered($get);
    } elseif ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      // fallback: if model has search_users use it, otherwise do simple name search
      if (method_exists($this->AmilsahebM, 'search_users')) {
        $data['users'] = $this->AmilsahebM->search_users($keyword);
      } else {
        $data['users'] = $this->AmilsahebM->get_users_filtered(['name' => $keyword]);
      }
    } else {
      $data['users'] = $this->AmilsahebM->get_all_users();
    }
    // Always provide full user list for lookups (HOF name resolution, filters)
    $data['all_users'] = $this->AmilsahebM->get_all_users();
    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Mumineendirectory', $data);
  }
  public function update_user_details()
  {
    // Load model
    $this->load->model('AmilsahebM');

    // Get posted data
    $data = $this->input->post();

    // Extract ITS_ID and remove it from update array
    $its_id = $data['ITS_ID'] ?? null;
    unset($data['ITS_ID']);

    // Validate ITS_ID
    if (!$its_id) {
      echo json_encode(['success' => false, 'error' => 'ITS_ID missing']);
      return;
    }

    // Update via model
    $updated = $this->AmilsahebM->update_user_by_its_id($its_id, $data);

    echo json_encode(['success' => $updated]);
  }

  public function appointment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Appointment/Home', $data);
  }
  public function slots_calendar() {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $this->load->model('AmilsahebM');
    $data['user_name'] = $_SESSION['user']['username'];

    // Use Hijri month/year selection. Accept ?hijri_month & ?hijri_year; otherwise compute from today.
    $this->load->model('HijriCalendar');
    $hijri_month = $this->input->get('hijri_month');
    $hijri_year = $this->input->get('hijri_year');

    if (empty($hijri_month) || empty($hijri_year)) {
      $parts = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      if ($parts) {
        $hijri_month = $parts['hijri_month'];
        $hijri_year = $parts['hijri_year'];
      }
    }

    // provide lists for selects
    $data['hijri_years'] = $this->HijriCalendar->get_distinct_hijri_years();
    $data['hijri_months'] = $this->HijriCalendar->get_hijri_months_for_year($hijri_year);
    $data['hijri_month'] = (int)$hijri_month;
    $data['hijri_year'] = (int)$hijri_year;

    // get gregorian date range for the selected hijri month
    $hijri_days = $this->HijriCalendar->get_hijri_days_for_month_year($data['hijri_month'], $data['hijri_year']);
    if (!empty($hijri_days)) {
      $start = $hijri_days[0]['greg_date'];
      $end = $hijri_days[count($hijri_days)-1]['greg_date'];
      $data['slot_statuses'] = $this->AmilsahebM->get_slot_summary_for_range($start, $end);
      // also provide hijri_days to view so the calendar renders hijri dates mapped to gregorian
      $data['hijri_days'] = $hijri_days;
      // set view_month/year to the gregorian month/year of the start date for initial table context
      $sd = new DateTime($start);
      $data['view_year'] = (int)$sd->format('Y');
      $data['view_month'] = (int)$sd->format('n');
    } else {
      // fallback to current gregorian month
      $data['view_year'] = (int)date('Y');
      $data['view_month'] = (int)date('n');
      $data['slot_statuses'] = $this->AmilsahebM->get_slot_summary_for_month($data['view_year'], $data['view_month']);
    }

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Appointment/SlotsCalendar', $data);
  }
  public function manage_slots()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $this->load->model('AmilsahebM');
    // allow ?start=YYYY-MM-DD&end=YYYY-MM-DD to open manage_slots for a week range
    $startParam = $this->input->get('start');
    $endParam = $this->input->get('end');
    $requested = $this->input->get('date');
    if (!empty($startParam) && !empty($endParam)) {
      $ds = DateTime::createFromFormat('Y-m-d', $startParam);
      $de = DateTime::createFromFormat('Y-m-d', $endParam);
      if ($ds && $ds->format('Y-m-d') === $startParam && $de && $de->format('Y-m-d') === $endParam) {
        $_SESSION['slotdate'] = $startParam;
        $_SESSION['slotdate_end'] = $endParam;
      } else {
        $_SESSION['slotdate'] = date('Y-m-d');
        unset($_SESSION['slotdate_end']);
      }
    } elseif (!empty($requested)) {
      // basic validation for single date
      $d = DateTime::createFromFormat('Y-m-d', $requested);
      if ($d && $d->format('Y-m-d') === $requested) {
        $_SESSION['slotdate'] = $requested;
        unset($_SESSION['slotdate_end']);
      } else {
        $_SESSION['slotdate'] = date('Y-m-d');
        unset($_SESSION['slotdate_end']);
      }
    } else {
      $_SESSION['slotdate'] = date('Y-m-d');
      unset($_SESSION['slotdate_end']);
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['slots'] = $this->AmilsahebM->getSlotsData();

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Appointment/AddSlot', $data);
  }
  public function save_slots()
  {
    $selectedDate = $this->input->post('selected_date');
    $selectedTimeSlots = $this->input->post('selected_time_slot');
    // Normalize session values
    $rangeStart = isset($_SESSION['slotdate']) ? $_SESSION['slotdate'] : null;
    $rangeEnd = isset($_SESSION['slotdate_end']) ? $_SESSION['slotdate_end'] : null;

    if (!empty($selectedDate) && !empty($selectedTimeSlots) && is_array($selectedTimeSlots)) {
      // If a week range is active, apply changes to each date in range; otherwise apply to single selected_date
      $datesToProcess = [];
      if (!empty($rangeStart) && !empty($rangeEnd)) {
        $ds = DateTime::createFromFormat('Y-m-d', $rangeStart);
        $de = DateTime::createFromFormat('Y-m-d', $rangeEnd);
        if ($ds && $de && $ds->format('Y-m-d') === $rangeStart && $de->format('Y-m-d') === $rangeEnd && $ds <= $de) {
          $period = new DatePeriod($ds, new DateInterval('P1D'), (clone $de)->modify('+1 day'));
          foreach ($period as $dt) $datesToProcess[] = $dt->format('Y-m-d');
        } else {
          $datesToProcess[] = $selectedDate;
        }
      } else {
        $datesToProcess[] = $selectedDate;
      }

      foreach ($datesToProcess as $d) {
        // remove slots not in selected list
        $old_slots = $this->AmilsahebM->getExistingTimeSlots($d);
        foreach ($old_slots as $os) {
          if (!in_array($os['time'], $selectedTimeSlots)) {
            $this->AmilsahebM->deleteSlot($os['slot_id']);
            $this->AmilsahebM->unassignSlot($os['slot_id']);
          }
        }
        // refresh existing slots and add missing ones
        $existing = $this->AmilsahebM->getExistingTimeSlots($d);
        $existingTimes = array_map(function($r){ return $r['time']; }, $existing);
        foreach ($selectedTimeSlots as $sel) {
          if (!in_array($sel, $existingTimes)) {
            $this->AmilsahebM->addSlot($d, $sel);
          }
        }
      }

      // After processing, keep session slotdate = first processed date
      $_SESSION['slotdate'] = $datesToProcess[0];
      if (count($datesToProcess) > 1) $_SESSION['slotdate_end'] = end($datesToProcess);

      $data['user_name'] = $_SESSION['user']['username'];
      $data['slots'] = $this->AmilsahebM->getSlotsData();
      $this->load->view('Amilsaheb/Header', $data);
      $this->load->view('Amilsaheb/Appointment/AddSlot', $data);
    } else {
      // No selected time slots: treat as delete-all for the selected date or range
      $toDelete = [];
      if (!empty($rangeStart) && !empty($rangeEnd)) {
        $ds = DateTime::createFromFormat('Y-m-d', $rangeStart);
        $de = DateTime::createFromFormat('Y-m-d', $rangeEnd);
        if ($ds && $de && $ds->format('Y-m-d') === $rangeStart && $de->format('Y-m-d') === $rangeEnd && $ds <= $de) {
          $period = new DatePeriod($ds, new DateInterval('P1D'), (clone $de)->modify('+1 day'));
          foreach ($period as $dt) $toDelete[] = $dt->format('Y-m-d');
        } else {
          $toDelete[] = $selectedDate ?: date('Y-m-d');
        }
      } else {
        $toDelete[] = $selectedDate ?: date('Y-m-d');
      }
      foreach ($toDelete as $d) {
        $old_slot = $this->AmilsahebM->getExistingTimeSlots($d);
        foreach ($old_slot as $os) {
          $this->AmilsahebM->deleteSlot($os['slot_id']);
          $this->AmilsahebM->unassignSlot($os['slot_id']);
        }
      }
      $data['user_name'] = $_SESSION['user']['username'];
      $data['slots'] = $this->AmilsahebM->getSlotsData();
      $this->load->view('Amilsaheb/Header', $data);
      $this->load->view('Amilsaheb/Appointment/AddSlot', $data);
      echo '<script>alert("All Slots Deleted successful!");</script>';
    }
  }

  public function getExistingTimeSlots()
  {
    // Get the selected date from the AJAX request
    $selectedDate = $this->input->get('date');

    // Fetch existing time slots from the model
    $existingTimeSlots = $this->AmilsahebM->getExistingTimeSlots($selectedDate);

    // Return the result as JSON
    echo json_encode($existingTimeSlots);
  }

  public function manage_appointment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['appointment_list'] = $this->AmilsahebM->get_all_appointment();
    $data['total'] = count($data['appointment_list']);

    $count = 0;
    foreach ($data['appointment_list'] as $item) {
      if ($item['status'] == 0) {
        $count++;
      }
    }

    $data['pending'] = $count;
    $data['attended'] = count($data['appointment_list']) - $data['pending'];

    $current = [];
    $upcoming = [];
    $remaining = [];

    $today = date('Y-m-d');

    foreach ($data['appointment_list'] as $appointment) {
      if ($appointment['date'] == $today) {
        $current[] = $appointment;
      } elseif ($appointment['date'] > $today) {
        $upcoming[] = $appointment;
      } else {
        $remaining[] = $appointment;
      }
    }
    $data['appointment_list'] = array_merge($current, $upcoming, $remaining);

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Appointment/ManageAppointments', $data);
  }
  public function update_appointment_list($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $check = $this->AmilsahebM->update_appointment_list($id);
    if ($check) {
      redirect('/amilsaheb/manage_appointment');
    } else {
      redirect('/amilsaheb/error/manage_appointment');
    }
  }
  public function asharaohbat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Year selection (Hijri)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $current_hijri_year = (int)explode('-', $h['hijri_date'])[2];
    $selected_year = (int)($this->input->get('year') ?: $current_hijri_year);
    // Fetch years directly from HijriCalendar for the dropdown
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      $year_options = [$current_hijri_year - 1, $current_hijri_year, $current_hijri_year + 1];
    }
    if (!in_array($selected_year, $year_options, true)) {
      array_unshift($year_options, $selected_year);
    }
    $yearColumnExists = $this->db->field_exists('year', 'ashara_ohbat');

    // Fetch users based on search or get all
    if ($this->input->post('search')) {
      $users = $this->AmilsahebM->search_all_ashara($this->input->post('search'), $selected_year);
    } else {
      $users = $this->AmilsahebM->get_all_ashara($selected_year);
    }

    if (!$yearColumnExists && $selected_year !== $current_hijri_year) {
      // No year column: emulate "no data for selected year" by clearing status/comment
      foreach ($users as &$u) {
        $u['LeaveStatus'] = '';
        $u['Comment'] = '';
      }
      unset($u);
    }

    // Fetch overall sector and sub-sector stats
    $sectorsData = $this->AmilsahebM->get_all_sector_stats($selected_year);
    $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats($selected_year);
    if (!$yearColumnExists && $selected_year !== $current_hijri_year) {
      $sectorsData = [];
      $subSectorsData = [];
    }

    // Initialize stats
    $stats = [
      'HOF' => 0,
      'FM' => 0,
      'Mardo' => 0,
      'Bairo' => 0,
      'Age_0_4' => 0,
      'Age_5_15' => 0,
      'Buzurgo' => 0,
      'LeaveStatus' => [],
      'Sectors' => $sectorsData,
      'SubSectors' => $subSectorsData
    ];

    foreach ($users as $u) {
      $type = $u['HOF_FM_TYPE'] ?? '';
      $gender = strtolower($u['Gender'] ?? '');
      $age = isset($u['Age']) ? intval($u['Age']) : 0;
      $status = $u['LeaveStatus'] ?? 'Unknown';

      if ($type === 'HOF')
        $stats['HOF']++;
      if ($type === 'FM')
        $stats['FM']++;
      if ($gender === 'male')
        $stats['Mardo']++;
      if ($gender === 'female')
        $stats['Bairo']++;
      if ($age >= 0 && $age <= 4)
        $stats['Age_0_4']++;
      if ($age >= 5 && $age <= 15)
        $stats['Age_5_15']++;
      if ($age > 65)
        $stats['Buzurgo']++;

      if (!isset($stats['LeaveStatus'][$status])) {
        $stats['LeaveStatus'][$status] = 0;
      }
      $stats['LeaveStatus'][$status]++;
    }

    $data = [
      'user_name' => $username,
      'users' => $users,
      'stats' => $stats,
      'current_sector' => '',
      'current_sub_sector' => '',
      'selected_year' => $selected_year,
      'year_options' => $year_options,
      'back_url' => base_url('amilsaheb')
    ];

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('MasoolMusaid/AsharaOhbat', $data);
  }

  public function ashara_attendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Use GET parameters if available
    $sel_sector = $this->input->get('sector');
    $sel_sub = $this->input->get('subsector');

    // Hijri Year selection (UI scope only; attendance table not year-scoped)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $current_hijri_year = (int)explode('-', $h['hijri_date'])[2];
    $selected_year = (int)($this->input->get('year') ?: $current_hijri_year);
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      $year_options = [$current_hijri_year - 1, $current_hijri_year, $current_hijri_year + 1];
    }
    if (!in_array($selected_year, $year_options, true)) {
      array_unshift($year_options, $selected_year);
    }

    // Fetch all sectors and sub-sectors for dropdowns
    $all_sectors = $this->MasoolMusaidM->get_all_sectors();
    $all_sub_sectors = $sel_sector ? $this->MasoolMusaidM->get_all_sub_sectors($sel_sector) : [];

    // Fetch attendance data
    if ($this->input->post('search')) {
      $kw = $this->input->post('search', true);
      $users = $this->MasoolMusaidM->search_attendance_by_sector($kw, $sel_sector, $sel_sub, $selected_year);
    } else {
      $users = $this->MasoolMusaidM->get_attendance_by_sector($sel_sector, $sel_sub, $selected_year);
    }

    // Stats
    $stats = $this->MasoolMusaidM->get_sector_stats($sel_sector, $sel_sub, $selected_year);

    // View Data
    $data = [
      'username' => $username,
      'user_sector' => '',
      'user_sub' => '',
      'sel_sector' => $sel_sector,
      'sel_sub' => $sel_sub,
      'all_sectors' => $all_sectors,
      'all_sub_sectors' => $all_sub_sectors,
      'users' => $users,
      'stats' => $stats,
      'user_name' => $username,
      'days' => range(2, 9),
      'status_options' => [
        'Attended with Maula',
        'Attended in Khar on Time',
        'Attended in Khar Late',
        'Attended in Other Jamaat',
        'Not attended anywhere',
        'Not in Town',
        'Married Outcaste'
      ],
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load view
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }
}
