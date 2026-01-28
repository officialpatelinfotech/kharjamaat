<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Anjuman extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('CommonM');
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->model('AmilsahebM');
    $this->load->model('MasoolMusaidM');
    $this->load->model('AnjumanM');
    $this->load->model('HijriCalendar');
    $this->load->library('email', $this->config->item('email'));
  }
  public function index()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];

    // Early JSON endpoints: handle AJAX requests before rendering any views
    $fmt = $this->input->get('format');
    if ($fmt === 'json') {
      // Monthly stats for a given Hijri month/year
      $hijri_year_param = $this->input->get('hijri_year');
      $hijri_month_param = $this->input->get('hijri_month');
      if ($hijri_year_param && $hijri_month_param) {
        $mstats = $this->CommonM->get_monthly_thaali_stats((int)$hijri_month_param, (int)$hijri_year_param);
        $payload = [
          'success' => true,
          'monthly_stats' => $mstats
        ];
        return $this->output->set_content_type('application/json')->set_output(json_encode($payload));
      }

      // Miqaat RSVP counts/lists
      $miqaat_rsvp = $this->input->get('miqaat_rsvp');
      if ($miqaat_rsvp) {
        $miqaat_id = $this->input->get('miqaat_id');
        $m = $miqaat_id ? $this->CommonM->get_next_miqaat_rsvp_stats((int)$miqaat_id)
          : $this->CommonM->get_next_miqaat_rsvp_stats();
        $payload = ['success' => true, 'miqaat_rsvp' => $m];
        return $this->output->set_content_type('application/json')->set_output(json_encode($payload));
      }

      // Previous miqaat before a given date
      $miqaat_prev = $this->input->get('miqaat_prev');
      if ($miqaat_prev) {
        $before_date = $this->input->get('before_date');
        $payload = ['success' => false, 'miqaat' => null];
        if ($before_date) {
          $row = $this->db->query(
            "SELECT id, name, type, date, assigned_to FROM miqaat WHERE date < ? ORDER BY date DESC LIMIT 1",
            [$before_date]
          )->row_array();
          if ($row) {
            $hparts = $this->HijriCalendar->get_hijri_parts_by_greg_date($row['date']);
            if ($hparts && isset($hparts['hijri_day'])) {
              $row['hijri_label'] = trim((($hparts['hijri_day'] ?? '')) . ' ' . (($hparts['hijri_month_name'] ?? $hparts['hijri_month'] ?? '')) . ' ' . (($hparts['hijri_year'] ?? '')));
            } else {
              $row['hijri_label'] = '';
            }
            $row['hijri_parts'] = $hparts;
            $payload = ['success' => true, 'miqaat' => $row];
          }
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($payload));
      }

      // Unknown JSON request
      return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unknown request']));
    }

    $users = $this->AmilsahebM->get_all_ashara();

    // Resident-only sector and overview stats for Jamaat cards
    $sectorsData = $this->AmilsahebM->get_resident_sector_stats();
    $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats();

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

    // Determine if frontend requested a specific hijri month/year (AJAX or direct)
    $sel_hijri_year = $this->input->get('hijri_year') ? trim($this->input->get('hijri_year')) : null;
    $sel_hijri_month = $this->input->get('hijri_month') ? trim($this->input->get('hijri_month')) : null;

    // Dashboard financial & monthly data (pass month params if provided)
    $dashboard_data = $this->get_dashboard_summary_data($sel_hijri_month, $sel_hijri_year);

    // Pass data to view
    $data = [
      'user_name' => $data['user_name'],
      'users' => $users,
      'stats' => $stats,
      'current_sector' => '',
      'current_sub_sector' => '',
      'dashboard_data' => $dashboard_data
    ];

    // Expense dashboard: status breakdown for Source of Funds and Area of Spend
    $expense_dashboard = [
      'sources' => ['active' => 0, 'inactive' => 0],
      'areas_available' => false,
      'areas' => ['active' => 0, 'inactive' => 0],
    ];

    // Source of Funds (expense_sources)
    if (method_exists($this->db, 'table_exists') && $this->db->table_exists('expense_sources')) {
      $rows = $this->db->select('status, COUNT(*) AS cnt')->from('expense_sources')->group_by('status')->get()->result_array();
      foreach ($rows as $r) {
        $st = $r['status'] ?? '';
        $cnt = (int)($r['cnt'] ?? 0);
        $isActive = false;
        if (is_numeric($st)) {
          $isActive = ((int)$st) === 1;
        } else {
          $isActive = strtolower(trim((string)$st)) === 'active';
        }
        if ($isActive) $expense_dashboard['sources']['active'] += $cnt;
        else $expense_dashboard['sources']['inactive'] += $cnt;
      }
    }

    // Area of Spend (optional table: expense_areas)
    if (method_exists($this->db, 'table_exists') && $this->db->table_exists('expense_areas')) {
      $expense_dashboard['areas_available'] = true;
      $rows = $this->db->select('status, COUNT(*) AS cnt')->from('expense_areas')->group_by('status')->get()->result_array();
      foreach ($rows as $r) {
        $st = $r['status'] ?? '';
        $cnt = (int)($r['cnt'] ?? 0);
        $isActive = false;
        if (is_numeric($st)) {
          $isActive = ((int)$st) === 1;
        } else {
          $isActive = strtolower(trim((string)$st)) === 'active';
        }
        if ($isActive) $expense_dashboard['areas']['active'] += $cnt;
        else $expense_dashboard['areas']['inactive'] += $cnt;
      }
    }
    $data['expense_dashboard'] = $expense_dashboard;

    // Member types distribution for dashboard (reusing AmilsahebM helper)
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

    $data['year_daytype_stats'] = $this->CommonM->get_year_calendar_daytypes();

    // Corpus funds overview: assigned, paid, outstanding (assigned - paid)
    $this->load->model('CorpusFundM');
    $funds = $this->CorpusFundM->get_funds();
    $corpus_funds = [];
    foreach ($funds as $f) {
      $fid = (int)($f['id'] ?? 0);
      $assignedTotal = 0.0;
      $paidTotal = 0.0;
      $assignments = [];
      if ($fid > 0) {
        // Sum assigned for this fund
        $assignments = $this->CorpusFundM->get_assignments($fid);
        foreach ($assignments as $a) {
          $assignedTotal += (float)($a['amount_assigned'] ?? 0);
        }
        // Sum paid for this fund
        $rowPaid = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')
          ->from('corpus_fund_payment')
          ->where('fund_id', $fid)
          ->get()->row_array();
        $paidTotal = isset($rowPaid['total_paid']) ? (float)$rowPaid['total_paid'] : 0.0;
      }
      $outstanding = max(0, $assignedTotal - $paidTotal);
      $f['assigned_total'] = $assignedTotal;
      $f['paid_total'] = $paidTotal;
      $f['outstanding'] = $outstanding;
      $f['assignments'] = $assignments;
      $corpus_funds[] = $f;
    }
    $data['corpus_funds'] = $corpus_funds;

    // Ekram funds overview: assigned, paid, outstanding
    $this->load->model('EkramFundM');
    $efunds = $this->EkramFundM->get_funds();
    $ekram_funds = [];
    foreach ($efunds as $ef) {
      $efid = (int)($ef['id'] ?? 0);
      $assignedTotal = 0.0;
      $paidTotal = 0.0;
      $assignments = [];
      if ($efid > 0) {
        $assignments = $this->EkramFundM->get_assignments($efid);
        foreach ($assignments as $a) {
          $assignedTotal += (float)($a['amount_assigned'] ?? 0);
        }
        $rowPaid = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')
          ->from('ekram_fund_payment')
          ->where('fund_id', $efid)
          ->get()->row_array();
        $paidTotal = isset($rowPaid['total_paid']) ? (float)$rowPaid['total_paid'] : 0.0;
      }
      $outstanding = max(0, $assignedTotal - $paidTotal);
      $ef['assigned_total'] = $assignedTotal;
      $ef['paid_total'] = $paidTotal;
      $ef['outstanding'] = $outstanding;
      $ef['assignments'] = $assignments;
      $ekram_funds[] = $ef;
    }
    $data['ekram_funds'] = $ekram_funds;

    // If frontend requested a specific hijri month/year (AJAX or direct), compute representative parts
    $selected_hijri_parts = null;
    if ($sel_hijri_year && $sel_hijri_month) {
      // get the days for that hijri month/year and pick the first greg date as representative
      $days = $this->HijriCalendar->get_hijri_days_for_month_year($sel_hijri_month, $sel_hijri_year);
      if (!empty($days) && isset($days[0]['greg_date'])) {
        $rep_greg = $days[0]['greg_date'];
        $selected_hijri_parts = $this->HijriCalendar->get_hijri_parts_by_greg_date($rep_greg);
      }
    }
    $data['selected_hijri_parts'] = $selected_hijri_parts;

    // Recent expenses and total for dashboard card (current or selected Hijri year)
    $this->load->model('ExpenseM');
    $expense_filters = [];
    if ($sel_hijri_year) {
      $expense_filters['hijri_year'] = (int)$sel_hijri_year;
    } else {
      $today_parts_for_expense = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      if ($today_parts_for_expense && isset($today_parts_for_expense['hijri_year'])) {
        $expense_filters['hijri_year'] = (int)$today_parts_for_expense['hijri_year'];
      }
    }

    // Keep a small list (up to 5) if needed elsewhere
    $list_filters = $expense_filters;
    $list_filters['limit'] = 5;
    $data['dashboard_expenses'] = $this->ExpenseM->get_list($list_filters);

    // Compute total expense amount for the selected Hijri year for dashboard display
    $dashboard_expense_total = 0.0;
    if (!empty($expense_filters['hijri_year'])) {
      $all_year_expenses = $this->ExpenseM->get_list(['hijri_year' => $expense_filters['hijri_year']]);
      foreach ($all_year_expenses as $erow) {
        $dashboard_expense_total += (float)($erow['amount'] ?? 0);
      }
    }
    $data['dashboard_expense_total'] = $dashboard_expense_total;
    $data['dashboard_expense_hijri_year'] = isset($expense_filters['hijri_year']) ? (int)$expense_filters['hijri_year'] : null;

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Home', $data);
  }

  public function mumineendirectory()
  {
    // Allow only Anjuman role (3)
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    // Load users list (support simple search like Amilsaheb)
    if ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      $data['users'] = $this->AmilsahebM->search_users($keyword);
    } else {
      $data['users'] = $this->AmilsahebM->get_all_users();
    }
    $data['user_name'] = $_SESSION['user']['username'];
    // Provide view params for shared directory view
    $data['back_url'] = base_url('anjuman');
    $data['update_user_url'] = base_url('anjuman/update_user_details');

    $this->load->view('Anjuman/Header', $data);
    // Reuse the same directory view used by Amilsaheb
    $this->load->view('Amilsaheb/Mumineendirectory', $data);
  }

  public function wajebaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->model('WajebaatM');
    $data['wajebaat_rows'] = $this->WajebaatM->get_all();
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/WajebaatDetails', $data);
  }

  public function qardan_hasana()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->model('QardanHasanaM');
    $data['qardan_hasana_rows'] = $this->QardanHasanaM->get_all();
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/QardanHasanaDetails', $data);
  }

  public function expense()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];

    // Expense listing with filters for Jamaat users
    $this->load->model('ExpenseM');
    $this->load->model('ExpenseSourceM');
    $this->load->model('ExpenseAreaM');

    $filters = [
      'aos' => trim((string)$this->input->get('aos')),
      'sof' => trim((string)$this->input->get('sof')),
      'hijri_year' => trim((string)$this->input->get('hijri_year')),
      'date_from' => trim((string)$this->input->get('date_from')),
      'date_to' => trim((string)$this->input->get('date_to')),
    ];

    // Normalise empty values to null
    foreach ($filters as $k => $v) {
      if ($v === '') $filters[$k] = null;
    }

    // On initial load, default to current Hijri year if not specified
    $current_hijri_year = null;
    if (empty($filters['hijri_year'])) {
      $today_parts = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      if ($today_parts && isset($today_parts['hijri_year'])) {
        $current_hijri_year = (int)$today_parts['hijri_year'];
        $filters['hijri_year'] = $current_hijri_year;
      }
    } else {
      $current_hijri_year = (int)$filters['hijri_year'];
    }

    $expenses = $this->ExpenseM->get_list($filters);
    $total_amount = 0.0;
    foreach ($expenses as $row) {
      $total_amount += (float)($row['amount'] ?? 0);
    }

    $data['filters'] = $filters;
    $data['expenses'] = $expenses;
    $data['expense_total'] = $total_amount;
    $data['current_hijri_year_for_expense'] = $current_hijri_year;

    $data['sof_options'] = $this->ExpenseSourceM->get_all();
    $data['aos_options'] = $this->ExpenseAreaM->get_all_active();
    $data['hijri_year_options'] = $this->ExpenseM->get_distinct_hijri_years();

    // Ensure current Hijri year appears in dropdown even if no rows yet
    if ($current_hijri_year && !in_array($current_hijri_year, $data['hijri_year_options'], true)) {
      array_unshift($data['hijri_year_options'], $current_hijri_year);
      $data['hijri_year_options'] = array_values(array_unique($data['hijri_year_options']));
      rsort($data['hijri_year_options']);
    }

    // Keep existing list of all SOF for the secondary table, if needed
    $data['sources'] = $data['sof_options'];

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Expense', $data);
  }

  public function expense_add()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $this->load->model('ExpenseM');
    $this->load->model('ExpenseSourceM');
    $this->load->model('ExpenseAreaM');

    // Determine current Hijri year for defaults
    $today_parts = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
    $current_hijri_year = ($today_parts && isset($today_parts['hijri_year'])) ? (int)$today_parts['hijri_year'] : null;

    if ($this->input->method() === 'post') {
      $aos_name = trim((string)$this->input->post('aos_name'));
      $area_id = null;
      if ($aos_name !== '') {
        $area_id = $this->ExpenseAreaM->get_or_create_by_name($aos_name);
      }

      $payload = [
        'expense_date' => $this->input->post('expense_date'),
        'area_id'      => $area_id,
        'amount'       => $this->input->post('amount'),
        'source_id'    => $this->input->post('source_id'),
        'hijri_year'   => $this->input->post('hijri_year'),
        'notes'        => $this->input->post('notes'),
      ];

      // Basic required fields check
      if (!empty($payload['expense_date']) && !empty($payload['amount']) && !empty($payload['source_id']) && !empty($payload['hijri_year'])) {
        $id = $this->ExpenseM->create($payload);
        if ($id) {
          $this->session->set_flashdata('success', 'Expense added successfully.');
          redirect('anjuman/expense');
          return;
        }
      }

      $this->session->set_flashdata('error', 'Failed to add expense. Please check the form.');
    }

    $data = [];
    $data['user_name'] = $_SESSION['user']['username'];
    $data['sof_options'] = $this->ExpenseSourceM->get_all();
    $data['aos_options'] = $this->ExpenseAreaM->get_all_active();
    $data['hijri_year_options'] = $this->ExpenseM->get_distinct_hijri_years();
    if ($current_hijri_year && !in_array($current_hijri_year, $data['hijri_year_options'], true)) {
      array_unshift($data['hijri_year_options'], $current_hijri_year);
      $data['hijri_year_options'] = array_values(array_unique($data['hijri_year_options']));
      rsort($data['hijri_year_options']);
    }
    $data['current_hijri_year_for_expense'] = $current_hijri_year;

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/ExpenseForm', $data);
  }

  public function expense_edit($id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $id = (int)$id;
    if ($id <= 0) {
      redirect('anjuman/expense');
    }

    $this->load->model('ExpenseM');
    $this->load->model('ExpenseSourceM');
    $this->load->model('ExpenseAreaM');

    $expense = $this->ExpenseM->get($id);
    if (!$expense) {
      $this->session->set_flashdata('error', 'Expense not found.');
      redirect('anjuman/expense');
      return;
    }

    // Current Hijri year for dropdown convenience
    $today_parts = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
    $current_hijri_year = ($today_parts && isset($today_parts['hijri_year'])) ? (int)$today_parts['hijri_year'] : null;

    if ($this->input->method() === 'post') {
      $aos_name = trim((string)$this->input->post('aos_name'));
      $area_id = null;
      if ($aos_name !== '') {
        $area_id = $this->ExpenseAreaM->get_or_create_by_name($aos_name);
      }

      $payload = [
        'expense_date' => $this->input->post('expense_date'),
        'area_id'      => $area_id,
        'amount'       => $this->input->post('amount'),
        'source_id'    => $this->input->post('source_id'),
        'hijri_year'   => $this->input->post('hijri_year'),
        'notes'        => $this->input->post('notes'),
      ];

      if (!empty($payload['expense_date']) && !empty($payload['amount']) && !empty($payload['source_id']) && !empty($payload['hijri_year'])) {
        $ok = $this->ExpenseM->update($id, $payload);
        if ($ok) {
          $this->session->set_flashdata('success', 'Expense updated successfully.');
          redirect('anjuman/expense');
          return;
        }
      }

      $this->session->set_flashdata('error', 'Failed to update expense. Please check the form.');
      // Refresh local data from posted values for redisplay
      $expense = array_merge($expense, $payload);
    }

    $data = [];
    $data['user_name'] = $_SESSION['user']['username'];
    $data['expense'] = $expense;
    $data['sof_options'] = $this->ExpenseSourceM->get_all();
    $data['aos_options'] = $this->ExpenseAreaM->get_all_active();
    $data['hijri_year_options'] = $this->ExpenseM->get_distinct_hijri_years();
    if ($current_hijri_year && !in_array($current_hijri_year, $data['hijri_year_options'], true)) {
      array_unshift($data['hijri_year_options'], $current_hijri_year);
      $data['hijri_year_options'] = array_values(array_unique($data['hijri_year_options']));
      rsort($data['hijri_year_options']);
    }
    $data['current_hijri_year_for_expense'] = $current_hijri_year;

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/ExpenseForm', $data);
  }

  public function expense_delete($id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $id = (int)$id;
    if ($id <= 0) {
      redirect('anjuman/expense');
    }

    $this->load->model('ExpenseM');
    $ok = $this->ExpenseM->delete($id);
    if ($ok) {
      $this->session->set_flashdata('success', 'Expense deleted successfully.');
    } else {
      $this->session->set_flashdata('error', 'Failed to delete expense.');
    }
    redirect('anjuman/expense');
  }

  public function update_user_details()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      http_response_code(403);
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }
    $data = $this->input->post();
    $its_id = $data['ITS_ID'] ?? null;
    unset($data['ITS_ID']);
    if (!$its_id) {
      echo json_encode(['success' => false, 'error' => 'ITS_ID missing']);
      return;
    }
    $updated = $this->AmilsahebM->update_user_by_its_id($its_id, $data);
    echo json_encode(['success' => $updated]);
  }

  private function get_dashboard_summary_data($sel_hijri_month = null, $sel_hijri_year = null)
  {
    // Get Sabeel summary
    $sabeel_summary = $this->get_sabeel_summary();

    // Get Thaali summary  
    $thaali_summary = $this->get_thaali_summary();

    // Get FMB General Contribution summary
    $fmb_summary = $this->get_fmb_contribution_summary();

    // Get Miqaat payment summary
    $miqaat_summary = $this->get_miqaat_payment_summary();

    // Raza summary (pending/approved/rejected)
    $raza_summary = $this->get_raza_summary();

    // Miqaat finance summary (invoices vs payments)
    $miqaat_finance = $this->get_miqaat_finance_summary();

    // FMB Miqaats (current Hijri year): count + finance
    $fmb_miqaats = $this->get_fmb_miqaats_summary();
    $fmb_miqaats_items = $this->get_fmb_miqaats_items(5);

    // Upcoming miqaats (next 5)
    $upcoming_miqaats = $this->get_upcoming_miqaats(5);
    // Enrich upcoming miqaats with hijri parts/label for view convenience
    if (!empty($upcoming_miqaats)) {
      foreach ($upcoming_miqaats as &$um) {
        $um_date = isset($um['date']) ? $um['date'] : null;
        $hparts = null;
        if ($um_date) {
          $hparts = $this->HijriCalendar->get_hijri_parts_by_greg_date($um_date);
        }
        if ($hparts && isset($hparts['hijri_day'])) {
          $um['hijri_label'] = trim(($hparts['hijri_day'] ?? '') . ' ' . ($hparts['hijri_month_name'] ?? $hparts['hijri_month'] ?? '') . ' ' . ($hparts['hijri_year'] ?? ''));
        } else {
          $um['hijri_label'] = '';
        }
        $um['hijri_parts'] = $hparts;
      }
      unset($um);
    }

    // Top dues (Sabeel & Thaali)
    $top_dues = [
      'sabeel' => $this->get_top_dues_sabeel(5),
      'thaali' => $this->get_top_dues_thaali(5)
    ];

    // Get member grade breakdown (establishment & residential)
    $grade_breakdown = $this->get_grade_breakdown();
    $grade_breakdown_res = $this->get_grade_breakdown_residential();

    // Get mohallah breakdown
    $mohallah_breakdown = $this->get_mohallah_breakdown();

    // FMB takhmeen sector-wise (current Hijri year)
    $fmb_takhmeen_sector = $this->get_fmb_takhmeen_sector_breakdown();

    // Sabeel takhmeen sector-wise (current Hijri year)
    $sabeel_takhmeen_sector = $this->get_sabeel_takhmeen_sector_breakdown();

    // Weekly signup trends (placeholder for now)
    $weekly_signups = $this->get_weekly_signup_trends();

    // Recent member details for table
    $recent_members = $this->get_recent_member_details();

    // This week (Mon-Sun) thaali signup average per sector (HOF per day)
    $this_week_sector_signup_avg = $this->get_this_week_sector_signup_avg();
    // Families who did NOT sign up for thaali any day in the current week
    $no_thaali_families = $this->get_no_thaali_families_this_week();

    // Monthly (Hijri) thaali stats - compute for selected month if provided, otherwise current Hijri month
    $month_signed_up = 0;
    $no_thaali_month_list = [];
    if ($sel_hijri_month && $sel_hijri_year) {
      $mstats = $this->CommonM->get_monthly_thaali_stats($sel_hijri_month, $sel_hijri_year);
      $month_signed_up = isset($mstats['families_signed_up']) ? (int)$mstats['families_signed_up'] : 0;
      $no_thaali_month_list = isset($mstats['no_thaali_list']) ? $mstats['no_thaali_list'] : [];
    } else {
      // current hijri month
      $today_parts = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      if ($today_parts && isset($today_parts['hijri_month']) && isset($today_parts['hijri_year'])) {
        $mstats = $this->CommonM->get_monthly_thaali_stats($today_parts['hijri_month'], $today_parts['hijri_year']);
        $month_signed_up = isset($mstats['families_signed_up']) ? (int)$mstats['families_signed_up'] : 0;
        $no_thaali_month_list = isset($mstats['no_thaali_list']) ? $mstats['no_thaali_list'] : [];
      }
    }

    // Wajebaat & Qardan Hasana (simple aggregates)
    $wajebaat_summary = $this->get_wajebaat_summary();
    $qardan_hasana_summary = $this->get_qardan_hasana_summary();

    return [
      'sabeel_summary' => $sabeel_summary,
      'thaali_summary' => $thaali_summary,
      'fmb_summary' => $fmb_summary,
      'miqaat_summary' => $miqaat_summary,
      'raza_summary' => $raza_summary,
      'miqaat_finance' => $miqaat_finance,
      'fmb_miqaats' => $fmb_miqaats,
      'fmb_miqaats_items' => $fmb_miqaats_items,
      'top_dues' => $top_dues,
      'grade_breakdown' => $grade_breakdown,
      'grade_breakdown_est' => $grade_breakdown, // keep legacy key & explicit est
      'grade_breakdown_res' => $grade_breakdown_res,
      'mohallah_breakdown' => $mohallah_breakdown,
      'fmb_takhmeen_sector' => $fmb_takhmeen_sector['rows'],
      'fmb_takhmeen_year' => $fmb_takhmeen_sector['year'],
      'sabeel_takhmeen_sector' => $sabeel_takhmeen_sector['rows'],
      'sabeel_takhmeen_year' => $sabeel_takhmeen_sector['year'],
      'weekly_signups' => $weekly_signups,
      'recent_members' => $recent_members,
      'this_week_sector_signup_avg' => $this_week_sector_signup_avg,
      'no_thaali_families' => $no_thaali_families,
      'this_month_families_signed_up' => $month_signed_up,
      'no_thaali_families_month' => $no_thaali_month_list,
      'upcoming_miqaats' => $upcoming_miqaats,
      'miqaat_rsvp' => $this->CommonM->get_next_miqaat_rsvp_stats(),
      'wajebaat_summary' => $wajebaat_summary,
      'qardan_hasana_summary' => $qardan_hasana_summary,
    ];
  }

  private function get_wajebaat_summary()
  {
    $row = $this->db->query(
      "SELECT COUNT(*) AS cnt, SUM(amount) AS total_amount, SUM(due) AS total_due, SUM(CASE WHEN amount > due THEN (amount - due) ELSE 0 END) AS total_received FROM wajebaat"
    )->row_array();

    $cnt = (int)($row['cnt'] ?? 0);
    $total = (float)($row['total_amount'] ?? 0);
    $due = (float)($row['total_due'] ?? 0);
    $received = (float)($row['total_received'] ?? max(0, $total - $due));

    return [
      'count' => $cnt,
      'total' => (int)round($total),
      'received' => (int)round($received),
      'due' => (int)round($due),
    ];
  }

  private function get_qardan_hasana_summary()
  {
    $row = $this->db->query(
      "SELECT COUNT(*) AS cnt, SUM(amount) AS total_amount, SUM(due) AS total_due, SUM(CASE WHEN amount > due THEN (amount - due) ELSE 0 END) AS total_received FROM qardan_hasana"
    )->row_array();

    $cnt = (int)($row['cnt'] ?? 0);
    $total = (float)($row['total_amount'] ?? 0);
    $due = (float)($row['total_due'] ?? 0);
    $received = (float)($row['total_received'] ?? max(0, $total - $due));

    return [
      'count' => $cnt,
      'total' => (int)round($total),
      'received' => (int)round($received),
      'due' => (int)round($due),
    ];
  }

  /**
   * Return list of HOF families who did not sign up for thaali on any day
   * in the current week (Mon-Sun). Each item mirrors `get_all_users()` row
   * (contains `ITS_ID`, `Full_Name`, `Sector`, `Sub_Sector`, ...).
   */
  private function get_no_thaali_families_this_week()
  {
    // Determine Monday-Sunday of current week
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));
    $start = $monday;
    $end = $sunday;

    // Build date list inclusive
    $dates = [];
    $cursor = strtotime($start);
    $endTs = strtotime($end);
    while ($cursor <= $endTs) {
      $dates[] = date('Y-m-d', $cursor);
      $cursor = strtotime('+1 day', $cursor);
    }

    // Collect HOF ids who signed up on any day in the week
    $signedHofs = [];
    foreach ($dates as $d) {
      $rows = $this->CommonM->getsignupforaday_aggregated(['date' => $d, 'thali_taken' => 1]);
      foreach ($rows as $r) {
        $hofId = isset($r['ITS_ID']) ? $r['ITS_ID'] : (isset($r['HOF_ID']) ? $r['HOF_ID'] : null);
        if ($hofId) $signedHofs[$hofId] = true;
      }
    }

    // Get all HOF families and filter those not present in $signedHofs
    $allHofs = $this->CommonM->get_all_users();
    $no = [];
    foreach ($allHofs as $h) {
      $its = isset($h['ITS_ID']) ? $h['ITS_ID'] : null;
      if (!$its) continue;
      if (!isset($signedHofs[$its])) {
        $no[] = $h;
      }
    }
    return $no;
  }

  private function get_sabeel_summary()
  {
    // Get total Sabeel amounts and outstanding
    $query = "SELECT 
      SUM(est_grade.amount + COALESCE(res_grade.amount * 12, 0)) as total_sabeel,
      SUM(COALESCE(est_paid.total_paid, 0) + COALESCE(res_paid.total_paid, 0)) as total_paid,
      (SUM(est_grade.amount + COALESCE(res_grade.amount * 12, 0)) - 
       SUM(COALESCE(est_paid.total_paid, 0) + COALESCE(res_paid.total_paid, 0))) as outstanding
    FROM user u
    LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID 
      AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID)
    LEFT JOIN sabeel_takhmeen_grade est_grade ON est_grade.id = st.establishment_grade
    LEFT JOIN sabeel_takhmeen_grade res_grade ON res_grade.id = st.residential_grade
    LEFT JOIN (
      SELECT user_id, SUM(amount) as total_paid 
      FROM sabeel_takhmeen_payments 
      WHERE type = 'establishment' 
      GROUP BY user_id
    ) est_paid ON est_paid.user_id = u.ITS_ID
    LEFT JOIN (
      SELECT user_id, SUM(amount) as total_paid 
      FROM sabeel_takhmeen_payments 
      WHERE type = 'residential' 
      GROUP BY user_id
    ) res_paid ON res_paid.user_id = u.ITS_ID
    WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL";

    $result = $this->db->query($query)->row_array();
    return [
      'total' => floatval($result['total_sabeel'] ?? 0),
      'paid' => floatval($result['total_paid'] ?? 0),
      'outstanding' => floatval($result['outstanding'] ?? 0)
    ];
  }

  private function get_thaali_summary()
  {
    // Get total Thaali amounts and outstanding
    // Note: Payments are stored in fmb_takhmeen_payments (no year column). We approximate
    // paid for the latest takhmeen year as all payments made by users who have a takhmeen
    // record in that latest year.
    $query = "SELECT 
        (SELECT SUM(ft.total_amount)
           FROM fmb_takhmeen ft
          WHERE ft.year = (SELECT MAX(year) FROM fmb_takhmeen)
        ) AS total_thaali,
        (SELECT SUM(p.amount)
           FROM fmb_takhmeen_payments p
          WHERE p.user_id IN (
                SELECT DISTINCT ft2.user_id
                  FROM fmb_takhmeen ft2
                 WHERE ft2.year = (SELECT MAX(year) FROM fmb_takhmeen)
          )
        ) AS total_paid";

    $result = $this->db->query($query)->row_array();
    $total = floatval($result['total_thaali'] ?? 0);
    $paid = floatval($result['total_paid'] ?? 0);
    $outstanding = max(0, $total - $paid);
    return [
      'total' => $total,
      'paid' => $paid,
      'outstanding' => $outstanding
    ];
  }

  private function get_fmb_contribution_summary()
  {
    // Get FMB General Contribution summary
    $query = "SELECT 
      SUM(amount) as total_amount,
      SUM(CASE WHEN payment_status = 1 THEN amount ELSE 0 END) as paid_amount,
      (SUM(amount) - SUM(CASE WHEN payment_status = 1 THEN amount ELSE 0 END)) as outstanding
    FROM fmb_general_contribution";

    $result = $this->db->query($query)->row_array();
    return [
      'total' => floatval($result['total_amount'] ?? 0),
      'paid' => floatval($result['paid_amount'] ?? 0),
      'outstanding' => floatval($result['outstanding'] ?? 0)
    ];
  }

  private function get_miqaat_payment_summary()
  {
    // Get Miqaat payment summary (placeholder)
    return [
      'total' => 0,
      'paid' => 0,
      'outstanding' => 0
    ];
  }

  private function get_raza_summary()
  {
    // Simple aggregate based on status field
    $row = $this->db->query(
      "SELECT 
          SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pending,
          SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS approved,
          SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS rejected
        FROM raza
        WHERE active = 1"
    )->row_array();
    return [
      'pending' => (int)($row['pending'] ?? 0),
      'approved' => (int)($row['approved'] ?? 0),
      'rejected' => (int)($row['rejected'] ?? 0)
    ];
  }

  private function get_miqaat_finance_summary()
  {
    $row = $this->db->query(
      "SELECT 
          COALESCE(SUM(i.amount), 0) AS total_invoiced,
          (
            SELECT COALESCE(SUM(p.amount), 0) FROM miqaat_payment p
          ) AS total_paid
        FROM miqaat_invoice i"
    )->row_array();
    $total = (float)($row['total_invoiced'] ?? 0);
    $paid = (float)($row['total_paid'] ?? 0);
    return [
      'total' => $total,
      'paid' => $paid,
      'outstanding' => max(0, $total - $paid)
    ];
  }

  /**
   * FMB Miqaats summary for the current Hijri year
   * - hijri_year: numeric hijri year (e.g., 1446)
   * - count: number of miqaats with type='FMB' within that Hijri year's gregorian range
   * - total: sum of invoices for those miqaats plus Fala ni Niyaz invoices with miqaat_type='FMB' for the same hijri year
   * - paid: sum of payments against those invoices
   * - outstanding: total - paid
   */
  private function get_fmb_miqaats_summary()
  {
    // Resolve current hijri year via calendar stats and get its gregorian date range
    $year_stats = $this->CommonM->get_year_calendar_daytypes();
    $hijri_year = isset($year_stats['hijri_year']) ? $year_stats['hijri_year'] : null;

    if (empty($hijri_year)) {
      return [
        'hijri_year' => null,
        'count' => 0,
        'total' => 0.0,
        'paid' => 0.0,
        'outstanding' => 0.0,
      ];
    }

    // Compute gregorian bounds for the hijri year
    $rangeRow = $this->db->query(
      "SELECT MIN(greg_date) AS min_d, MAX(greg_date) AS max_d FROM hijri_calendar WHERE hijri_date LIKE ?",
      ['%-' . $hijri_year]
    )->row_array();
    $minDate = $rangeRow && !empty($rangeRow['min_d']) ? $rangeRow['min_d'] : null;
    $maxDate = $rangeRow && !empty($rangeRow['max_d']) ? $rangeRow['max_d'] : null;

    if (empty($minDate) || empty($maxDate)) {
      return [
        'hijri_year' => $hijri_year,
        'count' => 0,
        'total' => 0.0,
        'paid' => 0.0,
        'outstanding' => 0.0,
      ];
    }

    // Count FMB miqaats within date range
    $cntRow = $this->db->query(
      "SELECT COUNT(*) AS c FROM miqaat WHERE type = 'FMB' AND date >= ? AND date <= ?",
      [$minDate, $maxDate]
    )->row_array();
    $count = (int)($cntRow['c'] ?? 0);

    // Sum invoices scoped to FMB miqaats or Fala ni Niyaz FMB of the same hijri year
    $invRow = $this->db->query(
      "SELECT 
          COALESCE(SUM(inv.amount), 0) AS total_invoiced
        FROM miqaat_invoice inv
        LEFT JOIN miqaat m ON m.id = inv.miqaat_id
        WHERE (
          (inv.miqaat_id IS NOT NULL AND m.type = 'FMB' AND m.date >= ? AND m.date <= ?) 
          OR 
          (inv.miqaat_id IS NULL AND inv.miqaat_type = 'FMB' AND inv.year = ?)
        )",
      [$minDate, $maxDate, $hijri_year]
    )->row_array();
    $total = (float)($invRow['total_invoiced'] ?? 0);

    // Payments against those invoices
    $payRow = $this->db->query(
      "SELECT 
          COALESCE(SUM(p.amount), 0) AS total_paid
        FROM miqaat_payment p
        JOIN miqaat_invoice inv ON inv.id = p.miqaat_invoice_id
        LEFT JOIN miqaat m ON m.id = inv.miqaat_id
        WHERE (
          (inv.miqaat_id IS NOT NULL AND m.type = 'FMB' AND m.date >= ? AND m.date <= ?) 
          OR 
          (inv.miqaat_id IS NULL AND inv.miqaat_type = 'FMB' AND inv.year = ?)
        )",
      [$minDate, $maxDate, $hijri_year]
    )->row_array();
    $paid = (float)($payRow['total_paid'] ?? 0);

    return [
      'hijri_year' => $hijri_year,
      'count' => $count,
      'total' => $total,
      'paid' => $paid,
      'outstanding' => max(0, $total - $paid),
    ];
  }

  private function get_upcoming_miqaats($limit = 5)
  {
    $limit = (int)$limit;
    // Return upcoming miqaats by date (do not restrict to raza-approved)
    $sql = "SELECT m.id, m.name, m.type, m.date, m.assigned_to
        FROM miqaat m
        WHERE m.date >= CURDATE()
        ORDER BY m.date ASC
        LIMIT $limit";
    return $this->db->query($sql)->result_array();
  }

  private function get_top_dues_sabeel($limit = 5)
  {
    $limit = (int)$limit;
    $sql = "SELECT 
              u.ITS_ID,
              u.Full_Name,
              COALESCE(est_grade.amount,0) + COALESCE(res_grade.amount*12,0) AS total_sabeel,
              COALESCE(est_paid.total_paid,0) + COALESCE(res_paid.total_paid,0) AS total_paid,
              GREATEST(
                (COALESCE(est_grade.amount,0) + COALESCE(res_grade.amount*12,0)) - 
                (COALESCE(est_paid.total_paid,0) + COALESCE(res_paid.total_paid,0)), 0
              ) AS due
            FROM user u
            LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID 
              AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID)
            LEFT JOIN sabeel_takhmeen_grade est_grade ON est_grade.id = st.establishment_grade
            LEFT JOIN sabeel_takhmeen_grade res_grade ON res_grade.id = st.residential_grade
            LEFT JOIN (
              SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments WHERE type='establishment' GROUP BY user_id
            ) est_paid ON est_paid.user_id = u.ITS_ID
            LEFT JOIN (
              SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments WHERE type='residential' GROUP BY user_id
            ) res_paid ON res_paid.user_id = u.ITS_ID
            WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL
            ORDER BY due DESC
            LIMIT $limit";
    return $this->db->query($sql)->result_array();
  }

  private function get_top_dues_thaali($limit = 5)
  {
    $limit = (int)$limit;
    $sql = "SELECT 
              u.ITS_ID,
              u.Full_Name,
              COALESCE(SUM(f.total_amount),0) AS total_takhmeen,
              COALESCE(p.total_paid,0) AS total_paid,
              GREATEST(COALESCE(SUM(f.total_amount),0) - COALESCE(p.total_paid,0), 0) AS due
            FROM user u
            LEFT JOIN fmb_takhmeen f ON f.user_id = u.ITS_ID
            LEFT JOIN (
              SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments GROUP BY user_id
            ) p ON p.user_id = u.ITS_ID
            WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL
            GROUP BY u.ITS_ID, u.Full_Name, p.total_paid
            ORDER BY due DESC
            LIMIT $limit";
    return $this->db->query($sql)->result_array();
  }

  private function get_grade_breakdown()
  {
    // Determine current hijri parts
    $current_hijri = $this->HijriCalendar->get_hijri_date(date("Y-m-d"));
    $takhmeen_year_current = null;
    if (!empty($current_hijri) && !empty($current_hijri['hijri_date'])) {
      $parts = explode('-', $current_hijri['hijri_date']); // expected d-m-Y
      if (count($parts) === 3) {
        $current_hijri_month = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
        $current_hijri_year = $parts[2];
        // If month is 01-08, financial year is previousYear-currentYearShort (e.g. 1445-46)
        if ($current_hijri_month >= '01' && $current_hijri_month <= '08') {
          $y1 = intval($current_hijri_year) - 1;
          $y2 = substr($current_hijri_year, -2);
          $takhmeen_year_current = sprintf('%d-%s', $y1, $y2);
        } else { // month 09-12 -> currentYear-nextYearShort (e.g. 1446-47)
          $y1 = intval($current_hijri_year);
          $y2 = substr(strval($current_hijri_year + 1), -2);
          $takhmeen_year_current = sprintf('%d-%s', $y1, $y2);
        }
      }
    }

    // Fallback: if we couldn't compute hijri year, use MAX(year) from table
    if (empty($takhmeen_year_current)) {
      $row = $this->db->query("SELECT MAX(year) as y FROM sabeel_takhmeen")->row_array();
      $takhmeen_year_current = $row && isset($row['y']) ? $row['y'] : null;
    }

    $query = "SELECT 
        COALESCE(g.grade, 'No Grade') AS grade,
        COUNT(*) AS count
      FROM sabeel_takhmeen st
      JOIN user u 
        ON u.ITS_ID = st.user_id 
       AND u.HOF_FM_TYPE = 'HOF' 
       AND u.Inactive_Status IS NULL
      LEFT JOIN sabeel_takhmeen_grade g ON g.id = st.establishment_grade
      WHERE st.year = ?
      GROUP BY g.grade
      ORDER BY count DESC";

    return $this->db->query($query, [$takhmeen_year_current])->result_array();
  }

  private function get_grade_breakdown_residential()
  {
    // Determine current hijri parts (same logic as establishment breakdown)
    $current_hijri = $this->HijriCalendar->get_hijri_date(date("Y-m-d"));
    $takhmeen_year_current = null;
    if (!empty($current_hijri) && !empty($current_hijri['hijri_date'])) {
      $parts = explode('-', $current_hijri['hijri_date']); // expected d-m-Y
      if (count($parts) === 3) {
        $current_hijri_month = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
        $current_hijri_year = $parts[2];
        if ($current_hijri_month >= '01' && $current_hijri_month <= '08') {
          $y1 = intval($current_hijri_year) - 1;
          $y2 = substr($current_hijri_year, -2);
          $takhmeen_year_current = sprintf('%d-%s', $y1, $y2);
        } else {
          $y1 = intval($current_hijri_year);
          $y2 = substr(strval($current_hijri_year + 1), -2);
          $takhmeen_year_current = sprintf('%d-%s', $y1, $y2);
        }
      }
    }

    // Fallback: if we couldn't compute hijri year, use MAX(year) from table
    if (empty($takhmeen_year_current)) {
      $row = $this->db->query("SELECT MAX(year) as y FROM sabeel_takhmeen")->row_array();
      $takhmeen_year_current = $row && isset($row['y']) ? $row['y'] : null;
    }

    $query = "SELECT 
        COALESCE(g.grade, 'No Grade') AS grade,
        COUNT(*) AS count
      FROM sabeel_takhmeen st
      JOIN user u 
        ON u.ITS_ID = st.user_id 
       AND u.HOF_FM_TYPE = 'HOF' 
       AND u.Inactive_Status IS NULL
      LEFT JOIN sabeel_takhmeen_grade g ON g.id = st.residential_grade
      WHERE st.year = ?
      GROUP BY g.grade
      ORDER BY count DESC";

    return $this->db->query($query, [$takhmeen_year_current])->result_array();
  }

  private function get_mohallah_breakdown()
  {
    // Get mohallah-wise breakdown (Sabeel and Thaali)
    $query = "SELECT 
      u.Sector as mohallah,
      COUNT(*) as total_members,
      SUM(CASE WHEN st.id IS NOT NULL THEN 1 ELSE 0 END) as sabeel_count,
      SUM(CASE WHEN ft.id IS NOT NULL THEN 1 ELSE 0 END) as thaali_count
    FROM user u
    LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID 
      AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID)
    LEFT JOIN fmb_takhmeen ft ON ft.user_id = u.ITS_ID 
      AND ft.year = (SELECT MAX(year) FROM fmb_takhmeen WHERE user_id = u.ITS_ID)
    WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL
    GROUP BY u.Sector
    ORDER BY total_members DESC
    LIMIT 10";

    return $this->db->query($query)->result_array();
  }

  private function get_fmb_takhmeen_sector_breakdown()
  {
    // Compute current Hijri financial year string (e.g., 1447-48)
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

    if (empty($takhmeen_year_current)) {
      $row = $this->db->query("SELECT MAX(year) AS y FROM fmb_takhmeen")->row_array();
      $takhmeen_year_current = $row && isset($row['y']) ? $row['y'] : null;
    }

    // Pull per-user FMB takhmeen details with FIFO allocation (oldest-first) and aggregate current year per sector
    $users = $this->AnjumanM->get_user_takhmeen_details();

    $agg = [];
    foreach ($users as $u) {
      $sector = isset($u['Sector']) ? trim($u['Sector']) : '';
      if ($sector === '') $sector = 'Unassigned';

      $total_year = 0.0;
      $paid_year = 0.0;
      if (!empty($u['all_takhmeen']) && is_array($u['all_takhmeen'])) {
        foreach ($u['all_takhmeen'] as $yr) {
          if (isset($yr['year']) && $yr['year'] == $takhmeen_year_current) {
            $total_year = (float)($yr['total_amount'] ?? 0);
            $paid_year  = (float)($yr['total_paid'] ?? 0); // already allocated oldest-first in model
            break;
          }
        }
      }

      if (!isset($agg[$sector])) {
        $agg[$sector] = [
          'sector' => $sector,
          'total_takhmeen' => 0.0,
          'total_paid' => 0.0,
          'members' => 0,
        ];
      }
      $agg[$sector]['total_takhmeen'] += $total_year;
      $agg[$sector]['total_paid'] += $paid_year;
      if ($total_year > 0) {
        $agg[$sector]['members'] += 1;
      }
    }

    // Finalize rows with outstanding
    $rows = array_values(array_map(function ($r) {
      $total = (float)($r['total_takhmeen'] ?? 0);
      $paid = (float)($r['total_paid'] ?? 0);
      $r['outstanding'] = max(0, $total - $paid);
      return $r;
    }, $agg));

    // Sort by total_takhmeen DESC for consistency
    usort($rows, function ($a, $b) {
      $ta = (float)($a['total_takhmeen'] ?? 0);
      $tb = (float)($b['total_takhmeen'] ?? 0);
      if ($ta == $tb) return strcmp($a['sector'], $b['sector']);
      return ($ta < $tb) ? 1 : -1;
    });

    return ['year' => $takhmeen_year_current, 'rows' => $rows];
  }

  private function get_sabeel_takhmeen_sector_breakdown()
  {
    // Compute current Hijri financial year string (e.g., 1447-48)
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

    if (empty($takhmeen_year_current)) {
      $row = $this->db->query("SELECT MAX(year) AS y FROM sabeel_takhmeen")->row_array();
      $takhmeen_year_current = $row && isset($row['y']) ? $row['y'] : null;
    }

    // Pull per-user takhmeen details with FIFO allocation (oldest-first) and aggregate current year per sector
    $users = $this->AnjumanM->get_user_sabeel_takhmeen_details([
      'allocation_order' => 'oldest-first',
      'year' => $takhmeen_year_current,
    ]);

    $agg = [];
    foreach ($users as $u) {
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
        $paid_year = $estP + $resP; // already allocated oldest-first inside model
      }

      if (!isset($agg[$sector])) {
        $agg[$sector] = [
          'sector' => $sector,
          'total_takhmeen' => 0.0,
          'total_paid' => 0.0,
          'members' => 0,
        ];
      }
      $agg[$sector]['total_takhmeen'] += $total_year;
      $agg[$sector]['total_paid'] += $paid_year;
      if ($total_year > 0) {
        $agg[$sector]['members'] += 1;
      }
    }

    // Finalize rows with outstanding
    $rows = array_values(array_map(function ($r) {
      $total = (float)($r['total_takhmeen'] ?? 0);
      $paid = (float)($r['total_paid'] ?? 0);
      $r['outstanding'] = max(0, $total - $paid);
      return $r;
    }, $agg));

    // Sort by total_takhmeen DESC to keep consistent ordering
    usort($rows, function ($a, $b) {
      $ta = (float)($a['total_takhmeen'] ?? 0);
      $tb = (float)($b['total_takhmeen'] ?? 0);
      if ($ta == $tb) return strcmp($a['sector'], $b['sector']);
      return ($ta < $tb) ? 1 : -1;
    });

    return ['year' => $takhmeen_year_current, 'rows' => $rows];
  }

  private function get_weekly_signup_trends()
  {
    // Placeholder for weekly signup trends
    return [
      ['week' => 'Week 1', 'signups' => 20],
      ['week' => 'Week 2', 'signups' => 38],
      ['week' => 'Week 3', 'signups' => 25],
      ['week' => 'Week 4', 'signups' => 52]
    ];
  }

  /**
   * Compute sector-wise average daily thaali signups (HOFs) for the current week (Mon-Sun).
   * Returns: ['start'=>'Y-m-d','end'=>'Y-m-d','days'=>int,'sectors'=>[['sector'=>string,'total'=>int,'avg'=>float]]]
   */
  private function get_this_week_sector_signup_avg()
  {
    // Determine Monday-Sunday of current week
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));
    // If today is Sunday, 'sunday this week' is today; works fine.
    $start = $monday;
    $end = $sunday;
    // Build date list inclusive
    $dates = [];
    $cursor = strtotime($start);
    $endTs = strtotime($end);
    while ($cursor <= $endTs) {
      $dates[] = date('Y-m-d', $cursor);
      $cursor = strtotime('+1 day', $cursor);
    }
    $days = count($dates);
    if ($days <= 0) {
      return ['start' => $start, 'end' => $end, 'days' => 0, 'sectors' => []];
    }

    // Aggregate totals per sector across the week
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

    // Build display array with averages
    $sectors = [];
    foreach ($agg as $sector => $total) {
      $sectors[] = [
        'sector' => $sector,
        'total' => (int)$total,
        'avg' => round($total / $days, 2),
      ];
    }

    // Sort by avg desc, then name asc
    usort($sectors, function ($a, $b) {
      if ($a['avg'] == $b['avg']) return strcmp($a['sector'], $b['sector']);
      return ($a['avg'] < $b['avg']) ? 1 : -1;
    });

    return [
      'start' => $start,
      'end' => $end,
      'days' => $days,
      'sectors' => $sectors,
    ];
  }

  /**
   * List top FMB miqaats within current Hijri year with invoice/payment totals.
   * Also appends a synthetic Fala ni Niyaz entry for that year when applicable.
   */
  private function get_fmb_miqaats_items($limit = 5)
  {
    $limit = (int)$limit;
    $year_stats = $this->CommonM->get_year_calendar_daytypes();
    $hijri_year = isset($year_stats['hijri_year']) ? $year_stats['hijri_year'] : null;
    if (empty($hijri_year)) return [];

    $rangeRow = $this->db->query(
      "SELECT MIN(greg_date) AS min_d, MAX(greg_date) AS max_d FROM hijri_calendar WHERE hijri_date LIKE ?",
      ['%-' . $hijri_year]
    )->row_array();
    $minDate = $rangeRow && !empty($rangeRow['min_d']) ? $rangeRow['min_d'] : null;
    $maxDate = $rangeRow && !empty($rangeRow['max_d']) ? $rangeRow['max_d'] : null;
    if (empty($minDate) || empty($maxDate)) return [];

    // Per-miqat totals for FMB in the hijri year
    $rows = $this->db->query(
      "SELECT m.id, m.name, m.date,
              COALESCE(SUM(inv.amount), 0) AS total_invoiced,
              COALESCE(SUM(p.amount), 0) AS total_paid
         FROM miqaat m
         LEFT JOIN miqaat_invoice inv ON inv.miqaat_id = m.id
         LEFT JOIN miqaat_payment p ON p.miqaat_invoice_id = inv.id
        WHERE m.type = 'FMB' AND m.date >= ? AND m.date <= ?
        GROUP BY m.id, m.name, m.date
        ORDER BY m.date ASC
        LIMIT $limit",
      [$minDate, $maxDate]
    )->result_array();

    $items = [];
    foreach ($rows as $r) {
      $total = (float)($r['total_invoiced'] ?? 0);
      $paid = (float)($r['total_paid'] ?? 0);
      $items[] = [
        'id' => (int)$r['id'],
        'name' => $r['name'],
        'date' => $r['date'],
        'total' => $total,
        'paid' => $paid,
        'outstanding' => max(0, $total - $paid),
      ];
    }

    // Fala ni Niyaz aggregate for FMB in this hijri year (unlinked invoices)
    $fala = $this->db->query(
      "SELECT 
          COALESCE(SUM(inv.amount), 0) AS total_invoiced
         FROM miqaat_invoice inv
        WHERE inv.miqaat_id IS NULL AND inv.miqaat_type = 'FMB' AND inv.year = ?",
      [$hijri_year]
    )->row_array();
    $fala_total = (float)($fala['total_invoiced'] ?? 0);
    $fala_paid_row = $this->db->query(
      "SELECT 
          COALESCE(SUM(p.amount), 0) AS total_paid
         FROM miqaat_payment p
         JOIN miqaat_invoice inv ON inv.id = p.miqaat_invoice_id
        WHERE inv.miqaat_id IS NULL AND inv.miqaat_type = 'FMB' AND inv.year = ?",
      [$hijri_year]
    )->row_array();
    $fala_paid = (float)($fala_paid_row['total_paid'] ?? 0);

    if ($fala_total > 0 || $fala_paid > 0) {
      $items[] = [
        'id' => null,
        'name' => 'Fala ni Niyaz',
        'date' => null,
        'total' => $fala_total,
        'paid' => $fala_paid,
        'outstanding' => max(0, $fala_total - $fala_paid),
        'hijri_year' => $hijri_year,
      ];
    }

    return $items;
  }

  private function get_recent_member_details()
  {
    // Get recent member payment details
    $query = "SELECT 
      u.ITS_ID,
      u.First_Name,
      u.Surname,
      u.Sector as mohallah,
      COALESCE(st_est.amount, 0) as sabeel_amount,
      COALESCE(ft.total_amount, 0) as thaali_amount
    FROM user u
    LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID 
      AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID)
    LEFT JOIN sabeel_takhmeen_grade st_est ON st_est.id = st.establishment_grade
    LEFT JOIN fmb_takhmeen ft ON ft.user_id = u.ITS_ID 
      AND ft.year = (SELECT MAX(year) FROM fmb_takhmeen WHERE user_id = u.ITS_ID)
    WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL
    ORDER BY u.ITS_ID DESC
    LIMIT 10";

    return $this->db->query($query)->result_array();
  }
  public function RazaRequest()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['raza'] = $this->AdminM->get_raza();
    $data['razatype'] = $this->AdminM->get_razatype();
    foreach ($data['raza'] as $key => $value) {
      $username = $this->AccountM->get_user($value['user_id']);
      $razatype = $this->AdminM->get_razatype_byid($value['razaType'])[0];
      $data['raza'][$key]['razaType'] = $razatype['name'];
      $data['raza'][$key]['razafields'] = $razatype['fields'];
      $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Raza/RazaRequest', $data);
  }
  public function EventRazaRequest()
  {
    // Guard session safely: redirect if not logged in as Anjuman role (3)
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $this->load->model('AmilsahebM');
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->model('AnjumanM');

    $event_type = $this->input->get('event_type');

    if ($event_type == 1) {
      $data['umoor'] = "Miqaat Raza Requests";
    } elseif ($event_type == 2) {
      $data['umoor'] = "Kaaraj Raza Requests";
    } else {
      $data['umoor'] = "Event Raza Requests";
    }

    $razaList = $this->AmilsahebM->get_raza_event($event_type);
    $data['raza'] = is_array($razaList) ? $razaList : [];
    $data['razatype'] = $this->AdminM->get_eventrazatype();

    // Fetch total chat count for each raza_id
    if (!empty($data['raza'])) {
      foreach ($data['raza'] as $key => $value) {
        $rid = isset($value['id']) ? $value['id'] : null;
        $chatCount = $rid ? $this->AccountM->get_chat_count($rid) : 0;
        $data['raza'][$key]['chat_count'] = (int)$chatCount;
      }
    }

    // Fetch user details and raza type
    if (!empty($data['raza'])) {
      foreach ($data['raza'] as $key => $value) {
        $usernameArr = isset($value['user_id']) ? $this->AccountM->get_user($value['user_id']) : [];
        $userName = (is_array($usernameArr) && !empty($usernameArr)) ? ($usernameArr[0]['Full_Name'] ?? '') : '';

        $razatypeArr = isset($value['razaType']) ? $this->AdminM->get_razatype_byid($value['razaType']) : [];
        $razatype = (is_array($razatypeArr) && !empty($razatypeArr)) ? $razatypeArr[0] : ['name' => 'Unknown', 'id' => null, 'fields' => [], 'umoor' => ''];

        $data['raza'][$key]['razaType'] = $razatype['name'];
        $data['raza'][$key]['razaType_id'] = $razatype['id'];
        $data['raza'][$key]['razafields'] = $razatype['fields'];
        $data['raza'][$key]['umoor'] = $razatype['umoor'];
        $data['raza'][$key]['user_name'] = $userName;

        $data['raza'][$key]['miqaat_id'] = isset($value['miqaat_id']) ? $value['miqaat_id'] : null;
        if (!empty($value['miqaat_id'])) {
          $miqaat = $this->AnjumanM->get_miqaat_by_id($value['miqaat_id']);
          $data['raza'][$key]['miqaat_details'] = is_array($miqaat) ? json_encode($miqaat) : '';
        } else {
          $data['raza'][$key]['miqaat_details'] = '';
        }
      }
    }
    // exit;
    $data['event_type'] = $event_type;
    // Set user name
    $data['user_name'] = $_SESSION['user']['username'];

    // Load the view
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/RazaRequest', $data);
  }

  public function UmoorRazaRequest()
  {
    // Safe session guard: must be logged in and role=3 (Anjuman)
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $this->load->model('AmilsahebM');
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->model('AnjumanM');
    $data['umoor'] = "12 Umoor Raza Applications";
    $umoorList = $this->AmilsahebM->get_raza_umoor();
    $data['raza'] = is_array($umoorList) ? $umoorList : [];
    $data['razatype'] = $this->AdminM->get_umoorrazatype();
    // $data['umoortype'] = $this->AdminM->get_umoortype();

    if (!empty($data['raza'])) {
      foreach ($data['raza'] as $key => $value) {
        $rid = isset($value['id']) ? $value['id'] : null;
        $data['raza'][$key]['chat_count'] = $rid ? (int)$this->AccountM->get_chat_count($rid) : 0;
      }
    }

    if (!empty($data['raza'])) {
      foreach ($data['raza'] as $key => $value) {
        $usernameArr = isset($value['user_id']) ? $this->AccountM->get_user($value['user_id']) : [];
        $data['raza'][$key]['user_name'] = (is_array($usernameArr) && !empty($usernameArr)) ? ($usernameArr[0]['Full_Name'] ?? '') : '';

        $razatypeArr = isset($value['razaType']) ? $this->AdminM->get_razatype_byid($value['razaType']) : [];
        $razatype = (is_array($razatypeArr) && !empty($razatypeArr)) ? $razatypeArr[0] : ['name' => 'Unknown', 'fields' => [], 'umoor' => ''];
        $data['raza'][$key]['razaType'] = $razatype['name'];
        $data['raza'][$key]['razafields'] = $razatype['fields'];
        $data['raza'][$key]['umoor'] = $razatype['umoor'];
      }
    }
    $data['user_name'] = $_SESSION['user']['username'];
    // echo '<pre>';
    // echo print_r($data);
    // die();
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/RazaRequest', $data);
  }
  public function miqaat()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/Home', $data);
  }
  public function DeleteRaza($id)
  {
    // Retrieve the value of $umoor from the URL parameters
    $umoor = $this->input->get('umoor');

    $flag = $this->AccountM->delete_raza($id);

    if ($flag) {
      // Check the value of $umoor and redirect accordingly
      if ($umoor == 'Event Raza Applications') {
        redirect('/anjuman/success/EventRazaRequest');
      } else {
        redirect('/anjuman/success/UmoorRazaRequest');
      }
    } else {
      // Check the value of $umoor and redirect to the appropriate error URL
      if ($umoor == 'Event Raza Applications') {
        redirect('/anjuman/error/EventRazaRequest');
      } else {
        redirect('/anjuman/error/UmoorRazaRequest');
      }
    }
  }
  public function approveRaza()
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $remark = $_POST['remark'];
    $raza_id = $_POST['raza_id'];
    $user = $this->AdminM->get_user_by_raza_id($raza_id);

    $this->load->helper('raza_details');
    $razaRow = $this->db->select('id, raza_id, user_id, razaType, razadata, miqaat_id')
      ->from('raza')
      ->where('id', $raza_id)
      ->get()->row_array();
    $rtRow = null;
    if (!empty($razaRow['razaType'])) {
      $rtRow = $this->db->select('id, name, fields')
        ->from('raza_type')
        ->where('id', (int)$razaRow['razaType'])
        ->get()->row_array();
    }
    $razadataDecoded = [];
    if (!empty($razaRow['razadata'])) {
      $tmp = json_decode($razaRow['razadata'], true);
      if (is_array($tmp)) $razadataDecoded = $tmp;
    }
    $rtFieldsDecoded = [];
    if (!empty($rtRow['fields'])) {
      $tmp = json_decode($rtRow['fields'], true);
      if (is_array($tmp)) $rtFieldsDecoded = $tmp;
    }
    $razaName = isset($rtRow['name']) ? (string)$rtRow['name'] : 'Raza';
    $razaPublicId = isset($razaRow['raza_id']) && $razaRow['raza_id'] !== '' ? (string)$razaRow['raza_id'] : (string)$raza_id;
    $detailsHtml = render_raza_details_table_html($razaName, $rtFieldsDecoded, $razadataDecoded);
    $remarkHtml = $remark !== '' ? ('<p><strong>Remark:</strong> ' . nl2br(htmlspecialchars($remark)) . '</p>') : '';
    $flag = $this->AdminM->approve_raza($raza_id, $remark);

    // Enqueue email to user (non-blocking)
    $this->load->model('EmailQueueM');
    $amilsaheb_details = $this->AdminM->get_user_by_role("Amilsaheb");
    $amilsaheb_mobile = substr(preg_replace('/\D+/', '', $amilsaheb_details[0]['Mobile'] ?? ''), -10);

    $message = '<p>Baad Afzalus Salaam,</p>'
      . '<p><strong>Mubarak!</strong> Your Raza request has received a recommendation from Anjuman-e-Saifee Jamaat.</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($razaPublicId) . '</p>'
      . $remarkHtml
      . $detailsHtml
      . '<p>Kindly reach out to <strong>Janab Amil Saheb</strong> via <strong>phone or WhatsApp</strong> at: <strong>+91-' . $amilsaheb_mobile . '</strong> to obtain his <strong>final Raza and Dua</strong>.</p>'
      . '<p><strong>Wassalaam.</strong></p>';

    if (!empty($user['Email'])) {
      $this->EmailQueueM->enqueue($user['Email'], 'Update on Your Raza Request', $message, null, 'html');
    }

    // Notify monitoring/admin recipients
    $msg_html = '<p>Raza request has been recommended by the Jamaat Coordinator.</p>'
      . '<p><strong>Member:</strong> ' . htmlspecialchars($user['Full_Name']) . ' (' . htmlspecialchars($user['ITS_ID']) . ')</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($razaPublicId) . '</p>'
      . $remarkHtml
      . $detailsHtml;
    $notify_recipients = [
      'kharjamaat@gmail.com',
      '3042@carmelnmh.in',
      'kharamilsaheb@gmail.com',
      'kharjamaat786@gmail.com',
      'khozemtopiwalla@gmail.com',
      'ybookwala@gmail.com'
    ];
    foreach ($notify_recipients as $recipient) {
      $this->EmailQueueM->enqueue($recipient, 'Raza Recommended', $msg_html, null, 'html');
    }

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }
  public function rejectRaza()
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $remark = $_POST['remark'];
    $raza_id = $_POST['raza_id'];
    $flag = $this->AdminM->reject_raza($raza_id, $remark);

    $user = $this->AdminM->get_user_by_raza_id($raza_id);

    // Queue rejection email via EmailQueue so it is branded consistently.
    $this->load->model('EmailQueueM');
    $this->load->helper('raza_details');
    $razaRow = $this->db->select('id, raza_id, user_id, razaType, razadata, miqaat_id')
      ->from('raza')
      ->where('id', $raza_id)
      ->get()->row_array();
    $rtRow = null;
    if (!empty($razaRow['razaType'])) {
      $rtRow = $this->db->select('id, name, fields')
        ->from('raza_type')
        ->where('id', (int)$razaRow['razaType'])
        ->get()->row_array();
    }
    $razadataDecoded = [];
    if (!empty($razaRow['razadata'])) {
      $tmp = json_decode($razaRow['razadata'], true);
      if (is_array($tmp)) $razadataDecoded = $tmp;
    }
    $rtFieldsDecoded = [];
    if (!empty($rtRow['fields'])) {
      $tmp = json_decode($rtRow['fields'], true);
      if (is_array($tmp)) $rtFieldsDecoded = $tmp;
    }
    $razaName = isset($rtRow['name']) ? (string)$rtRow['name'] : 'Raza';
    $razaPublicId = isset($razaRow['raza_id']) && $razaRow['raza_id'] !== '' ? (string)$razaRow['raza_id'] : (string)$raza_id;
    $detailsHtml = render_raza_details_table_html($razaName, $rtFieldsDecoded, $razadataDecoded);
    $remarkHtml = $remark !== '' ? ('<p><strong>Remark:</strong> ' . nl2br(htmlspecialchars($remark)) . '</p>') : '';

    $memberEmail = isset($user['Email']) ? trim((string)$user['Email']) : '';
    if ($memberEmail !== '' && filter_var($memberEmail, FILTER_VALIDATE_EMAIL)) {
      $body = '<p>Baad Afzalus Salaam,</p>'
        . '<p>Your Raza has <strong>not</strong> been recommended by the Jamaat coordinator.</p>'
        . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($razaPublicId) . '</p>'
        . $remarkHtml
        . $detailsHtml
        . '<p>Please wait for Janab\'s response or contact Jamaat office for guidance.</p>'
        . '<p><strong>Wassalaam.</strong></p>';
      $this->EmailQueueM->enqueue($memberEmail, 'Update on Your Raza Request', $body, null, 'html');
    }
    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }
  public function razalist()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['raza_type'] = $this->AdminM->get_razatype();
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/ManageRaza', $data);
  }
  public function manage_edit_raza($id)
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['raza'] = $this->AdminM->get_razatype_byid($id)[0];
    // echo '<pre>';
    // echo print_r($data['raza']);
    // die();
    $data['raza']['fields'] = json_decode($data['raza']['fields'], true);
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Raza/EditRaza', $data);
  }
  public function modifyrazaoption()
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $postData = $this->input->post();
    $raza = $this->AdminM->get_razatype_byid($postData['raza-id'])[0];
    $fieldIndexToUpdate = $postData['option-id'];
    $raza['fields'] = json_decode($raza['fields'], true);

    unset($_POST['raza-id']);
    unset($_POST['option-id']);

    if (isset($raza['fields']['fields'][$fieldIndexToUpdate])) {
      $options = [];
      $i = 0;
      foreach ($_POST as $key => $value) {
        $options[] = ['id' => $i, "name" => $value];
        $i++;
      }
      $raza['fields']['fields'][$fieldIndexToUpdate]['options'] = $options;
    }

    // echo '<pre>';
    // echo $postData['option-id'];
    // echo $fieldIndexToUpdate;
    // echo print_r($raza['fields']);
    // die();

    $flag = $this->AdminM->update_raza_type($postData['raza-id'], json_encode($raza['fields']));

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }

  function addField($id)
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $raza = $this->AdminM->get_razatype_byid($id)[0];
    $type = array("0" => "date", "1" => "text", "2" => "number", "3" => "textarea", "4" => "select");

    $raza['fields'] = json_decode($raza['fields'], true);
    if ($_POST['fieldtype'] != "4") {
      $newField = array(
        "name" => $_POST['fieldname'],
        "type" => $type[$_POST['fieldtype']],
        "required" => $_POST['fieldrequired'] == "1",
      );
      $raza['fields']['fields'][] = $newField;
    } else {
      $newField = array(
        "name" => $_POST['fieldname'],
        "type" => $type[$_POST['fieldtype']],
        "required" => $_POST['fieldrequired'] == "1",
        "options" => array(array("id" => "0", "name" => "select"))
      );

      $raza['fields']['fields'][] = $newField;
    }

    // echo '<pre>';
    // echo print_r($raza);
    // die();
    $flag = $this->AdminM->update_raza_type($id, json_encode($raza['fields']));

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }

  function removeField($id)
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $fieldname = $_POST['fieldname'];
    $raza = $this->AdminM->get_razatype_byid($id)[0];
    $raza['fields'] = json_decode($raza['fields'], true);
    foreach ($raza['fields']['fields'] as $key => $field) {
      if ($field['name'] == $fieldname) {
        unset($raza['fields']['fields'][$key]);
        break;
      }
    }
    $flag = $this->AdminM->update_raza_type($id, json_encode($raza['fields']));

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }
  function addRaza()
  {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $raza_name = $_POST['raza-name'];
    // echo $raza_name;
    // die();
    $flag = $this->AdminM->add_new_razatype($raza_name);

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }
  function manage_delete_raza($id)
  {
    $check = $this->AdminM->delet_raza_type($id);
    if ($check) {
      redirect('/anjuman/success/razalist');
    } else {
      redirect('/anjuman/error/razalist');
    }
  }
  public function success($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Success.php', $data);
  }
  public function error($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Error.php', $data);
  }
  public function managemiqaat()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/CreateMiqaat', $data);
  }
  public function addmiqaat()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/AddMiqaat');
  }
  public function submitmiqaat()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
      redirect('/anjuman/success/managemiqaat');
    } else {
      redirect('/anjuman/error/managemiqaat');
    }
  }
  public function modifymiqaat($id)
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/ModifyMiqaat', $data);
  }
  public function submitmodifymiqaat($id)
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
      redirect('/anjuman/success/managemiqaat');
    } else {
      redirect('/anjuman/error/managemiqaat');
    }
  }
  function deletemiqaat($id)
  {
    $check = $this->AdminM->delete_miqaat($id);
    if ($check) {
      redirect('/anjuman/success/managemiqaat');
    } else {
      redirect('/anjuman/error/managemiqaat');
    }
  }
  public function miqaatattendance()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/MiqaatAttendance', $data);
  }

  // Update a miqaat invoice amount (AJAX)
  public function updateMiqaatInvoiceAmount()
  {
    // Expect POST: invoice_id, amount
    $invoice_id = $this->input->post('invoice_id');
    $amount = $this->input->post('amount');

    if (empty($invoice_id) || !is_numeric($invoice_id)) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid invoice id']));
      return;
    }

    if ($amount === null || $amount === '' || !is_numeric($amount) || floatval($amount) < 0) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid amount']));
      return;
    }

    $updated = $this->AnjumanM->update_miqaat_invoice_amount((int)$invoice_id, number_format((float)$amount, 2, '.', ''));

    if ($updated) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(['status' => true]));
    } else {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode(['status' => false, 'error' => 'Update failed']));
    }
  }

  // Delete a miqaat invoice (AJAX)
  public function deleteMiqaatInvoice()
  {
    $invoice_id = $this->input->post('invoice_id');
    if (empty($invoice_id) || !is_numeric($invoice_id)) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid invoice id']));
      return;
    }

    $deleted = $this->AnjumanM->delete_miqaat_invoice((int)$invoice_id);
    if ($deleted) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(['status' => true]));
    } else {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode(['status' => false, 'error' => 'Delete failed']));
    }
  }

  public function asharaohbat()
  {
    $username = $_SESSION['user']['username'];

    // Year selection (Hijri)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $current_hijri_year = (int)explode('-', $h['hijri_date'])[2];
    $selected_year = (int)($this->input->get('year') ?: $current_hijri_year);
    // Pull available Hijri years from calendar for the dropdown
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      // Fallback: surrounding years around current
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

    // If year column doesn't exist, only show data for current year; otherwise, no data.
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
      // Optionally zero-out year-scoped stats when schema lacks year
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

    // Loop through users and populate stats
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

    // Pass data to view
    $data = [
      'user_name' => $username,
      'users' => $users,
      'stats' => $stats,
      'current_sector' => '',
      'current_sub_sector' => '',
      'selected_year' => $selected_year,
      'year_options' => $year_options,
      'back_url' => base_url('anjuman')
    ];

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('MasoolMusaid/AsharaOhbat', $data);
  }


  public function ashara_attendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Use GET parameters if available
    $sel_sector = $this->input->get('sector');
    $sel_sub = $this->input->get('subsector');

    // Hijri Year selection (UI scope only)
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
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }

  // Updated by Patel Infotech Services

  public function getmemberdetails()
  {
    $user_id = $this->input->post("user_id");
    if ($user_id) {
      $member_detials = $this->AnjumanM->getmemberdetails($user_id);
      if ($member_detials) {
        echo json_encode(["success" => true, "member_details" => $member_detials[0]]);
      }
    }
  }

  public function fmbmodule()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/FMBModule', $data);
  }

  public function fmbthaali()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/FMBThaali', $data);
  }

  public function fmbniyaz()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/FMBNiyaz', $data);
  }

  public function fmbthaalitakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    // Build year options from fmb_takhmeen table distinct years (DESC)
    $yearRows = $this->db->select('DISTINCT year', false)->from('fmb_takhmeen')->order_by('year', 'DESC')->get()->result_array();
    $yearOptions = array_values(array_map(function ($r) {
      return $r['year'];
    }, $yearRows));

    // Compute current Hijri financial composite year (months 09–12 + next 01–08)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $currentCompositeYear = null;
    if ($h && isset($h['hijri_date'])) {
      $parts = explode('-', $h['hijri_date']); // d-m-Y
      $hm = isset($parts[1]) ? str_pad($parts[1], 2, '0', STR_PAD_LEFT) : null;
      $hy = isset($parts[2]) ? (int)$parts[2] : null;
      if ($hy) {
        if ($hm >= '09' && $hm <= '12') {
          $currentCompositeYear = sprintf('%d-%s', $hy, substr($hy + 1, -2));
        } else {
          $currentCompositeYear = sprintf('%d-%s', $hy - 1, substr($hy, -2));
        }
      }
    }

    // Default strictly to current Hijri FY (no table fallback)
    $selectedYear = $this->input->post('fmb_year') ?: $currentCompositeYear;

    // Fetch per-user takhmeen details
    $users = $this->AnjumanM->get_user_takhmeen_details();

    // Prepare selected-year display fields per user
    foreach ($users as $idx => $u) {
      $selTotal = 0.0;
      if (!empty($u['all_takhmeen']) && is_array($u['all_takhmeen'])) {
        foreach ($u['all_takhmeen'] as $yr) {
          if (isset($yr['year']) && $yr['year'] == $selectedYear) {
            $selTotal = (float)($yr['total_amount'] ?? 0);
            break;
          }
        }
      }
      $users[$idx]['selected_total_takhmeen'] = $selTotal;
      $users[$idx]['selected_takhmeen_year'] = $selectedYear;
    }

    // Server-side filtering (optional) via GET params: its, sector, sub_sector
    $filter_its = trim($this->input->get('its') ?? '');
    $filter_sector = trim($this->input->get('sector') ?? '');
    $filter_sub_sector = trim($this->input->get('sub_sector') ?? '');

    $filtered = $users;
    if ($filter_its !== '') {
      $filtered = array_filter($filtered, function ($row) use ($filter_its) {
        return (strpos((string)$row['ITS_ID'], $filter_its) !== false);
      });
    }
    if ($filter_sector !== '') {
      $filtered = array_filter($filtered, function ($row) use ($filter_sector) {
        return (isset($row['Sector']) && stripos($row['Sector'], $filter_sector) !== false);
      });
    }
    if ($filter_sub_sector !== '') {
      $filtered = array_filter($filtered, function ($row) use ($filter_sub_sector) {
        return (isset($row['Sub_Sector']) && stripos($row['Sub_Sector'], $filter_sub_sector) !== false);
      });
    }

    // Reindex array for view
    $filtered = array_values($filtered);

    $data["all_user_fmb_takhmeen"] = $filtered;
    $data["user_name"] = $username;
    $data['hijri_years'] = $yearOptions;
    $data['current_year'] = $currentCompositeYear;
    $data['selected_year'] = $selectedYear;
    // expose current filters so view can prefill inputs
    $data['filter_its'] = $filter_its;
    $data['filter_sector'] = $filter_sector;
    $data['filter_sub_sector'] = $filter_sub_sector;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/FMBThaaliTakhmeen', $data);
  }

  // FMB General Contribution section
  public function searchmembers()
  {
    $query = $this->input->post("query");
    if ($query) {
      $members = $this->AnjumanM->search_members($query);
      if ($members) {
        echo json_encode(["success" => true, "members" => $members]);
      } else {
        echo json_encode(["success" => false, "message" => "No members found."]);
      }
    } else {
      echo json_encode(["success" => false, "message" => "Search term is required."]);
    }
  }
  public function fmbgeneralcontribution($type)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $username = $_SESSION['user']['username'];

    $data["contri_type_gc"] = $this->AnjumanM->get_fmbgc_by_type($type);

    $data["all_user_fmbgc"] = $this->AnjumanM->get_user_fmbgc($type);

    $data["user_name"] = $username;
    $data["type"] = $type;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/FMBGeneralContribution', $data);
  }

  public function validatefmbgc()
  {
    $contri_year = $this->input->post("contri_year");
    $user_id = $this->input->post("user_id");
    $contri_type = $this->input->post("contri_type");

    if (
      $contri_year &&
      // Dropdown options: distinct years from table (DESC)
      $contri_type
    ) {
      $result = $this->AnjumanM->validatefmbgc(
        $contri_year,
        $user_id,
        $contri_type
      );
    }

    $yearRows = $this->db->select('DISTINCT year', false)->from('sabeel_takhmeen')->order_by('year', 'DESC')->get()->result_array();
    $yearOptions = array_values(array_map(function ($r) {
      return $r['year'];
    }, $yearRows));
    if ($result) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false]);
    }
  }

  public function addfmbgc()
  {
    $contri_year = $this->input->post("contri_year");
    $user_id = $this->input->post("user_id");
    $fmb_type = $this->input->post("fmb_type");
    $contri_type = $this->input->post("contri_type");
    $amount = $this->input->post("amount");
    $description = $this->input->post("description");

    $data = array(
      "contri_year" => $contri_year,
      "user_id" => $user_id,
      "fmb_type" => $fmb_type,
      "contri_type" => $contri_type,
      "amount" => $amount,
      "description" => $description,
    );

    $result = $this->AnjumanM->addfmbgc($data);


    if ($fmb_type == "Thaali") {
      redirect("anjuman/fmbgeneralcontribution/" . "1");
    } else {
      redirect("anjuman/fmbgeneralcontribution/" . "2");
    }
  }

  public function updatefmbgcpayment1($id = NULL, $type = NULL)
  {
    $result = $this->AnjumanM->updatefmbgcpayment($id, 1);
    if ($type == 1) {
      redirect("anjuman/fmbgeneralcontribution/" . "1");
    } else {
      redirect("anjuman/fmbgeneralcontribution/" . "2");
    }
  }
  public function updatefmbgcpayment0($id = NULL, $type = NULL)
  {
    $result = $this->AnjumanM->updatefmbgcpayment($id, 0);
    if ($type == 1) {
      redirect("anjuman/fmbgeneralcontribution/" . "1");
    } else {
      redirect("anjuman/fmbgeneralcontribution/" . "2");
    }
  }

  /**
   * New unified payment receiver for General Contribution invoices.
   * Accepts POST with: fmbgc_id, user_id, payment_method, payment_received_amount, payment_date, payment_remarks
   */
  public function updatefmbgcpayment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $fmbgc_id = (int)$this->input->post('fmbgc_id');
    $user_id = (int)$this->input->post('user_id');
    $method = trim($this->input->post('payment_method'));
    $amount = (float)$this->input->post('payment_received_amount');
    $pdate = $this->input->post('payment_date');
    $remarks = $this->input->post('payment_remarks');

    if (!$fmbgc_id || !$user_id || !$method || !$amount || !$pdate) {
      $this->session->set_flashdata('error', 'Missing required payment fields.');
      redirect($_SERVER['HTTP_REFERER'] ?? base_url('anjuman/fmbgeneralcontribution/1'));
    }

    // Determine fmb type for redirect
    $invoice = $this->db->select('id, fmb_type')->from('fmb_general_contribution')->where('id', $fmbgc_id)->get()->row_array();
    $redirectType = 1; // default Thaali
    if ($invoice && isset($invoice['fmb_type']) && strtolower($invoice['fmb_type']) === 'niyaz') {
      $redirectType = 2;
    }

    $result = $this->AnjumanM->insert_fmbgc_payment($fmbgc_id, $user_id, $amount, $method, $pdate, $remarks);
    if ($result['success']) {
      $this->session->set_flashdata('success', $result['message']);
    } else {
      $this->session->set_flashdata('error', $result['message']);
    }
    redirect('anjuman/fmbgeneralcontribution/' . $redirectType);
  }

  /**
   * AJAX: return JSON payment history for an FMB General Contribution invoice.
   * Expects POST: fmbgc_id
   */
  public function fmbgc_payment_history()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      show_error('Unauthorized', 401);
      return;
    }
    $fmbgc_id = (int)$this->input->post('fmbgc_id');
    if (!$fmbgc_id) {
      echo json_encode(['success' => false, 'message' => 'Missing invoice id']);
      return;
    }
    $history = $this->AnjumanM->get_fmbgc_payment_history($fmbgc_id);
    if (!$history['meta']) {
      echo json_encode(['success' => false, 'message' => 'Invoice not found']);
      return;
    }
    // Compute totals
    $total_received = 0;
    foreach ($history['payments'] as $p) {
      $total_received += (float)$p['amount'];
    }
    $balance = $history['meta']['amount'] - $total_received;
    // Normalize key: ensure each payment has payment_id field
    $payments = array_map(function ($p) {
      if (!isset($p['payment_id']) && isset($p['id'])) {
        $p['payment_id'] = $p['id'];
      }
      return $p;
    }, $history['payments']);
    echo json_encode([
      'success' => true,
      'invoice' => $history['meta'],
      'payments' => $payments,
      'total_received' => $total_received,
      'balance_due' => $balance < 0 ? 0 : $balance
    ]);
  }

  /**
   * AJAX: delete a payment (POST: payment_id)
   */
  public function fmbgc_delete_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      return;
    }
    $pid = (int)$this->input->post('payment_id');
    if (!$pid) {
      echo json_encode(['success' => false, 'message' => 'Missing payment id']);
      return;
    }
    $res = $this->AnjumanM->delete_fmbgc_payment($pid);
    if ($res['success'] && isset($res['invoice_id'])) {
      $summary = $this->AnjumanM->get_fmbgc_invoice_summary($res['invoice_id']);
      $res['summary'] = $summary;
    }
    echo json_encode($res);
  }

  /**
   * AJAX: delete entire GC invoice (POST: invoice_id)
   */
  public function fmbgc_delete_invoice()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      return;
    }
    $invoice_id = (int)$this->input->post('invoice_id');
    if (!$invoice_id) {
      echo json_encode(['success' => false, 'message' => 'Missing invoice id']);
      return;
    }
    $res = $this->AnjumanM->delete_fmbgc_invoice($invoice_id);
    echo json_encode($res);
  }
  // FMB General Contribution section

  public function update_fmb_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $username = $_SESSION['user']['username'];

    $user_id = $this->input->post("user_id");
    $payment_method = $this->input->post("payment_method");
    $amount = $this->input->post("amount");
    $payment_date = $this->input->post("payment_date");
    $remarks = $this->input->post("remarks");

    $formData = array(
      "user_id" => $user_id,
      "payment_method" => $payment_method,
      "amount" => $amount,
      "payment_date" => $payment_date,
      "remarks" => $remarks,
    );

    $result = $this->AnjumanM->update_fmb_payment($formData);

    if ($result) {
      redirect('anjuman/success/fmbthaalitakhmeen');
    } else {
      $this->session->set_flashdata('error', 'Error updating payment. Please try again.');
      redirect('anjuman/fmbthaalitakhmeen');
    }
  }

  public function delete_takhmeen_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $payment_id = $this->input->post("payment_id");

    if ($payment_id) {
      $result = $this->AnjumanM->delete_takhmeen_payment($payment_id);
      if ($result) {
        echo json_encode(["success" => true]);
      } else {
        echo json_encode(["success" => false, "message" => "Failed to delete payment."]);
      }
    } else {
      echo json_encode(["success" => false, "message" => "Invalid payment ID."]);
    }
  }
  public function sabeeltakhmeendashboard()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Build year options from sabeel_takhmeen table distinct years (DESC)
    $yearRows = $this->db->select('DISTINCT year', false)->from('sabeel_takhmeen')->order_by('year', 'DESC')->get()->result_array();
    $yearOptions = array_values(array_map(function ($r) {
      return $r['year'];
    }, $yearRows));
    // Compute current Hijri financial composite year (months 09–12 + next 01–08)
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $currentCompositeYear = null;
    if ($h && isset($h['hijri_date'])) {
      $parts = explode('-', $h['hijri_date']); // d-m-Y
      $hm = isset($parts[1]) ? str_pad($parts[1], 2, '0', STR_PAD_LEFT) : null;
      $hy = isset($parts[2]) ? (int)$parts[2] : null;
      if ($hy) {
        if ($hm >= '09' && $hm <= '12') {
          $currentCompositeYear = sprintf('%d-%s', $hy, substr($hy + 1, -2));
        } else {
          $currentCompositeYear = sprintf('%d-%s', $hy - 1, substr($hy, -2));
        }
      }
    }
    // Default strictly to current Hijri FY (no table fallback)
    $selectedYear = $this->input->post('sabeel_year') ?: $currentCompositeYear;

    // Fetch data for selected year
    $data["all_user_sabeel_takhmeen"] = $this->AnjumanM->get_user_sabeel_takhmeen_details([
      'year' => $selectedYear,
      // Include HOF even if they have no takhmeen for selected year
      'require_current_year' => false,
      'allow_latest_when_missing' => false,
    ]);

    $data["user_name"] = $username;
    $data['hijri_years'] = $yearOptions;
    $data['current_year'] = $currentCompositeYear;
    $data['selected_year'] = $selectedYear;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/SabeelTakhmeenDashboard', $data);
  }
  public function filteruserinsabeeltakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $member_name = $this->input->post("member_name");
    $its_id = $this->input->post("its_id");
    $selectedYear = $this->input->post('sabeel_year');

    // If year not posted, compute current composite like in main method
    if (empty($selectedYear)) {
      $today = date('Y-m-d');
      $h = $this->HijriCalendar->get_hijri_date($today);
      if ($h && isset($h['hijri_date'])) {
        $parts = explode('-', $h['hijri_date']);
        $hm = isset($parts[1]) ? str_pad($parts[1], 2, '0', STR_PAD_LEFT) : null;
        $hy = isset($parts[2]) ? (int)$parts[2] : null;
        if ($hy) {
          if ($hm >= '09' && $hm <= '12') {
            $selectedYear = sprintf('%d-%s', $hy, substr($hy + 1, -2));
          } else {
            $selectedYear = sprintf('%d-%s', $hy - 1, substr($hy, -2));
          }
        }
      }
    }

    $filter_data = [];
    if (!empty($member_name)) $filter_data["member_name"] = $member_name;
    if (!empty($its_id)) $filter_data["its_id"] = $its_id;
    if (!empty($selectedYear)) $filter_data['year'] = $selectedYear;
    // Include HOF even if they have no takhmeen for selected year
    $filter_data['require_current_year'] = false;
    $filter_data['allow_latest_when_missing'] = false;

    $data["all_user_sabeel_takhmeen"] = $this->AnjumanM->get_user_sabeel_takhmeen_details($filter_data);

    // Build year options from table (DESC) for header
    $yearRows = $this->db->select('DISTINCT year', false)->from('sabeel_takhmeen')->order_by('year', 'DESC')->get()->result_array();
    $yearOptions = array_values(array_map(function ($r) {
      return $r['year'];
    }, $yearRows));

    $data["user_name"] = $username;
    $data["member_name"] = $member_name;
    $data["its_id"] = $its_id;
    $data['hijri_years'] = $yearOptions;
    $data['selected_year'] = $selectedYear;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/SabeelTakhmeenDashboard', $data);
  }
  public function updatesabeeltakhmeenpayment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $username = $_SESSION['user']['username'];

    $user_id = $this->input->post("user_id");
    $payment_method = $this->input->post("payment_method");
    $type = $this->input->post("type");
    $amount = $this->input->post("amount");
    $payment_date = $this->input->post("payment_date");
    $remarks = $this->input->post("remarks");

    $formData = array(
      "user_id" => $user_id,
      "payment_method" => $payment_method,
      "type" => $type,
      "amount" => $amount,
      "payment_date" => $payment_date,
      "remarks" => $remarks,
    );

    $result = $this->AnjumanM->update_sabeel_payment($formData);

    if ($result) {
      redirect('Anjuman/success/SabeelTakhmeenDashboard');
    }
  }

  public function getPaymentHistory($for)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $user_id = $this->input->post('user_id');
    $result = $this->AnjumanM->getPaymentHistory($user_id, $for);
    echo json_encode($result);
  }

  public function miqaatinvoicepayment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $miqaat_type = $this->input->get('miqaat_type');
    switch ($miqaat_type) {
      case 1:
        $miqaat_type = 'Shehrullah';
        break;
      case 2:
        $miqaat_type = 'Ashara';
        break;
      case 3:
        $miqaat_type = 'General';
        break;
      case 4:
        $miqaat_type = 'Ladies';
        break;
      default:
        $miqaat_type = 'Shehrullah';
        break;
    }
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;

    $data["miqaat_type"] = $miqaat_type;

    // Determine selected Hijri year: prefer GET 'year', else current Hijri year
    $year_stats = $this->CommonM->get_year_calendar_daytypes();
    $current_hijri_year = isset($year_stats['hijri_year']) ? $year_stats['hijri_year'] : null;
    $selected_year = $this->input->get('year');
    if (empty($selected_year)) {
      $selected_year = $current_hijri_year;
    }
    $data['current_hijri_year'] = $selected_year;

    // Include FM entries as well so invoices generated for family members appear here; scope by selected year
    $data["member_miqaat_payments"] = $this->AnjumanM->get_all_member_miqaat_payments($miqaat_type, true, $selected_year);

    // Build list of available Hijri years from hijri_calendar table (FMB calendar), newest first
    $yearsList = [];
    // hijri_date expected in format 'd-m-Y' (day-month-year). Extract year part.
    $rows = $this->db->select("DISTINCT SUBSTRING_INDEX(hijri_date, '-', -1) as hy")->from('hijri_calendar')->order_by('hy DESC')->get()->result_array();
    foreach ($rows as $r) {
      if (!empty($r['hy'])) $yearsList[] = $r['hy'];
    }
    $yearsList = array_values(array_unique($yearsList));
    rsort($yearsList);
    $data['hijri_years'] = $yearsList;

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/MiqaatInvoicePayment', $data);
  }

  public function generatemiqaatinvoice()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $miqaat_type = $this->input->get('miqaat_type');
    switch ($miqaat_type) {
      case 1:
        $miqaat_type = 'Shehrullah';
        break;
      case 2:
        $miqaat_type = 'Ashara';
        break;
      case 3:
        $miqaat_type = 'General';
        break;
      case 4:
        $miqaat_type = 'Ladies';
        break;
      default:
        $miqaat_type = 'Shehrullah';
        break;
    }
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;

    $data["miqaat_type"] = $miqaat_type;

    $data["miqaats"] = $this->AnjumanM->get_all_approved_past_miqaats($miqaat_type);

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/GenerateMiqaatInvoice', $data);
  }

  public function updatemiqaatinvoice()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $miqaat_type = $this->input->get('miqaat_type');
    switch ($miqaat_type) {
      case 1:
        $miqaat_type = 'Shehrullah';
        break;
      case 2:
        $miqaat_type = 'Ashara';
        break;
      case 3:
        $miqaat_type = 'General';
        break;
      case 4:
        $miqaat_type = 'Ladies';
        break;
      default:
        $miqaat_type = 'Shehrullah';
        break;
    }
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;

    $data["miqaat_type"] = $miqaat_type;

    // Resolve today's Hijri year so we can default to it when no `year` GET param
    $todayHijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $currentHijriYear = null;
    if ($todayHijri && isset($todayHijri['hijri_date'])) {
      $parts = explode('-', $todayHijri['hijri_date']);
      $currentHijriYear = $parts[2] ?? null;
    }

    // Determine selected Hijri year: GET param `year` or default to current Hijri year
    $selectedYear = $this->input->get('year');
    if (empty($selectedYear) && !empty($currentHijriYear)) {
      $selectedYear = $currentHijriYear;
    }

    $data["member_miqaat_invoices"] = $this->AnjumanM->get_all_member_miqaat_invoices($miqaat_type, $selectedYear);
    // Normalize invoice amounts so views can rely on both keys
    if (is_array($data["member_miqaat_invoices"])) {
      // When model returns structure with ['members']
      if (isset($data["member_miqaat_invoices"]["members"]) && is_array($data["member_miqaat_invoices"]["members"])) {
        foreach ($data["member_miqaat_invoices"]["members"] as $mi => $member) {
          if (isset($member['miqaat_invoices']) && is_array($member['miqaat_invoices'])) {
            foreach ($member['miqaat_invoices'] as $ii => $inv) {
              $hasAmount = isset($inv['amount']) && $inv['amount'] !== '' && $inv['amount'] !== null;
              $hasInvoiceAmount = isset($inv['invoice_amount']) && $inv['invoice_amount'] !== '' && $inv['invoice_amount'] !== null;
              if ($hasAmount && !$hasInvoiceAmount) {
                $data["member_miqaat_invoices"]["members"][$mi]['miqaat_invoices'][$ii]['invoice_amount'] = $inv['amount'];
              } elseif (!$hasAmount && $hasInvoiceAmount) {
                $data["member_miqaat_invoices"]["members"][$mi]['miqaat_invoices'][$ii]['amount'] = $inv['invoice_amount'];
              } elseif (!$hasAmount && !$hasInvoiceAmount) {
                $data["member_miqaat_invoices"]["members"][$mi]['miqaat_invoices'][$ii]['amount'] = 0;
                $data["member_miqaat_invoices"]["members"][$mi]['miqaat_invoices'][$ii]['invoice_amount'] = 0;
              }
            }
          }
        }
      } else {
        // Flat array of members
        foreach ($data["member_miqaat_invoices"] as $mi => $member) {
          if (isset($member['miqaat_invoices']) && is_array($member['miqaat_invoices'])) {
            foreach ($member['miqaat_invoices'] as $ii => $inv) {
              $hasAmount = isset($inv['amount']) && $inv['amount'] !== '' && $inv['amount'] !== null;
              $hasInvoiceAmount = isset($inv['invoice_amount']) && $inv['invoice_amount'] !== '' && $inv['invoice_amount'] !== null;
              if ($hasAmount && !$hasInvoiceAmount) {
                $data["member_miqaat_invoices"][$mi]['miqaat_invoices'][$ii]['invoice_amount'] = $inv['amount'];
              } elseif (!$hasAmount && $hasInvoiceAmount) {
                $data["member_miqaat_invoices"][$mi]['miqaat_invoices'][$ii]['amount'] = $inv['invoice_amount'];
              } elseif (!$hasAmount && !$hasInvoiceAmount) {
                $data["member_miqaat_invoices"][$mi]['miqaat_invoices'][$ii]['amount'] = 0;
                $data["member_miqaat_invoices"][$mi]['miqaat_invoices'][$ii]['invoice_amount'] = 0;
              }
            }
          }
        }
      }
    }

    // Fetch distinct sectors & sub sectors for filter dropdowns (backend sourced instead of view derivation)
    $sectorRows = $this->db->distinct()->select('Sector')->from('user')->where('Sector !=', '')->order_by('Sector', 'ASC')->get()->result_array();
    $subSectorRows = $this->db->distinct()->select('Sub_Sector')->from('user')->where('Sub_Sector !=', '')->order_by('Sub_Sector', 'ASC')->get()->result_array();
    $data['sectors'] = $sectorRows; // each row: ['Sector' => '...']
    $data['sub_sectors'] = $subSectorRows; // each row: ['Sub_Sector' => '...']

    // Hijri year list for year filter dropdown (full range from hijri_calendar)
    $todayHijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $currentHijriYear = null;
    if ($todayHijri && isset($todayHijri['hijri_date'])) {
      $parts = explode('-', $todayHijri['hijri_date']);
      $currentHijriYear = $parts[2] ?? null;
    }
    $rangeRow = $this->db->query("SELECT MIN(CAST(SUBSTRING_INDEX(hijri_date,'-',-1) AS UNSIGNED)) AS min_y, MAX(CAST(SUBSTRING_INDEX(hijri_date,'-',-1) AS UNSIGNED)) AS max_y FROM hijri_calendar")->row_array();
    $minY = isset($rangeRow['min_y']) && $rangeRow['min_y'] !== null ? (int)$rangeRow['min_y'] : (int)$currentHijriYear;
    $maxY = isset($rangeRow['max_y']) && $rangeRow['max_y'] !== null ? (int)$rangeRow['max_y'] : (int)$currentHijriYear;
    $hijri_years = [];
    if ($maxY >= $minY && $minY > 0) {
      for ($y = $maxY; $y >= $minY; $y--) {
        $hijri_years[] = (string)$y;
      }
    } elseif ($currentHijriYear) {
      $hijri_years[] = (string)$currentHijriYear;
    }
    $data['hijri_years'] = $hijri_years;
    $data['current_hijri_year'] = $currentHijriYear; // explicit current hijri year
    $data['selected_year'] = $selectedYear; // currently selected filter year

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/UpdateMiqaatInvoice', $data);
  }

  public function get_miqaats_by_type()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $type = $this->input->post('type');
    $year = $this->input->post('year');
    $result = $this->AnjumanM->get_miqaats_by_type($type, $year);
    echo json_encode($result);
  }

  public function get_miqaat_assigned_to()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $miqaat_id = $this->input->post('miqaat_id');
    $assigned_to = $this->AnjumanM->get_miqaat_assigned_to($miqaat_id);
    echo json_encode($assigned_to);
  }

  public function get_miqaat_members()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $miqaat_id = $this->input->post('miqaat_id');
    $assigned_to = $this->input->post('assigned_to');
    $members = [];
    if ($miqaat_id && $assigned_to) {
      $members = $this->AnjumanM->get_miqaat_members($miqaat_id, $assigned_to);
    }
    echo json_encode($members);
  }

  public function create_miqaat_invoice()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $miqaat_id   = $this->input->post('miqaat_id');
    $raza_id     = $this->input->post('raza_id');
    $miqaat_type = $this->input->post('miqaat_type');
    $year        = $this->input->post('year');
    $assigned_to = $this->input->post('assigned_to');
    $member_id   = $this->input->post('member_id');
    $amount      = $this->input->post('amount');
    $description = $this->input->post('description');
    $date        = $this->input->post('invoice_date');

    switch ($miqaat_type) {
      case 'Shehrullah':
        $miqaat_type_page = 1;
        break;
      case 'Ashara':
        $miqaat_type_page = 2;
        break;
      case 'General':
        $miqaat_type_page = 3;
        break;
      case 'Ladies':
        $miqaat_type_page = 4;
        break;
      default:
        $miqaat_type_page = 1;
        break;
    }

    if (strtolower($assigned_to) == 'fala ni niyaz' && $year) {
      $dates = $this->db->select('greg_date')
        ->from('hijri_calendar')
        ->like('hijri_date', $year)
        ->get()
        ->result_array();

      $greg_dates = array_column($dates, 'greg_date');

      if (!empty($greg_dates)) {
        $miqaats = $this->db->select('id, name, assigned_to')
          ->from('miqaat')
          ->where_in('date', $greg_dates)
          ->where('type', $miqaat_type)
          ->get()
          ->result_array();

        $miqaat_assigned_to = array_map(function ($v) {
          return is_string($v) ? trim($v) : $v;
        }, array_column($miqaats, 'assigned_to'));

        $hasEmptyAssignment = false;
        foreach ($miqaat_assigned_to as $assigned) {
          if ($assigned === null || $assigned === '') {
            $hasEmptyAssignment = true;
            break;
          }
        }

        if ($hasEmptyAssignment) {
          $this->session->set_flashdata('error', "Miqaat takhmeen can't be done without complete assignments of the miqaats.");
          redirect('Anjuman/generatemiqaatinvoice?miqaat_type=' . $miqaat_type_page);
          return;
        }

        $miqaat_ids = array_column($miqaats, 'id');

        if (!empty($miqaat_ids)) {
          $all_hofs = $this->db
            ->distinct()
            ->select('HOF_ID')
            ->from('user')
            ->where("HOF_FM_TYPE", 'HOF')
            ->where('Inactive_Status IS NULL')
            ->get()
            ->result_array();
          $all_hof_ids = array_column($all_hofs, 'HOF_ID');

          $sql = "SELECT DISTINCT member_id AS participant_id
                  FROM miqaat_assignments
                  WHERE miqaat_id IN (" . implode(',', $miqaat_ids) . ")
                    AND member_id IS NOT NULL
                    AND member_id <> ''
                    AND TRIM(LOWER(assign_type)) = 'Individual'";

          $participated = $this->db->query($sql)->result_array();
          $participated_ids = array_column($participated, 'participant_id');

          $participated_hof_ids = [];
          if (!empty($participated_ids)) {
            $participated_hofs = $this->db
              ->distinct()
              ->select('HOF_ID')
              ->from('user')
              ->where_in('ITS_ID', $participated_ids)
              ->get()
              ->result_array();
            $participated_hof_ids = array_column($participated_hofs, 'HOF_ID');
          }

          $not_participated_hofs = array_diff($all_hof_ids, $participated_hof_ids);

          $description_for_fala_ni_niyaz = $miqaat_type . " " . $year . " Niyaz Fund";

          foreach ($not_participated_hofs as $hof_id) {
            if (!$hof_id) continue;
            if ($miqaat_type == "General" || $miqaat_type == "Ladies") {
              $data = [
                'date'          => $date,
                'year'          => $year,
                'miqaat_id'     => $miqaat_id,
                'miqaat_type'   => $miqaat_type,
                'raza_id'       => $raza_id,
                'user_id'       => $hof_id,
                'amount'        => $amount,
                'description'   => $description_for_fala_ni_niyaz
              ];
            } else {
              $data = [
                'date'          => $date,
                'year'          => $year,
                'miqaat_type'   => $miqaat_type,
                'user_id'       => $hof_id,
                'amount'        => $amount,
                'description'   => $description_for_fala_ni_niyaz
              ];
            }
            $this->AnjumanM->create_miqaat_invoice($data);
          }
        }
      }

      $this->session->set_flashdata('success', 'Invoice(s) created successfully.');
      redirect('Anjuman/generatemiqaatinvoice?miqaat_type=' . $miqaat_type_page);
      return;
    }

    if ($miqaat_id && $assigned_to && !empty($member_id) && $amount && $date) {
      // Assign invoice directly to the selected members (ITS_ID), not to HOF
      $member_ids = is_array($member_id) ? $member_id : [$member_id];
      foreach ($member_ids as $mid) {
        if (empty($mid)) continue;
        $data = [
          'date'        => $date,
          'miqaat_id'   => $miqaat_id,
          'raza_id'     => $raza_id,
          'user_id'     => $mid,
          'amount'      => $amount,
          'description' => $description
        ];
        $this->AnjumanM->create_miqaat_invoice($data);
      }

      $this->session->set_flashdata('success', 'Invoice(s) created successfully.');
      redirect('Anjuman/generatemiqaatinvoice?miqaat_type=' . $miqaat_type_page);
    } else {
      $this->session->set_flashdata('error', 'Failed to create invoice.');
      redirect('Anjuman/generatemiqaatinvoice');
    }
  }

  public function miqaatinvoice()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;

    $im_limit = 5;
    $im_page = isset($_GET['im_page']) ? max(1, intval($_GET['im_page'])) : 1;
    $im_offset = ($im_page - 1) * $im_limit;
    $data["invoice_miqaats"] = $this->AnjumanM->get_grouped_invoices_by_miqaat($im_limit, $im_offset);
    $im_total = $this->AnjumanM->get_miqaats_with_invoices_count();
    $data['im_pagination'] = [
      'total' => $im_total,
      'limit' => $im_limit,
      'page' => $im_page,
      'pages' => ceil($im_total / $im_limit),
    ];


    $limit = 5;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $limit;

    $data['miqaat_invoices'] = $this->AnjumanM->get_miqaat_invoices_paginated($limit, $offset);
    $total = $this->AnjumanM->get_miqaat_invoices_count();
    $data['pagination'] = [
      'total' => $total,
      'limit' => $limit,
      'page' => $page,
      'pages' => ceil($total / $limit),
    ];

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/MiqaatInvoice', $data);
  }


  // AJAX: Update miqaat invoice amount
  public function update_miqaat_invoice_amount()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }
    $invoice_id = $this->input->post('invoice_id');
    $amount = $this->input->post('amount');
    if (!$invoice_id || $amount === null) {
      echo json_encode(['success' => false, 'error' => 'Missing data']);
      return;
    }
    $result = $this->AnjumanM->update_miqaat_invoice_amount($invoice_id, $amount);
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Update failed']);
    }
  }

  // AJAX: Delete miqaat invoice
  public function delete_miqaat_invoice()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }
    $invoice_id = $this->input->post('invoice_id');
    if (!$invoice_id) {
      echo json_encode(['success' => false, 'error' => 'Missing invoice_id']);
      return;
    }
    $result = $this->AnjumanM->delete_miqaat_invoice($invoice_id);
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Delete failed']);
    }
  }

  public function miqaatpayment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;

    // Pagination setup
    $limit = 10;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $limit;

    $data['miqaat_payments'] = $this->AnjumanM->get_miqaat_payments_paginated($limit, $offset);
    $total = $this->AnjumanM->get_miqaat_payments_count();
    $data['pagination'] = [
      'total' => $total,
      'limit' => $limit,
      'page' => $page,
      'pages' => ceil($total / $limit),
    ];

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/MiqaatPayment', $data);
  }

  public function newmiqaatpayment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/NewMiqaatPayment', $data);
  }

  public function search_members()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $query = $this->input->post("query");

    $all_members = $this->AnjumanM->get_all_members($query);
    echo json_encode($all_members);
  }

  public function addmiqaatpayment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $payment_date = $this->input->post('payment_date');
    $payment_method = $this->input->post('payment_method');
    $member_id = $this->input->post('user_id');
    $amount = $this->input->post('amount');
    $remarks = $this->input->post('remark');

    if (!$member_id || !$amount || !$payment_date) {
      $this->session->set_flashdata('error', 'Missing required fields.');
      redirect('Anjuman/newmiqaatpayment');
      return;
    }

    $data = [
      'payment_date' => $payment_date,
      'payment_method' => $payment_method,
      'user_id' => $member_id,
      'amount' => $amount,
      'remarks' => $remarks
    ];

    $result = $this->AnjumanM->addmiqaatpayment($data);

    if ($result) {
      $this->session->set_flashdata('success', 'Payment recorded successfully.');
      redirect('Anjuman/miqaatpayment');
    } else {
      $this->session->set_flashdata('error', 'Failed to record payment.');
      redirect('Anjuman/newmiqaatpayment');
    }
  }

  // AJAX: Record a payment against a specific invoice (invoice-wise)
  public function add_miqaat_invoice_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }

    $invoice_id     = $this->input->post('invoice_id');
    $amount         = $this->input->post('amount');
    $payment_date   = $this->input->post('payment_date');
    $payment_method = $this->input->post('payment_method');
    $remarks        = $this->input->post('remarks');

    if (!$invoice_id || !is_numeric($invoice_id)) {
      echo json_encode(['success' => false, 'error' => 'Missing or invalid invoice_id']);
      return;
    }
    if ($amount === null || $amount === '' || !is_numeric($amount) || floatval($amount) <= 0) {
      echo json_encode(['success' => false, 'error' => 'Invalid amount']);
      return;
    }
    if (empty($payment_date)) {
      echo json_encode(['success' => false, 'error' => 'Payment date required']);
      return;
    }

    // Server-side guard: do not allow payment beyond due
    $caps = $this->AnjumanM->get_invoice_paid_and_amount((int)$invoice_id);
    $invoice_total = (float)$caps['invoice_amount'];
    $already_paid = (float)$caps['paid_sum'];
    $due = max(0.0, $invoice_total - $already_paid);
    $amount_f = (float) number_format((float)$amount, 2, '.', '');
    if ($amount_f > $due + 1e-6) {
      echo json_encode(['success' => false, 'error' => 'Amount exceeds due. Max allowed: ' . number_format($due, 2)]);
      return;
    }

    $ok = $this->AnjumanM->add_miqaat_invoice_payment((int)$invoice_id, number_format((float)$amount, 2, '.', ''), $payment_date, $payment_method, $remarks);

    if ($ok) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Failed to save payment']);
    }
  }

  public function update_miqaat_payment_amount()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }
    $payment_id = $this->input->post('payment_id');
    $amount = $this->input->post('amount');
    if (isset($payment_id) === null || $amount === null) {
      echo json_encode(['success' => false, 'error' => 'Missing data']);
      return;
    }
    // Server-side guard: ensure editing this payment doesn't exceed invoice total
    $amount_f = (float) number_format((float)$amount, 2, '.', '');
    if (!$this->AnjumanM->can_update_payment_amount($payment_id, $amount_f)) {
      echo json_encode(['success' => false, 'error' => 'Updated amount exceeds invoice total.']);
      return;
    }
    $result = $this->AnjumanM->update_miqaat_payment_amount($payment_id, $amount_f);
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Update failed']);
    }
  }

  public function delete_miqaat_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }
    $payment_id = $this->input->post('payment_id');
    if (isset($payment_id) === null) {
      echo json_encode(['success' => false, 'error' => 'Missing payment_id']);
      return;
    }
    $result = $this->AnjumanM->delete_miqaat_payment($payment_id);
    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Delete failed']);
    }
  }

  public function deleteSabeelPayment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'unauthorized']));
      return;
    }
    $payment_id = $this->input->post('payment_id');
    if (empty($payment_id)) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'missing id']));
      return;
    }
    $ok = $this->AnjumanM->delete_sabeel_payment($payment_id);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => (bool)$ok]));
  }

  /* ================= Corpus Funds (Receive) ================= */
  public function corpusfunds_receive()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $this->load->model('CorpusFundM');
    $data['user_name'] = $_SESSION['user']['username'];
    $data['message'] = $this->session->flashdata('corpus_payment_message');
    $data['error'] = $this->session->flashdata('corpus_payment_error');
    // Read optional filters from GET (for persistent server-side filtering)
    $filters = [];
    // Avoid trim(null) deprecation by casting to string when input may be null
    $filters['its_id'] = ($this->input->get('its_id') !== null) ? trim((string)$this->input->get('its_id')) : null;
    $filters['sector'] = ($this->input->get('sector') !== null) ? trim((string)$this->input->get('sector')) : null;
    $filters['sub_sector'] = ($this->input->get('sub_sector') !== null) ? trim((string)$this->input->get('sub_sector')) : null;
    $filters['fund_id'] = $this->input->get('fund_id') ? (int)$this->input->get('fund_id') : null;
    $filters['hijri_year'] = $this->input->get('hijri_year') ? (int)$this->input->get('hijri_year') : null;

    // Fetch all assignments with payment aggregates (apply filters if any)
    $data['assignments'] = $this->CorpusFundM->get_all_assignments_with_payments($filters);
    // Expose filter values back to view for prefill
    $data['filter_its'] = $filters['its_id'];
    $data['filter_sector'] = $filters['sector'];
    $data['filter_sub_sector'] = $filters['sub_sector'];
    $data['filter_fund'] = $filters['fund_id'];
    $data['filter_hijri_year'] = $filters['hijri_year'];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/CorpusFundsReceive', $data);
  }

  public function corpusfunds_receive_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('anjuman/corpusfunds_receive');
    }
    $fund_id = (int)$this->input->post('fund_id');
    $hof_id = (int)$this->input->post('hof_id');
    $amount = (float)$this->input->post('amount');
    $notes = trim($this->input->post('notes'));
    $payment_date = trim($this->input->post('payment_date'));
    $payment_method = trim($this->input->post('payment_method'));
    if ($fund_id <= 0 || $hof_id <= 0 || $amount <= 0) {
      $this->session->set_flashdata('corpus_payment_error', 'Invalid data (positive amount required).');
      redirect('anjuman/corpusfunds_receive');
    }
    $this->load->model('CorpusFundM');
    $received_by = isset($_SESSION['user']['ITS_ID']) ? $_SESSION['user']['ITS_ID'] : null;
    // Prevent overpayment: check current due
    $current_due = $this->CorpusFundM->get_due_for_assignment($fund_id, $hof_id);
    if ($amount > $current_due + 0.0001) { // small epsilon
      $this->session->set_flashdata('corpus_payment_error', 'Amount exceeds due (Due: ₹' . number_format((float)$current_due, 2) . ').');
      redirect('anjuman/corpusfunds_receive');
    }
    // Build paid_at as datetime; if only date provided, set to 00:00:00
    $paid_at = null;
    if (!empty($payment_date)) {
      // Accept either date or datetime
      $ts = strtotime($payment_date);
      $paid_at = $ts ? date('Y-m-d H:i:s', $ts) : null;
    }
    $result = $this->CorpusFundM->record_payment($fund_id, $hof_id, $amount, $received_by, $notes, $paid_at, $payment_method);
    if (isset($result['success']) && $result['success']) {
      $this->session->set_flashdata('corpus_payment_message', 'Payment recorded.');
    } else {
      $err = isset($result['error']) ? $result['error'] : 'Failed to record payment';
      $this->session->set_flashdata('corpus_payment_error', $err);
    }
    redirect('anjuman/corpusfunds_receive');
  }

  public function corpusfunds_payment_history()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    $fund_id = (int)$this->input->get_post('fund_id');
    $hof_id = (int)$this->input->get_post('hof_id');
    if ($fund_id <= 0 || $hof_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid identifiers']));
      return;
    }
    $this->load->model('CorpusFundM');
    $rows = $this->CorpusFundM->get_payments_for_assignment($fund_id, $hof_id);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'payments' => $rows]));
  }

  public function corpusfunds_payment_receipt()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo 'Unauthorized';
      return;
    }
    $payment_id = (int)$this->input->get('id');
    if ($payment_id <= 0) {
      echo 'Invalid receipt id';
      return;
    }
    $this->load->model('CorpusFundM');
    $row = $this->CorpusFundM->get_payment_detail($payment_id);
    if (!$row) {
      echo 'Receipt not found';
      return;
    }
    // Simple HTML receipt
    $amt = number_format((float)($row['amount_paid'] ?? 0), 2);
    $dt = htmlspecialchars($row['paid_at'] ?? '');
    $method = htmlspecialchars($row['payment_method'] ?? '');
    $fund = htmlspecialchars($row['fund_title'] ?? '');
    $hof = htmlspecialchars($row['hof_name'] ?? '');
    $notes = htmlspecialchars($row['notes'] ?? '');
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Receipt</title><link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'></head><body class='p-3'>" .
      "<div class='container'><div class='card'><div class='card-header'>Corpus Fund Payment Receipt</div><div class='card-body'>" .
      "<p><strong>Member:</strong> {$hof}</p>" .
      "<p><strong>Fund:</strong> {$fund}</p>" .
      "<p><strong>Amount:</strong> ₹{$amt}</p>" .
      "<p><strong>Date:</strong> {$dt}</p>" .
      "<p><strong>Method:</strong> {$method}</p>" .
      ($notes ? "<p><strong>Notes:</strong> {$notes}</p>" : "") .
      "</div></div></div></body></html>";
  }

  public function corpusfunds_delete_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    $payment_id = (int)$this->input->post('id');
    if ($payment_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid id']));
      return;
    }
    $this->load->model('CorpusFundM');
    $res = $this->CorpusFundM->delete_payment($payment_id);
    $this->output->set_content_type('application/json')->set_output(json_encode($res));
  }

  public function corpusfunds_hof_funds()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    $hof_id = (int)$this->input->get('hof_id');
    if ($hof_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid hof_id']));
      return;
    }
    $this->load->model('CorpusFundM');
    $rows = $this->CorpusFundM->get_assignments_with_payments_by_hof($hof_id);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'funds' => $rows]));
  }

  /* ================= Ekram Funds (Receive) - Class level endpoints ================= */
  public function ekramfunds_receive()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $this->load->model('EkramFundM');
    $data['user_name'] = $_SESSION['user']['username'];
    $data['message'] = $this->session->flashdata('ekram_payment_message');
    $data['error'] = $this->session->flashdata('ekram_payment_error');
    // Read optional filters from GET
    $filters = [];
    $filters['its_id'] = ($this->input->get('its_id') !== null) ? trim((string)$this->input->get('its_id')) : null;
    $filters['sector'] = ($this->input->get('sector') !== null) ? trim((string)$this->input->get('sector')) : null;
    $filters['sub_sector'] = ($this->input->get('sub_sector') !== null) ? trim((string)$this->input->get('sub_sector')) : null;
    $filters['fund_id'] = $this->input->get('fund_id') ? (int)$this->input->get('fund_id') : null;

    $data['assignments'] = $this->EkramFundM->get_all_assignments_with_payments($filters);
    $data['filter_its'] = $filters['its_id'];
    $data['filter_sector'] = $filters['sector'];
    $data['filter_sub_sector'] = $filters['sub_sector'];
    $data['filter_fund'] = $filters['fund_id'];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/EkramFundsReceive', $data);
  }

  /**
   * JSON endpoint: return assignments with payment aggregates for Ekram funds
   */
  public function ekramfunds_receive_data()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    $this->load->model('EkramFundM');
    $filters = [];
    $filters['its_id'] = ($this->input->get('its_id') !== null) ? trim((string)$this->input->get('its_id')) : null;
    $filters['sector'] = ($this->input->get('sector') !== null) ? trim((string)$this->input->get('sector')) : null;
    $filters['sub_sector'] = ($this->input->get('sub_sector') !== null) ? trim((string)$this->input->get('sub_sector')) : null;
    $filters['fund_id'] = $this->input->get('fund_id') ? (int)$this->input->get('fund_id') : null;
    $filters['hijri_year'] = $this->input->get('hijri_year') ? (int)$this->input->get('hijri_year') : null;
    $rows = $this->EkramFundM->get_all_assignments_with_payments($filters);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'assignments' => $rows]));
  }

  /**
   * Return Ekram fund assignments for a specific HOF (Ekram-only, per-HOF)
   */
  public function ekramfunds_hof_funds()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    $hof_id = (int)$this->input->get('hof_id');
    if ($hof_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid hof_id']));
      return;
    }
    $this->load->model('EkramFundM');
    $rows = $this->EkramFundM->get_assignments_with_payments_by_hof($hof_id);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'funds' => $rows]));
  }

  public function ekramfunds_receive_payment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('anjuman/ekramfunds_receive');
    }
    $fund_id = (int)$this->input->post('fund_id');
    $hof_id = (int)$this->input->post('hof_id');
    $amount = (float)$this->input->post('amount');
    $notes = trim($this->input->post('notes'));
    $payment_date = trim($this->input->post('payment_date'));
    $payment_method = trim($this->input->post('payment_method'));
    if ($fund_id <= 0 || $hof_id <= 0 || $amount <= 0) {
      $this->session->set_flashdata('ekram_payment_error', 'Invalid data (positive amount required).');
      redirect('anjuman/ekramfunds_receive');
    }
    $this->load->model('EkramFundM');
    $received_by = isset($_SESSION['user']['ITS_ID']) ? $_SESSION['user']['ITS_ID'] : null;
    $current_due = $this->EkramFundM->get_due_for_assignment($fund_id, $hof_id);
    if ($amount > $current_due + 0.0001) {
      $this->session->set_flashdata('ekram_payment_error', 'Amount exceeds due (Due: ₹' . number_format((float)$current_due, 2) . ').');
      redirect('anjuman/ekramfunds_receive');
    }
    $paid_at = null;
    if (!empty($payment_date)) {
      $ts = strtotime($payment_date);
      $paid_at = $ts ? date('Y-m-d H:i:s', $ts) : null;
    }
    $result = $this->EkramFundM->record_payment($fund_id, $hof_id, $amount, $received_by, $notes, $paid_at, $payment_method);
    if (isset($result['success']) && $result['success']) {
      $this->session->set_flashdata('ekram_payment_message', 'Payment recorded.');
    } else {
      $err = isset($result['error']) ? $result['error'] : 'Failed to record payment';
      $this->session->set_flashdata('ekram_payment_error', $err);
    }
    redirect('anjuman/ekramfunds_receive');
  }

  public function ekramfunds_payment_history()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    $fund_id = (int)$this->input->get_post('fund_id');
    $hof_id = (int)$this->input->get_post('hof_id');
    if ($fund_id <= 0 || $hof_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid identifiers']));
      return;
    }
    $this->load->model('EkramFundM');
    $rows = $this->EkramFundM->get_payments_for_assignment($fund_id, $hof_id);
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'payments' => $rows]));
  }

  public function ekramfunds_payment_receipt()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      echo 'Unauthorized';
      return;
    }
    $payment_id = (int)$this->input->get('id');
    if ($payment_id <= 0) {
      echo 'Invalid receipt id';
      return;
    }
    $this->load->model('EkramFundM');
    $data = $this->EkramFundM->get_payment_detail($payment_id);
    if (empty($data)) { echo 'Not found'; return; }
    $this->load->view('Anjuman/EkramPaymentReceipt', $data);
  }

  /**
   * Member financials: show Sabeel, FMB, Corpus and General/Miqaat items for a member
   * Accepts GET: its_id OR hof_id
   */
  public function financials()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    // If an ITS or HOF identifier provided explicitly, show individual member financials
    // Note: do not auto-redirect when the user used the generic search `q` — keep that as a list filter
    $orig_reqIts = $this->input->get('its_id');
    $orig_reqHof = $this->input->get('hof_id');
    $reqIts = $orig_reqIts;
    $reqHof = $orig_reqHof;
    // Support a single-query input 'q' (ITS ID, HOF ID, or member name) but treat it as a filter
    $q = trim((string)$this->input->get('q'));
    if ($q !== '') {
      // numeric: try ITS_ID first, then HOF_ID (do not auto-open member view)
      if (preg_match('/^\d+$/', $q)) {
        $row = $this->db->get_where('user', ['ITS_ID' => $q])->row_array();
        if (!empty($row)) {
          $reqIts = $q;
        } else {
          $row2 = $this->db->get_where('user', ['HOF_ID' => (int)$q])->row_array();
          if (!empty($row2)) {
            $reqHof = (int)$q;
          }
        }

 
      } else {
        // name search: first matching user by name (used only to pre-select if desired)
        $like = "%{$q}%";
        $r = $this->db->query("SELECT ITS_ID, HOF_ID FROM user WHERE Full_Name LIKE ? OR Full_Name LIKE ? LIMIT 1", [$like, $like])->row_array();
        if (!empty($r)) {
          $reqIts = $r['ITS_ID'];
          $reqHof = isset($r['HOF_ID']) ? $r['HOF_ID'] : $reqHof;
        }
      }
    }
    // Only show member financials when the request explicitly contained `its_id` or `hof_id` parameters
    if (!empty($orig_reqIts) || !empty($orig_reqHof)) {
      $this->member_financials();
      return;
    }

    // Otherwise show the HOF listing with aggregate dues (additional layer)
    $this->load->model('CommonM');
    $this->load->model('AccountM');

    $users = $this->CommonM->get_all_users();

    // Search and pagination
    $q = trim((string)$this->input->get('q'));
    if ($q !== '') {
      $qL = strtolower($q);
      $users = array_values(array_filter($users, function ($u) use ($qL) {
        $name = isset($u['Full_Name']) ? strtolower($u['Full_Name']) : '';
        $its = isset($u['ITS_ID']) ? strtolower((string)$u['ITS_ID']) : '';
        return (strpos($name, $qL) !== false) || (strpos($its, $qL) !== false);
      }));
    }

    $total = count($users);
    $per_page = (int)$this->input->get('per_page');
    if ($per_page <= 0) $per_page = 50;
    $page = (int)$this->input->get('page');
    if ($page <= 0) $page = 1;
    $offset = ($page - 1) * $per_page;

    $paged_users = array_slice($users, $offset, $per_page);

    $hofs = [];
    $ids = array_column($paged_users, 'ITS_ID');
    // corpus fund model needed to compute corpus dues per HOF
    $this->load->model('CorpusFundM');
    if (!empty($ids)) {
      // Fetch mobile numbers in a single query for paged users
      $mobRows = $this->db->select('ITS_ID, Mobile')->from('user')->where_in('ITS_ID', $ids)->get()->result_array();
      $mobMap = [];
      foreach ($mobRows as $m) {
        $mobMap[$m['ITS_ID']] = trim((string)($m['Mobile'] ?? ''));
      }

      foreach ($paged_users as $u) {
        $its = isset($u['ITS_ID']) ? $u['ITS_ID'] : null;
        if (!$its) continue;
        // Aggregate dues across the entire family (HOF + members)
        $memberIds = [$its];
        $mrows = $this->db->select('ITS_ID')->from('user')->where('HOF_ID', $its)->get()->result_array();
        if (!empty($mrows)) {
          foreach ($mrows as $mr) {
            if (!empty($mr['ITS_ID'])) $memberIds[] = $mr['ITS_ID'];
          }
        }
        // Ensure we don't double-count the same ITS (some rows may include the HOF itself)
        $memberIds = array_values(array_unique($memberIds));

        $fmb_due = 0.0;
        $sabeel_due = 0.0;
        $miqaat_due = 0.0;
        $gc_due = 0.0;
        $corpus_due = 0.0;
        foreach ($memberIds as $mid) {
          // FMB takhmeen due
          $fmb = $this->AccountM->get_member_total_fmb_due($mid);
          if (is_array($fmb) && isset($fmb['total_due'])) $fmb_due += (float)$fmb['total_due'];
          // Sabeel due - exclude upcoming takhmeen years from the due shown in the list
          $sabeel = $this->AccountM->get_member_total_sabeel_due($mid);
          if (is_array($sabeel) && isset($sabeel['total_due'])) {
            $sabeel_total_due = (float)$sabeel['total_due'];
            // attempt to subtract any future takhmeen year's due using detailed per-year rows
            $deduct_future = 0.0;
            try {
              $details = $this->AccountM->viewSabeelTakhmeen($mid);
              // compute current takhmeen start (numeric) same as other places
              $cur_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
              $current_takhmeen_start = null;
              if (!empty($cur_hijri) && !empty($cur_hijri['hijri_date'])) {
                $parts = explode('-', $cur_hijri['hijri_date']);
                $cur_month = $parts[1] ?? null;
                $cur_year = isset($parts[2]) ? (int)$parts[2] : null;
                if ($cur_month !== null && $cur_year !== null) {
                  if ($cur_month >= '01' && $cur_month <= '08') {
                    $current_takhmeen_start = $cur_year - 1;
                  } else {
                    $current_takhmeen_start = $cur_year;
                  }
                }
              }
              $get_start_year = function ($y) {
                if (empty($y)) return null;
                $p = explode('-', (string)$y);
                return is_numeric($p[0]) ? (int)$p[0] : null;
              };
              if (!empty($details) && is_array($details) && isset($details['all_takhmeen']) && is_array($details['all_takhmeen'])) {
                foreach ($details['all_takhmeen'] as $trow) {
                  $tstart = $get_start_year($trow['year'] ?? null);
                  if ($current_takhmeen_start !== null && $tstart !== null && $tstart > $current_takhmeen_start) {
                    $deduct_future += (float)($trow['total_due'] ?? 0);
                  }
                }
              }
            } catch (Exception $e) {
              // if anything fails, don't deduct
              $deduct_future = 0.0;
            }
            $sabeel_due += max(0, $sabeel_total_due - $deduct_future);
          }
          // Miqaat invoices due for this member
          $miqaatInv = $this->AccountM->get_user_miqaat_invoices($mid);
          if (is_array($miqaatInv) && !empty($miqaatInv)) {
            foreach ($miqaatInv as $inv) {
              if (isset($inv['due_amount'])) $miqaat_due += (float)$inv['due_amount'];
            }
          }
          // General contribution (FMB GC) due for this member
          $gc = $this->AccountM->get_member_total_general_contrib_due($mid);
          $gc_due += (float)$gc;
        }
        // Corpus fund dues are assigned at HOF level; fetch assignments/payments for this HOF once
        $corpus_rows = $this->CorpusFundM->get_assignments_with_payments_by_hof($its);
        if (!empty($corpus_rows) && is_array($corpus_rows)) {
          foreach ($corpus_rows as $cr) {
            $corpus_due += (float)($cr['amount_due'] ?? 0.0);
          }
        }

        $all_due = $fmb_due + $sabeel_due + $miqaat_due + $gc_due + $corpus_due;
        $hofs[] = [
          'ITS_ID' => $its,
          'Full_Name' => isset($u['Full_Name']) ? $u['Full_Name'] : (isset($u['First_Name']) ? trim(($u['First_Name'] . ' ' . ($u['Surname'] ?? ''))) : $its),
          'Mobile' => isset($mobMap[$its]) ? $mobMap[$its] : '',
          'all_due' => round($all_due, 2)
        ];
      }
    }

    $pagination = [
      'total' => $total,
      'page' => $page,
      'per_page' => $per_page,
      'total_pages' => (int)ceil($total / $per_page),
      'q' => $q
    ];

    $data = [
      'user_name' => $_SESSION['user']['username'],
      'hofs' => $hofs,
      'pagination' => $pagination
    ];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/FinancialsList', $data);
  }

  /**
   * JSON endpoint returning aggregated financial summaries for a member/HOF
   * GET params: its_id or hof_id
   */
  public function member_financials_json()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }

    $this->load->model('AccountM');
    $this->load->model('CorpusFundM');

    $its = $this->input->get('its_id');
    $hof_id = $this->input->get('hof_id');

    if (empty($its) && empty($hof_id)) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Missing identifier']));
      return;
    }

    // Resolve hof if ITS provided
    if (!empty($its)) {
      $u = $this->db->get_where('user', ['ITS_ID' => $its])->row_array();
      if (!empty($u) && !empty($u['HOF_ID'])) $hof_id = $u['HOF_ID'];
    }

    // Build memberIds to aggregate: if hof available include all members + hof, else just the ITS
    $memberIds = [];
    if (!empty($hof_id)) {
      $memberRows = $this->db->query("SELECT ITS_ID FROM user WHERE HOF_ID = ?", [$hof_id])->result_array();
      $memberIds = array_column($memberRows, 'ITS_ID');
      if (!in_array((string)$hof_id, $memberIds, true)) $memberIds[] = (string)$hof_id;
    } else if (!empty($its)) {
      $memberIds[] = (string)$its;
    }

    $fmb_due = 0.0;
    $sabeel_due = 0.0;
    $gc_due = 0.0;
    $miqaat_due = 0.0;
    $miqaat_invoices = [];

    foreach ($memberIds as $mid) {
      $fmb = $this->AccountM->get_member_total_fmb_due($mid);
      if (is_array($fmb) && isset($fmb['total_due'])) $fmb_due += (float)$fmb['total_due'];

      $sabeel = $this->AccountM->get_member_total_sabeel_due($mid);
      if (is_array($sabeel) && isset($sabeel['total_due'])) $sabeel_due += (float)$sabeel['total_due'];

      $gc = $this->AccountM->get_member_total_general_contrib_due($mid);
      $gc_due += (float)$gc;

      $miq = $this->AccountM->get_user_miqaat_invoices($mid);
      if (is_array($miq) && !empty($miq)) {
        // fetch member display name once
        $memberRow = $this->db->get_where('user', ['ITS_ID' => $mid])->row_array();
        $memberName = '';
        if (!empty($memberRow)) $memberName = isset($memberRow['Full_Name']) ? $memberRow['Full_Name'] : ($memberRow['Fullname'] ?? '');
        foreach ($miq as $inv) {
          if (isset($inv['due_amount']) && (float)$inv['due_amount'] > 0) {
            $miqaat_due += (float)$inv['due_amount'];
            $miqaat_invoices[] = [
              'assigned_to' => $memberName,
              'invoice' => $inv['miqaat_name'] ?? ('#' . $inv['id']),
              'amount' => (float)($inv['amount'] ?? 0),
              'paid' => (float)($inv['paid_amount'] ?? 0),
              'due' => (float)($inv['due_amount'] ?? 0)
            ];
          }
        }
      }
    }

    // Corpus fund due (only meaningful when hof_id present)
    $corpus_due = 0.0;
    $corpus_rows = [];
    if (!empty($hof_id)) {
      $corpus = $this->CorpusFundM->get_assignments_with_payments_by_hof($hof_id);
      if (!empty($corpus)) {
        foreach ($corpus as $c) {
          $assigned = (float)($c['amount_assigned'] ?? 0);
          $paid = (float)($c['amount_paid'] ?? 0);
          $due = max(0, $assigned - $paid);
          $corpus_due += $due;
          $corpus_rows[] = [
            'title' => $c['title'] ?? '',
            'assigned' => $assigned,
            'paid' => $paid,
            'due' => $due
          ];
        }
      }
    }

    $total = $fmb_due + $sabeel_due + $gc_due + $miqaat_due + $corpus_due;

    $payload = [
      'success' => true,
      'fmb_due' => round($fmb_due, 2),
      'sabeel_due' => round($sabeel_due, 2),
      'gc_due' => round($gc_due, 2),
      'miqaat_due' => round($miqaat_due, 2),
      'corpus_due' => round($corpus_due, 2),
      'total_due' => round($total, 2),
      'miqaat_invoices' => $miqaat_invoices,
      'corpus_rows' => $corpus_rows
    ];

    $this->output->set_content_type('application/json')->set_output(json_encode($payload));
  }

  public function member_financials()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $this->load->model('AnjumanM');
    $this->load->model('CorpusFundM');
    $this->load->model('AccountM');
    $this->load->model('AdminM');

    $its = null;
    $hof_id = null;
    $reqIts = $this->input->get('its_id');
    $reqHof = $this->input->get('hof_id');
    // Support a single-query input 'q' (ITS ID, HOF ID, or member name)
    $q = trim((string)$this->input->get('q'));
    if ($q !== '') {
      if (preg_match('/^\d+$/', $q)) {
        $row = $this->db->get_where('user', ['ITS_ID' => $q])->row_array();
        if (!empty($row)) {
          $reqIts = $q;
        } else {
          $row2 = $this->db->get_where('user', ['HOF_ID' => (int)$q])->row_array();
          if (!empty($row2)) $reqHof = (int)$q;
        }
      } else {
        $like = "%{$q}%";
        $r = $this->db->query("SELECT ITS_ID, HOF_ID FROM user WHERE Full_Name LIKE ? OR Full_Name LIKE ? LIMIT 1", [$like, $like])->row_array();
        if (!empty($r)) {
          $reqIts = $r['ITS_ID'];
          if (!empty($r['HOF_ID'])) $reqHof = $r['HOF_ID'];
        }
      }
    }

    // If ITS provided, always try to resolve the HOF_ID from the user table first
    if (!empty($reqIts)) {
      $its_in = trim((string)$reqIts);
      // lookup user row by ITS_ID
      $userRow = $this->db->get_where('user', ['ITS_ID' => $its_in])->row_array();
      if (!empty($userRow)) {
        $its = isset($userRow['ITS_ID']) ? $userRow['ITS_ID'] : $its_in;
        $hof_id = isset($userRow['HOF_ID']) ? (int)$userRow['HOF_ID'] : null;
      } else {
        // no user row — keep raw ITS for later probing (payments/takhmeen rows)
        $its = $its_in;
      }
    }

    // If HOF provided explicitly and not already set from ITS, use it
    if (!empty($reqHof) && empty($hof_id)) {
      $hof_id = (int)$reqHof;
      // if ITS not resolved, try to pick an ITS under this HOF (for member-scoped queries)
      if (empty($its)) {
        $row = $this->db->get_where('user', ['HOF_ID' => $hof_id])->row_array();
        if (!empty($row)) $its = isset($row['ITS_ID']) ? $row['ITS_ID'] : null;
      }
    }

    // Fallback: if an ITS was provided but no user row found, accept the raw ITS
    // when there's evidence of finance rows for that ITS (payments or takhmeen entries).
    if (empty($its) && !empty($reqIts)) {
      $probeIts = trim((string)$reqIts);
      // check presence in finance-related tables
      $found = false;
      $tables = [
        'fmb_takhmeen' => 'user_id',
        'fmb_takhmeen_payments' => 'user_id',
        'sabeel_takhmeen' => 'user_id',
        'fmb_general_contribution' => 'user_id',
        'miqaat_invoice' => 'user_id'
      ];
      foreach ($tables as $tbl => $col) {
        $r = $this->db->query("SELECT 1 FROM {$tbl} WHERE {$col} = ? LIMIT 1", [$probeIts])->row_array();
        if (!empty($r)) {
          $found = true;
          break;
        }
      }
      if ($found) {
        $its = $probeIts;
      }
    }

    // Resolve HOF name (if we have a hof_id)
    $hof_name = null;
    if (!empty($hof_id)) {
      $hofRow = $this->db->get_where('user', ['ITS_ID' => (string)$hof_id])->row_array();
      if (!empty($hofRow)) {
        $hof_name = isset($hofRow['Full_Name']) ? $hofRow['Full_Name'] : (isset($hofRow['Fullname']) ? $hofRow['Fullname'] : null);
      }
    }

    if (empty($its) && empty($hof_id)) {
      // No identifier provided — render the page with a search form instead of redirecting to dashboard.
      $data = [
        'user_its' => null,
        'user_hof' => null,
        'hof_name' => null,
        'sabeel' => [],
        'fmb_takhheen' => [],
        'corpus' => [],
        'general_contribs' => [],
        'miqaat_invoices' => [],
        'user_name' => $_SESSION['user']['username'],
        'need_search' => true
      ];
      $this->load->view('Anjuman/Header', $data);
      $this->load->view('Anjuman/MemberFinancials', $data);
      return;
    }

    // Sabeel takhmeen details (use AnjumanM helper)
    $sabeel = [];
    if (!empty($hof_id)) {
      $sabeelRows = $this->AnjumanM->get_user_sabeel_takhmeen_details(['its_id' => $hof_id, 'require_current_year' => false, 'allow_latest_when_missing' => true]);
      // function returns keyed by ITS; take single entry
      if (!empty($sabeelRows) && isset($sabeelRows[$hof_id])) {
        $sabeel = $sabeelRows[$hof_id];
      } else if (!empty($sabeelRows)) {
        // if array numeric, take first
        $sabeel = reset($sabeelRows);
      }
    }

    // FMB takhmeen: payments are stored per-user in `fmb_takhmeen_payments` (no takhmeen_id)
    // Allocate total paid amount FIFO to oldest takhmeen entries so each takhmeen shows its actual paid portion.
    $fmbTakhveen = [];
    if (!empty($hof_id)) {
      // fetch takhmeen rows for user ordered oldest first for allocation
      $rows = $this->db->query("SELECT id, user_id, year, total_amount, remark FROM fmb_takhmeen WHERE user_id = ? ORDER BY year ASC, id ASC", [$hof_id])->result_array();
      // total paid by user (all payments against fmb takhmeen)
      $paidRow = $this->db->query("SELECT COALESCE(SUM(amount),0) AS total_paid FROM fmb_takhmeen_payments WHERE user_id = ?", [$hof_id])->row_array();
      $total_paid = (float)($paidRow['total_paid'] ?? 0.0);

      // Fallback: if no takhmeen rows found for this ITS, it's possible the input was a HOF id
      // (or the family's takhmeen rows are under other ITS). Try fetching takhmeen rows
      // for all members with this HOF and aggregate payments across them so amounts show.
      if (empty($rows) && is_numeric($hof_id)) {
        $hof = (int)$hof_id;
        $memberRows = $this->db->query("SELECT ITS_ID FROM user WHERE HOF_ID = ?", [$hof])->result_array();
        $memberIts = array_column($memberRows, 'ITS_ID');
        if (!empty($memberIts)) {
          // fetch takhmeen rows for all members in this HOF
          $placeholders = implode(',', array_fill(0, count($memberIts), '?'));
          $q = "SELECT id, user_id, year, total_amount, remark FROM fmb_takhmeen WHERE user_id IN ($placeholders) ORDER BY year ASC, id ASC";
          $rows = $this->db->query($q, $memberIts)->result_array();
          // sum payments across these members
          $q2 = "SELECT COALESCE(SUM(amount),0) AS total_paid FROM fmb_takhmeen_payments WHERE user_id IN ($placeholders)";
          $paidRow = $this->db->query($q2, $memberIts)->row_array();
          $total_paid = (float)($paidRow['total_paid'] ?? 0.0);
        }
      }

      // allocate FIFO to rows
      $remaining = $total_paid;
      $allocated = [];
      foreach ($rows as $r) {
        $amt = (float)($r['total_amount'] ?? 0.0);
        $alloc = min($amt, max(0.0, $remaining));
        $remaining -= $alloc;
        if ($remaining < 0) $remaining = 0;
        $r['amount_paid'] = round($alloc, 2);
        $r['due'] = round(max(0.0, $amt - $alloc), 2);
        $allocated[] = $r;
      }

      // For display, show most recent first (consistent with previous ordering)
      $fmbTakhveen = array_reverse($allocated);
    }

    // Corpus fund assignments/payments by HOF id
    $corpus = [];
    if (!empty($hof_id)) {
      $corpus = $this->CorpusFundM->get_assignments_with_payments_by_hof($hof_id);
    }

    // General contributions (FMB GC) for user
    $generalContribs = [];
    if (!empty($its)) {
      $sql = "SELECT gc.*, COALESCE(p.total_received,0) AS amount_paid, (gc.amount - COALESCE(p.total_received,0)) AS amount_due
              FROM fmb_general_contribution gc
              LEFT JOIN (SELECT fmbgc_id, SUM(amount) AS total_received FROM fmb_general_contribution_payments GROUP BY fmbgc_id) p ON p.fmbgc_id = gc.id
              WHERE gc.user_id = ?
              ORDER BY gc.created_at DESC";
      $generalContribs = $this->db->query($sql, [$its])->result_array();
    }

    // Miqaat invoices
    $miqaatInvoices = [];
    if (!empty($its) || !empty($hof_id)) {
      // If we have a HOF id, include invoices for all family members (HOF + members)
      if (!empty($hof_id)) {
        $hof = (int)$hof_id;
        $memberRows = $this->db->query("SELECT ITS_ID FROM user WHERE HOF_ID = ?", [$hof])->result_array();
        $memberIts = array_column($memberRows, 'ITS_ID');
        // include the hof itself
        if (!in_array($hof, $memberIts, true)) $memberIts[] = (string)$hof;
        if (!empty($memberIts)) {
          $placeholders = implode(',', array_fill(0, count($memberIts), '?'));
          $sql = "SELECT 
                    i.id,
                    i.miqaat_id,
                    m.name AS miqaat_name,
                    m.type AS miqaat_type,
                    i.date AS invoice_date,
                    i.amount,
                    i.description,
                    COALESCE(SUM(p.amount),0) AS paid_amount,
                    (i.amount - COALESCE(SUM(p.amount),0)) AS due_amount
                  FROM miqaat_invoice i
                  LEFT JOIN miqaat_payment p ON p.miqaat_invoice_id = i.id
                  LEFT JOIN miqaat m ON m.id = i.miqaat_id
                  WHERE i.user_id IN ($placeholders)
                  GROUP BY i.id, i.miqaat_id, m.name, m.type, i.date, i.amount, i.description
                  ORDER BY i.date DESC, i.id DESC";
          $miqaatInvoices = $this->db->query($sql, $memberIts)->result_array();
        }
      } else {
        // single ITS: use existing helper
        $miqaatInvoices = $this->AccountM->get_user_miqaat_invoices($its);
      }
    }

    $data = [
      'user_its' => $its,
      'user_hof' => $hof_id,
      'hof_name' => $hof_name,
      'sabeel' => $sabeel,
      'fmb_takhmeen' => $fmbTakhveen,
      'corpus' => $corpus,
      'general_contribs' => $generalContribs,
      'miqaat_invoices' => $miqaatInvoices,
      'user_name' => $_SESSION['user']['username']
    ];

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/MemberFinancials', $data);
  }

  // Updated by Patel Infotech Services
}
