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

  /**
   * Expense listing for Amilsaheb (role 2), mirroring Jamaat module.
   */
  public function expense()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->model('ExpenseM');
    $this->load->model('ExpenseAreaM');

    $filters = [
      'aos'        => trim((string)$this->input->get('aos')),
      'payment_mode' => trim((string)$this->input->get('payment_mode')),
      'hijri_year' => trim((string)$this->input->get('hijri_year')),
      'date_from'  => trim((string)$this->input->get('date_from')),
      'date_to'    => trim((string)$this->input->get('date_to')),
    ];

    foreach ($filters as $k => $v) {
      if ($v === '') {
        $filters[$k] = null;
      }
    }

    $current_hijri_year = null;
    if (empty($filters['hijri_year'])) {
      $current_hijri_year = $this->HijriCalendar->get_financial_hijri_year_by_greg_date(date('Y-m-d'));
      if ($current_hijri_year) {
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

    $data['aos_options'] = $this->ExpenseAreaM->get_all_active();
    $data['hijri_year_options'] = $this->ExpenseM->get_distinct_hijri_years();

    if ($current_hijri_year && !in_array($current_hijri_year, $data['hijri_year_options'], true)) {
      array_unshift($data['hijri_year_options'], $current_hijri_year);
      $data['hijri_year_options'] = array_values(array_unique($data['hijri_year_options']));
      rsort($data['hijri_year_options']);
    }

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Expense', $data);
  }

  /**
   * Add expense (Amilsaheb), same behavior as Jamaat.
   */
  public function expense_add()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $this->load->model('ExpenseM');
    $this->load->model('ExpenseAreaM');

    $current_hijri_year = $this->HijriCalendar->get_financial_hijri_year_by_greg_date(date('Y-m-d'));

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
        'payment_mode' => $this->input->post('payment_mode'),
        'hijri_year'   => $this->input->post('hijri_year'),
        'notes'        => $this->input->post('notes'),
      ];

      if (!empty($payload['expense_date']) && !empty($payload['amount']) && !empty($payload['payment_mode']) && !empty($payload['hijri_year'])) {
        $id = $this->ExpenseM->create($payload);
        if ($id) {
          $this->session->set_flashdata('success', 'Expense added successfully.');
          redirect('amilsaheb/expense');
          return;
        }
      }

      $this->session->set_flashdata('error', 'Failed to add expense. Please check the form.');
    }

    $data = [];
    $data['user_name'] = $_SESSION['user']['username'];
    $data['aos_options'] = $this->ExpenseAreaM->get_all_active();
    $data['hijri_year_options'] = $this->ExpenseM->get_distinct_hijri_years();
    if ($current_hijri_year && !in_array($current_hijri_year, $data['hijri_year_options'], true)) {
      array_unshift($data['hijri_year_options'], $current_hijri_year);
      $data['hijri_year_options'] = array_values(array_unique($data['hijri_year_options']));
      rsort($data['hijri_year_options']);
    }
    $data['current_hijri_year_for_expense'] = $current_hijri_year;

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/ExpenseForm', $data);
  }

  /**
   * Edit expense (Amilsaheb).
   */
  public function expense_edit($id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $id = (int)$id;
    if ($id <= 0) {
      redirect('amilsaheb/expense');
    }

    $this->load->model('ExpenseM');
    $this->load->model('ExpenseAreaM');

    $expense = $this->ExpenseM->get($id);
    if (!$expense) {
      $this->session->set_flashdata('error', 'Expense not found.');
      redirect('amilsaheb/expense');
      return;
    }

    $current_hijri_year = $this->HijriCalendar->get_financial_hijri_year_by_greg_date(date('Y-m-d'));

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
        'payment_mode' => $this->input->post('payment_mode'),
        'hijri_year'   => $this->input->post('hijri_year'),
        'notes'        => $this->input->post('notes'),
      ];

      if (!empty($payload['expense_date']) && !empty($payload['amount']) && !empty($payload['payment_mode']) && !empty($payload['hijri_year'])) {
        $ok = $this->ExpenseM->update($id, $payload);
        if ($ok) {
          $this->session->set_flashdata('success', 'Expense updated successfully.');
          redirect('amilsaheb/expense');
          return;
        }
      }

      $this->session->set_flashdata('error', 'Failed to update expense. Please check the form.');
      $expense = array_merge($expense, $payload);
    }

    $data = [];
    $data['user_name'] = $_SESSION['user']['username'];
    $data['expense'] = $expense;
    $data['aos_options'] = $this->ExpenseAreaM->get_all_active();
    $data['hijri_year_options'] = $this->ExpenseM->get_distinct_hijri_years();
    if ($current_hijri_year && !in_array($current_hijri_year, $data['hijri_year_options'], true)) {
      array_unshift($data['hijri_year_options'], $current_hijri_year);
      $data['hijri_year_options'] = array_values(array_unique($data['hijri_year_options']));
      rsort($data['hijri_year_options']);
    }
    $data['current_hijri_year_for_expense'] = $current_hijri_year;

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/ExpenseForm', $data);
  }

  /**
   * Delete expense (Amilsaheb).
   */
  public function expense_delete($id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $id = (int)$id;
    if ($id <= 0) {
      redirect('amilsaheb/expense');
    }

    $this->load->model('ExpenseM');
    $ok = $this->ExpenseM->delete($id);
    if ($ok) {
      $this->session->set_flashdata('success', 'Expense deleted successfully.');
    } else {
      $this->session->set_flashdata('error', 'Failed to delete expense.');
    }
    redirect('amilsaheb/expense');
  }
  public function index()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
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

    $users = []; // Not used on main dashboard, search is AJAX-based

    $db_debug_original = $this->db->db_debug;
    $this->db->db_debug = FALSE;

    // Resident-only sector and overview stats for cards
    $sectorsData = [];
    $subSectorsData = [];
    $residentOverview = [];
    $stats = [
      'HOF' => 0, 'FM' => 0, 'Mardo' => 0, 'Bairo' => 0,
      'Age_0_4' => 0, 'Age_5_15' => 0, 'Age_16_25' => 0, 'Age_26_65' => 0, 'Buzurgo' => 0,
      'LeaveStatus' => [], 'Sectors' => [], 'SubSectors' => [],
      'deeni_eligible' => 0, 'deeni_taking' => 0, 'madresa_deprived' => 0, 'singles_21_40' => 0,
      'status_counts' => [], 'active_inactive' => []
    ];
    try {
      $sectorsData = $this->AmilsahebM->get_resident_sector_stats();
      $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats();
      $residentOverview = $this->AmilsahebM->get_resident_overview_counts(true);
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
        'LeaveStatus' => [],
        'Sectors' => $sectorsData ?: [],
        'SubSectors' => $subSectorsData ?: [],
        'deeni_eligible' => $this->AmilsahebM->get_deeni_eligible_count(),
        'deeni_taking' => $this->AmilsahebM->get_deeni_taking_count(),
        'madresa_deprived' => $this->AmilsahebM->get_madresa_deprived_count(),
        'singles_21_40' => $this->AmilsahebM->get_singles_21_40_count(),
        'status_counts' => $this->AmilsahebM->get_status_counts(),
        'active_inactive' => $this->AmilsahebM->get_active_inactive_counts(),
      ];
    } catch (Throwable $e) {
      log_message('error', 'Dashboard resident stats error: ' . $e->getMessage());
    }

    // Determine if frontend requested a specific hijri month/year
    $sel_hijri_year = $this->input->get('hijri_year') ? trim($this->input->get('hijri_year')) : null;
    $sel_hijri_month = $this->input->get('hijri_month') ? trim($this->input->get('hijri_month')) : null;

    // Dashboard financial & monthly data
    $dashboard_data = [];
    try {
      $dashboard_data = $this->get_dashboard_summary_data($sel_hijri_month, $sel_hijri_year);
    } catch (Throwable $e) {
      log_message('error', 'Dashboard summary data error: ' . $e->getMessage());
    }

    // Pass data to view
    $data = [
      'user_name' => $data['user_name'],
      'users' => $users,
      'stats' => $stats,
      'current_sector' => '',
      'current_sub_sector' => '',
      'dashboard_data' => $dashboard_data
    ];

    // Expense dashboard: status breakdown
    $expense_dashboard = [
      'sources' => ['active' => 0, 'inactive' => 0],
      'areas_available' => false,
      'areas' => ['active' => 0, 'inactive' => 0],
    ];
    try {
      if (method_exists($this->db, 'table_exists') && $this->db->table_exists('expense_sources')) {
        $rows = $this->db->select('status, COUNT(*) AS cnt')->from('expense_sources')->group_by('status')->get()->result_array();
        foreach ($rows as $r) {
          $st = $r['status'] ?? '';
          $cnt = (int)($r['cnt'] ?? 0);
          $isActive = is_numeric($st) ? (((int)$st) === 1) : (strtolower(trim((string)$st)) === 'active');
          if ($isActive) $expense_dashboard['sources']['active'] += $cnt;
          else $expense_dashboard['sources']['inactive'] += $cnt;
        }
      }

      if (method_exists($this->db, 'table_exists') && $this->db->table_exists('expense_areas')) {
        $expense_dashboard['areas_available'] = true;
        $rows = $this->db->select('status, COUNT(*) AS cnt')->from('expense_areas')->group_by('status')->get()->result_array();
        foreach ($rows as $r) {
          $st = $r['status'] ?? '';
          $cnt = (int)($r['cnt'] ?? 0);
          $isActive = is_numeric($st) ? (((int)$st) === 1) : (strtolower(trim((string)$st)) === 'active');
          if ($isActive) $expense_dashboard['areas']['active'] += $cnt;
          else $expense_dashboard['areas']['inactive'] += $cnt;
        }
      }
    } catch (Throwable $e) {
      log_message('error', 'Dashboard expense status error: ' . $e->getMessage());
    }
    $data['expense_dashboard'] = $expense_dashboard;

    // Marital status distribution (excluding members under 21)
    $marital_status_counts = [];
    try {
      $marital_status_counts = $this->AmilsahebM->get_marital_status_distribution();
    } catch (Throwable $e) {
      log_message('error', 'Dashboard marital counts error: ' . $e->getMessage());
    }
    $data['marital_status_counts'] = $marital_status_counts;

    $year_daytype_stats = [];
    try {
      $year_daytype_stats = $this->CommonM->get_year_calendar_daytypes();
    } catch (Throwable $e) {
      log_message('error', 'Dashboard year daytype error: ' . $e->getMessage());
    }
    $data['year_daytype_stats'] = $year_daytype_stats;

    // Corpus funds overview
    $corpus_funds = [];
    try {
      $this->load->model('CorpusFundM');
      $funds = $this->CorpusFundM->get_funds();
      foreach ($funds as $f) {
        $fid = (int)($f['id'] ?? 0);
        $assignedTotal = 0.0;
        $paidTotal = 0.0;
        if ($fid > 0) {
          $assignments = $this->CorpusFundM->get_assignments($fid);
          foreach ($assignments as $a) { $assignedTotal += (float)($a['amount_assigned'] ?? 0); }
          $rowPaid = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')->from('corpus_fund_payment')->where('fund_id', $fid)->get()->row_array();
          $paidTotal = isset($rowPaid['total_paid']) ? (float)$rowPaid['total_paid'] : 0.0;
        }
        $f['assigned_total'] = $assignedTotal;
        $f['paid_total'] = $paidTotal;
        $f['outstanding'] = max(0, $assignedTotal - $paidTotal);
        $f['assignments'] = isset($assignments) ? $assignments : [];
        $corpus_funds[] = $f;
      }
    } catch (Throwable $e) {
      log_message('error', 'Dashboard corpus funds error: ' . $e->getMessage());
    }
    $data['corpus_funds'] = $corpus_funds;

    // Ekram funds overview
    $ekram_funds = [];
    try {
      $this->load->model('EkramFundM');
      $efunds = $this->EkramFundM->get_funds();
      foreach ($efunds as $ef) {
        $efid = (int)($ef['id'] ?? 0);
        $assignedTotal = 0.0;
        $paidTotal = 0.0;
        if ($efid > 0) {
          $assignments = $this->EkramFundM->get_assignments($efid);
          foreach ($assignments as $a) { $assignedTotal += (float)($a['amount_assigned'] ?? 0); }
          $rowPaid = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')->from('ekram_fund_payment')->where('fund_id', $efid)->get()->row_array();
          $paidTotal = isset($rowPaid['total_paid']) ? (float)$rowPaid['total_paid'] : 0.0;
        }
        $ef['assigned_total'] = $assignedTotal;
        $ef['paid_total'] = $paidTotal;
        $ef['outstanding'] = max(0, $assignedTotal - $paidTotal);
        $ef['assignments'] = isset($assignments) ? $assignments : [];
        $ekram_funds[] = $ef;
      }
    } catch (Throwable $e) {
      log_message('error', 'Dashboard ekram funds error: ' . $e->getMessage());
    }
    $data['ekram_funds'] = $ekram_funds;

    // Recent expenses
    $dashboard_expenses = [];
    $dashboard_expense_total = 0.0;
    $dashboard_expense_hijri_year = null;
    try {
      $this->load->model('ExpenseM');
      $expense_filters = [];
      $today_parts_for_expense = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      if ($today_parts_for_expense && isset($today_parts_for_expense['hijri_year'])) {
        $expense_filters['hijri_year'] = (int)$today_parts_for_expense['hijri_year'];
      }
      $list_filters = $expense_filters; $list_filters['limit'] = 5;
      $dashboard_expenses = $this->ExpenseM->get_list($list_filters);
      if (!empty($expense_filters['hijri_year'])) {
        $all_year_expenses = $this->ExpenseM->get_list(['hijri_year' => $expense_filters['hijri_year']]);
        foreach ($all_year_expenses as $erow) { $dashboard_expense_total += (float)($erow['amount'] ?? 0); }
      }
      $dashboard_expense_hijri_year = $expense_filters['hijri_year'] ?? null;
    } catch (Throwable $e) {
      log_message('error', 'Dashboard recent expenses error: ' . $e->getMessage());
    }
    $data['dashboard_expenses'] = $dashboard_expenses;
    $data['dashboard_expense_total'] = $dashboard_expense_total;
    $data['dashboard_expense_hijri_year'] = $dashboard_expense_hijri_year;

    // Qardan Hasana totals
    $qh_moh = 0.0; $qh_tah = 0.0; $qh_hus = 0.0;
    try {
      $this->load->model('QardanHasanaM');
      $qh_moh = (float)$this->QardanHasanaM->get_scheme_total_amount('mohammedi');
      $qh_tah = (float)$this->QardanHasanaM->get_scheme_total_amount('taher');
      $qh_hus = (float)$this->QardanHasanaM->get_scheme_total_amount('husain');
    } catch (Throwable $e) {
      log_message('error', 'Dashboard qardan hasana error: ' . $e->getMessage());
    }
    $data['qh_all_schemes_totals'] = [
      'mohammedi' => $qh_moh, 'taher' => $qh_tah, 'husain' => $qh_hus, 'total' => $qh_moh + $qh_tah + $qh_hus,
    ];

    // Laagat & Rent dashboard total
    $dashboard_laagat_total = 0.0;
    $dashboard_laagat_paid = 0.0;
    $dashboard_laagat_due = 0.0;
    $dashboard_rent_total = 0.0;
    $dashboard_rent_paid = 0.0;
    $dashboard_rent_due = 0.0;
    $lr_year_query = null;
    try {
      $this->load->model('LaagatRentM');
      $max_lr_row = $this->db->query("SELECT MAX(hijri_year) AS y FROM laagat_rent")->row_array();
      $max_lr_year = $max_lr_row && isset($max_lr_row['y']) ? $max_lr_row['y'] : null;
      $lr_year = $sel_hijri_year ?: ($max_lr_year ?: ($data['year_daytype_stats']['hijri_year'] ?? 1446));
      $lr_year_query = (is_numeric($lr_year) && strlen((string)$lr_year) === 4) 
          ? (int)$lr_year . '-' . substr((string)((int)$lr_year + 1), -2) 
          : $lr_year;
      $lr_invoices = $this->LaagatRentM->get_invoices(['year' => $lr_year_query]);
      foreach ($lr_invoices as $lrinv) {
          $ma = (float)($lrinv['master_amount'] ?? 0);
          $pa = (float)($lrinv['paid_amount'] ?? 0);
          $ct = strtolower(trim($lrinv['charge_type'] ?? ''));
          if ($ct === 'rent') {
              $dashboard_rent_total += $ma;
              $dashboard_rent_paid += $pa;
              $dashboard_rent_due += max(0.0, $ma - $pa);
          } else {
              // default to laagat if empty or explicitly 'laagat'
              $dashboard_laagat_total += $ma;
              $dashboard_laagat_paid += $pa;
              $dashboard_laagat_due += max(0.0, $ma - $pa);
          }
      }
    } catch (Throwable $e) {
      log_message('error', 'Dashboard laagat rent error: ' . $e->getMessage());
    }

    $data['dashboard_laagat_rent_hijri_year'] = $lr_year_query;
    $data['laagat_summary'] = [
      'total' => $dashboard_laagat_total,
      'received' => $dashboard_laagat_paid,
      'due' => $dashboard_laagat_due
    ];
    $data['rent_summary'] = [
      'total' => $dashboard_rent_total,
      'received' => $dashboard_rent_paid,
      'due' => $dashboard_rent_due
    ];

    $this->db->db_debug = $db_debug_original;

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/Home', $data);
  }

  /**
   * Ekram funds details page for Amilsaheb role (simple overview)
   */
  public function ekramfunds_details()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $this->load->model('EkramFundM');
    $data = [];
    $data['user_name'] = $_SESSION['user']['username'];
    $funds = $this->EkramFundM->get_funds();
    $ekram_details = [];
    foreach ($funds as $f) {
      $fid = (int)($f['id'] ?? 0);
      if ($fid <= 0) continue;
      $assignments = $this->EkramFundM->get_assignments($fid);
      $rows = [];
      $assignedTotal = 0.0;
      $paidTotal = 0.0;
      $outstandingTotal = 0.0;
      foreach ($assignments as $a) {
        $hof = (int)($a['hof_id'] ?? $a['HOF_ID'] ?? 0);
        $ud = $hof > 0 ? $this->db->query("SELECT Full_Name, Sector, Sub_Sector FROM user WHERE HOF_ID = ? LIMIT 1", [$hof])->row_array() : null;
        $name = ($ud['Full_Name'] ?? '') ?: ($a['Full_Name'] ?? ($a['member_name'] ?? ''));
        $sector = ($ud['Sector'] ?? '') ?: ($a['Sector'] ?? '');
        $subsector = ($ud['Sub_Sector'] ?? '') ?: ($a['Sub_Sector'] ?? '');
        $assigned = (float)($a['amount_assigned'] ?? ($a['amount'] ?? 0));
        $paid = 0.0;
        if (method_exists($this->EkramFundM, 'get_payments_for_assignment')) {
          $plist = $this->EkramFundM->get_payments_for_assignment($fid, $hof);
          foreach ($plist as $p) { $paid += (float)($p['amount_paid'] ?? 0); }
        } else {
          $rp = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')->from('ekram_fund_payment')->where('fund_id', $fid)->where('hof_id', $hof)->get()->row_array();
          $paid += isset($rp['total_paid']) ? (float)$rp['total_paid'] : 0.0;
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
      $ekram_details[] = [
        'fund' => $f,
        'rows' => $rows,
        'assigned_total' => $assignedTotal,
        'paid_total' => $paidTotal,
        'outstanding_total' => $outstandingTotal
      ];
    }
    $data['ekram_details'] = $ekram_details;
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/EkramFundsDetails', $data);
  }

  private function get_dashboard_summary_data($sel_hijri_month = null, $sel_hijri_year = null)
  {
    $sabeel_summary = $this->get_sabeel_summary();
    $thaali_summary = $this->get_thaali_summary();
    $fmb_summary = $this->get_fmb_contribution_summary();
    $miqaat_summary = $this->get_miqaat_payment_summary();
    $raza_summary = $this->get_raza_summary();
    $miqaat_finance = $this->get_miqaat_finance_summary();
    $fmb_miqaats = $this->get_fmb_miqaats_summary();
    $fmb_miqaats_items = $this->get_fmb_miqaats_items(5);
    $upcoming_miqaats = $this->get_upcoming_miqaats(5);
    if (!empty($upcoming_miqaats)) {
      foreach ($upcoming_miqaats as &$um) {
        $um_date = $um['date'] ?? null; $hparts = $um_date ? $this->HijriCalendar->get_hijri_parts_by_greg_date($um_date) : null;
        $um['hijri_label'] = ($hparts && isset($hparts['hijri_day'])) ? trim(($hparts['hijri_day'] ?? '') . ' ' . ($hparts['hijri_month_name'] ?? $hparts['hijri_month'] ?? '') . ' ' . ($hparts['hijri_year'] ?? '')) : '';
        $um['hijri_parts'] = $hparts;
      }
      unset($um);
    }
    $top_dues = ['sabeel' => $this->get_top_dues_sabeel(5), 'thaali' => $this->get_top_dues_thaali(5)];
    $grade_breakdown = $this->get_grade_breakdown();
    $grade_breakdown_res = $this->get_grade_breakdown_residential();
    $mohallah_breakdown = $this->get_mohallah_breakdown();
    $fmb_takhmeen_sector = $this->get_fmb_takhmeen_sector_breakdown();
    $sabeel_takhmeen_sector = $this->get_sabeel_takhmeen_sector_breakdown();
    $weekly_signups = $this->get_weekly_signup_trends();
    $recent_members = $this->get_recent_member_details();
    $this_week_sector_signup_avg = $this->get_this_week_sector_signup_avg();
    $no_thaali_families = $this->get_no_thaali_families_this_week();
    $month_signed_up = 0; $no_thaali_month_list = [];
    if ($sel_hijri_month && $sel_hijri_year) {
      $mstats = $this->CommonM->get_monthly_thaali_stats($sel_hijri_month, $sel_hijri_year);
      $month_signed_up = (int)($mstats['families_signed_up'] ?? 0); $no_thaali_month_list = $mstats['no_thaali_list'] ?? [];
    } else {
      $today_parts = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      if ($today_parts && isset($today_parts['hijri_month'])) {
        $mstats = $this->CommonM->get_monthly_thaali_stats($today_parts['hijri_month'], $today_parts['hijri_year']);
        $month_signed_up = (int)($mstats['families_signed_up'] ?? 0); $no_thaali_month_list = $mstats['no_thaali_list'] ?? [];
      }
    }
    return [
      'sabeel_summary' => $sabeel_summary, 'thaali_summary' => $thaali_summary, 'fmb_summary' => $fmb_summary, 'miqaat_summary' => $miqaat_summary,
      'raza_summary' => $raza_summary, 'miqaat_finance' => $miqaat_finance, 'fmb_miqaats' => $fmb_miqaats, 'fmb_miqaats_items' => $fmb_miqaats_items,
      'top_dues' => $top_dues, 'grade_breakdown' => $grade_breakdown, 'grade_breakdown_est' => $grade_breakdown, 'grade_breakdown_res' => $grade_breakdown_res,
      'mohallah_breakdown' => $mohallah_breakdown, 'fmb_takhmeen_sector' => $fmb_takhmeen_sector['rows'], 'fmb_takhmeen_year' => $fmb_takhmeen_sector['year'],
      'sabeel_takhmeen_sector' => $sabeel_takhmeen_sector['rows'], 'sabeel_takhmeen_year' => $sabeel_takhmeen_sector['year'],
      'weekly_signups' => $weekly_signups, 'recent_members' => $recent_members, 'this_week_sector_signup_avg' => $this_week_sector_signup_avg,
      'no_thaali_families' => $no_thaali_families, 'this_month_families_signed_up' => $month_signed_up, 'no_thaali_families_month' => $no_thaali_month_list,
      'upcoming_miqaats' => $upcoming_miqaats, 'miqaat_rsvp' => $this->CommonM->get_next_miqaat_rsvp_stats(), 'wajebaat_summary' => $this->get_wajebaat_summary(),
    ];
  }

  private function get_sabeel_summary()
  {
    $query = "SELECT SUM(COALESCE(est_grade.amount,0) + COALESCE(res_grade.amount * 12, 0) + COALESCE(mut_grade.amount * 12, 0)) as total_sabeel, SUM(COALESCE(est_paid.total_paid, 0) + COALESCE(res_paid.total_paid, 0) + COALESCE(mut_paid.total_paid, 0)) as total_paid FROM user u LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID) LEFT JOIN sabeel_takhmeen_grade est_grade ON est_grade.id = st.establishment_grade LEFT JOIN sabeel_takhmeen_grade res_grade ON res_grade.id = st.residential_grade LEFT JOIN sabeel_takhmeen_grade mut_grade ON mut_grade.id = st.mutawatteneen_grade LEFT JOIN (SELECT user_id, SUM(amount) as total_paid FROM sabeel_takhmeen_payments WHERE type = 'establishment' GROUP BY user_id) est_paid ON est_paid.user_id = u.ITS_ID LEFT JOIN (SELECT user_id, SUM(amount) as total_paid FROM sabeel_takhmeen_payments WHERE type = 'residential' GROUP BY user_id) res_paid ON res_paid.user_id = u.ITS_ID LEFT JOIN (SELECT user_id, SUM(amount) as total_paid FROM sabeel_takhmeen_payments WHERE type = 'mutawatteneen' GROUP BY user_id) mut_paid ON mut_paid.user_id = u.ITS_ID WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL";
    $result = $this->db->query($query)->row_array();
    $total = (float)($result['total_sabeel'] ?? 0); $paid = (float)($result['total_paid'] ?? 0);
    return ['total' => $total, 'paid' => $paid, 'outstanding' => max(0, $total - $paid)];
  }

  private function get_thaali_summary()
  {
    $max_year_row = $this->db->select_max('year')->get('fmb_takhmeen')->row_array();
    $max_year = $max_year_row ? $max_year_row['year'] : null;

    $total = 0.0;
    $paid = 0.0;

    if (!empty($max_year)) {
      $totalRow = $this->db->select_sum('total_amount')->get_where('fmb_takhmeen', ['year' => $max_year])->row_array();
      $total = floatval($totalRow['total_amount'] ?? 0);

      $paidRow = $this->db->query("
        SELECT SUM(amount) AS total_paid 
        FROM fmb_takhmeen_payments 
        WHERE user_id IN (
          SELECT DISTINCT user_id FROM fmb_takhmeen WHERE year = ?
        )
      ", [$max_year])->row_array();
      $paid = floatval($paidRow['total_paid'] ?? 0);
    }

    return ['total' => $total, 'paid' => $paid, 'outstanding' => max(0, $total - $paid)];
  }

  private function get_fmb_contribution_summary()
  {
    $query = "SELECT SUM(amount) as total_amount, SUM(CASE WHEN payment_status = 1 THEN amount ELSE 0 END) as paid_amount FROM fmb_general_contribution";
    $result = $this->db->query($query)->row_array();
    $total = (float)($result['total_amount'] ?? 0); $paid = (float)($result['paid_amount'] ?? 0);
    return ['total' => $total, 'paid' => $paid, 'outstanding' => max(0, $total - $paid)];
  }

  private function get_miqaat_payment_summary() { return ['total' => 0, 'paid' => 0, 'outstanding' => 0]; }

  private function get_raza_summary()
  {
    $row = $this->db->query("SELECT SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pending, SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS approved, SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS rejected FROM raza WHERE active = 1")->row_array();
    return ['pending' => (int)($row['pending'] ?? 0), 'approved' => (int)($row['approved'] ?? 0), 'rejected' => (int)($row['rejected'] ?? 0)];
  }

  private function get_miqaat_finance_summary()
  {
    $row = $this->db->query("SELECT COALESCE(SUM(i.amount), 0) AS total_invoiced, (SELECT COALESCE(SUM(p.amount), 0) FROM miqaat_payment p) AS total_paid FROM miqaat_invoice i")->row_array();
    $total = (float)($row['total_invoiced'] ?? 0); $paid = (float)($row['total_paid'] ?? 0);
    return ['total' => $total, 'paid' => $paid, 'outstanding' => max(0, $total - $paid)];
  }

  private function get_fmb_miqaats_summary()
  {
    $year_stats = $this->CommonM->get_year_calendar_daytypes(); $hijri_year = $year_stats['hijri_year'] ?? null;
    if (empty($hijri_year)) return ['hijri_year' => null, 'count' => 0, 'total' => 0.0, 'paid' => 0.0, 'outstanding' => 0.0];
    $rangeRow = $this->db->query("SELECT MIN(greg_date) AS min_d, MAX(greg_date) AS max_d FROM hijri_calendar WHERE hijri_date LIKE ?", ['%-' . $hijri_year])->row_array();
    $minDate = $rangeRow['min_d'] ?? null; $maxDate = $rangeRow['max_d'] ?? null;
    if (!$minDate || !$maxDate) return ['hijri_year' => $hijri_year, 'count' => 0, 'total' => 0.0, 'paid' => 0.0, 'outstanding' => 0.0];
    $cnt = (int)($this->db->query("SELECT COUNT(*) AS c FROM miqaat WHERE type = 'FMB' AND date >= ? AND date <= ?", [$minDate, $maxDate])->row_array()['c'] ?? 0);
    $inv = (float)($this->db->query("SELECT COALESCE(SUM(inv.amount), 0) AS total_invoiced FROM miqaat_invoice inv LEFT JOIN miqaat m ON m.id = inv.miqaat_id WHERE ((inv.miqaat_id IS NOT NULL AND m.type = 'FMB' AND m.date >= ? AND m.date <= ?) OR (inv.miqaat_id IS NULL AND inv.miqaat_type = 'FMB' AND inv.year = ?))", [$minDate, $maxDate, $hijri_year])->row_array()['total_invoiced'] ?? 0);
    $pay = (float)($this->db->query("SELECT COALESCE(SUM(p.amount), 0) AS total_paid FROM miqaat_payment p JOIN miqaat_invoice inv ON inv.id = p.miqaat_invoice_id LEFT JOIN miqaat m ON m.id = inv.miqaat_id WHERE ((inv.miqaat_id IS NOT NULL AND m.type = 'FMB' AND m.date >= ? AND m.date <= ?) OR (inv.miqaat_id IS NULL AND inv.miqaat_type = 'FMB' AND inv.year = ?))", [$minDate, $maxDate, $hijri_year])->row_array()['total_paid'] ?? 0);
    return ['hijri_year' => $hijri_year, 'count' => $cnt, 'total' => $inv, 'paid' => $pay, 'outstanding' => max(0, $inv - $pay)];
  }

  private function get_upcoming_miqaats($limit = 5) { return $this->db->query("SELECT id, name, type, date, assigned_to FROM miqaat WHERE date >= CURDATE() ORDER BY date ASC LIMIT " . (int)$limit)->result_array(); }

  private function get_top_dues_sabeel($limit = 5)
  {
    return $this->db->query("SELECT u.ITS_ID, u.Full_Name, GREATEST((COALESCE(est_grade.amount,0) + COALESCE(res_grade.amount*12,0) + COALESCE(mut_grade.amount*12,0)) - (COALESCE(est_paid.total_paid,0) + COALESCE(res_paid.total_paid,0) + COALESCE(mut_paid.total_paid,0)), 0) AS due FROM user u LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID) LEFT JOIN sabeel_takhmeen_grade est_grade ON est_grade.id = st.establishment_grade LEFT JOIN sabeel_takhmeen_grade res_grade ON res_grade.id = st.residential_grade LEFT JOIN sabeel_takhmeen_grade mut_grade ON mut_grade.id = st.mutawatteneen_grade LEFT JOIN (SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments WHERE type='establishment' GROUP BY user_id) est_paid ON est_paid.user_id = u.ITS_ID LEFT JOIN (SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments WHERE type='residential' GROUP BY user_id) res_paid ON res_paid.user_id = u.ITS_ID LEFT JOIN (SELECT user_id, SUM(amount) AS total_paid FROM sabeel_takhmeen_payments WHERE type='mutawatteneen' GROUP BY user_id) mut_paid ON mut_paid.user_id = u.ITS_ID WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL ORDER BY due DESC LIMIT " . (int)$limit)->result_array();
  }

  private function get_top_dues_thaali($limit = 5)
  {
    return $this->db->query("SELECT u.ITS_ID, u.Full_Name, GREATEST(COALESCE(SUM(f.total_amount),0) - COALESCE(p.total_paid,0), 0) AS due FROM user u LEFT JOIN fmb_takhmeen f ON f.user_id = u.ITS_ID LEFT JOIN (SELECT user_id, SUM(amount) AS total_paid FROM fmb_takhmeen_payments GROUP BY user_id) p ON p.user_id = u.ITS_ID WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL GROUP BY u.ITS_ID, u.Full_Name, p.total_paid ORDER BY due DESC LIMIT " . (int)$limit)->result_array();
  }

  private function get_grade_breakdown()
  {
    $row = $this->db->query("SELECT MAX(year) as y FROM sabeel_takhmeen")->row_array(); $yr = $row['y'] ?? null;
    return $this->db->query("SELECT COALESCE(g.grade, 'No Grade') AS grade, COUNT(*) AS count FROM sabeel_takhmeen st JOIN user u ON u.ITS_ID = st.user_id AND u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL LEFT JOIN sabeel_takhmeen_grade g ON g.id = st.establishment_grade WHERE st.year = ? GROUP BY g.grade ORDER BY count DESC", [$yr])->result_array();
  }

  private function get_grade_breakdown_residential()
  {
    $row = $this->db->query("SELECT MAX(year) as y FROM sabeel_takhmeen")->row_array(); $yr = $row['y'] ?? null;
    return $this->db->query("SELECT COALESCE(g.grade, 'No Grade') AS grade, COUNT(*) AS count FROM sabeel_takhmeen st JOIN user u ON u.ITS_ID = st.user_id AND u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL LEFT JOIN sabeel_takhmeen_grade g ON g.id = st.residential_grade WHERE st.year = ? GROUP BY g.grade ORDER BY count DESC", [$yr])->result_array();
  }

  private function get_mohallah_breakdown()
  {
    return $this->db->query("SELECT u.Sector as mohallah, COUNT(*) as total_members, SUM(CASE WHEN st.id IS NOT NULL THEN 1 ELSE 0 END) as sabeel_count, SUM(CASE WHEN ft.id IS NOT NULL THEN 1 ELSE 0 END) as thaali_count FROM user u LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID) LEFT JOIN fmb_takhmeen ft ON ft.user_id = u.ITS_ID AND ft.year = (SELECT MAX(year) FROM fmb_takhmeen WHERE user_id = u.ITS_ID) WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL GROUP BY u.Sector ORDER BY total_members DESC LIMIT 10")->result_array();
  }

  private function get_fmb_takhmeen_sector_breakdown()
  {
    $row = $this->db->query("SELECT MAX(year) AS y FROM fmb_takhmeen")->row_array(); $yr = $row['y'] ?? null;
    $users = $this->AnjumanM->get_user_takhmeen_details(); $agg = [];
    foreach ($users as $u) {
      $s = trim($u['Sector'] ?? 'Unassigned'); if ($s==='') $s='Unassigned';
      $ty = 0.0; $py = 0.0;
      if (!empty($u['all_takhmeen'])) { foreach ($u['all_takhmeen'] as $y) { if ($y['year'] == $yr) { $ty = (float)$y['total_amount']; $py = (float)$y['total_paid']; break; } } }
      if (!isset($agg[$s])) $agg[$s] = ['sector'=>$s,'total_takhmeen'=>0.0,'total_paid'=>0.0,'members'=>0];
      $agg[$s]['total_takhmeen'] += $ty; $agg[$s]['total_paid'] += $py; if ($ty>0) $agg[$s]['members']++;
    }
    $rows = array_values(array_map(function($r){ $r['outstanding']=max(0, $r['total_takhmeen']-$r['total_paid']); return $r; }, $agg));
    usort($rows, function($a,$b){ return ($b['total_takhmeen'] <=> $a['total_takhmeen']); });
    return ['year'=>$yr, 'rows'=>$rows];
  }

  private function get_sabeel_takhmeen_sector_breakdown()
  {
    $row = $this->db->query("SELECT MAX(year) AS y FROM sabeel_takhmeen")->row_array(); $yr = $row['y'] ?? null;
    $users = $this->AnjumanM->get_user_sabeel_takhmeen_details(['allocation_order'=>'oldest-first','year'=>$yr]); $agg = [];
    foreach ($users as $u) {
      $s = trim($u['Sector'] ?? 'Unassigned'); if ($s==='') $s='Unassigned';
      $ct = $u['current_year_takhmeen'] ?? null; $ty = 0.0; $py = 0.0;
      if ($ct) { 
        $ty = (float)($ct['establishment']['yearly'] + $ct['residential']['yearly'] + ($ct['mutawatteneen']['yearly'] ?? 0)); 
        $py = (float)($ct['establishment']['paid'] + $ct['residential']['paid'] + ($ct['mutawatteneen']['paid'] ?? 0)); 
      }
      if (!isset($agg[$s])) $agg[$s] = ['sector'=>$s,'total_takhmeen'=>0.0,'total_paid'=>0.0,'members'=>0];
      $agg[$s]['total_takhmeen'] += $ty; $agg[$s]['total_paid'] += $py; if ($ty>0) $agg[$s]['members']++;
    }
    $rows = array_values(array_map(function($r){ $r['outstanding']=max(0, $r['total_takhmeen']-$r['total_paid']); return $r; }, $agg));
    usort($rows, function($a,$b){ return ($b['total_takhmeen'] <=> $a['total_takhmeen']); });
    return ['year'=>$yr, 'rows'=>$rows];
  }

  private function get_weekly_signup_trends() { return [['week'=>'Week 1','signups'=>20],['week'=>'Week 2','signups'=>38],['week'=>'Week 3','signups'=>25],['week'=>'Week 4','signups'=>52]]; }

  private function get_this_week_sector_signup_avg()
  {
    $start = date('Y-m-d', strtotime('monday this week')); $end = date('Y-m-d', strtotime('sunday this week'));
    $days = (int)round((strtotime($end) - strtotime($start)) / 86400) + 1; if ($days<=0) return ['start'=>$start,'end'=>$end,'days'=>0,'sectors'=>[]];
    $agg = []; $rows = $this->CommonM->getsignupcount_by_sector_range($start, $end);
    foreach ($rows as $r) { $s = trim($r['Sector'] ?? ''); if ($s===''||strtolower($s)==='unassigned') continue; $agg[$s] = ($agg[$s]??0) + (int)$r['hof_signup_count']; }
    $sectors = []; foreach ($agg as $s => $t) { $sectors[] = ['sector'=>$s, 'total'=>(int)$t, 'avg'=>round($t/$days, 2)]; }
    usort($sectors, function($a,$b){ return ($b['avg'] <=> $a['avg']) ?: strcmp($a['sector'], $b['sector']); });
    return ['start'=>$start, 'end'=>$end, 'days'=>$days, 'sectors'=>$sectors];
  }

  private function get_no_thaali_families_this_week()
  {
    $start = date('Y-m-d', strtotime('monday this week')); $end = date('Y-m-d', strtotime('sunday this week'));
    $signed = $this->CommonM->get_signed_up_hofs_range($start, $end); $all = $this->CommonM->get_all_users(); $no = [];
    foreach ($all as $h) { if (($h['HOF_FM_TYPE']??'')==='HOF' && ($h['ITS_ID']??null) && !isset($signed[$h['ITS_ID']])) $no[] = $h; }
    return $no;
  }

  private function get_fmb_miqaats_items($limit = 5)
  {
    $yr = $this->CommonM->get_year_calendar_daytypes()['hijri_year'] ?? null; if (!$yr) return [];
    $range = $this->db->query("SELECT MIN(greg_date) AS min_d, MAX(greg_date) AS max_d FROM hijri_calendar WHERE hijri_date LIKE ?", ['%-' . $yr])->row_array();
    $min = $range['min_d'] ?? null; $max = $range['max_d'] ?? null; if (!$min || !$max) return [];
    $rows = $this->db->query("SELECT m.id, m.name, m.date, COALESCE(SUM(inv.amount), 0) AS total_invoiced, COALESCE(SUM(p.amount), 0) AS total_paid FROM miqaat m LEFT JOIN miqaat_invoice inv ON inv.miqaat_id = m.id LEFT JOIN miqaat_payment p ON p.miqaat_invoice_id = inv.id WHERE m.type = 'FMB' AND m.date >= ? AND m.date <= ? GROUP BY m.id, m.name, m.date ORDER BY m.date ASC LIMIT " . (int)$limit, [$min, $max])->result_array();
    $items = []; foreach ($rows as $r) { $items[] = ['id'=>(int)$r['id'], 'name'=>$r['name'], 'date'=>$r['date'], 'total'=>(float)$r['total_invoiced'], 'paid'=>(float)$r['total_paid'], 'outstanding'=>max(0, (float)$r['total_invoiced']-(float)$r['total_paid'])]; }
    $fala = $this->db->query("SELECT COALESCE(SUM(inv.amount), 0) AS t, (SELECT COALESCE(SUM(p.amount),0) FROM miqaat_payment p JOIN miqaat_invoice inv2 ON inv2.id=p.miqaat_invoice_id WHERE inv2.miqaat_id IS NULL AND inv2.miqaat_type='FMB' AND inv2.year=?) AS p FROM miqaat_invoice inv WHERE inv.miqaat_id IS NULL AND inv.miqaat_type='FMB' AND inv.year=?", [$yr, $yr])->row_array();
    if (($fala['t']??0)>0 || ($fala['p']??0)>0) { $items[] = ['id'=>null, 'name'=>'Fala ni Niyaz', 'date'=>null, 'total'=>(float)$fala['t'], 'paid'=>(float)$fala['p'], 'outstanding'=>max(0, (float)$fala['t']-(float)$fala['p']), 'hijri_year'=>$yr]; }
    return $items;
  }

  private function get_recent_member_details()
  {
    return $this->db->query("SELECT u.ITS_ID, u.First_Name, u.Surname, u.Sector as mohallah, COALESCE(st_est.amount, 0) as sabeel_amount, COALESCE(ft.total_amount, 0) as thaali_amount FROM user u LEFT JOIN sabeel_takhmeen st ON st.user_id = u.ITS_ID AND st.year = (SELECT MAX(year) FROM sabeel_takhmeen WHERE user_id = u.ITS_ID) LEFT JOIN sabeel_takhmeen_grade st_est ON st_est.id = st.establishment_grade LEFT JOIN fmb_takhmeen ft ON ft.user_id = u.ITS_ID AND ft.year = (SELECT MAX(year) FROM fmb_takhmeen WHERE user_id = u.ITS_ID) WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL ORDER BY u.ITS_ID DESC LIMIT 10")->result_array();
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
    $data['event_type'] = $event_type;
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

  public function wajebaat_details()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->model('WajebaatM');
    $this->load->model('HijriCalendar');

    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $parts = explode('-', $today_hijri['hijri_date']);
    $current_hijri_year = (int)$parts[2];

    $selected_year = (int)($this->input->get('year') ?: $current_hijri_year);
    $data['selected_year'] = $selected_year;
    $data['available_years'] = $this->WajebaatM->get_years();
    $data['wajebaat_rows'] = $this->WajebaatM->get_all($selected_year);

    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Amilsaheb/WajebaatDetails', $data);
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

    // WhatsApp: Raza approved (admin + member)
    if ($flag) {
      $this->load->library('Notification_lib');
      $adminWaRecipients = admin_whatsapp_recipients();

      $memberPhoneRaw = (string)($user['Registered_Family_Mobile'] ?? $user['Mobile'] ?? $user['WhatsApp_No'] ?? '');
      $memberWa = substr(preg_replace('/\D+/', '', $memberPhoneRaw), -10);
      $memberName = (string)($user['Full_Name'] ?? $user['ITS_ID'] ?? '');
      $memberIts = (string)($user['ITS_ID'] ?? '');

      $razaRowForWa = $this->db->select('id, raza_id, user_id, miqaat_id, razaType, razadata')
        ->from('raza')
        ->where('id', $raza_id)
        ->get()->row_array();

      $razaPublicId = isset($razaRowForWa['raza_id']) && $razaRowForWa['raza_id'] !== '' ? (string)$razaRowForWa['raza_id'] : (string)$raza_id;
      $waRazaId = (string)$razaPublicId;
      if ($waRazaId !== '' && stripos($waRazaId, 'R#') !== 0) {
        $waRazaId = 'R#' . $waRazaId;
      }

      $detailsText = '';
      if (!empty($razaRowForWa['miqaat_id'])) {
        $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRowForWa['miqaat_id']);
        $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
        $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRowForWa['miqaat_id'];
        $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
        $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';

        $assignmentLabel = '';
        $assignmentGroupName = '';
        $ass = $this->AccountM->get_miqaat_assignment_for_member((int)$razaRowForWa['miqaat_id'], $razaRowForWa['user_id']);
        if (!empty($ass)) {
          $assignmentLabel = isset($ass['assign_type']) ? (string)$ass['assign_type'] : '';
          $assignmentGroupName = isset($ass['group_name']) ? (string)$ass['group_name'] : '';
          $al = strtolower(trim($assignmentLabel));
          if ($al === 'group') $assignmentLabel = 'Group';
          if ($al === 'individual') $assignmentLabel = 'Individual';
        }

        $parts = [];
        if ($miqaatName !== '') $parts[] = 'Miqaat: ' . $miqaatName;
        if ($miqaatPublicId !== '') {
          $waMiqaatId = (string)$miqaatPublicId;
          if ($waMiqaatId !== '' && stripos($waMiqaatId, 'M#') !== 0) {
            $waMiqaatId = 'M#' . $waMiqaatId;
          }
          $parts[] = 'Miqaat ID: ' . $waMiqaatId;
        }
        if ($miqaatType !== '') $parts[] = 'Type: ' . $miqaatType;
        if ($miqaatDate !== '') $parts[] = 'Date: ' . $miqaatDate;
        if ($assignmentLabel !== '') $parts[] = 'Assignment: ' . $assignmentLabel;
        if ($assignmentGroupName !== '') $parts[] = 'Group: ' . $assignmentGroupName;
        $detailsText = implode(' | ', $parts);
      }

      if ($detailsText === '') {
        $this->load->helper('raza_details');
        $rtRow = null;
        if (!empty($razaRowForWa['razaType'])) {
          $rtRow = $this->db->select('id, name, fields')
            ->from('raza_type')
            ->where('id', (int)$razaRowForWa['razaType'])
            ->get()->row_array();
        }
        $razadataDecoded = [];
        if (!empty($razaRowForWa['razadata'])) {
          $tmp = json_decode($razaRowForWa['razadata'], true);
          if (is_array($tmp)) $razadataDecoded = $tmp;
        }
        $rtFieldsDecoded = [];
        if (!empty($rtRow['fields'])) {
          $tmp = json_decode($rtRow['fields'], true);
          if (is_array($tmp)) $rtFieldsDecoded = $tmp;
        }
        $razaName = isset($rtRow['name']) ? (string)$rtRow['name'] : 'Raza';
        $detailsText = function_exists('render_raza_details_compact_text')
          ? (string)render_raza_details_compact_text($razaName, $rtFieldsDecoded, $razadataDecoded)
          : '';
        if ($detailsText === '') {
          $detailsHtml = render_raza_details_table_html($razaName, $rtFieldsDecoded, $razadataDecoded);
          $detailsText = function_exists('render_raza_details_compact_text_from_html')
            ? (string)render_raza_details_compact_text_from_html($detailsHtml)
            : trim(preg_replace('/\s+/', ' ', strip_tags((string)$detailsHtml)));
        }
      }

      // Keep variables strictly single-line; ExprezBot/WhatsApp templates may not dispatch reliably with embedded newlines.
      $detailsText = trim(preg_replace('/\s+/', ' ', str_replace(["\r", "\n", "\r\n"], ' ', (string)$detailsText)));
      if ($detailsText === '') $detailsText = 'Raza';

      if ($memberWa !== '') {
        $this->notification_lib->send_whatsapp([
          'recipient' => $memberWa,
          'template_name' => 'raza_approved_member',
          'template_language' => 'en',
          'body_vars' => [
            (string)$memberName,
            (string)$waRazaId,
            (string)$detailsText,
          ]
        ]);
      }

      foreach ($adminWaRecipients as $wa) {
        $this->notification_lib->send_whatsapp([
          'recipient' => $wa,
          'template_name' => 'raza_approved_admin',
          'template_language' => 'en',
          'body_vars' => [
            (string)$memberName,
            (string)$memberIts,
            (string)$waRazaId,
            (string)$detailsText,
          ]
        ]);
      }
    }

    // enqueue notifications instead of sending immediately
    $this->load->model('NotificationM');
    $this->load->helper('email_template');
    $this->load->helper('url');
    $userEmail = isset($user['Email']) ? $user['Email'] : null;
    $userName = isset($user['Full_Name']) ? $user['Full_Name'] : '';
    $msg = $userName . ' (' . ($user['ITS_ID'] ?? '') . ') - Raza has been Approved by Amil Saheb';

    // Notify member (include full details)
    if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
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

      $memberDetails = [
        'Raza ID' => (string)$razaPublicId,
      ];
      // If this is a Miqaat Raza, include miqaat details in the email.
      if (!empty($razaRow['miqaat_id'])) {
        $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRow['miqaat_id']);
        $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
        $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRow['miqaat_id'];
        $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
        $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';

        if ($miqaatName !== '') $memberDetails['Miqaat'] = $miqaatName;
        if ($miqaatPublicId !== '') $memberDetails['Miqaat ID'] = $miqaatPublicId;
        if ($miqaatType !== '') $memberDetails['Type'] = $miqaatType;
        if ($miqaatDate !== '') $memberDetails['Date'] = $miqaatDate;

        $ass = $this->AccountM->get_miqaat_assignment_for_member((int)$razaRow['miqaat_id'], $razaRow['user_id']);
        if (!empty($ass)) {
          $assignmentLabel = isset($ass['assign_type']) ? (string)$ass['assign_type'] : '';
          $assignmentGroupName = isset($ass['group_name']) ? (string)$ass['group_name'] : '';
          $al = strtolower(trim($assignmentLabel));
          if ($al === 'group') $assignmentLabel = 'Group';
          if ($al === 'individual') $assignmentLabel = 'Individual';
          if ($assignmentLabel !== '') $memberDetails['Assignment'] = $assignmentLabel;
          if ($assignmentGroupName !== '') $memberDetails['Group'] = $assignmentGroupName;
        }
      }

      $memberBody = render_generic_email_html([
        'title' => 'Raza Status',
        'todayDate' => date('l, j M Y, h:i:s A'),
        'greeting' => 'Baad Afzalus Salaam,',
        'name' => (string)$userName,
        'its' => (string)($user['ITS_ID'] ?? ''),
        // Place the Mubarak line as the card title so it appears above the details table
        'cardTitle' => 'Mubarak! Your Raza has been approved by Janab Amil Saheb.',
        'details' => $memberDetails,
        'body' => $remarkHtml
          . (empty($razaRow['miqaat_id']) ? $detailsHtml : '')
          . '<p>If you require further assistance, please contact the Jamaat office.</p>'
          . '<p>Wasalaam,<br/>Amil Saheb Office</p>',
        'ctaUrl' => base_url('accounts'),
        'ctaText' => 'Login to your account',
      ]);

      $this->NotificationM->insert_notification([
        'channel' => 'email',
        'recipient' => $userEmail,
        'recipient_type' => 'user',
        'subject' => 'Raza Status',
        'body' => $memberBody,
        'scheduled_at' => null
      ]);
    }

    // Notify admins
    // Build details HTML once so admin email matches member email.
    $this->load->helper('raza_details');
    $razaRowEmail = $this->db->select('id, raza_id, user_id, razaType, razadata, miqaat_id')
      ->from('raza')
      ->where('id', $raza_id)
      ->get()->row_array();
    $rtRowEmail = null;
    if (!empty($razaRowEmail['razaType'])) {
      $rtRowEmail = $this->db->select('id, name, fields')
        ->from('raza_type')
        ->where('id', (int)$razaRowEmail['razaType'])
        ->get()->row_array();
    }
    $razadataDecodedEmail = [];
    if (!empty($razaRowEmail['razadata'])) {
      $tmp = json_decode($razaRowEmail['razadata'], true);
      if (is_array($tmp)) $razadataDecodedEmail = $tmp;
    }
    $rtFieldsDecodedEmail = [];
    if (!empty($rtRowEmail['fields'])) {
      $tmp = json_decode($rtRowEmail['fields'], true);
      if (is_array($tmp)) $rtFieldsDecodedEmail = $tmp;
    }
    $razaNameEmail = isset($rtRowEmail['name']) ? (string)$rtRowEmail['name'] : 'Raza';
    $razaPublicIdEmail = isset($razaRowEmail['raza_id']) && $razaRowEmail['raza_id'] !== '' ? (string)$razaRowEmail['raza_id'] : (string)$raza_id;
    $detailsHtmlEmail = render_raza_details_table_html($razaNameEmail, $rtFieldsDecodedEmail, $razadataDecodedEmail);
    $remarkHtmlEmail = $remark !== '' ? ('<p><strong>Remark:</strong> ' . nl2br(htmlspecialchars($remark)) . '</p>') : '';

    $admins = admin_email_recipients();
    foreach ($admins as $a) {
      // Render the member info as a table inside the body so it always shows,
      // even when we append the Raza Details table (body contains <table>, so
      // render_generic_email_html() would otherwise skip the `details` table).
      $adminDetailsRows = [
        ['label' => 'Member', 'value' => (string)$userName],
        ['label' => 'ITS', 'value' => (string)($user['ITS_ID'] ?? '')],
        ['label' => 'Raza ID', 'value' => (string)$razaPublicIdEmail],
      ];

      // If this is a Miqaat Raza, include miqaat details.
      if (!empty($razaRowEmail) && !empty($razaRowEmail['miqaat_id'])) {
        $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRowEmail['miqaat_id']);
        $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
        $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRowEmail['miqaat_id'];
        $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
        $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
        if ($miqaatName !== '') $adminDetailsRows[] = ['label' => 'Miqaat', 'value' => $miqaatName];
        if ($miqaatPublicId !== '') $adminDetailsRows[] = ['label' => 'Miqaat ID', 'value' => $miqaatPublicId];
        if ($miqaatType !== '') $adminDetailsRows[] = ['label' => 'Type', 'value' => $miqaatType];
        if ($miqaatDate !== '') $adminDetailsRows[] = ['label' => 'Date', 'value' => $miqaatDate];
      }

      $adminMemberTableHtml = function_exists('email_kv_details_table_html')
        ? (string)email_kv_details_table_html($adminDetailsRows)
        : '';

      $adminBody = render_generic_email_html([
        'title' => 'Raza Approved',
        'todayDate' => date('l, j M Y, h:i:s A'),
        'greeting' => 'Baad Afzalus Salaam,',
        'cardTitle' => 'Please be informed that the following Raza has been approved by Amil Saheb.',
        'body' => $adminMemberTableHtml
          . '<div style="margin-top:12px;">'
          . $remarkHtmlEmail
          . (empty($razaRowEmail['miqaat_id']) ? $detailsHtmlEmail : '')
          . 'Regards,<br/>Amil Saheb Office'
          . '</div>',
        'ctaUrl' => base_url('accounts'),
        'ctaText' => 'Login to your account',
      ]);

      $this->NotificationM->insert_notification([
        'channel' => 'email',
        'recipient' => $a,
        'recipient_type' => 'admin',
        'subject' => 'Raza Approved',
        'body' => $adminBody,
        'scheduled_at' => null
      ]);
    }


    // RSVP notifications: only for Miqaat Raza approvals
    if ($flag) {
      $razaRow = $this->db->select('id, razaType, miqaat_id')
        ->from('raza')
        ->where('id', $raza_id)
        ->get()
        ->row_array();

      $miqaatId = isset($razaRow['miqaat_id']) ? (int)$razaRow['miqaat_id'] : 0;
      $razaTypeId = isset($razaRow['razaType']) ? (int)$razaRow['razaType'] : 0;

      $rt = null;
      if ($razaTypeId > 0) {
        $rt = $this->db->select('id, name, umoor')
          ->from('raza_type')
          ->where('id', $razaTypeId)
          ->get()
          ->row_array();
      }

      $isMiqaatRaza = false;
      $umoor = isset($rt['umoor']) ? (string)$rt['umoor'] : '';
      $rtName = isset($rt['name']) ? (string)$rt['name'] : '';
      if ($umoor === 'Public-Event' || stripos($rtName, 'miqaat') !== false) {
        $isMiqaatRaza = true;
      }

      if ($isMiqaatRaza && $miqaatId > 0) {
        $this->load->library('Notification_lib');

        $miqaat = $this->AnjumanM->get_miqaat_by_id($miqaatId);
        $miqName = is_array($miqaat) ? ($miqaat['name'] ?? '') : '';
        // Always fetch the public Miqaat ID from `miqaat.miqaat_id` for notifications.
        // Important: AnjumanM::get_miqaat_by_id() selects m.* and ma.*; both contain `miqaat_id`,
        // so the assignment FK can overwrite the public code in the resulting array.
        $miqPublicId = '';
        $row = $this->db->select('miqaat_id')
          ->from('miqaat')
          ->where('id', (int)$miqaatId)
          ->limit(1)
          ->get()->row_array();
        if (!empty($row['miqaat_id'])) {
          $miqPublicId = (string)$row['miqaat_id'];
        }

        // Fallbacks (should be rare).
        if ($miqPublicId === '' && is_array($miqaat) && !empty($miqaat['miqaat_code'])) {
          $miqPublicId = (string)$miqaat['miqaat_code'];
        }
        if ($miqPublicId === '' && is_array($miqaat) && !empty($miqaat['miqaat_id'])) {
          $miqPublicId = (string)$miqaat['miqaat_id'];
        }
        if ($miqPublicId === '') {
          // Last-resort fallback (should be rare): keep something non-empty.
          $miqPublicId = (string)$miqaatId;
        }
        $miqDate = is_array($miqaat) ? (date("d-m-Y", strtotime($miqaat['date'])) ?? '') : '';
        $miqTime = is_array($miqaat) ? ($miqaat['time'] ?? '') : '';
        $miqHijri = is_array($miqaat) ? ($miqaat['hijri_date'] ?? '') : '';
        $miqDesc = is_array($miqaat) ? ($miqaat['description'] ?? '') : '';
        $rsvpLink = base_url('accounts/general_rsvp/' . $miqaatId);

        $subject = 'RSVP Open: ' . ($miqName !== '' ? $miqName : ('Miqaat #' . $miqaatId));
        $rsvpDetails = [
          // Use public ID so members see M#1447-118, not M#318.
          'Miqaat ID' => (string)$miqPublicId,
        ];
        if ($miqName !== '') $rsvpDetails['Miqaat'] = $miqName;
        if ($miqDate !== '') $rsvpDetails['Date'] = $miqDate;
        if ($miqTime !== '') $rsvpDetails['Time'] = $miqTime;
        if ($miqHijri !== '') $rsvpDetails['Hijri Date'] = $miqHijri;
        if ($miqDesc !== '') $rsvpDetails['Description'] = $miqDesc;

        $body = render_generic_email_html([
          'title' => $subject,
          'todayDate' => date('l, j M Y, h:i:s A'),
          'greeting' => 'Baad Afzalus Salaam,',
          'cardTitle' => '',
          'details' => $rsvpDetails,
          'body' => 'A Miqaat has been approved and is now open for RSVP. Please submit your RSVP using the button below.<br/><br/>Regards,<br/>Anjuman E Saifee Dawoodi Bohra Jamaat Khar',
          'ctaUrl' => $rsvpLink,
          'ctaText' => 'Submit RSVP',
        ]);

        // Select distinct, trimmed emails and exclude empty/null values so each address is contacted once
        $members = $this->db->select("DISTINCT(TRIM(Email)) AS Email", false)
          ->from('user')
          ->where('inactive_status IS NULL', null, false)
          ->where('Email IS NOT NULL', null, false)
          ->where("TRIM(Email) != ''", null, false)
          ->get()
          ->result_array();
        foreach ($members as $m) {
          $email = isset($m['Email']) ? trim($m['Email']) : '';
          if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
          $this->NotificationM->insert_notification([
            'channel' => 'email',
            'recipient' => $email,
            'recipient_type' => 'member',
            'subject' => $subject,
            'body' => $body,
            'scheduled_at' => null
          ]);
        }

        // WhatsApp: send ExprezBot template to members with a valid mobile.
        // Template variables/order are controlled by the ExprezBot template itself.
        // Start with no variables (works with templates that have no placeholders).
        $waMembers = $this->db->select("u.Full_Name AS name, COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') AS mobile", false)
          ->from('user u')
          ->where('u.inactive_status IS NULL', null, false)
          ->where("COALESCE(u.Registered_Family_Mobile, u.Mobile, u.WhatsApp_No, '') <> ''", null, false)
          ->get()
          ->result_array();

        foreach ($waMembers as $wm) {
          $phone = preg_replace('/[^0-9]/', '', (string)($wm['mobile'] ?? ''));
          if ($phone === '') continue;
          $waName = trim((string)($wm['name'] ?? ''));
          if ($waName === '') $waName = 'Member';

          $this->config->load('whatsapp', true);
          $tplCfg = $this->config->item('templates', 'whatsapp');
          $tplKey = (is_array($tplCfg) && isset($tplCfg['rsvp_open_member_v2'])) ? 'rsvp_open_member_v2' : 'rsvp_open_member';
          $tpl = is_array($tplCfg) && isset($tplCfg[$tplKey]) ? $tplCfg[$tplKey] : [];
          $tplLang = isset($tpl['language']) ? (string)$tpl['language'] : 'en';
          $tplVars = isset($tpl['vars']) && is_array($tpl['vars']) ? $tpl['vars'] : ['name', 'miqaat', 'miqaat_id', 'date', 'rsvp_miqaat_id'];

          $varsMap = [
            'name' => (string)$waName,
            'miqaat' => (string)$miqName,
            'miqaat_id' => (stripos((string)$miqPublicId, 'M#') === 0 ? (string)$miqPublicId : ('M#' . (string)$miqPublicId)),
            'date' => (string)$miqDate,
          ];

          $bodyVars = [];
          foreach ($tplVars as $k) {
            $key = is_string($k) ? trim($k) : '';
            if ($key === '') continue;
            $bodyVars[] = isset($varsMap[$key]) ? (string)$varsMap[$key] : '';
          }

          $this->notification_lib->send_whatsapp([
            'recipient' => $phone,
            'recipient_type' => 'member',
            'template_name' => $tplKey,
            'template_language' => $tplLang,
            'body_vars' => $bodyVars,
          ]);
        }
      }
    }

    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }

  private function get_wajebaat_summary()
  {
    $row = $this->db->query("SELECT COUNT(*) AS cnt, SUM(amount) AS total_amount, SUM(due) AS total_due, SUM(CASE WHEN amount > due THEN (amount - due) ELSE 0 END) AS total_received FROM wajebaat")->row_array();
    $total = (float)($row['total_amount'] ?? 0); $due = (float)($row['total_due'] ?? 0);
    return ['count' => (int)($row['cnt'] ?? 0), 'total' => (int)round($total), 'received' => (int)round($row['total_received'] ?? max(0, $total - $due)), 'due' => (int)round($due)];
  }

  public function DeleteRaza($id)
  {
    // Retrieve the value of $umoor from the URL parameters
    $umoor = $this->input->get('umoor');
    $event_type = $this->input->get('event_type');

    $flag = $this->AccountM->delete_raza($id);

    if ($flag) {
      // Check the value of $umoor and redirect accordingly
      if ($umoor == 'Event Raza Applications' || $umoor == 'Miqaat Raza Requests' || $umoor == 'Kaaraj Raza Requests' || $umoor == 'Event Raza Requests' || $umoor == 'Miqaat Request' || $umoor == 'Kaaraj Request') {
        $query_str = !empty($event_type) ? '?event_type=' . $event_type : '';
        redirect('/amilsaheb/success/EventRazaRequest' . $query_str);
      } else {
        redirect('/amilsaheb/success/UmoorRazaRequest');
      }
    } else {
      // Check the value of $umoor and redirect to the appropriate error URL
      if ($umoor == 'Event Raza Applications' || $umoor == 'Miqaat Raza Requests' || $umoor == 'Kaaraj Raza Requests' || $umoor == 'Event Raza Requests' || $umoor == 'Miqaat Request' || $umoor == 'Kaaraj Request') {
        $query_str = !empty($event_type) ? '?event_type=' . $event_type : '';
        redirect('/amilsaheb/error/EventRazaRequest' . $query_str);
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

    // Enqueue notifications (templated by Notifications worker)
    $this->load->model('NotificationM');
    $this->load->helper('raza_details');
    $this->load->helper('email_template');
    $this->load->helper('url');

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
      $memberDetails = [
        'Raza ID' => (string)$razaPublicId,
      ];
      if (!empty($razaRow['miqaat_id'])) {
        $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRow['miqaat_id']);
        $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
        $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRow['miqaat_id'];
        $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
        $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
        if ($miqaatName !== '') $memberDetails['Miqaat'] = $miqaatName;
        if ($miqaatPublicId !== '') $memberDetails['Miqaat ID'] = $miqaatPublicId;
        if ($miqaatType !== '') $memberDetails['Type'] = $miqaatType;
        if ($miqaatDate !== '') $memberDetails['Date'] = $miqaatDate;
        $ass = $this->AccountM->get_miqaat_assignment_for_member((int)$razaRow['miqaat_id'], $razaRow['user_id']);
        if (!empty($ass)) {
          $assignmentLabel = isset($ass['assign_type']) ? (string)$ass['assign_type'] : '';
          $assignmentGroupName = isset($ass['group_name']) ? (string)$ass['group_name'] : '';
          $al = strtolower(trim($assignmentLabel));
          if ($al === 'group') $assignmentLabel = 'Group';
          if ($al === 'individual') $assignmentLabel = 'Individual';
          if ($assignmentLabel !== '') $memberDetails['Assignment'] = $assignmentLabel;
          if ($assignmentGroupName !== '') $memberDetails['Group'] = $assignmentGroupName;
        }
      }

      $memberBody = render_generic_email_html([
        'title' => 'Raza Status',
        'todayDate' => date('l, j M Y, h:i:s A'),
        'greeting' => 'Baad Afzalus Salaam,',
        'name' => (string)($user['Full_Name'] ?? ''),
        'its' => (string)($user['ITS_ID'] ?? ''),
        'cardTitle' => '',
        'details' => $memberDetails,
        'body' => '<p>Your Raza has been <strong>rejected</strong> by Janab Amil Saheb.</p>'
          . $remarkHtml
          . (empty($razaRow['miqaat_id']) ? $detailsHtml : '')
          . '<p>If you require further assistance, please contact the Jamaat office.</p>'
          . '<p>Wasalaam,<br/>Amil Saheb Office</p>',
        'ctaUrl' => base_url('accounts'),
        'ctaText' => 'Login to your account',
      ]);
      $this->NotificationM->insert_notification([
        'channel' => 'email',
        'recipient' => $memberEmail,
        'recipient_type' => 'user',
        'subject' => 'Raza Status',
        'body' => $memberBody,
        'scheduled_at' => null
      ]);
    }

    $admins = admin_email_recipients();
    $adminDetails = [
      'Member' => (string)($user['Full_Name'] ?? ''),
      'ITS' => (string)($user['ITS_ID'] ?? ''),
      'Raza ID' => (string)$razaPublicId,
    ];
    if (!empty($razaRow['miqaat_id'])) {
      $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRow['miqaat_id']);
      $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
      $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRow['miqaat_id'];
      $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
      $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';
      if ($miqaatName !== '') $adminDetails['Miqaat'] = $miqaatName;
      if ($miqaatPublicId !== '') $adminDetails['Miqaat ID'] = $miqaatPublicId;
      if ($miqaatType !== '') $adminDetails['Type'] = $miqaatType;
      if ($miqaatDate !== '') $adminDetails['Date'] = $miqaatDate;
    }

    $adminBody = render_generic_email_html([
      'title' => 'Raza Rejected',
      'todayDate' => date('l, j M Y, h:i:s A'),
      'greeting' => 'Baad Afzalus Salaam,',
      'cardTitle' => '',
      'details' => $adminDetails,
      'body' => '<p>Raza has been <strong>rejected</strong> by Janab Amil Saheb.</p>'
        . $remarkHtml
        . (empty($razaRow['miqaat_id']) ? $detailsHtml : '')
        . '<p>Regards,<br/>Amil Saheb Office</p>',
      'ctaUrl' => base_url('accounts'),
      'ctaText' => 'Login to your account',
    ]);
    foreach ($admins as $a) {
      $this->NotificationM->insert_notification([
        'channel' => 'email',
        'recipient' => $a,
        'recipient_type' => 'admin',
        'subject' => 'Raza Rejected',
        'body' => $adminBody,
        'scheduled_at' => null
      ]);
    }

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

    if (isset($data['users']) && is_array($data['users'])) {
      foreach ($data['users'] as &$u) {
        $its = isset($u->ITS_ID) ? $u->ITS_ID : (isset($u['ITS_ID']) ? $u['ITS_ID'] : (isset($u['ITS']) ? $u['ITS'] : null));
        $val = isset($ohbat_map[$its]) && !empty($ohbat_map[$its]) ? $ohbat_map[$its] : "Musaaid didn't Contacted Yet";
        if (is_object($u)) $u->LeaveStatus = $val;
        else $u['LeaveStatus'] = $val;
      }
      unset($u);
    }

    if (isset($data['all_users']) && is_array($data['all_users'])) {
      foreach ($data['all_users'] as &$u) {
        $its = isset($u->ITS_ID) ? $u->ITS_ID : (isset($u['ITS_ID']) ? $u['ITS_ID'] : (isset($u['ITS']) ? $u['ITS'] : null));
        $val = isset($ohbat_map[$its]) && !empty($ohbat_map[$its]) ? $ohbat_map[$its] : "Musaaid didn't Contacted Yet";
        if (is_object($u)) $u->LeaveStatus = $val;
        else $u['LeaveStatus'] = $val;
      }
      unset($u);
    }

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
  public function slots_calendar()
  {
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
      $end = $hijri_days[count($hijri_days) - 1]['greg_date'];
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
        $existingTimes = array_map(function ($r) {
          return $r['time'];
        }, $existing);
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

    // Year selection (Hijri) — default to next year if current Hijri month >= 10
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts[2];
    $current_hijri_month = (int)$hijri_parts[1];
    $default_year = $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year);
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

    // Default empty/null/Unknown LeaveStatus to "Musaaid didn't Contacted Yet"
    foreach ($users as &$u) {
      if (empty($u['LeaveStatus']) || $u['LeaveStatus'] === 'Unknown') {
        $u['LeaveStatus'] = "Musaaid didn't Contacted Yet";
      }
    }
    unset($u);

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
      'Age_16_25' => 0,
      'Age_26_65' => 0,
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

    // Hijri Year selection (UI scope only; attendance table not year-scoped) — default to next year if month >= 10
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
        'Not attended anywhere'
      ],
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load view
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }

  public function ashara_attendance_list()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Use GET parameters if available
    $sel_sector = $this->input->get('sector');
    $sel_sub = $this->input->get('subsector');

    // Hijri Year selection (UI scope only; attendance table not year-scoped) — default to next year if month >= 10
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
        'Not attended anywhere'
      ],
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load view
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendanceList', $data);
  }

  // Qardan Hasana (Amilsaheb)
  // - /amilsaheb/qardanhasana
  // - /amilsaheb/qardanhasana/mohammedi | taher | husain
  // - /amilsaheb/qardanhasana/{scheme}/import (POST)
  // - /amilsaheb/qardanhasana/{scheme}/delete/{id} (POST)
  // - /amilsaheb/qardanhasana/{scheme}/update/{id} (POST)
  public function qardanhasana($scheme = null, $action = null, $id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }

    $data = [];
    $data['user_name'] = $_SESSION['user']['username'] ?? '';
    $data['qh_prefix'] = 'amilsaheb';
    $data['can_manage'] = false;
    $data['can_import'] = false;

    $scheme = $scheme !== null ? strtolower(trim((string)$scheme)) : null;
    $action = $action !== null ? strtolower(trim((string)$action)) : null;

    $this->load->view('Amilsaheb/Header', $data);

    if ($scheme === null || $scheme === '') {
      $this->load->view('Admin/QardanHasana', $data);
      return;
    }

    if (!in_array($scheme, ['mohammedi', 'taher', 'husain'], true)) {
      redirect('amilsaheb/qardanhasana');
      return;
    }

    $titles = [
      'mohammedi' => 'Mohammedi Scheme',
      'taher' => 'Taher Scheme',
      'husain' => 'Husain Scheme'
    ];
    $data['scheme'] = $scheme;
    $data['scheme_key'] = $scheme;
    $data['scheme_title'] = $titles[$scheme] ?? ucfirst($scheme) . ' Scheme';

    // Delete disabled (read-only)
    if ($action === 'delete') {
      $this->session->set_flashdata('qh_import_error', 'Delete is not allowed.');
      redirect('amilsaheb/qardanhasana/' . $scheme);
      return;
    }

    // Update disabled (read-only)
    if ($action === 'update') {
      $this->session->set_flashdata('qh_import_error', 'Update is not allowed.');
      redirect('amilsaheb/qardanhasana/' . $scheme);
      return;
    }

    // Import CSV disabled for Amilsaheb
    if ($action === 'import') {
      $this->session->set_flashdata('qh_import_error', 'Import is not allowed.');
      redirect('amilsaheb/qardanhasana/' . $scheme);
      return;
    }

    // Filters (GET) - keep same shape as Admin/Anjuman for shared view
    $data['filters'] = [
      'miqaat_id' => $this->input->get('miqaat_id'),
      'hijri_date' => $this->input->get('hijri_date'),
      'greg_date' => $this->input->get('greg_date'),
      'deposit_date' => $this->input->get('deposit_date'),
      'maturity_date' => $this->input->get('maturity_date'),
      'duration' => $this->input->get('duration'),
      'its' => $this->input->get('its'),
      'member_name' => $this->input->get('member_name')
    ];

    // Miqaat list for filters
    $data['miqaats'] = $this->db
      ->select('m.id, m.name, m.date, hc.hijri_date')
      ->from('miqaat m')
      ->join('hijri_calendar hc', 'hc.greg_date = m.date', 'left')
      ->order_by('m.date', 'DESC')
      ->get()->result_array();

    // Import messages
    $data['qh_import_message'] = $this->session->flashdata('qh_import_message');
    $data['qh_import_error'] = $this->session->flashdata('qh_import_error');

    // Resolve miqaat_id -> miqaat_name for filtering scheme table (which stores miqaat_name as text)
    $miqaatNameFilter = '';
    $miqaatId = isset($data['filters']['miqaat_id']) ? trim((string)$data['filters']['miqaat_id']) : '';
    if ($miqaatId !== '') {
      $row = $this->db->select('name')->from('miqaat')->where('id', (int)$miqaatId)->get()->row_array();
      $miqaatNameFilter = isset($row['name']) ? (string)$row['name'] : '';
    }

    $this->load->model('QardanHasanaM');
    if ($scheme === 'mohammedi') {
      $data['records'] = $this->QardanHasanaM->get_mohammedi_records([
        'miqaat_name' => $miqaatNameFilter,
        'hijri_date' => isset($data['filters']['hijri_date']) ? trim((string)$data['filters']['hijri_date']) : '',
        'eng_date' => isset($data['filters']['greg_date']) ? trim((string)$data['filters']['greg_date']) : ''
      ]);
    } elseif ($scheme === 'taher') {
      $data['records'] = $this->QardanHasanaM->get_taher_records([
        'miqaat_name' => $miqaatNameFilter,
        'ITS' => isset($data['filters']['its']) ? trim((string)$data['filters']['its']) : '',
        'member_name' => isset($data['filters']['member_name']) ? trim((string)$data['filters']['member_name']) : ''
      ]);
    } elseif ($scheme === 'husain') {
      $depositDate = isset($data['filters']['deposit_date']) ? trim((string)$data['filters']['deposit_date']) : '';
      if ($depositDate === '') {
        $depositDate = isset($data['filters']['greg_date']) ? trim((string)$data['filters']['greg_date']) : '';
      }
      $maturityDate = isset($data['filters']['maturity_date']) ? trim((string)$data['filters']['maturity_date']) : '';
      $duration = isset($data['filters']['duration']) ? trim((string)$data['filters']['duration']) : '';
      $data['records'] = $this->QardanHasanaM->get_husain_records([
        'deposit_date' => $depositDate,
        'maturity_date' => $maturityDate,
        'duration' => $duration,
        'ITS' => isset($data['filters']['its']) ? trim((string)$data['filters']['its']) : '',
        'member_name' => isset($data['filters']['member_name']) ? trim((string)$data['filters']['member_name']) : ''
      ]);
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

    $this->load->view('Admin/QardanHasanaScheme', $data);
  }

  public function search_members_json()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      echo json_encode([]);
      return;
    }
    $query = $this->input->get('query');
    $results = $this->AmilsahebM->search_members($query);
    echo json_encode($results);
  }

  public function viewmember($its_id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    if (!$its_id) {
      redirect('amilsaheb/mumineendirectory');
      return;
    }
    $member = $this->AdminM->get_member_by_its($its_id);
    if (!$member) {
      redirect('amilsaheb/mumineendirectory');
      return;
    }
    $hof_id = !empty($member['HOF_ID']) ? $member['HOF_ID'] : $its_id;
    $data['user_name']         = $_SESSION['user']['username'];
    $data['member']            = $member;
    $data['family_members']    = $this->AdminM->get_family_members($its_id);
    $data['family_financials'] = $this->AdminM->get_family_financial_data($its_id, array_column($data['family_members'], 'ITS_ID'));
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Admin/ViewMember', $data);
  }

  public function editmember($its_id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      redirect('/accounts');
    }
    if (!$its_id) {
      redirect('amilsaheb');
      return;
    }
    $member = $this->AdminM->get_member_by_its($its_id);
    if (!$member) {
      redirect('amilsaheb');
      return;
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member'] = $member;
    $hof_list = [];
    $hof_list = $this->AdminM->get_family_members_by_hof_id($member['HOF_ID']);
    $data['hof_list'] = $hof_list;

    $hof_name = '';
    if (!empty($member['HOF_ID'])) {
      $hof_row = $this->AdminM->get_member_by_its($member['HOF_ID']);
      if ($hof_row) {
        $hof_name = $hof_row['Full_Name'];
      }
    }
    $data['hof_name'] = $hof_name;

    $data['sector_map'] = $this->AdminM->get_sector_hierarchy();
    $data['sector_list'] = array_keys($data['sector_map']);
    $data['incharges_map'] = $this->AdminM->get_sector_incharges_map();
    $this->load->model('MemberStatusM');
    $data['deeni_status_options']       = MemberStatusM::deeni_status_options();
    $data['residential_status_options'] = MemberStatusM::residential_status_options();
    $data['health_status_options']      = MemberStatusM::health_status_options();
    $data['activity_status_options']    = MemberStatusM::activity_status_options();
    $this->load->view('Amilsaheb/Header', $data);
    $this->load->view('Admin/EditMember', $data);
  }

  public function add_confidential_comment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
      echo json_encode(['success' => false, 'error' => 'Unauthorized']);
      return;
    }
    $its_id = (int)$this->input->post('its_id');
    $comment = trim((string)$this->input->post('comment'));
    if ($its_id <= 0 || $comment === '') {
      echo json_encode(['success' => false, 'error' => 'Invalid inputs']);
      return;
    }

    $created_by = $_SESSION['user']['username'];
    $created_by_name = 'Amil Saheb';
    if (!empty($_SESSION['user_data']['First_Name']) || !empty($_SESSION['user_data']['Surname'])) {
      $created_by_name = trim(($_SESSION['user_data']['First_Name'] ?? '') . ' ' . ($_SESSION['user_data']['Surname'] ?? ''));
    }

    $this->load->model('ConfidentialCommentM');
    $ok = $this->ConfidentialCommentM->add_comment($its_id, $comment, $created_by, $created_by_name);
    echo json_encode(['success' => $ok]);
  }
}
