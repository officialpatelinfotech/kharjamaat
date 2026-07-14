<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MasoolMusaid extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('MasoolMusaidM'); // ✅ Load your dedicated model
    $this->load->model('CommonM');
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->library('email', $this->config->item('email'));
    $this->load->model('HijriCalendar');
  }

  // public function index()
  // {
  //   // ✅ Restrict access strictly to role 16 (Masool/Musaid)
  //   if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
  //     redirect('/accounts');
  //   }

  //   $username = $_SESSION['user']['username'];
  //   $data['user_name'] = $username;

  //   // Derive sector/sub-sector scope from username (e.g., BurhaniA -> sector=Burhani, sub=A)
  //   $user_sector = '';
  //   $user_sub = '';
  //   if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $m)) {
  //     $user_sector = ucfirst(strtolower($m[1] ?? ''));
  //     $user_sub = strtoupper($m[2] ?? '');
  //   }

  //   // Build current week Monday–Sunday
  //   $start_date = date('Y-m-d', strtotime('monday this week'));
  //   $end_date = date('Y-m-d', strtotime('sunday this week'));
  //   $days = 7;

  //   // Iterate each day, aggregate signed-up families per sector/sub-sector within scope
  //   $bySub = [];
  //   $cursor = strtotime($start_date);
  //   $endTs = strtotime($end_date);
  //   while ($cursor !== false && $cursor <= $endTs) {
  //     $d = date('Y-m-d', $cursor);
  //     $bd = $this->CommonM->get_thaali_signup_breakdown($d);
  //     $rows = isset($bd['breakdown']) ? $bd['breakdown'] : [];
  //     foreach ($rows as $r) {
  //       $sec = trim($r['sector'] ?? '');
  //       $sub = trim($r['sub_sector'] ?? '');
  //       if ($sec === '' || strcasecmp($sec, $user_sector) !== 0) continue; // only within user's sector
  //       if ($user_sub !== '' && strcasecmp($sub, $user_sub) !== 0) continue; // if user bound to sub-sector, filter it
  //       $key = $sec . '||' . $sub;
  //       if (!isset($bySub[$key])) {
  //         $bySub[$key] = ['sector' => $sec, 'sub_sector' => $sub, 'total' => 0];
  //       }
  //       $bySub[$key]['total'] += (int)($r['signed_up'] ?? 0);
  //     }
  //     $cursor = strtotime('+1 day', $cursor);
  //   }

  //   // Ensure zero entries for sub-sectors (when user is sector-wide)
  //   if ($user_sector !== '' && $user_sub === '') {
  //     $subs = $this->MasoolMusaidM->get_all_sub_sectors($user_sector);
  //     foreach ($subs as $row) {
  //       $sub = trim($row['sub_sector'] ?? '');
  //       $key = $user_sector . '||' . $sub;
  //       if (!isset($bySub[$key])) {
  //         $bySub[$key] = ['sector' => $user_sector, 'sub_sector' => $sub, 'total' => 0];
  //       }
  //     }
  //   }

  //   // Compose display items with avg/day
  //   $items = [];
  //   foreach ($bySub as $entry) {
  //     $items[] = [
  //       'sector' => $entry['sector'],
  //       'sub_sector' => $entry['sub_sector'],
  //       'total' => (int)$entry['total'],
  //       'avg' => $days > 0 ? round(((int)$entry['total']) / $days, 2) : 0,
  //     ];
  //   }
  //   // Sort by total desc for readability
  //   usort($items, function($a, $b){ return ($b['total'] ?? 0) <=> ($a['total'] ?? 0); });

  //   $data['weekly_signup_avg'] = [
  //     'start' => $start_date,
  //     'end' => $end_date,
  //     'days' => $days,
  //     'items' => $items,
  //     'scope' => [ 'sector' => $user_sector, 'sub_sector' => $user_sub ],
  //   ];

  //   $this->load->view('MasoolMusaid/Header', $data);
  //   $this->load->view('MasoolMusaid/Home', $data);
  // }



  public function index()
  {
    // 🔐 Auth
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $data['user_name'] = $username;

    $this->load->model('CommonM');
    $this->load->model('HijriCalendar');

    /* ===============================
       ✅ EARLY JSON HANDLER (CRITICAL)
    ================================ */
    if ($this->input->get('format') === 'json') {

      // Extract sector and optional sub-sector from username
      $sector = '';
      $subsector = '';
      if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
        $sector = $matches[1];
        $subsector = $matches[2];
      }

      $miqaat_prev = $this->input->get('miqaat_prev');
      if ($miqaat_prev) {
        $before = $this->input->get('before_date');
        $prev_miqaat = $this->db->query("SELECT m.id, m.name, m.type, m.date, m.assigned_to
            FROM miqaat m
            JOIN raza r ON r.miqaat_id = m.id AND r.`Janab-status` = 1
            WHERE m.date < ?
            ORDER BY m.date DESC
            LIMIT 1", [$before])->row_array();
        if ($prev_miqaat) {
          $hijri_date = $this->HijriCalendar->get_hijri_parts_by_greg_date($prev_miqaat['date']);
          $hijri_month = $this->db->where('id', $hijri_date['hijri_month'])->get('hijri_month')->row_array()['hijri_month'] ?? '';
          $prev_miqaat['hijri_date'] = ($hijri_date['hijri_date'] ?? '') . ' ' . $hijri_month;
          return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true, 'miqaat' => $prev_miqaat]));
        } else {
          return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => false]));
        }
      }

      if ($this->input->get('miqaat_rsvp') == 1) {
        $miqaat_id = $this->input->get('miqaat_id');
        $m = $this->MasoolMusaidM->get_scoped_next_miqaat_rsvp_stats($sector, $subsector, $miqaat_id);
        return $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode([
            'success' => true,
            'miqaat_rsvp' => $m
          ]));
      }

      $hijri_year = (int) $this->input->get('hijri_year');
      $hijri_month = (int) $this->input->get('hijri_month');

      if ($hijri_year && $hijri_month) {

        // 🔥 SAME METHOD AS ANJUMAN
        $mstats = $this->CommonM
          ->get_monthly_thaali_stats($hijri_month, $hijri_year, $username);

        return $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode([
            'success' => true,
            'monthly_stats' => $mstats
          ]));
      }

      return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
          'success' => false,
          'error' => 'Missing hijri month/year'
        ]));
    }

    /* ===============================
       NORMAL PAGE LOAD (HTML)
    ================================ */

    // 📅 Hijri selection
    $hijri_year = $this->input->get('hijri_year');
    $hijri_month = $this->input->get('hijri_month');

    if ($hijri_year && $hijri_month) {
      $data['selected_hijri_parts'] = [
        'hijri_year' => $hijri_year,
        'hijri_month' => $hijri_month
      ];
    } else {
      $data['selected_hijri_parts'] =
        $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
    }

    // 📊 Weekly
    $start_date = date('Y-m-d', strtotime('monday this week'));
    $end_date = date('Y-m-d', strtotime('sunday this week'));

    $data['weekly_signup_avg'] =
      $this->CommonM->get_weekly_thaali_by_username(
        $username,
        $start_date,
        $end_date
      );

    // 📆 Monthly (for cards only)
    $data['month_stats'] =
      $this->CommonM->get_monthly_thaali_by_username(
        $username,
        $data['selected_hijri_parts']['hijri_month'],
        $data['selected_hijri_parts']['hijri_year']
      );

    // Extract sector and optional sub-sector from username
    $sector = '';
    $subsector = '';
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }

    $incharge_male = '';
    $incharge_female = '';
    if ($subsector !== '') {
      $row = $this->db->select('Sub_Sector_Incharge_Name, Sub_Sector_Incharge_Female_Name')
                      ->from('user')
                      ->where('Sector', $sector)
                      ->where('Sub_Sector', $subsector)
                      ->group_start()
                        ->where("Sub_Sector_Incharge_Name != '' AND Sub_Sector_Incharge_Name IS NOT NULL")
                        ->or_where("Sub_Sector_Incharge_Female_Name != '' AND Sub_Sector_Incharge_Female_Name IS NOT NULL")
                      ->group_end()
                      ->limit(1)
                      ->get()
                      ->row_array();
      if ($row) {
        $incharge_male = trim($row['Sub_Sector_Incharge_Name'] ?? '');
        $incharge_female = trim($row['Sub_Sector_Incharge_Female_Name'] ?? '');
      }
    } else {
      $row = $this->db->select('Sector_Incharge_Name, Sector_Incharge_Female_Name')
                      ->from('user')
                      ->where('Sector', $sector)
                      ->group_start()
                        ->where("Sector_Incharge_Name != '' AND Sector_Incharge_Name IS NOT NULL")
                        ->or_where("Sector_Incharge_Female_Name != '' AND Sector_Incharge_Female_Name IS NOT NULL")
                      ->group_end()
                      ->limit(1)
                      ->get()
                      ->row_array();
      if ($row) {
        $incharge_male = trim($row['Sector_Incharge_Name'] ?? '');
        $incharge_female = trim($row['Sector_Incharge_Female_Name'] ?? '');
      }
    }
    $data['incharge_male'] = $incharge_male;
    $data['incharge_female'] = $incharge_female;

    $sectorsData = $this->MasoolMusaidM->get_sectors_stats($sector, $subsector);
    $subSectorsData = $this->MasoolMusaidM->get_sub_sectors_stats($sector, $subsector);

    $residentOverview = $this->MasoolMusaidM->get_resident_overview_counts($sector, $subsector, true);
    $stats = [
      'HOF' => (int)($residentOverview['hof'] ?? 0),
      'FM' => (int)($residentOverview['fm'] ?? 0),
      'Mardo' => (int)($residentOverview['male'] ?? 0),
      'Bairo' => (int)($residentOverview['female'] ?? 0),
      'Age_0_4' => (int)($residentOverview['age_0_4'] ?? 0),
      'Age_5_15' => (int)($residentOverview['age_5_15'] ?? 0),
      'Age_16_25' => (int)($residentOverview['age_16_25'] ?? 0),
      'Age_26_65' => (int)($residentOverview['age_26_65'] ?? 0),
      'Buzurgo' => (int)($residentOverview['seniors'] ?? 0),
      'Sectors' => $sectorsData,
      'SubSectors' => $subSectorsData,
      'deeni_eligible' => $this->MasoolMusaidM->get_deeni_eligible_count($sector, $subsector),
      'deeni_taking' => $this->MasoolMusaidM->get_deeni_taking_count($sector, $subsector),
      'madresa_deprived' => $this->MasoolMusaidM->get_madresa_deprived_count($sector, $subsector),
      'singles_21_40' => $this->MasoolMusaidM->get_singles_21_40_count($sector, $subsector),
      'status_counts' => $this->MasoolMusaidM->get_status_counts($sector, $subsector),
      'active_inactive' => $this->MasoolMusaidM->get_active_inactive_counts($sector, $subsector),
    ];

    // RSVP stats
    $upcoming_miqaats = $this->db->query("SELECT m.id, m.name, m.type, m.date, m.assigned_to 
                                          FROM miqaat m 
                                          JOIN raza r ON r.miqaat_id = m.id AND r.`Janab-status` = 1
                                          WHERE m.date >= CURDATE() AND m.status = 1
                                          ORDER BY m.date ASC 
                                          LIMIT 5")->result_array();
    if (!empty($upcoming_miqaats)) {
      foreach ($upcoming_miqaats as &$um) {
        $hijri_date = $this->HijriCalendar->get_hijri_parts_by_greg_date($um['date']);
        $hijri_month = $this->db->where('id', $hijri_date['hijri_month'])->get('hijri_month')->row_array()['hijri_month'] ?? '';
        $um['hijri_label'] = ($hijri_date['hijri_date'] ?? '') . ' ' . $hijri_month;
      }
    }
    $data['upcoming_miqaats'] = $upcoming_miqaats;
    
    // Seed initial index of target miqaat
    $miq_rsvp = $this->MasoolMusaidM->get_scoped_next_miqaat_rsvp_stats($sector, $subsector);
    $data['miqaat_rsvp'] = $miq_rsvp;
    
    $initial_index = 0;
    $initial_id = isset($miq_rsvp['next_miqaat']['id']) ? $miq_rsvp['next_miqaat']['id'] : (isset($upcoming_miqaats[0]['id']) ? $upcoming_miqaats[0]['id'] : '');
    foreach ($upcoming_miqaats as $k => $m) {
      if (isset($m['id']) && $m['id'] == $initial_id) {
        $initial_index = $k;
        break;
      }
    }
    $data['initial_index'] = $initial_index;

    $data['stats'] = $stats;
    $data['current_sector'] = $sector;
    $data['current_sub_sector'] = $subsector;
    $data['marital_status_counts'] = $this->MasoolMusaidM->get_marital_status_distribution($sector, $subsector);
    $data['year_daytype_stats'] = $this->CommonM->get_year_calendar_daytypes();

    // ── Husaini Scheme stats for dashboard ─────────────────
    $this->load->model('QardanHasanaM');
 
    // All active members in this sector/subsector
    $husaini_members = $this->MasoolMusaidM->get_members_for_husaini($sector, $subsector);
 
    // All ITS that have ever paid into Husaini scheme
    $husaini_payers_list = $this->MasoolMusaidM->get_husaini_payer_its_list();
    $husaini_payers_set  = array_flip($husaini_payers_list);
 
    $husaini_active_given     = 0;
    $husaini_active_not_given = 0;
 
    foreach ($husaini_members as $m) {
        $its = (string)($m['ITS_ID'] ?? '');
 
        // Determine active status (same logic as qardanhasana controller)
        $inactive_status = trim((string)($m['Inactive_Status']   ?? ''));
        $activity_status = strtolower(trim((string)($m['activity_status'] ?? '')));
        $is_active = ($inactive_status === '')
                  && ($activity_status === '' || $activity_status === 'active');
 
        if (!$is_active) continue; // only count active members
 
        if (isset($husaini_payers_set[$its])) {
            $husaini_active_given++;
        } else {
            $husaini_active_not_given++;
        }
    }
 
    $data['husaini_stats'] = [
        'active_given'     => $husaini_active_given,
        'active_not_given' => $husaini_active_not_given,
        'active_total'     => $husaini_active_given + $husaini_active_not_given,
    ];
 
     $this->load->view('MasoolMusaid/Header', $data);
     $this->load->view('MasoolMusaid/Home', $data);
   }
 
   public function search_members_json()
   {
     if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
       echo json_encode([]);
       return;
     }
     $username = $_SESSION['user']['username'];
     $sector = '';
     $subsector = '';
     if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
       $sector = $matches[1];
       $subsector = $matches[2];
     }
     $query = $this->input->get('query');
     $results = $this->MasoolMusaidM->search_members($query, $sector, $subsector);
     echo json_encode($results);
   }

  public function miqaat_rsvp_user_counts()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      return;
    }
    $username = $_SESSION['user']['username'];
    $sector = ''; $subsector = '';
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }
    $miqaat_id = (int)$this->input->get('miqaat_id');
    $this->output->set_content_type('application/json');
    if (!$miqaat_id) {
      echo json_encode(['success' => false, 'message' => 'miqaat_id required']);
      return;
    }

    $m = $this->MasoolMusaidM->get_scoped_next_miqaat_rsvp_stats($sector, $subsector, $miqaat_id);
    echo json_encode([
      'success' => true,
      'miqaat_id' => $miqaat_id,
      'will_attend' => $m['combined_summary']['total'],
      'will_not_attend' => $m['will_not_attend'],
      'rsvp_not_submitted' => $m['rsvp_not_submitted']
    ]);
  }

  public function mumineendirectory()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    if ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      $data['users'] = $this->MasoolMusaidM->search_users_by_sector($keyword, $sector, $subsector);
    } else {
      $data['users'] = $this->MasoolMusaidM->get_users_by_sector($sector, $subsector);
    }

    // Fetch and merge LeaveStatus for the current/selected Hijri Year
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts[2];
    $current_hijri_month = (int)$hijri_parts[1];
    $default_year = $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year);

    $ohbat_rows = $this->db->where('year', $selected_year)->get('ashara_ohbat')->result_array();
    $ohbat_map = [];
    foreach ($ohbat_rows as $row) {
      $ohbat_map[$row['ITS']] = $row['LeaveStatus'];
    }

    foreach ($data['users'] as &$u) {
      $its = $u['ITS_ID'] ?? $u['ITS'] ?? null;
      $u['LeaveStatus'] = isset($ohbat_map[$its]) && !empty($ohbat_map[$its]) ? $ohbat_map[$its] : "Musaaid didn't Contacted Yet";
    }
    unset($u);

    $data['user_name'] = $username;

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/Mumineendirectory', $data);
  }

  public function update_user_details()
  {
    // Load model
    $this->load->model('MasoolMusaidM');

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
    $updated = $this->MasoolMusaidM->update_user_by_its_id($its_id, $data);

    echo json_encode(['success' => $updated]);
  }


  public function asharaohbat()
  {
    // Authorization check
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Parse sector and optional sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = strtoupper($matches[2]); // Normalize to uppercase
    }

    // Determine Hijri year selection (default to upcoming year if Hijri month >= 10)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts[2];
    $current_hijri_month = (int)$hijri_parts[1];
    $default_year = $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year);
    // Fetch available Hijri years from calendar (fallback to +/-1 range if empty)
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      $year_options = [$current_hijri_year - 1, $current_hijri_year, $current_hijri_year + 1];
    }
    // Ensure the currently selected year appears in options
    if (!in_array($selected_year, $year_options, true)) {
      array_unshift($year_options, $selected_year);
    }

    // Handle search or fetch all (year-scoped if schema supports it)
    if ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      $users = $this->MasoolMusaidM->search_ashara_by_sector($keyword, $sector, $subsector, $selected_year);
    } else {
      $users = $this->MasoolMusaidM->get_ashara_by_sector($sector, $subsector, $selected_year);
    }

    // Default empty/null/Unknown LeaveStatus to "Musaaid didn't Contacted Yet"
    foreach ($users as &$u) {
      if (empty($u['LeaveStatus']) || $u['LeaveStatus'] === 'Unknown') {
        $u['LeaveStatus'] = "Musaaid didn't Contacted Yet";
      }
    }
    unset($u);

    // Get all sectors and sub-sectors data for the logged-in user's scope
    $sectorsData = $this->MasoolMusaidM->get_sectors_stats($sector, $subsector, $selected_year);
    $subSectorsData = $this->MasoolMusaidM->get_sub_sectors_stats($sector, $subsector, $selected_year);

    // Stats initialization
    $stats = [
      'HOF' => 0,
      'FM' => 0,
      'Mardo' => 0,
      'Bairo' => 0,
      'Age_0_4' => 0,
      'Age_5_15' => 0,
      'Age_16_25' => 0,
      'Age_26_65' => 0,
      'Buzurgo' => 0,
      'LeaveStatus' => [],
      'Sectors' => $sectorsData,
      'SubSectors' => $subSectorsData
    ];

    // Populate stats
    foreach ($users as $u) {
      $type = $u['HOF_FM_TYPE'];
      $gender = strtolower($u['Gender']);
      $age = intval($u['Age']);
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
      if ($age >= 16 && $age <= 25)
        $stats['Age_16_25']++;
      if ($age >= 26 && $age <= 65)
        $stats['Age_26_65']++;
      if ($age > 65)
        $stats['Buzurgo']++;

      if (!isset($stats['LeaveStatus'][$status])) {
        $stats['LeaveStatus'][$status] = 0;
      }
      $stats['LeaveStatus'][$status]++;
    }

    // Pass data to views
    $data = [
      'user_name' => $username,
      'users' => $users,
      'stats' => $stats,
      'current_sector' => $sector,
      'current_sub_sector' => $subsector,
      'selected_year' => $selected_year,
      'year_options' => $year_options,
      'back_url' => base_url('MasoolMusaid')
    ];

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/AsharaOhbat', $data);
  }

  public function update_ashara_ohbat_details()
  {
    $ITS = $this->input->post('ITS');
    $leaveStatus = $this->input->post('LeaveStatus');
    $postedYear = $this->input->post('year');

    // Fallback to current Hijri year if not provided
    if ($postedYear) {
      $year = (int)$postedYear;
    } else {
      $today = date('Y-m-d');
      $h = $this->HijriCalendar->get_hijri_date($today);
      $year = (int)explode('-', $h['hijri_date'])[2];
    }

    $updateData = [
      'LeaveStatus' => $leaveStatus,
      'Comment' => $this->input->post('Comment')
    ];

    $this->load->model('MasoolMusaidM');
  $result = $this->MasoolMusaidM->upsert_ashara_row($ITS, $updateData, $year);

    // If LeaveStatus is special, also update ashara_attendance
    if (in_array($leaveStatus, ['Not in Town', 'Married Outcaste'])) {
      // Attendance may be year-scoped
      $this->MasoolMusaidM->update_attendance_leave_status($ITS, $leaveStatus, $year);
    }

    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(['success' => $result]));
  }


  public function ashara_attendance()
  {
    // Authorization
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Extract sector and sub-sector from username
    preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $m);
    $user_sector = ucfirst(strtolower($m[1] ?? ''));
    $user_sub = strtoupper($m[2] ?? '');

    // Use GET parameters if available, else fallback to user's sector/sub-sector
    $sel_sector = $this->input->get('sector') ?? $user_sector;
    $sel_sub = $this->input->get('subsector') ?? $user_sub;

    // Hijri Year selection (UI scope only; attendance table is not year-scoped)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts_att = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts_att[2];
    $current_hijri_month_att = (int)$hijri_parts_att[1];
    $default_year_att = $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year_att);
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      $year_options = [$current_hijri_year - 1, $current_hijri_year, $current_hijri_year + 1];
    }
    if (!in_array($selected_year, $year_options, true)) {
      array_unshift($year_options, $selected_year);
    }

    // Validate selected sector
    $all_sectors = $this->MasoolMusaidM->get_all_sectors();
    if (!in_array($sel_sector, array_column($all_sectors, 'sector'))) {
      $sel_sector = $user_sector;
      $sel_sub = $user_sub;
    }

    // Determine whether to filter by sub-sector or allow search
    if (!empty($user_sub)) {
      $sel_sub = $user_sub;
      $users = $this->MasoolMusaidM->get_attendance_by_sub_sector($user_sector, $user_sub, $selected_year);
      $stats = $this->MasoolMusaidM->get_sub_sector_stats($user_sector, $user_sub, $selected_year);
    } else {
      if ($this->input->post('search')) {
        $kw = $this->input->post('search', true);
        $users = $this->MasoolMusaidM->search_attendance_by_sector($kw, $sel_sector, $sel_sub, $selected_year);
      } else {
        $users = $this->MasoolMusaidM->get_attendance_by_sector($sel_sector, $sel_sub, $selected_year);
      }
      $stats = $this->MasoolMusaidM->get_sector_stats($sel_sector, $sel_sub, $selected_year);
    }

    // Prepare view data
    $data = [
      'username' => $username,
      'user_sector' => $user_sector,
      'user_sub' => $user_sub,
      'sel_sector' => $sel_sector,
      'sel_sub' => $sel_sub,
      'all_sectors' => $all_sectors,
      'users' => $users,
      'stats' => $stats,
      'user_name' => $username,
      'days' => range(2, 9),
      'status_options' => [
        'Attended with Maula',
        'Attended in ' . jamaat_place() . ' on Time',
        'Attended in ' . jamaat_place() . ' Late',
        'Attended in Other Jamaat',
        'Not attended anywhere'
      ],
      'all_sub_sectors' => $this->MasoolMusaidM->get_all_sub_sectors($sel_sector),
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load views
    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }

  public function ashara_attendance_list()
  {
    // Authorization
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Extract sector and sub-sector from username
    preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $m);
    $user_sector = ucfirst(strtolower($m[1] ?? ''));
    $user_sub = strtoupper($m[2] ?? '');

    // Use GET parameters if available, else fallback to user's sector/sub-sector
    $sel_sector = $this->input->get('sector') ?? $user_sector;
    $sel_sub = $this->input->get('subsector') ?? $user_sub;

    // Hijri Year selection (UI scope only; attendance table is not year-scoped)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts_att = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts_att[2];
    $current_hijri_month_att = (int)$hijri_parts_att[1];
    $default_year_att = $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year_att);
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      $year_options = [$current_hijri_year - 1, $current_hijri_year, $current_hijri_year + 1];
    }
    if (!in_array($selected_year, $year_options, true)) {
      array_unshift($year_options, $selected_year);
    }

    // Validate selected sector
    $all_sectors = $this->MasoolMusaidM->get_all_sectors();
    if (!in_array($sel_sector, array_column($all_sectors, 'sector'))) {
      $sel_sector = $user_sector;
      $sel_sub = $user_sub;
    }

    // Determine whether to filter by sub-sector or allow search
    if (!empty($user_sub)) {
      $sel_sub = $user_sub;
      $users = $this->MasoolMusaidM->get_attendance_by_sub_sector($user_sector, $user_sub, $selected_year);
      $stats = $this->MasoolMusaidM->get_sub_sector_stats($user_sector, $user_sub, $selected_year);
    } else {
      if ($this->input->post('search')) {
        $kw = $this->input->post('search', true);
        $users = $this->MasoolMusaidM->search_attendance_by_sector($kw, $sel_sector, $sel_sub, $selected_year);
      } else {
        $users = $this->MasoolMusaidM->get_attendance_by_sector($sel_sector, $sel_sub, $selected_year);
      }
      $stats = $this->MasoolMusaidM->get_sector_stats($sel_sector, $sel_sub, $selected_year);
    }

    // Prepare view data
    $data = [
      'username' => $username,
      'user_sector' => $user_sector,
      'user_sub' => $user_sub,
      'sel_sector' => $sel_sector,
      'sel_sub' => $sel_sub,
      'all_sectors' => $all_sectors,
      'users' => $users,
      'stats' => $stats,
      'user_name' => $username,
      'days' => range(2, 9),
      'status_options' => [
        'Attended with Maula',
        'Attended in ' . jamaat_place() . ' on Time',
        'Attended in ' . jamaat_place() . ' Late',
        'Attended in Other Jamaat',
        'Not attended anywhere'
      ],
      'all_sub_sectors' => $this->MasoolMusaidM->get_all_sub_sectors($sel_sector),
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load views
    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendanceList', $data);
  }




  public function update_attendance()
  {
    $its = $this->input->post('its');
    $dayInput = $this->input->post('day'); // 2–9 or "Ashura"
    $status = $this->input->post('status');
    $comment = $this->input->post('comment');
    $postedYear = $this->input->post('year');

    // Resolve year (default to current Hijri year)
    if ($postedYear) {
      $year = (int)$postedYear;
    } else {
      $today = date('Y-m-d');
      $h = $this->HijriCalendar->get_hijri_date($today);
      $year = (int)explode('-', $h['hijri_date'])[2];
    }

    if (!$its || !$dayInput || !$status) {
      http_response_code(400); // Bad request
      echo json_encode(['error' => 'Missing required fields']);
      return;
    }

    $dayColumn = ($dayInput === 'Ashura') ? 'Ashura' : 'Day' . $dayInput;
    $commentColumn = ($dayInput === 'Ashura') ? 'CommentAshura' : 'Comment' . $dayInput;

    $data = [
      $dayColumn => $status,
      $commentColumn => $comment
    ];

    // Update or insert (year-scoped when column exists)
    if ($this->db->field_exists('year', 'ashara_attendance')) {
      $this->db->where('ITS', $its)->where('year', $year);
      $exists = $this->db->get('ashara_attendance')->row();
      if ($exists) {
        $this->db->where('ITS', $its)->where('year', $year);
        $result = $this->db->update('ashara_attendance', $data);
      } else {
        $data['ITS'] = $its;
        $data['year'] = $year;
        $result = $this->db->insert('ashara_attendance', $data);
      }
    } else {
      // Fallback for legacy schema without year column
      $this->db->where('ITS', $its);
      $exists = $this->db->get('ashara_attendance')->row();
      if ($exists) {
        $this->db->where('ITS', $its);
        $result = $this->db->update('ashara_attendance', $data);
      } else {
        $data['ITS'] = $its;
        $result = $this->db->insert('ashara_attendance', $data);
      }
    }

    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      http_response_code(500); // Internal server error
      echo json_encode(['error' => 'Failed to update attendance']);
    }
  }


  public function bulk_update_attendance()
  {
    $data = json_decode($this->input->raw_input_stream, true);

    $its_list = $data['its_list'] ?? null;
    $day = $data['day'] ?? null;
    $status = $data['status'] ?? null;
    $postedYear = $data['year'] ?? null;

    // Resolve year (default current Hijri)
    if ($postedYear) {
      $year = (int)$postedYear;
    } else {
      $today = date('Y-m-d');
      $h = $this->HijriCalendar->get_hijri_date($today);
      $year = (int)explode('-', $h['hijri_date'])[2];
    }

    if (!$its_list || !$day || !$status) {
      show_error('Invalid data provided.', 400);
    }

    $dayColumn = ($day === 'Ashura' || strpos((string)$day, 'Day') === 0) ? $day : 'Day' . $day;

    foreach ($its_list as $its) {
      if ($this->db->field_exists('year', 'ashara_attendance')) {
        // Try update first
        $this->db->where('ITS', $its)->where('year', $year);
        $exists = $this->db->get('ashara_attendance')->row();
        if ($exists) {
          $this->db->where('ITS', $its)->where('year', $year)->update('ashara_attendance', [ $dayColumn => $status ]);
        } else {
          $row = [ 'ITS' => $its, 'year' => $year, $dayColumn => $status ];
          $this->db->insert('ashara_attendance', $row);
        }
      } else {
        $this->db->where('ITS', $its)->update('ashara_attendance', [ $dayColumn => $status ]);
      }
    }
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true]));
  }

  // Updated by Patel Infotech Services

  public function rsvp_list()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    $data['user_name'] = $username;

    $miqaat_rsvp_counts = $this->MasoolMusaidM->get_rsvp_counts_by_miqaat($sector, $subsector);

    if (isset($miqaat_rsvp_counts)) {
      $data["miqaat_rsvp_counts"] = $miqaat_rsvp_counts;
      foreach ($miqaat_rsvp_counts as $key => $miqaat) {
        $hijri_date = $this->HijriCalendar->get_hijri_date($miqaat['miqaat_date']);
        $hijri_month = $this->HijriCalendar->hijri_month_name(explode("-", $hijri_date["hijri_date"])[1])["hijri_month"];
        $data["miqaat_rsvp_counts"][$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];

        $miqaat_raza_status = $this->AccountM->get_miqaat_raza_status($miqaat["miqaat_id"]);
        if ($miqaat_raza_status) {
          $data["miqaat_rsvp_counts"][$key]["raza_status"] = $miqaat_raza_status ?? 'Unknown';
        } else {
          $data["miqaat_rsvp_counts"][$key]["raza_status"] = 'Unknown';
        }
      }
    } else {
      $data["miqaat_rsvp_counts"] = [];
    }

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/RSVP/Home');
  }

  /**
   * AJAX endpoint: search miqaat rsvp counts and return rendered cards
   */
  public function rsvp_search()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
      return;
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }

    $q = $this->input->get('q', true);
    if ($q === null) $q = '';

    $miqaat_rsvp_counts = $this->MasoolMusaidM->search_rsvp_counts_by_miqaat($q, $sector, $subsector);

    if (isset($miqaat_rsvp_counts)) {
      foreach ($miqaat_rsvp_counts as $key => $miqaat) {
        $hijri_date = $this->HijriCalendar->get_hijri_date($miqaat['miqaat_date']);
        $hijri_month = $this->HijriCalendar->hijri_month_name(explode("-", $hijri_date["hijri_date"])[1])["hijri_month"];
        $miqaat_rsvp_counts[$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];

        $miqaat_raza_status = $this->AccountM->get_miqaat_raza_status($miqaat["miqaat_id"]);
        if ($miqaat_raza_status) {
          $miqaat_rsvp_counts[$key]["raza_status"] = $miqaat_raza_status ?? 'Unknown';
        } else {
          $miqaat_rsvp_counts[$key]["raza_status"] = 'Unknown';
        }
      }
    } else {
      $miqaat_rsvp_counts = [];
    }

    $html = $this->load->view('MasoolMusaid/RSVP/_miqaat_cards', ['miqaat_rsvp_counts' => $miqaat_rsvp_counts], true);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'html' => $html]));
  }

  public function general_rsvp($miqaat_id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    $data['user_name'] = $username;

    // Fetch miqaat details
    $miqaat = $this->MasoolMusaidM->get_miqaat_by_id($miqaat_id);
    if (!$miqaat) {
      show_404();
    }
    $data['miqaat'] = $miqaat;

    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);
    $data['members'] = $members;

    $existing_rsvps = $this->MasoolMusaidM->get_rsvps_by_miqaat($miqaat_id);
    $data['existing_rsvps'] = [];
    foreach ($existing_rsvps as $rsvp) {
      $data['existing_rsvps'][$rsvp['user_id']] = $rsvp;
    }

    $data['miqaat_id'] = $miqaat_id;

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/RSVP/GeneralRSVP', $data);
  }

  /**
   * AJAX: search upcoming miqaats (for Miqaat Home)
   */
  public function miqaat_search()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
      return;
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }

    $q = $this->input->get('q', true);
    if ($q === null) $q = '';

    $miqaats = $this->MasoolMusaidM->search_upcoming_miqaats($q);
    if ($miqaats) {
      foreach ($miqaats as $key => $miqaat) {
        if (isset($miqaat['date'])) {
          $hijri_date = $this->HijriCalendar->get_hijri_date($miqaat['date']);
          $hijri_month = $this->HijriCalendar->hijri_month_name(explode("-", $hijri_date["hijri_date"])[1])["hijri_month"];
          $miqaats[$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];
        }

        if (isset($miqaat['id'])) {
          $miqaats[$key]['member_count'] = count($this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector));
          $miqaats[$key]['attendee_count'] = $this->MasoolMusaidM->get_miqaat_attendee_count($miqaat['id'], $sector, $subsector);

          $miqaat_raza_status = $this->AccountM->get_miqaat_raza_status($miqaat["id"]);
          if ($miqaat_raza_status) {
            $miqaats[$key]["raza_status"] = $miqaat_raza_status ?? 'Unknown';
          } else {
            $miqaats[$key]["raza_status"] = 'Unknown';
          }
        }

        unset($miqaats[$key]['sector']);
        unset($miqaats[$key]['subsector']);
      }
    } else {
      $miqaats = [];
    }

    $html = $this->load->view('MasoolMusaid/Miqaat/_miqaat_cards', ['miqaats' => $miqaats], true);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'html' => $html]));
  }

  public function submit_general_rsvp()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $miqaat_id = $this->input->post('miqaat_id');
    $rsvp_members = $this->input->post('rsvp_members') ?? [];

    if (!$miqaat_id) {
      show_error('Miqaat ID is required', 400);
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    // Fetch members for the sector/sub-sector
    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);
    $valid_member_ids = array_column($members, 'ITS_ID');

    // Filter submitted member IDs to only include valid ones
    $valid_rsvp_ids = array_intersect($rsvp_members, $valid_member_ids);

    // Fetch existing RSVPs for the miqaat
    $existing_rsvps = $this->MasoolMusaidM->get_rsvps_by_miqaat($miqaat_id);
    $existing_rsvp_ids = array_column($existing_rsvps, 'user_id');

    // Determine which RSVPs to add and which to remove
    $to_add = array_diff($valid_rsvp_ids, $existing_rsvp_ids);

    $to_remove = array_diff($existing_rsvp_ids, $valid_rsvp_ids);


    // Add new RSVPs (send hof_id as well)
    foreach ($to_add as $user_id) {
      // Find hof_id for this user_id from $members
      $hof_id = null;
      foreach ($members as $m) {
        if ($m['ITS_ID'] == $user_id) {
          $hof_id = $m['HOF_ID'] ?? null;
          break;
        }
      }
      $this->MasoolMusaidM->add_general_rsvp($miqaat_id, $user_id, $hof_id);
    }

    foreach ($to_remove as $user_id) {
      $this->MasoolMusaidM->remove_general_rsvp($miqaat_id, $user_id);
    }

    if (!empty($to_add)) {
      $this->session->set_flashdata('success', 'RSVPs updated successfully.');
    } else {
      $this->session->set_flashdata('info', 'No changes made to RSVPs.');
    }
    // Redirect
    redirect('/MasoolMusaid/general_rsvp/' . $miqaat_id);
  }

  public function miqaat_attendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }

    $data['user_name'] = $username;

    $miqaats = $this->MasoolMusaidM->get_upcoming_miqaats();
    if (!$miqaats) {
      $data['miqaats'] = [];
    } else {
      foreach ($miqaats as $key => $miqaat) {
        if (isset($miqaat['date'])) {
          $hijri_date = $this->HijriCalendar->get_hijri_date($miqaat['date']);
          $hijri_month = $this->HijriCalendar->hijri_month_name(explode("-", $hijri_date["hijri_date"])[1])["hijri_month"];
          $miqaats[$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];
        }

        if (isset($miqaat['id'])) {
          $miqaats[$key]['member_count'] = count($this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector));
          // Get attendee count for this miqaat
          $miqaats[$key]['attendee_count'] = $this->MasoolMusaidM->get_miqaat_attendee_count($miqaat['id'], $sector, $subsector);

          $miqaat_raza_status = $this->AccountM->get_miqaat_raza_status($miqaat["id"]);
          if ($miqaat_raza_status) {
            $miqaats[$key]["raza_status"] = $miqaat_raza_status ?? 'Unknown';
          } else {
            $miqaats[$key]["raza_status"] = 'Unknown';
          }
        }

        unset($miqaats[$key]['sector']);
        unset($miqaats[$key]['subsector']);
      }
      $data['miqaats'] = $miqaats;
    }

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/Miqaat/Home', $data);
  }

  public function miqaat_attendance_details($miqaat_id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }

    $data['user_name'] = $username;

    // Fetch miqaat details
    $miqaat = $this->MasoolMusaidM->get_miqaat_by_id($miqaat_id);
    if (!$miqaat) {
      show_404();
    }
    $data['miqaat'] = $miqaat;

    // Fetch members for the sector/sub-sector
    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);

    // Fetch existing attendance records for the miqaat
    $existing_attendance = $this->MasoolMusaidM->get_miqaat_attendance_by_miqaat($miqaat_id);
    $attendance_map = [];
    foreach ($existing_attendance as $attendance) {
      $attendance_map[$attendance['user_id']] = $attendance;
    }
    $data["existing_attendance"] = $existing_attendance;

    // Merge attendance info into members array
    foreach ($members as &$member) {
      $its = $member['ITS_ID'];
      if (isset($attendance_map[$its])) {
        foreach ($attendance_map[$its] as $k => $v) {
          if ($k !== 'user_id') {
            $member[$k] = $v;
          }
        }
      }
    }
    unset($member);

    $data['members'] = $members;
    $data['miqaat_id'] = $miqaat_id;

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/Miqaat/AttendanceDetails', $data);
  }

  public function submit_miqaat_attendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $miqaat_id = $this->input->post('miqaat_id');
    $attendance_members = $this->input->post('attendance_members') ?? [];
    $attendance_comments = $this->input->post('attendance_comments') ?? [];

    if (!$miqaat_id) {
      show_error('Miqaat ID is required', 400);
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    // Fetch members for the sector/sub-sector
    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);
    $valid_member_ids = array_column($members, 'ITS_ID');

    // Only allow valid member IDs
    $valid_attendance_ids = array_intersect($attendance_members, $valid_member_ids);

    // Fetch existing attendance records for the miqaat
    $existing_attendance = $this->MasoolMusaidM->get_miqaat_attendance_by_miqaat($miqaat_id);
    $existing_attendance_ids = array_column($existing_attendance, 'user_id');

    // Determine which attendance records to add and which to remove
    $to_add = array_diff($valid_attendance_ids, $existing_attendance_ids);
    $to_remove = array_diff($existing_attendance_ids, $valid_attendance_ids);

    // Add new attendance records (send comment, not status)
    foreach ($to_add as $user_id) {
      $comment = isset($attendance_comments[$user_id]) ? $attendance_comments[$user_id] : '';
      // Find hof_id for this user_id from $members
      $hof_id = null;
      foreach ($members as $m) {
        if ($m['ITS_ID'] == $user_id) {
          $hof_id = $m['HOF_ID'] ?? null;
          break;
        }
      }
      $this->MasoolMusaidM->add_miqaat_attendance($miqaat_id, $user_id, $hof_id, $comment);
    }

    // Remove attendance records
    foreach ($to_remove as $user_id) {
      $this->MasoolMusaidM->remove_miqaat_attendance($miqaat_id, $user_id);
    }

    $this->session->set_flashdata('success', 'Attendance updated successfully.');
    redirect('/MasoolMusaid/miqaat_attendance_details/' . $miqaat_id);
  }

  public function viewmember($its_id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }
    if (!$its_id) {
      redirect('MasoolMusaid/mumineendirectory');
      return;
    }
    $member = $this->AdminM->get_member_by_its($its_id);
    if (!$member) {
      redirect('MasoolMusaid/mumineendirectory');
      return;
    }
    $hof_id = !empty($member['HOF_ID']) ? $member['HOF_ID'] : $its_id;
    $data['user_name']         = $_SESSION['user']['username'];
    $data['member']            = $member;
    $data['family_members']    = $this->AdminM->get_family_members($its_id);
    $data['family_financials'] = $this->AdminM->get_family_financial_data($its_id, array_column($data['family_members'], 'ITS_ID'));
    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('Admin/ViewMember', $data);
  }

  public function qardanhasana()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }
 
    $username  = $_SESSION['user']['username'];
    $sector    = '';
    $subsector = '';
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $m)) {
      $sector    = $m[1];
      $subsector = strtoupper($m[2]);
    }
 
    $this->load->model('QardanHasanaM');
 
    // Only active members returned from model
    $members = $this->MasoolMusaidM->get_members_for_husaini($sector, $subsector);
 
    // ITS that have ever paid
    $payers_set = array_flip(
      $this->MasoolMusaidM->get_husaini_payer_its_list()
    );
 
    $total     = 0;
    $given     = 0;
    $not_given = 0;
 
    foreach ($members as &$member) {
      $its               = (string)($member['ITS_ID'] ?? '');
      $member['has_given'] = isset($payers_set[$its]);
      $total++;
      $member['has_given'] ? $given++ : $not_given++;
    }
    unset($member);
 
    $data = [
      'user_name' => $username,
      'sector'    => $sector,
      'subsector' => $subsector,
      'members'   => $members,
      'summary'   => [
        'total'     => $total,
        'given'     => $given,
        'not_given' => $not_given,
      ],
    ];
 
    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/QardanHasana/Husaini', $data);
  }
}
