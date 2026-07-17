<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Admin extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->model('SettingsM');
    $this->load->model('HijriCalendar');
    $this->load->library('email', $this->config->item('email'));
    $this->AdminM->updateHijriGregorianDates();

    // Block Raza recommendation/approval actions for UmoorFMB (role 12) and UmoorDakheliyah (role 9)
    if (!empty($_SESSION['user']) && in_array((int)$_SESSION['user']['role'], [9, 12], true)) {
      $method = strtolower($this->router->fetch_method());
      if ($method === 'approveraza' || $method === 'rejectraza') {
        show_error('Edit access is disabled for this login.');
      }
    }
  }

  private function validateUser($user)
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
  }

  // Admin dashboard
  public function index()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Home', $data);
  }

  // System Login Tracking Report
  public function login_report()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $filters = [
      'search' => $this->input->get('search'),
      'start_date' => $this->input->get('start_date'),
      'end_date' => $this->input->get('end_date')
    ];

    $data['filters'] = $filters;
    $data['logs'] = $this->AccountM->get_login_logs($filters);
    
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/LoginReport', $data);
  }

  // Laagat Module
  public function laagat()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['module_type'] = 'laagat';

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/LaagatRentMenu', $data);
  }

  // Rent Module
  public function rent()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['module_type'] = 'rent';

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/LaagatRentMenu', $data);
  }

  public function laagat_create()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['module_type'] = 'laagat';

    $this->load->model('LaagatRentM');

    // Build Hijri financial year ranges like 1442-43 up to current hijri year.
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $current_hijri_year = null;
    if (isset($today_hijri['hijri_date'])) {
      $parts = explode('-', (string)$today_hijri['hijri_date']);
      if (count($parts) === 3 && is_numeric($parts[2])) {
        $current_hijri_year = (int)$parts[2];
      }
    }
    if (!$current_hijri_year) {
      $current_hijri_year = 1442;
    }
    $start_year = 1442;
    $end_year = max($start_year, (int)$current_hijri_year);
    $year_ranges = [];
    for ($y = $start_year; $y <= $end_year; $y++) {
      $year_ranges[] = $y . '-' . substr((string)($y + 1), -2);
    }
    $data['hijri_year_options'] = $year_ranges;

    $data['flash_success'] = isset($_SESSION['laagat_flash_success']) ? (string)$_SESSION['laagat_flash_success'] : null;
    $data['flash_error'] = isset($_SESSION['laagat_flash_error']) ? (string)$_SESSION['laagat_flash_error'] : null;
    unset($_SESSION['laagat_flash_success'], $_SESSION['laagat_flash_error']);

    $data['venue_options'] = $this->LaagatRentM->get_all_venues_from_raza_forms();

    $defaultYear = !empty($year_ranges) ? $year_ranges[count($year_ranges) - 1] : '';
    $data['form'] = [
      'id' => null,
      'title' => '',
      'hijri_year' => $defaultYear,
      'charge_type' => 'laagat',
      'amount' => '',
      'venue' => '',
      'raza_type_id' => '',
      'raza_type_name' => '',
      'raza_types' => [],
      'rent_sabeel' => '',
      'deposit_sabeel' => '',
      'rent_non_sabeel' => '',
      'deposit_non_sabeel' => ''
    ];

    // Edit mode: /admin/laagat/create?edit={id}
    $editId = (int)$this->input->get('edit');
    if ($editId > 0) {
      $row = $this->LaagatRentM->get_by_id($editId);
      if ($row && $row['charge_type'] === 'laagat') {
        $razaTypes = $this->LaagatRentM->get_raza_types_for_record($editId);
        $data['form'] = [
          'id' => (int)$row['id'],
          'title' => (string)$row['title'],
          'hijri_year' => (string)$row['hijri_year'],
          'charge_type' => (string)$row['charge_type'],
          'amount' => isset($row['amount']) ? (string)$row['amount'] : '',
          'venue' => isset($row['venue']) ? (string)$row['venue'] : '',
          'raza_type_id' => isset($row['raza_type_id']) ? (string)$row['raza_type_id'] : '',
          'raza_type_name' => isset($row['raza_type_name']) ? (string)$row['raza_type_name'] : '',
          'raza_types' => is_array($razaTypes) ? $razaTypes : [],
          'rent_sabeel' => isset($row['rent_sabeel']) ? (string)$row['rent_sabeel'] : '',
          'deposit_sabeel' => isset($row['deposit_sabeel']) ? (string)$row['deposit_sabeel'] : '',
          'rent_non_sabeel' => isset($row['rent_non_sabeel']) ? (string)$row['rent_non_sabeel'] : '',
          'deposit_non_sabeel' => isset($row['deposit_non_sabeel']) ? (string)$row['deposit_non_sabeel'] : ''
        ];
      }
    }

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/LaagatRent', $data);
  }

  public function rent_create()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['module_type'] = 'rent';

    $this->load->model('LaagatRentM');

    // Build Hijri financial year ranges like 1442-43 up to current hijri year.
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $current_hijri_year = null;
    if (isset($today_hijri['hijri_date'])) {
      $parts = explode('-', (string)$today_hijri['hijri_date']);
      if (count($parts) === 3 && is_numeric($parts[2])) {
        $current_hijri_year = (int)$parts[2];
      }
    }
    if (!$current_hijri_year) {
      $current_hijri_year = 1442;
    }
    $start_year = 1442;
    $end_year = max($start_year, (int)$current_hijri_year);
    $year_ranges = [];
    for ($y = $start_year; $y <= $end_year; $y++) {
      $year_ranges[] = $y . '-' . substr((string)($y + 1), -2);
    }
    $data['hijri_year_options'] = $year_ranges;

    $data['flash_success'] = isset($_SESSION['laagat_flash_success']) ? (string)$_SESSION['laagat_flash_success'] : null;
    $data['flash_error'] = isset($_SESSION['laagat_flash_error']) ? (string)$_SESSION['laagat_flash_error'] : null;
    unset($_SESSION['laagat_flash_success'], $_SESSION['laagat_flash_error']);

    $data['venue_options'] = $this->LaagatRentM->get_all_venues_from_raza_forms();

    $defaultYear = !empty($year_ranges) ? $year_ranges[count($year_ranges) - 1] : '';
    $data['form'] = [
      'id' => null,
      'title' => '',
      'hijri_year' => $defaultYear,
      'charge_type' => 'rent',
      'amount' => '',
      'venue' => '',
      'raza_type_id' => '',
      'raza_type_name' => '',
      'raza_types' => [],
      'rent_sabeel' => '',
      'deposit_sabeel' => '',
      'rent_non_sabeel' => '',
      'deposit_non_sabeel' => '',
      'is_per_thaal' => 0,
      'thaal_ranges' => [],
      'items' => []
    ];

    // Edit mode: /admin/rent/create?edit={id}
    $editId = (int)$this->input->get('edit');
    if ($editId > 0) {
      $row = $this->LaagatRentM->get_by_id($editId);
      if ($row && $row['charge_type'] === 'rent') {
        $razaTypes = $this->LaagatRentM->get_raza_types_for_record($editId);
        $data['form'] = [
          'id' => (int)$row['id'],
          'title' => (string)$row['title'],
          'hijri_year' => (string)$row['hijri_year'],
          'charge_type' => (string)$row['charge_type'],
          'amount' => isset($row['amount']) ? (string)$row['amount'] : '',
          'venue' => isset($row['venue']) ? (string)$row['venue'] : '',
          'raza_type_id' => isset($row['raza_type_id']) ? (string)$row['raza_type_id'] : '',
          'raza_type_name' => isset($row['raza_type_name']) ? (string)$row['raza_type_name'] : '',
          'raza_types' => is_array($razaTypes) ? $razaTypes : [],
          'rent_sabeel' => isset($row['rent_sabeel']) ? (string)$row['rent_sabeel'] : '',
          'deposit_sabeel' => isset($row['deposit_sabeel']) ? (string)$row['deposit_sabeel'] : '',
          'rent_non_sabeel' => isset($row['rent_non_sabeel']) ? (string)$row['rent_non_sabeel'] : '',
          'deposit_non_sabeel' => isset($row['deposit_non_sabeel']) ? (string)$row['deposit_non_sabeel'] : '',
          'is_per_thaal' => isset($row['is_per_thaal']) ? (int)$row['is_per_thaal'] : 0,
          'thaal_ranges' => isset($row['thaal_ranges']) ? $row['thaal_ranges'] : [],
          'items' => isset($row['items']) ? $row['items'] : []
        ];
      }
    }

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/LaagatRent', $data);
  }

  public function laagat_manage()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['module_type'] = 'laagat';

    $this->load->model('LaagatRentM');

    // Filters (GET)
    $hijri_year = $this->input->get('hijri_year');
    if ($hijri_year === null) {
      $hijri_year = '1447-48';
    } else {
      $hijri_year = trim((string)$hijri_year);
    }

    $filters = [
      'title' => trim((string)$this->input->get('title')),
      'hijri_year' => $hijri_year,
      'charge_type' => 'laagat',
      'raza_category' => trim((string)$this->input->get('raza_category')),
    ];
    $data['filters'] = $filters;

    // Hijri year dropdown options (descending)
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $current_hijri_year = null;
    if (isset($today_hijri['hijri_date'])) {
      $parts = explode('-', (string)$today_hijri['hijri_date']);
      if (count($parts) === 3 && is_numeric($parts[2])) {
        $current_hijri_year = (int)$parts[2];
      }
    }
    if (!$current_hijri_year) {
      $current_hijri_year = 1442;
    }
    $start_year = 1442;
    $end_year = max($start_year, (int)$current_hijri_year);
    $year_ranges = [];
    for ($y = $start_year; $y <= $end_year; $y++) {
      $year_ranges[] = $y . '-' . substr((string)($y + 1), -2);
    }
    $data['hijri_year_options'] = array_reverse($year_ranges);

    $data['flash_success'] = isset($_SESSION['laagat_flash_success']) ? (string)$_SESSION['laagat_flash_success'] : null;
    $data['flash_error'] = isset($_SESSION['laagat_flash_error']) ? (string)$_SESSION['laagat_flash_error'] : null;
    unset($_SESSION['laagat_flash_success'], $_SESSION['laagat_flash_error']);

    $data['rows'] = $this->LaagatRentM->get_all($filters);
    foreach ($data['rows'] as &$row) {
      $row['has_invoices'] = $this->LaagatRentM->has_invoices($row['id']);
    }

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/LaagatRentManage', $data);
  }

  public function rent_manage()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['module_type'] = 'rent';

    $this->load->model('LaagatRentM');

    // Filters (GET)
    $hijri_year = $this->input->get('hijri_year');
    if ($hijri_year === null) {
      $hijri_year = '1447-48';
    } else {
      $hijri_year = trim((string)$hijri_year);
    }

    $filters = [
      'title' => trim((string)$this->input->get('title')),
      'hijri_year' => $hijri_year,
      'charge_type' => 'rent',
      'raza_category' => trim((string)$this->input->get('raza_category')),
    ];
    $data['filters'] = $filters;

    // Hijri year dropdown options (descending)
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $current_hijri_year = null;
    if (isset($today_hijri['hijri_date'])) {
      $parts = explode('-', (string)$today_hijri['hijri_date']);
      if (count($parts) === 3 && is_numeric($parts[2])) {
        $current_hijri_year = (int)$parts[2];
      }
    }
    if (!$current_hijri_year) {
      $current_hijri_year = 1442;
    }
    $start_year = 1442;
    $end_year = max($start_year, (int)$current_hijri_year);
    $year_ranges = [];
    for ($y = $start_year; $y <= $end_year; $y++) {
      $year_ranges[] = $y . '-' . substr((string)($y + 1), -2);
    }
    $data['hijri_year_options'] = array_reverse($year_ranges);

    $data['flash_success'] = isset($_SESSION['laagat_flash_success']) ? (string)$_SESSION['laagat_flash_success'] : null;
    $data['flash_error'] = isset($_SESSION['laagat_flash_error']) ? (string)$_SESSION['laagat_flash_error'] : null;
    unset($_SESSION['laagat_flash_success'], $_SESSION['laagat_flash_error']);

    $data['rows'] = $this->LaagatRentM->get_all($filters);
    foreach ($data['rows'] as &$row) {
      $row['has_invoices'] = $this->LaagatRentM->has_invoices($row['id']);
      // Load rent items for each row
      $row['items'] = $this->db
        ->from('laagat_rent_items')
        ->where('laagat_rent_id', (int)$row['id'])
        ->order_by('id ASC')
        ->get()
        ->result_array();
    }

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/LaagatRentManage', $data);
  }

  public function laagat_save()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('LaagatRentM');

    if (strtoupper((string)$this->input->method(TRUE)) !== 'POST') {
      redirect('admin/laagat/create');
      return;
    }

    // Rebuild Hijri year options for validation
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $current_hijri_year = null;
    if (isset($today_hijri['hijri_date'])) {
      $parts = explode('-', (string)$today_hijri['hijri_date']);
      if (count($parts) === 3 && is_numeric($parts[2])) {
        $current_hijri_year = (int)$parts[2];
      }
    }
    if (!$current_hijri_year) {
      $current_hijri_year = 1442;
    }
    $start_year = 1442;
    $end_year = max($start_year, (int)$current_hijri_year);
    $year_ranges = [];
    for ($y = $start_year; $y <= $end_year; $y++) {
      $year_ranges[] = $y . '-' . substr((string)($y + 1), -2);
    }

    $id = (int)$this->input->post('id');
    $title = trim((string)$this->input->post('title'));
    $hijriYear = trim((string)$this->input->post('hijri_year'));
    $chargeType = strtolower(trim((string)$this->input->post('charge_type')));
    $amountRaw = $this->input->post('amount');
    if ($amountRaw === null || $amountRaw === '') {
      $amountRaw = '0.00';
    }
    $postedRazaTypeIds = $this->input->post('raza_type_ids');
    if (!is_array($postedRazaTypeIds)) {
      $postedRazaTypeIds = [];
    }
    $razaTypeIds = [];
    foreach ($postedRazaTypeIds as $rid) {
      $rid = (int)$rid;
      if ($rid > 0) {
        $razaTypeIds[$rid] = $rid;
      }
    }
    $razaTypeIds = array_values($razaTypeIds);
    $razaTypeId = !empty($razaTypeIds) ? (int)$razaTypeIds[0] : 0;

    $rentSabeel = $this->input->post('rent_sabeel') !== null && $this->input->post('rent_sabeel') !== '' ? (float)$this->input->post('rent_sabeel') : 0.00;
    $depositSabeel = $this->input->post('deposit_sabeel') !== null && $this->input->post('deposit_sabeel') !== '' ? (float)$this->input->post('deposit_sabeel') : 0.00;
    $rentNonSabeel = $this->input->post('rent_non_sabeel') !== null && $this->input->post('rent_non_sabeel') !== '' ? (float)$this->input->post('rent_non_sabeel') : 0.00;
    $depositNonSabeel = $this->input->post('deposit_non_sabeel') !== null && $this->input->post('deposit_non_sabeel') !== '' ? (float)$this->input->post('deposit_non_sabeel') : 0.00;
    $isPerThaal = $this->input->post('is_per_thaal') !== null ? (int)$this->input->post('is_per_thaal') : 0;

    $valid = true;
    if ($title === '') {
      $valid = false;
    }
    if ($hijriYear === '' || !in_array($hijriYear, $year_ranges, true)) {
      $valid = false;
    }
    if (!in_array($chargeType, ['laagat', 'rent'], true)) {
      $valid = false;
    }
    if ($amountRaw === null || $amountRaw === '' || !is_numeric($amountRaw) || (float)$amountRaw < 0) {
      $valid = false;
    }
    if ($razaTypeId <= 0) {
      $valid = false;
    }
    if ($chargeType === 'rent') {
      if ($rentSabeel < 0 || $depositSabeel < 0 || $rentNonSabeel < 0 || $depositNonSabeel < 0) {
        $valid = false;
      }
    }

    if (!$valid) {
      $_SESSION['laagat_flash_error'] = 'Please fill Title, Hijri Year, Charge Type and Applicable Raza Categories.';
      $redir = $id > 0 ? ('admin/' . $chargeType . '/create?edit=' . $id) : ('admin/' . $chargeType . '/create');
      redirect($redir);
      return;
    }

    $gradeJamaatAmounts = $this->input->post('grade_jamaat_amounts');
    if (!is_array($gradeJamaatAmounts)) {
      $gradeJamaatAmounts = [];
    }
    $gradeSarkaarAmounts = $this->input->post('grade_sarkaar_amounts');
    if (!is_array($gradeSarkaarAmounts)) {
      $gradeSarkaarAmounts = [];
    }

    $gradeAmounts = [];
    $allGradeIds = array_unique(array_merge(array_keys($gradeJamaatAmounts), array_keys($gradeSarkaarAmounts)));
    foreach ($allGradeIds as $gradeId) {
      $jAmt = isset($gradeJamaatAmounts[$gradeId]) ? (float)$gradeJamaatAmounts[$gradeId] : 0.00;
      $sAmt = isset($gradeSarkaarAmounts[$gradeId]) ? (float)$gradeSarkaarAmounts[$gradeId] : 0.00;
      $gradeAmounts[$gradeId] = $jAmt + $sAmt;
    }

    if ($chargeType === 'rent') {
      $gradeJamaatAmounts = [];
      $gradeSarkaarAmounts = [];
      $gradeAmounts = [];
    }

    $venue = ($chargeType === 'rent') ? trim((string)$this->input->post('venue')) : null;
    if ($venue === '') {
      $venue = null;
    }

    $payload = [
      'title' => $title,
      'hijri_year' => $hijriYear,
      'charge_type' => $chargeType,
      'amount' => (float)$amountRaw,
      'venue' => $venue,
      'raza_type_id' => $razaTypeId,
      'raza_type_ids' => $razaTypeIds,
      'grade_amounts' => $gradeAmounts,
      'grade_jamaat_amounts' => $gradeJamaatAmounts,
      'grade_sarkaar_amounts' => $gradeSarkaarAmounts,
      'rent_sabeel' => $rentSabeel,
      'deposit_sabeel' => $depositSabeel,
      'rent_non_sabeel' => $rentNonSabeel,
      'deposit_non_sabeel' => $depositNonSabeel,
      'is_per_thaal' => $isPerThaal,
      'thaal_ranges' => [],
      'items' => [],
    ];

    if ($chargeType === 'rent' && $isPerThaal === 1) {
      $rangeThaalMin = $this->input->post('range_thaal_min');
      $rangeThaalMax = $this->input->post('range_thaal_max');
      $rangeRentSabeel = $this->input->post('range_rent_sabeel');
      $rangeDepositSabeel = $this->input->post('range_deposit_sabeel');
      $rangeRentNonSabeel = $this->input->post('range_rent_non_sabeel');
      $rangeDepositNonSabeel = $this->input->post('range_deposit_non_sabeel');

      if (is_array($rangeThaalMin)) {
        foreach ($rangeThaalMin as $idx => $tMin) {
          $tMin = (int)$tMin;
          $tMax = isset($rangeThaalMax[$idx]) ? (int)$rangeThaalMax[$idx] : 0;
          if ($tMin > 0 && $tMax >= $tMin) {
            $payload['thaal_ranges'][] = [
              'thaal_min' => $tMin,
              'thaal_max' => $tMax,
              'rent_sabeel' => isset($rangeRentSabeel[$idx]) ? (float)$rangeRentSabeel[$idx] : 0.00,
              'deposit_sabeel' => isset($rangeDepositSabeel[$idx]) ? (float)$rangeDepositSabeel[$idx] : 0.00,
              'rent_non_sabeel' => isset($rangeRentNonSabeel[$idx]) ? (float)$rangeRentNonSabeel[$idx] : 0.00,
              'deposit_non_sabeel' => isset($rangeDepositNonSabeel[$idx]) ? (float)$rangeDepositNonSabeel[$idx] : 0.00,
            ];
          }
        }
      }
    }

    if ($chargeType === 'rent') {
      $itemNames = $this->input->post('item_name');
      $itemCostPerPiece = $this->input->post('item_rent_sabeel'); // single cost field, same for all

      if (is_array($itemNames)) {
        foreach ($itemNames as $idx => $iName) {
          $iName = trim((string)$iName);
          if ($iName !== '') {
            $costPerPiece = isset($itemCostPerPiece[$idx]) ? (float)$itemCostPerPiece[$idx] : 0.00;
            $payload['items'][] = [
              'item_name'          => $iName,
              'rent_sabeel'        => $costPerPiece,
              'deposit_sabeel'     => 0.00,
              'rent_non_sabeel'    => $costPerPiece,
              'deposit_non_sabeel' => 0.00,
            ];
          }
        }
      }
    }

    if ($id > 0) {
      $res = $this->LaagatRentM->update($id, $payload);
      if (empty($res['success'])) {
        $_SESSION['laagat_flash_error'] = !empty($res['error']) ? (string)$res['error'] : 'Unable to update record.';
        redirect('admin/' . $chargeType . '/create?edit=' . $id);
        return;
      }
      $ctLabel = ucfirst($chargeType);
      $_SESSION['laagat_flash_success'] = $ctLabel . ' updated.';
    } else {
      $res = $this->LaagatRentM->create($payload);
      if (empty($res['success'])) {
        $_SESSION['laagat_flash_error'] = !empty($res['error']) ? (string)$res['error'] : 'Unable to create record.';
        redirect('admin/' . $chargeType . '/create');
        return;
      }
      $ctLabel = ucfirst($chargeType);
      $_SESSION['laagat_flash_success'] = $ctLabel . ' created.';
    }

    redirect('admin/' . $chargeType . '/manage');
  }

  public function laagat_toggle()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('LaagatRentM');

    if (strtoupper((string)$this->input->method(TRUE)) !== 'POST') {
      redirect('admin/laagat/manage');
      return;
    }

    $id = (int)$this->input->post('id');
    if ($id <= 0) {
      $_SESSION['laagat_flash_error'] = 'Invalid record.';
      redirect('admin/laagat/manage');
      return;
    }

    $res = $this->LaagatRentM->toggle_active($id);
    $chargeType = !empty($res['charge_type']) ? $res['charge_type'] : 'laagat';
    if (!empty($res['success'])) {
      $ctLabel = ucfirst($chargeType);
      $_SESSION['laagat_flash_success'] = $ctLabel . (((int)$res['is_active'] === 1) ? ' activated.' : ' deactivated.');
    } else {
      $_SESSION['laagat_flash_error'] = !empty($res['error']) ? (string)$res['error'] : 'Unable to update.';
    }
    redirect('admin/' . $chargeType . '/manage');
  }

  public function laagat_delete()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('LaagatRentM');

    if (strtoupper((string)$this->input->method(TRUE)) !== 'POST') {
      redirect('admin/laagat/manage');
      return;
    }

    $id = (int)$this->input->post('id');
    if ($id <= 0) {
      $_SESSION['laagat_flash_error'] = 'Invalid record.';
      redirect('admin/laagat/manage');
      return;
    }

    $row = $this->LaagatRentM->get_by_id($id);
    $chargeType = !empty($row['charge_type']) ? $row['charge_type'] : 'laagat';

    if ($this->LaagatRentM->has_invoices($id)) {
      $_SESSION['laagat_flash_error'] = 'This ' . ucfirst($chargeType) . ' cannot be deleted because invoices exist for this record.';
      redirect('admin/' . $chargeType . '/manage');
      return;
    }

    $ok = $this->LaagatRentM->delete($id);
    $ctLabel = ucfirst($chargeType);
    $_SESSION['laagat_flash_' . ($ok ? 'success' : 'error')] = $ok ? ($ctLabel . ' deleted.') : ('Unable to delete ' . strtolower($ctLabel) . '.');
    redirect('admin/' . $chargeType . '/manage');
  }

  public function rent_check_duplicate()
  {
    $this->laagat_check_duplicate();
  }

  // AJAX: Raza Categories for autocomplete, filtered by charge type
  public function laagat_raza_categories()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('LaagatRentM');

    $chargeType = (string)$this->input->get('charge_type');
    $term = (string)$this->input->get('term');
    $items = $this->LaagatRentM->search_raza_categories($chargeType, $term);

    $out = [];
    foreach ($items as $r) {
      $out[] = [
        'id' => (int)$r['id'],
        'name' => (string)$r['name'],
        'umoor' => (string)$r['umoor'],
      ];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode([
      'success' => true,
      'items' => $out
    ]));
  }

  // AJAX: Check whether a Laagat/Rent record already exists for same Hijri Year + Charge Type
  // with overlapping Applicable Raza Categories.
  public function laagat_check_duplicate()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('LaagatRentM');

    if (strtoupper((string)$this->input->method(TRUE)) !== 'POST') {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['success' => false, 'error' => 'Invalid request']));
      return;
    }

    $excludeId = (int)$this->input->post('id');
    $hijriYear = trim((string)$this->input->post('hijri_year'));
    $chargeType = strtolower(trim((string)$this->input->post('charge_type')));

    $postedRazaTypeIds = $this->input->post('raza_type_ids');
    if (!is_array($postedRazaTypeIds)) {
      $postedRazaTypeIds = [];
    }
    $razaTypeIds = [];
    foreach ($postedRazaTypeIds as $rid) {
      $rid = (int)$rid;
      if ($rid > 0) {
        $razaTypeIds[$rid] = $rid;
      }
    }
    $razaTypeIds = array_values($razaTypeIds);

    $venue = ($chargeType === 'rent') ? trim((string)$this->input->post('venue')) : null;
    if ($venue === '') {
      $venue = null;
    }

    $dupId = $this->LaagatRentM->check_duplicate_overlap($hijriYear, $chargeType, $razaTypeIds, $excludeId, $venue);
    $exists = $dupId > 0;

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => true,
        'exists' => $exists,
        'duplicate_id' => (int)$dupId,
        'message' => $exists ? "Raza already exist. You can't create same Raza." : '',
      ]));
  }

  // AJAX: Get Residential grades and saved amounts for a Hijri Year
  public function laagat_get_grade_amounts()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('LaagatRentM');

    $year = (string)$this->input->get('year');
    $laagatRentId = (int)$this->input->get('laagat_rent_id');

    $isRent = false;
    $chargeType = 'laagat';
    if ($laagatRentId > 0) {
      $lrType = $this->db->select('charge_type')->from('laagat_rent')->where('id', $laagatRentId)->get()->row_array();
      if ($lrType) {
        $chargeType = $lrType['charge_type'];
        if ($chargeType === 'rent') {
          $isRent = true;
        }
      }
    }

    $grades = [];
    if ($year !== '' && !$isRent) {
      $grades = $this->LaagatRentM->get_grade_amounts_for_year($year, $laagatRentId);
    }

    $masterAmount = 0.00;
    $rentSabeel = 0.00;
    $rentNonSabeel = 0.00;
    $depositSabeel = 0.00;
    $depositNonSabeel = 0.00;

    $isPerThaal = 0;
    $thaalRanges = [];
    if ($laagatRentId > 0) {
      $lr = $this->db->select('amount, rent_sabeel, rent_non_sabeel, deposit_sabeel, deposit_non_sabeel, is_per_thaal')->from('laagat_rent')->where('id', $laagatRentId)->get()->row_array();
      if ($lr) {
        $masterAmount = (float)$lr['amount'];
        $rentSabeel = (float)$lr['rent_sabeel'];
        $rentNonSabeel = (float)$lr['rent_non_sabeel'];
        $depositSabeel = (float)$lr['deposit_sabeel'];
        $depositNonSabeel = (float)$lr['deposit_non_sabeel'];
        $isPerThaal = (int)$lr['is_per_thaal'];

        if ($isPerThaal === 1) {
          $thaalRanges = $this->db
            ->from('laagat_rent_thaal_ranges')
            ->where('laagat_rent_id', $laagatRentId)
            ->order_by('thaal_min ASC')
            ->get()
            ->result_array();
        }
      }
    }

    $items = [];
    if ($laagatRentId > 0 && $isRent) {
      $items = $this->db
        ->from('laagat_rent_items')
        ->where('laagat_rent_id', $laagatRentId)
        ->order_by('id ASC')
        ->get()
        ->result_array();
    }

    $this->output->set_content_type('application/json')->set_output(json_encode([
      'success' => true,
      'master_amount' => $masterAmount,
      'charge_type' => $chargeType,
      'grades' => $grades,
      'rent_sabeel' => $rentSabeel,
      'rent_non_sabeel' => $rentNonSabeel,
      'deposit_sabeel' => $depositSabeel,
      'deposit_non_sabeel' => $depositNonSabeel,
      'is_per_thaal' => $isPerThaal,
      'thaal_ranges' => $thaalRanges,
      'items' => $items
    ]));
  }

  // Properties Management
  public function properties()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->model('PropertyM');
    $data['properties'] = $this->PropertyM->get_all();

    $data['flash_success'] = $this->session->flashdata('flash_success');
    $data['flash_error'] = $this->session->flashdata('flash_error');

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/PropertiesList', $data);
  }

  public function add_property()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('PropertyM');

    if (strtoupper((string)$this->input->method(TRUE)) !== 'POST') {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid request method.']));
      return;
    }

    $name = trim((string)$this->input->post('name'));
    if ($name === '') {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Property name is required.']));
      return;
    }

    if ($this->PropertyM->exists($name)) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Property name already exists.']));
      return;
    }

    $inserted_id = $this->PropertyM->create($name);
    if ($inserted_id) {
      $this->session->set_flashdata('flash_success', 'Property added successfully');
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true]));
    } else {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Unable to save property.']));
    }
  }

  public function update_property_name()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('PropertyM');

    if (strtoupper((string)$this->input->method(TRUE)) !== 'POST') {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid request method.']));
      return;
    }

    $id = (int)$this->input->post('rowId');
    $name = trim((string)$this->input->post('propertyName'));

    if ($id <= 0) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid property ID.']));
      return;
    }

    if ($name === '') {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Property name is required.']));
      return;
    }

    if ($this->PropertyM->exists($name, $id)) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Property name already exists.']));
      return;
    }

    $ok = $this->PropertyM->update($id, $name);
    if ($ok) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true]));
    } else {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Unable to update property.']));
    }
  }

  public function delete_property($id = null)
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('PropertyM');

    $id = (int)$id;
    if ($id <= 0) {
      $this->session->set_flashdata('flash_error', 'Invalid property ID.');
      redirect('admin/properties');
      return;
    }

    $property = $this->PropertyM->get_by_id($id);
    if (!$property) {
      $this->session->set_flashdata('flash_error', 'Property not found.');
      redirect('admin/properties');
      return;
    }

    // Check if property is used in laagat_rent
    $this->load->model('LaagatRentM');
    $count = $this->db->where('venue', $property['name'])->count_all_results('laagat_rent');
    if ($count > 0) {
      $this->session->set_flashdata('flash_error', 'Cannot delete property because it is currently used as a venue in Laagat/Rent records.');
      redirect('admin/properties');
      return;
    }

    $ok = $this->PropertyM->delete($id);
    if ($ok) {
      $this->session->set_flashdata('flash_success', 'Property deleted successfully.');
    } else {
      $this->session->set_flashdata('flash_error', 'Unable to delete property.');
    }
    redirect('admin/properties');
  }

  public function save_raza_fields($id)
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('AdminM');

    $id = (int)$id;
    if ($id <= 0) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid Raza ID.']));
      return;
    }

    $raza = $this->AdminM->get_razatype_byid($id);
    if (empty($raza) || empty($raza[0])) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Raza type not found.']));
      return;
    }
    $raza = $raza[0];

    $fieldsJson = $this->input->post('fields');
    $payload = json_decode($fieldsJson, true);

    if (!is_array($payload) || !isset($payload['fields']) || !is_array($payload['fields'])) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Invalid payload structure.']));
      return;
    }

    // Decode existing fields to preserve any other keys (e.g. umoor, name, id)
    $existing = json_decode($raza['fields'], true);
    if (!is_array($existing)) {
      $existing = [];
    }

    $existing['fields'] = $payload['fields'];
    // Update name and id in JSON to keep it consistent
    $existing['id'] = $id;
    $existing['name'] = $raza['name'];
    if (isset($raza['umoor'])) {
      $existing['umoor'] = $raza['umoor'];
    }

    $flag = $this->AdminM->update_raza_type($id, json_encode($existing));
    if ($flag) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true]));
    } else {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => false, 'error' => 'Unable to save form layout.']));
    }
  }

  // Qardan Hasana schemes
  // - /admin/qardanhasana
  // - /admin/qardanhasana/mohammedi | taher | husain
  // - /admin/qardanhasana/mohammedi/import (POST)
  // - /admin/qardanhasana/mohammedi/template (GET)
  // - /admin/qardanhasana/mohammedi/delete/{id} (POST)
  // - /admin/qardanhasana/mohammedi/update/{id} (POST)
  // - /admin/qardanhasana/taher/import (POST)
  // - /admin/qardanhasana/taher/template (GET)
  // - /admin/qardanhasana/taher/delete/{id} (POST)
  // - /admin/qardanhasana/taher/update/{id} (POST)
  // - /admin/qardanhasana/husain/import (POST)
  // - /admin/qardanhasana/husain/template (GET)
  // - /admin/qardanhasana/husain/delete/{id} (POST)
  // - /admin/qardanhasana/husain/update/{id} (POST)
  public function qardanhasana($scheme = null, $action = null, $id = null)
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $scheme = $scheme !== null ? strtolower(trim((string)$scheme)) : null;
    $action = $action !== null ? strtolower(trim((string)$action)) : null;
    $allowed = ['mohammedi', 'taher', 'husain'];

    // Mohammedi scheme: downloadable CSV import template
    // Must run before rendering any HTML to keep CSV output clean.
    if ($scheme === 'mohammedi' && $action === 'template') {
      $this->qh_download_mohammedi_import_template_csv();
      return;
    }

    // Taher scheme: downloadable CSV import template
    // Must run before rendering any HTML to keep CSV output clean.
    if ($scheme === 'taher' && $action === 'template') {
      $this->qh_download_taher_import_template_csv();
      return;
    }

    // Husain scheme: downloadable CSV import template
    // Must run before rendering any HTML to keep CSV output clean.
    if ($scheme === 'husain' && $action === 'template') {
      $this->qh_download_husain_import_template_csv();
      return;
    }

    $this->load->view('Admin/Header', $data);

    if ($scheme === null || $scheme === '') {
      $this->load->view('Admin/QardanHasana', $data);
      return;
    }

    if (!in_array($scheme, $allowed, true)) {
      redirect('admin/qardanhasana');
      return;
    }

    $titles = [
      'mohammedi' => 'Mohammedi Scheme',
      'taher' => 'Taher Scheme',
      'husain' => 'Husain Scheme'
    ];
    $data['scheme_key'] = $scheme;
    $data['scheme_title'] = $titles[$scheme] ?? ucfirst($scheme) . ' Scheme';

    // Mohammedi scheme: delete record
    if ($scheme === 'mohammedi' && $action === 'delete') {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin/qardanhasana/mohammedi');
        return;
      }
      $recordId = (int)$id;
      if ($recordId <= 0) {
        $this->session->set_flashdata('qh_import_error', 'Invalid record.');
        redirect('admin/qardanhasana/mohammedi');
        return;
      }

      $this->load->model('QardanHasanaM');
      $ok = $this->QardanHasanaM->delete_mohammedi_record($recordId);
      if ($ok) {
        $this->session->set_flashdata('qh_import_message', 'Record deleted successfully.');
      } else {
        $this->session->set_flashdata('qh_import_error', 'Could not delete record.');
      }
      redirect('admin/qardanhasana/mohammedi');
      return;
    }

    // Mohammedi scheme: update record
    if ($scheme === 'mohammedi' && $action === 'update') {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin/qardanhasana/mohammedi');
        return;
      }

      $recordId = (int)$id;
      if ($recordId <= 0) {
        $this->session->set_flashdata('qh_import_error', 'Invalid record.');
        redirect('admin/qardanhasana/mohammedi');
        return;
      }

      $payload = [
        'miqaat_name' => trim((string)$this->input->post('miqaat_name')),
        'hijri_date' => trim((string)$this->input->post('hijri_date')),
        'eng_date' => trim((string)$this->input->post('eng_date')),
        'collection_amount' => $this->input->post('collection_amount')
      ];

      $this->load->model('QardanHasanaM');
      $res = $this->QardanHasanaM->update_mohammedi_record($recordId, $payload);
      if (!empty($res['success'])) {
        $this->session->set_flashdata('qh_import_message', 'Record updated successfully.');
      } else {
        $err = !empty($res['error']) ? (string)$res['error'] : 'Could not update record.';
        $this->session->set_flashdata('qh_import_error', $err);
      }

      redirect('admin/qardanhasana/mohammedi');
      return;
    }

    // Mohammedi scheme: import action
    if ($scheme === 'mohammedi' && $action === 'import') {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fileName = isset($_FILES['import_file']['name']) ? (string) $_FILES['import_file']['name'] : '';
        $fileSize = isset($_FILES['import_file']['size']) ? (int) $_FILES['import_file']['size'] : 0;
        $tmpPath = isset($_FILES['import_file']['tmp_name']) ? (string) $_FILES['import_file']['tmp_name'] : '';
        $errCode = isset($_FILES['import_file']['error']) ? (int) $_FILES['import_file']['error'] : 0;

        if ($errCode !== UPLOAD_ERR_OK) {
          $this->session->set_flashdata('qh_import_error', 'Upload failed. Please try again.');
        } elseif ($fileName === '' || $fileSize <= 0 || $tmpPath === '') {
          $this->session->set_flashdata('qh_import_error', 'Please choose a CSV file to upload.');
        } else {
          $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          if ($ext !== 'csv') {
            $this->session->set_flashdata('qh_import_error', 'Only CSV files are supported.');
          } else {
            $this->load->model('QardanHasanaM');
            $importRes = $this->QardanHasanaM->import_mohammedi_csv($tmpPath);
            if (!empty($importRes['success'])) {
              $msg = 'Imported ' . ((int)($importRes['inserted'] ?? 0)) . ' new records.';
              $updated = (int)($importRes['updated'] ?? 0);
              if ($updated > 0) {
                $msg .= ' Updated ' . $updated . ' existing records.';
              }
              if (!empty($importRes['skipped'])) {
                $msg .= ' Skipped ' . ((int)$importRes['skipped']) . ' rows.';
              }
              $this->session->set_flashdata('qh_import_message', $msg);
              if (!empty($importRes['errors'])) {
                $this->session->set_flashdata('qh_import_error', implode(' | ', array_slice($importRes['errors'], 0, 3)));
              }
            } else {
              $this->session->set_flashdata('qh_import_error', 'Import failed.');
            }
          }
        }
      }
      redirect('admin/qardanhasana/mohammedi');
      return;
    }

    // Husain scheme: delete record
    if ($scheme === 'husain' && $action === 'delete') {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin/qardanhasana/husain');
        return;
      }
      $recordId = (int)$id;
      if ($recordId <= 0) {
        $this->session->set_flashdata('qh_import_error', 'Invalid record.');
        redirect('admin/qardanhasana/husain');
        return;
      }

      $this->load->model('QardanHasanaM');
      $ok = $this->QardanHasanaM->delete_husain_record($recordId);
      if ($ok) {
        $this->session->set_flashdata('qh_import_message', 'Record deleted successfully.');
      } else {
        $this->session->set_flashdata('qh_import_error', 'Could not delete record.');
      }
      redirect('admin/qardanhasana/husain');
      return;
    }

    // Husain scheme: update record
    if ($scheme === 'husain' && $action === 'update') {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin/qardanhasana/husain');
        return;
      }

      $recordId = (int)$id;
      if ($recordId <= 0) {
        $this->session->set_flashdata('qh_import_error', 'Invalid record.');
        redirect('admin/qardanhasana/husain');
        return;
      }

      $payload = [
        'ITS' => trim((string)$this->input->post('ITS')),
        'amount' => $this->input->post('amount'),
        'deposit_date' => trim((string)$this->input->post('deposit_date')),
        'maturity_date' => trim((string)$this->input->post('maturity_date')),
        'duration' => trim((string)$this->input->post('duration'))
      ];

      $this->load->model('QardanHasanaM');
      $res = $this->QardanHasanaM->update_husain_record($recordId, $payload);
      if (!empty($res['success'])) {
        $this->session->set_flashdata('qh_import_message', 'Record updated successfully.');
      } else {
        $err = !empty($res['error']) ? (string)$res['error'] : 'Could not update record.';
        $this->session->set_flashdata('qh_import_error', $err);
      }

      redirect('admin/qardanhasana/husain');
      return;
    }

    // Husain scheme: import action
    if ($scheme === 'husain' && $action === 'import') {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fileName = isset($_FILES['import_file']['name']) ? (string) $_FILES['import_file']['name'] : '';
        $fileSize = isset($_FILES['import_file']['size']) ? (int) $_FILES['import_file']['size'] : 0;
        $tmpPath = isset($_FILES['import_file']['tmp_name']) ? (string) $_FILES['import_file']['tmp_name'] : '';
        $errCode = isset($_FILES['import_file']['error']) ? (int) $_FILES['import_file']['error'] : 0;

        if ($errCode !== UPLOAD_ERR_OK) {
          $this->session->set_flashdata('qh_import_error', 'Upload failed. Please try again.');
        } elseif ($fileName === '' || $fileSize <= 0 || $tmpPath === '') {
          $this->session->set_flashdata('qh_import_error', 'Please choose a CSV file to upload.');
        } else {
          $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          if ($ext !== 'csv') {
            $this->session->set_flashdata('qh_import_error', 'Only CSV files are supported.');
          } else {
            $this->load->model('QardanHasanaM');
            $importRes = $this->QardanHasanaM->import_husain_csv($tmpPath);
            if (!empty($importRes['success'])) {
              $msg = 'Imported ' . ((int)($importRes['inserted'] ?? 0)) . ' new records.';
              $updated = (int)($importRes['updated'] ?? 0);
              if ($updated > 0) {
                $msg .= ' Updated ' . $updated . ' existing records.';
              }
              if (!empty($importRes['skipped'])) {
                $msg .= ' Skipped ' . ((int)$importRes['skipped']) . ' rows.';
              }
              $this->session->set_flashdata('qh_import_message', $msg);
              if (!empty($importRes['errors'])) {
                $this->session->set_flashdata('qh_import_error', implode(' | ', array_slice($importRes['errors'], 0, 3)));
              }
            } else {
              $this->session->set_flashdata('qh_import_error', 'Import failed.');
            }
          }
        }
      }
      redirect('admin/qardanhasana/husain');
      return;
    }

    // Taher scheme: delete record
    if ($scheme === 'taher' && $action === 'delete') {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin/qardanhasana/taher');
        return;
      }
      $recordId = (int)$id;
      if ($recordId <= 0) {
        $this->session->set_flashdata('qh_import_error', 'Invalid record.');
        redirect('admin/qardanhasana/taher');
        return;
      }

      $this->load->model('QardanHasanaM');
      $ok = $this->QardanHasanaM->delete_taher_record($recordId);
      if ($ok) {
        $this->session->set_flashdata('qh_import_message', 'Record deleted successfully.');
      } else {
        $this->session->set_flashdata('qh_import_error', 'Could not delete record.');
      }
      redirect('admin/qardanhasana/taher');
      return;
    }

    // Taher scheme: update record
    if ($scheme === 'taher' && $action === 'update') {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin/qardanhasana/taher');
        return;
      }

      $recordId = (int)$id;
      if ($recordId <= 0) {
        $this->session->set_flashdata('qh_import_error', 'Invalid record.');
        redirect('admin/qardanhasana/taher');
        return;
      }

      $payload = [
        'ITS' => trim((string)$this->input->post('ITS')),
        'unit' => $this->input->post('unit'),
        'units' => $this->input->post('units'),
        'miqaat_name' => trim((string)$this->input->post('miqaat_name'))
      ];

      if ($payload['ITS'] === '' || $payload['miqaat_name'] === '') {
        $this->session->set_flashdata('qh_import_error', 'Please fill all required fields.');
        redirect('admin/qardanhasana/taher');
        return;
      }

      $this->load->model('QardanHasanaM');
      $res = $this->QardanHasanaM->update_taher_record($recordId, $payload);
      if (!empty($res['success'])) {
        $this->session->set_flashdata('qh_import_message', 'Record updated successfully.');
      } else {
        $err = !empty($res['error']) ? (string)$res['error'] : 'Could not update record.';
        $this->session->set_flashdata('qh_import_error', $err);
      }

      redirect('admin/qardanhasana/taher');
      return;
    }

    // Taher scheme: import action
    if ($scheme === 'taher' && $action === 'import') {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fileName = isset($_FILES['import_file']['name']) ? (string) $_FILES['import_file']['name'] : '';
        $fileSize = isset($_FILES['import_file']['size']) ? (int) $_FILES['import_file']['size'] : 0;
        $tmpPath = isset($_FILES['import_file']['tmp_name']) ? (string) $_FILES['import_file']['tmp_name'] : '';
        $errCode = isset($_FILES['import_file']['error']) ? (int) $_FILES['import_file']['error'] : 0;

        if ($errCode !== UPLOAD_ERR_OK) {
          $this->session->set_flashdata('qh_import_error', 'Upload failed. Please try again.');
        } elseif ($fileName === '' || $fileSize <= 0 || $tmpPath === '') {
          $this->session->set_flashdata('qh_import_error', 'Please choose a CSV file to upload.');
        } else {
          $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          if ($ext !== 'csv') {
            $this->session->set_flashdata('qh_import_error', 'Only CSV files are supported.');
          } else {
            $this->load->model('QardanHasanaM');
            $importRes = $this->QardanHasanaM->import_taher_csv($tmpPath);
            if (!empty($importRes['success'])) {
              $msg = 'Imported ' . ((int)($importRes['inserted'] ?? 0)) . ' new records.';
              $updated = (int)($importRes['updated'] ?? 0);
              if ($updated > 0) {
                $msg .= ' Updated ' . $updated . ' existing records.';
              }
              if (!empty($importRes['skipped'])) {
                $msg .= ' Skipped ' . ((int)$importRes['skipped']) . ' rows.';
              }
              $this->session->set_flashdata('qh_import_message', $msg);
              if (!empty($importRes['errors'])) {
                $this->session->set_flashdata('qh_import_error', implode(' | ', array_slice($importRes['errors'], 0, 3)));
              }
            } else {
              $this->session->set_flashdata('qh_import_error', 'Import failed.');
            }
          }
        }
      }
      redirect('admin/qardanhasana/taher');
      return;
    }

    // Filters (GET)
    $data['filters'] = [
      'miqaat_id' => $this->input->get('miqaat_id'),
      'hijri_date' => $this->input->get('hijri_date'),
      'greg_date' => $this->input->get('greg_date'),
      'deposit_date' => $this->input->get('deposit_date'),
      'maturity_date' => $this->input->get('maturity_date'),
      'duration' => $this->input->get('duration'),
      'search' => $this->input->get('search'),
      'its' => $this->input->get('its'),
      'member_name' => $this->input->get('member_name')
    ];

    // Miqaat list for filters
    if (in_array($scheme, ['mohammedi', 'taher', 'husain'], true)) {
      // Note: some environments don't have miqaat.hijri_date; fetch it from hijri_calendar instead.
      $data['miqaats'] = $this->db
        ->select('m.id, m.name, m.date, hc.hijri_date')
        ->from('miqaat m')
        ->join('hijri_calendar hc', 'hc.greg_date = m.date', 'left')
        ->order_by('m.date', 'DESC')
        ->get()->result_array();
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
          'search' => isset($data['filters']['search']) ? trim((string)$data['filters']['search']) : '',
          'ITS' => isset($data['filters']['its']) ? trim((string)$data['filters']['its']) : '',
          'member_name' => isset($data['filters']['member_name']) ? trim((string)$data['filters']['member_name']) : ''
        ]);
      } elseif ($scheme === 'husain') {
        $depositDate = isset($data['filters']['deposit_date']) ? trim((string)$data['filters']['deposit_date']) : '';
        if ($depositDate === '') {
          // Backward compatibility: previously greg_date was reused as deposit_date
          $depositDate = isset($data['filters']['greg_date']) ? trim((string)$data['filters']['greg_date']) : '';
        }
        $maturityDate = isset($data['filters']['maturity_date']) ? trim((string)$data['filters']['maturity_date']) : '';
        $duration = isset($data['filters']['duration']) ? trim((string)$data['filters']['duration']) : '';
        $data['records'] = $this->QardanHasanaM->get_husain_records([
          'deposit_date' => $depositDate,
          'maturity_date' => $maturityDate,
          'duration' => $duration,
          'search' => isset($data['filters']['search']) ? trim((string)$data['filters']['search']) : '',
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
            // Mohammedi/Taher store amount in collection_amount
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
    }
    $this->load->view('Admin/QardanHasanaScheme', $data);
  }

  private function qh_download_mohammedi_import_template_csv()
  {
    $filename = 'qardan_hasana_mohammedi_import_template.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // UTF-8 BOM helps Excel open the CSV correctly.
    echo "\xEF\xBB\xBF";

    $out = fopen('php://output', 'w');
    if ($out === false) {
      exit;
    }
    fputcsv($out, ['Miqaat Name', 'Hijri Date', 'English Date', 'Collection Amount']);
    fclose($out);
    exit;
  }

  private function qh_download_taher_import_template_csv()
  {
    $filename = 'qardan_hasana_taher_import_template.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo "\xEF\xBB\xBF";

    $out = fopen('php://output', 'w');
    if ($out === false) {
      exit;
    }

    fputcsv($out, ['ITS', 'Unit = 215₹', 'No of 215₹ Units', 'Miqaat Name']);
    fclose($out);
    exit;
  }

  private function qh_download_husain_import_template_csv()
  {
    $filename = 'qardan_hasana_husain_import_template.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo "\xEF\xBB\xBF";

    $out = fopen('php://output', 'w');
    if ($out === false) {
      exit;
    }

    // Per requirement: do NOT include Member Name in import template.
    fputcsv($out, ['ITS', 'Amount', 'Deposit Date', 'Maturity Date', 'Duration']);
    fclose($out);
    exit;
  }

  // Niyaz Amounts
  public function manageniyazamounts()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    
    $this->load->model('AdminM');
    $this->load->library('HijriCalendar');
    
    // Get distinct Hijri years for the dropdown
    $data['hijri_years'] = $this->HijriCalendar->get_distinct_composite_years();
    
    // Determine selected year
    $selected_year = $this->input->get('year');
    if (empty($selected_year)) {
        // Default to current financial hijri year
        $todayHijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
        if ($todayHijri && isset($todayHijri['hijri_date'])) {
            $parts = explode('-', $todayHijri['hijri_date']);
            $m = (int)($parts[1] ?? 1);
            $y = (int)($parts[2] ?? date('Y'));
            if ($m >= 7 && $m <= 12) {
                $selected_year = $y . '-' . str_pad(($y + 1) % 100, 2, '0', STR_PAD_LEFT);
            } else {
                $selected_year = ($y - 1) . '-' . str_pad($y % 100, 2, '0', STR_PAD_LEFT);
            }
        } else {
            $selected_year = date('Y') . '-' . str_pad((date('Y') + 1) % 100, 2, '0', STR_PAD_LEFT);
        }
    }
    $data['selected_year'] = $selected_year;

    // Get current amounts for selected year
    $amounts = $this->AdminM->get_niyaz_amounts($selected_year);
    
    // Ensure defaults exist for the selected year
    $defaults = ['General', 'Ashara', 'Shehrullah', 'Ladies'];
    $existing = array_column($amounts, 'miqaat_type');
    
    $missing = array_diff($defaults, $existing);
    if (!empty($missing)) {
      $insert = [];
      foreach ($missing as $type) {
        $insert[] = [
          'miqaat_type' => $type,
          'individual_amount' => 0,
          'fala_amount' => 0,
          'year' => $selected_year
        ];
      }
      $this->db->insert_batch('miqaat_niyaz_amounts', $insert);
      $amounts = $this->AdminM->get_niyaz_amounts($selected_year); // reload
    }
    
    // Key amounts by miqaat_type
    $keyed_amounts = [];
    foreach ($amounts as $row) {
      $keyed_amounts[$row['miqaat_type']] = $row;
    }
    $data['amounts'] = $keyed_amounts;

    // Fetch additional contribution types (Niyaz) for the selected year
    $all_fmbgc = $this->db->get_where('fmb_general_contribution_master', [
      'fmb_type' => 'Niyaz',
      'hijri_year' => $selected_year
    ])->result_array();

    // Group them by contribution type name
    $grouped_additional = [];
    foreach ($all_fmbgc as $row) {
      $name = $row['name'];
      if (!isset($grouped_additional[$name])) {
        $grouped_additional[$name] = [
          'name' => $name,
          'General' => 0.00,
          'Ashara' => 0.00,
          'Shehrullah' => 0.00,
          'Ladies' => 0.00
        ];
      }
      $miqaat = $row['miqaat_type'];
      if (in_array($miqaat, ['General', 'Ashara', 'Shehrullah', 'Ladies'])) {
        $grouped_additional[$name][$miqaat] = (float)$row['amount'];
      }
    }
    $data['additional_types'] = array_values($grouped_additional);
    
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageNiyazAmounts', $data);
  }

  public function updateniyazamounts()
  {
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin/manageniyazamounts');
      return;
    }

    $year = $this->input->post('year');
    $individual_amounts = $this->input->post('individual_amount');
    $fala_amounts = $this->input->post('fala_amount');
    $additional_amounts = $this->input->post('additional_amount');

    if (!empty($year)) {
      $this->load->model('AdminM');
      
      // 1. Update fixed individual and hoob amounts
      $defaults = ['General', 'Ashara', 'Shehrullah', 'Ladies'];
      $fixed_data = [];
      foreach ($defaults as $type) {
        $fixed_data[] = [
          'miqaat_type' => $type,
          'individual_amount' => isset($individual_amounts[$type]) && $individual_amounts[$type] !== "" ? (float)$individual_amounts[$type] : 0.00,
          'fala_amount' => isset($fala_amounts[$type]) && $fala_amounts[$type] !== "" ? (float)$fala_amounts[$type] : 0.00,
          'year' => $year
        ];
      }
      $this->AdminM->update_niyaz_amounts($fixed_data, $year);

      // 2. Update additional contribution types
      if (!empty($additional_amounts) && is_array($additional_amounts)) {
        foreach ($additional_amounts as $name => $miqaat_values) {
          if (is_array($miqaat_values)) {
            foreach ($miqaat_values as $miqaat => $amount) {
              $exist = $this->db->get_where('fmb_general_contribution_master', [
                'name' => $name,
                'fmb_type' => 'Niyaz',
                'miqaat_type' => $miqaat,
                'hijri_year' => $year
              ])->row_array();

              if ($exist) {
                $this->db->where('id', $exist['id'])->update('fmb_general_contribution_master', [
                  'amount' => $amount !== "" ? (float)$amount : 0.00
                ]);
              } else {
                $this->db->insert('fmb_general_contribution_master', [
                  'name' => $name,
                  'fmb_type' => 'Niyaz',
                  'miqaat_type' => $miqaat,
                  'amount' => $amount !== "" ? (float)$amount : 0.00,
                  'hijri_year' => $year,
                  'status' => 1
                ]);
              }
            }
          }
        }
      }
      
      $this->session->set_flashdata('success', 'Niyaz amounts updated successfully for Hijri Year ' . htmlspecialchars($year) . '.');
    } else {
      $this->session->set_flashdata('error', 'No data to update.');
    }
    
    redirect('admin/manageniyazamounts' . (!empty($year) ? '?year=' . urlencode($year) : ''));
  }

  public function addfmbniyazcontritype()
  {
    $this->validateUser($_SESSION['user']);
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect('admin/manageniyazamounts');
      return;
    }

    $name = trim((string)$this->input->post('name'));
    $year = trim((string)$this->input->post('year'));
    $general_amount = $this->input->post('general_amount') !== "" ? (float)$this->input->post('general_amount') : 0.00;
    $ashara_amount = $this->input->post('ashara_amount') !== "" ? (float)$this->input->post('ashara_amount') : 0.00;
    $shehrullah_amount = $this->input->post('shehrullah_amount') !== "" ? (float)$this->input->post('shehrullah_amount') : 0.00;
    $ladies_amount = $this->input->post('ladies_amount') !== "" ? (float)$this->input->post('ladies_amount') : 0.00;

    if ($name !== '' && $year !== '') {
      $miqaats = [
        'General' => $general_amount,
        'Ashara' => $ashara_amount,
        'Shehrullah' => $shehrullah_amount,
        'Ladies' => $ladies_amount
      ];

      foreach ($miqaats as $miqaat => $amount) {
        $data = [
          'name' => $name,
          'fmb_type' => 'Niyaz',
          'miqaat_type' => $miqaat,
          'amount' => $amount,
          'hijri_year' => $year,
          'status' => 1
        ];
        $this->db->insert('fmb_general_contribution_master', $data);
      }
      $this->session->set_flashdata('success', 'New contribution type added successfully.');
    } else {
      $this->session->set_flashdata('error', 'Name and year are required.');
    }

    redirect('admin/manageniyazamounts' . (!empty($year) ? '?year=' . urlencode($year) : ''));
  }

  public function deletefmbniyazcontritype()
  {
    $this->validateUser($_SESSION['user']);
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
      redirect('admin/manageniyazamounts');
      return;
    }

    $name = trim((string)$this->input->post('name'));
    $year = trim((string)$this->input->post('year'));

    if ($name !== '' && $year !== '') {
      // Check if it is currently in use in fmb_general_contribution
      $this->db->where('contri_type', $name);
      $this->db->where('contri_year', $year);
      $this->db->where('fmb_type', 'Niyaz');
      $count = $this->db->count_all_results('fmb_general_contribution');

      if ($count > 0) {
        $this->session->set_flashdata('error', 'Cannot delete "' . htmlspecialchars($name) . '" because it is currently assigned to ' . $count . ' member contribution record(s).');
      } else {
        $this->db->where('name', $name);
        $this->db->where('hijri_year', $year);
        $this->db->where('fmb_type', 'Niyaz');
        $this->db->delete('fmb_general_contribution_master');
        
        if ($this->db->affected_rows() > 0) {
          $this->session->set_flashdata('success', 'Contribution type deleted successfully.');
        } else {
          $this->session->set_flashdata('error', 'Failed to delete contribution type. The record may not exist or could not be removed.');
        }
      }
    } else {
      $this->session->set_flashdata('error', 'Failed to delete contribution type. Invalid request parameters.');
    }

    redirect('admin/manageniyazamounts' . (!empty($year) ? '?year=' . urlencode($year) : ''));
  }

  // Ekram Fund card page
  public function ekramfunds()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/EkramFunds');
  }

  // Show full-page form to create a new ekram fund
  public function ekramfunds_new()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['message'] = $this->session->flashdata('ekram_message');
    $data['error'] = $this->session->flashdata('ekram_error');
    $data['old'] = $this->session->flashdata('ekram_old');
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/EkramFundsCreate', $data);
  }

  // AJAX: check whether an ekram fund exists for the current Hijri year
  public function ekramfunds_check_duplicate()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    // allow optional hijri_year parameter to check any year; otherwise use current date
    $input_year = null;
    if ($this->input->get_post('hijri_year') !== null && $this->input->get_post('hijri_year') !== '') {
      $input_year = (int)$this->input->get_post('hijri_year');
    }
    if ($input_year !== null && $input_year > 0) {
      $check_year = $input_year;
    } else {
      $h = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
      $check_year = null;
      if ($h && isset($h['hijri_date'])) {
        $parts = explode('-', $h['hijri_date']);
        if (count($parts) === 3) $check_year = (int)$parts[2];
      }
      if ($check_year === null) {
        $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Could not determine Hijri year']));
        return;
      }
    }
    $exists = $this->db->get_where('ekram_fund', ['hijri_year' => $check_year])->row_array();
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'exists' => !empty($exists), 'hijri_year' => $check_year]));
  }

  // Handle POST to create ekram fund and assign to HOFs
  public function ekramfunds_create()
  {
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin/ekramfunds');
      return;
    }
    $title = trim((string)$this->input->post('title'));
    $hijri_year_raw = $this->input->post('hijri_year');
    $hijri_year = ($hijri_year_raw !== null && $hijri_year_raw !== '') ? (int)$hijri_year_raw : null;
    $amount = (float)$this->input->post('amount');
    $description = trim((string)$this->input->post('description'));
    $old = ['title' => $title, 'hijri_year' => $hijri_year, 'amount' => $amount, 'description' => $description];
    // If title not supplied (form removed), auto-generate using Hijri year
    if ($title === '') {
      $title = $hijri_year ? ('Ekram Fund ' . $hijri_year) : 'Ekram Fund';
    }

    if ($amount <= 0 || $hijri_year === null) {
      $this->session->set_flashdata('ekram_error', 'Hijri year and positive amount are required');
      $this->session->set_flashdata('ekram_old', $old);
      redirect('admin/ekramfunds/new');
      return;
    }
    $this->load->model('EkramFundM');
    $result = $this->EkramFundM->create_fund($title, $amount, $description, isset($_SESSION['user']['ITS_ID']) ? $_SESSION['user']['ITS_ID'] : null, $hijri_year);
    if (is_array($result) && isset($result['success']) && $result['success'] === true) {
      $fund_id = $result['id'];
      // assign to all HOFs (simple batch insert)
      $hofs = $this->db->query(
        "SELECT DISTINCT HOF_ID FROM user 
          WHERE HOF_ID IS NOT NULL AND HOF_ID <> 0
            AND (Inactive_Status IS NULL OR Inactive_Status = 0)
            AND (Sector IS NOT NULL AND TRIM(Sector) <> '')
            AND (Sub_Sector IS NOT NULL AND TRIM(Sub_Sector) <> '')
            AND HOF_FM_TYPE = 'HOF'"
      )->result_array();
      $batch = [];
      foreach ($hofs as $h) {
        $hof_id = (int)$h['HOF_ID'];
        if ($hof_id <= 0) continue;
        $exists = $this->db->get_where('ekram_fund_assignment', ['fund_id' => $fund_id, 'hof_id' => $hof_id])->row_array();
        if (!$exists) {
          $batch[] = ['fund_id' => $fund_id, 'hof_id' => $hof_id, 'amount_assigned' => $amount, 'created_at' => date('Y-m-d H:i:s')];
        }
      }
      if (!empty($batch)) $this->db->insert_batch('ekram_fund_assignment', $batch);
      $assigned = count($batch);
      $this->session->set_flashdata('ekram_message', 'Ekram fund created (ID ' . $fund_id . ') and assigned to ' . $assigned . ' HOFs.');
      redirect('admin/ekramfunds');
      return;
    } else {
      $errMsg = 'Failed to create ekram fund.';
      if (is_array($result) && isset($result['error']['message'])) {
        $errMsg .= ' DB Error: ' . $result['error']['message'];
      }
      $this->session->set_flashdata('ekram_error', $errMsg);
      $this->session->set_flashdata('ekram_old', $old);
      redirect('admin/ekramfunds/new');
      return;
    }
  }

  // List Ekram funds
  public function ekramfunds_list()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('EkramFundM');
    $data['user_name'] = $_SESSION['user']['username'];
    $data['funds'] = $this->EkramFundM->get_funds();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/EkramFundsList', $data);
  }

  // Assigned HOFs view for Ekram funds
  public function ekramfunds_hofs()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('EkramFundM');
    $data['user_name'] = $_SESSION['user']['username'];
    // Get HOF assignments and totals from ekram tables
    $rows = $this->db->select('a.*, f.title AS fund_title, f.hijri_year AS fund_year, u.Full_Name, u.ITS_ID')
      ->from('ekram_fund_assignment a')
      ->join('ekram_fund f', 'f.id = a.fund_id', 'left')
      ->join('user u', 'u.HOF_ID = a.hof_id', 'left')
      ->order_by('a.hof_id, a.fund_id')
      ->get()->result_array();
    $hof_fund_details = [];
    foreach ($rows as $r) {
      $hid = isset($r['hof_id']) ? (int)$r['hof_id'] : 0;
      if (!isset($hof_fund_details[$hid])) $hof_fund_details[$hid] = [];
      $hof_fund_details[$hid][] = [
        'fund_id' => (int)$r['fund_id'],
        'title' => $r['fund_title'],
        'hijri_year' => isset($r['fund_year']) && $r['fund_year'] ? (int)$r['fund_year'] : null,
        'amount' => (float)$r['amount_assigned'],
        'created_at' => $r['created_at'] ?? null
      ];
    }
    // Active HOF list with totals (aggregate in subquery to stay compatible with ONLY_FULL_GROUP_BY)
    $assignAgg = '(SELECT hof_id, COALESCE(SUM(amount_assigned),0) AS ekram_total, COUNT(id) AS ekram_count FROM ekram_fund_assignment GROUP BY hof_id) a';
    $hofs = $this->db->select('u.HOF_ID, u.ITS_ID, u.Full_Name, u.Sector, u.Sub_Sector, COALESCE(a.ekram_total,0) AS ekram_total, COALESCE(a.ekram_count,0) AS ekram_count')
      ->from('user u')
      ->join($assignAgg, 'a.hof_id = u.HOF_ID', 'left', false)
      ->where("(u.Inactive_Status IS NULL OR u.Inactive_Status = 0)")
      ->where("(u.Sector IS NOT NULL AND TRIM(u.Sector) <> '')")
      ->where("(u.Sub_Sector IS NOT NULL AND TRIM(u.Sub_Sector) <> '')")
      ->where("u.HOF_FM_TYPE = 'HOF'")
      ->get()->result_array();

    // Compute paid and pending per HOF
    foreach ($hofs as $k => $h) {
      $hid = isset($h['HOF_ID']) ? (int)$h['HOF_ID'] : 0;
      $paidRow = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')
        ->from('ekram_fund_payment')
        ->where('hof_id', $hid)
        ->get()->row_array();
      $paid = isset($paidRow['total_paid']) ? (float)$paidRow['total_paid'] : 0.0;
      $assigned = isset($h['ekram_total']) ? (float)$h['ekram_total'] : 0.0;
      $hofs[$k]['total_paid'] = $paid;
      $hofs[$k]['pending_due'] = max(0, $assigned - $paid);
      // last updated
      $assignLastRow = $this->db->select('MAX(a.created_at) AS assign_last')
        ->from('ekram_fund_assignment a')
        ->where('a.hof_id', $hid)
        ->get()->row_array();
      $paymentLastRow = $this->db->select('MAX(p.paid_at) AS payment_last')
        ->from('ekram_fund_payment p')
        ->where('p.hof_id', $hid)
        ->get()->row_array();
      $assign_last = isset($assignLastRow['assign_last']) ? $assignLastRow['assign_last'] : null;
      $payment_last = isset($paymentLastRow['payment_last']) ? $paymentLastRow['payment_last'] : null;
      $last_updated = null;
      if ($assign_last && $payment_last) {
        $last_updated = ($assign_last > $payment_last) ? $assign_last : $payment_last;
      } else {
        $last_updated = $assign_last ?: $payment_last ?: null;
      }
      $hofs[$k]['last_updated'] = $last_updated;
    }

    // Hijri years mapping
    $hof_hijri_years = [];
    $all_years = [];
    if (!empty($hof_fund_details)) {
      foreach ($hof_fund_details as $hid => $funds) {
        foreach ($funds as $f) {
          $greg = substr($f['created_at'] ?? '', 0, 10);
          if (!$greg) continue;
          $parts = $this->HijriCalendar->get_hijri_parts_by_greg_date($greg);
          if ($parts && isset($parts['hijri_year'])) {
            $yr = $parts['hijri_year'];
            if (!isset($hof_hijri_years[$hid])) $hof_hijri_years[$hid] = [];
            if (!in_array($yr, $hof_hijri_years[$hid])) $hof_hijri_years[$hid][] = $yr;
            if (!in_array($yr, $all_years)) $all_years[] = $yr;
          }
        }
      }
    }
    rsort($all_years);

    $data['hofs'] = $hofs;
    $data['hof_fund_details'] = $hof_fund_details;
    $data['hof_hijri_years'] = $hof_hijri_years;
    $data['hijri_years'] = $all_years;
    // sector/subsector lists
    $sectorSet = [];
    $subSectorMap = [];
    foreach ($hofs as $row) {
      $sector = trim($row['Sector']);
      $sub = trim($row['Sub_Sector']);
      if ($sector !== '') $sectorSet[$sector] = true;
      if ($sector !== '') {
        if (!isset($subSectorMap[$sector])) $subSectorMap[$sector] = [];
        if ($sub !== '' && !isset($subSectorMap[$sector][$sub])) $subSectorMap[$sector][$sub] = true;
      }
    }
    $sectors = array_keys($sectorSet);
    sort($sectors, SORT_NATURAL | SORT_FLAG_CASE);
    $subSectorMapOut = [];
    foreach ($subSectorMap as $sec => $subs) {
      $subList = array_keys($subs);
      sort($subList, SORT_NATURAL | SORT_FLAG_CASE);
      $subSectorMapOut[$sec] = $subList;
    }
    $data['sectors'] = $sectors;
    $data['sector_sub_map'] = $subSectorMapOut;
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/EkramFundsHofs', $data);
  }

  public function ekramfunds_update_assignments()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $hof_id = (int)$this->input->post('hof_id');
    $raw = $this->input->post('assignments');
    $assignments = [];
    if (is_string($raw)) {
      $decoded = json_decode($raw, true);
      if (is_array($decoded)) $assignments = $decoded;
    } elseif (is_array($raw)) {
      $assignments = $raw;
    }
    if ($hof_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid hof_id']));
      return;
    }
    $errors = [];
    $updated = 0;
    foreach ($assignments as $fund_id => $amt) {
      $fid = (int)$fund_id;
      $amount = (float)$amt;
      if ($fid <= 0) continue;
      $exists = $this->db->get_where('ekram_fund_assignment', ['hof_id' => $hof_id, 'fund_id' => $fid])->row_array();
      if ($exists) {
        $ok = $this->db->where('id', $exists['id'])->update('ekram_fund_assignment', ['amount_assigned' => $amount]);
        if ($ok) $updated++;
        else $errors[] = "Failed update fund $fid";
      } else {
        $ok = $this->db->insert('ekram_fund_assignment', ['hof_id' => $hof_id, 'fund_id' => $fid, 'amount_assigned' => $amount, 'created_at' => date('Y-m-d H:i:s')]);
        if ($ok) $updated++;
        else $errors[] = "Failed insert fund $fid";
      }
    }
    $new_total_row = $this->db->select('COALESCE(SUM(amount_assigned),0) AS tot')->from('ekram_fund_assignment')->where('hof_id', $hof_id)->get()->row_array();
    $new_total = isset($new_total_row['tot']) ? (float)$new_total_row['tot'] : 0.0;
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => empty($errors), 'updated' => $updated, 'errors' => $errors, 'new_total' => $new_total]));
  }

  public function ekramfunds_delete_assignment()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $hof_id = (int)$this->input->post('hof_id');
    $fund_id = (int)$this->input->post('fund_id');
    if ($hof_id <= 0 || $fund_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid hof_id or fund_id']));
      return;
    }
    $this->db->where(['hof_id' => $hof_id, 'fund_id' => $fund_id])->delete('ekram_fund_assignment');
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true]));
  }

  public function ekramfunds_update_fund()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $fund_id = (int)$this->input->post('fund_id');
    $amount_raw = $this->input->post('amount');
    $title = $this->input->post('title');
    $description = $this->input->post('description');
    $propagate = $this->input->post('propagate');
    $propagate_flag = ($propagate === '0' || $propagate === 0) ? false : true;
    if ($fund_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid fund_id']));
      return;
    }
    if ($amount_raw === null || $amount_raw === '') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Amount required']));
      return;
    }
    $new_amount = (float)$amount_raw;
    if (!is_numeric($amount_raw) || $new_amount < 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid amount']));
      return;
    }
    $upd = ['amount' => $new_amount];
    if ($title !== null) $upd['title'] = $title;
    if ($description !== null) $upd['description'] = $description;
    $this->db->where('id', $fund_id)->update('ekram_fund', $upd);
    $fund_updated = $this->db->affected_rows();
    $assignments_updated = 0;
    if ($propagate_flag) {
      $this->db->where('fund_id', $fund_id)->update('ekram_fund_assignment', ['amount_assigned' => $new_amount]);
      $assignments_updated = $this->db->affected_rows();
    }
    $resp = ['success' => true, 'fund_updated' => $fund_updated, 'assignments_updated' => $assignments_updated, 'amount' => $new_amount, 'title' => $title];
    $this->output->set_content_type('application/json')->set_output(json_encode($resp));
  }

  public function ekramfunds_delete_fund()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $fund_id = (int)$this->input->post('fund_id');
    if ($fund_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid fund_id']));
      return;
    }
    // Delete assignments first
    $this->db->where('fund_id', $fund_id)->delete('ekram_fund_assignment');
    $assignments_deleted = $this->db->affected_rows();
    // Delete payments
    $this->db->where('fund_id', $fund_id)->delete('ekram_fund_payment');
    // Delete fund
    $this->db->where('id', $fund_id)->delete('ekram_fund');
    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'assignments_deleted' => $assignments_deleted]));
  }

  // AJAX: return HOF rows and fund details optionally filtered by hijri_year
  public function ekramfunds_hofs_data()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    $year = $this->input->get('hijri_year');
    $this->load->model('EkramFundM');
    $q = $this->db->select('a.*, f.title AS fund_title, f.hijri_year AS fund_year, u.Full_Name, u.ITS_ID, u.Sector, u.Sub_Sector')
      ->from('ekram_fund_assignment a')
      ->join('ekram_fund f', 'f.id = a.fund_id', 'left')
      ->join('user u', 'u.HOF_ID = a.hof_id', 'left');
    if ($year !== null && $year !== '') {
      $q->where('f.hijri_year', (int)$year);
    }
    $q->order_by('a.hof_id, a.fund_id');
    $rows = $q->get()->result_array();

    $hof_fund_details = [];
    foreach ($rows as $r) {
      $hid = isset($r['hof_id']) ? (int)$r['hof_id'] : 0;
      if ($hid <= 0) continue;
      if (!isset($hof_fund_details[$hid])) $hof_fund_details[$hid] = [];
      $hof_fund_details[$hid][] = [
        'fund_id' => (int)$r['fund_id'],
        'title' => $r['fund_title'],
        'hijri_year' => isset($r['fund_year']) && $r['fund_year'] ? (int)$r['fund_year'] : null,
        'amount' => (float)$r['amount_assigned'],
        'created_at' => $r['created_at'] ?? null
      ];
    }

    // Build HOF summary from fetched rows (only HOFs that have assignments for the selected year)
    $hofs = [];
    foreach ($rows as $r) {
      $hid = isset($r['hof_id']) ? (int)$r['hof_id'] : 0;
      if ($hid <= 0) continue;
      if (!isset($hofs[$hid])) {
        $hofs[$hid] = [
          'HOF_ID' => $hid,
          'ITS_ID' => $r['ITS_ID'] ?? '',
          'Full_Name' => $r['Full_Name'] ?? '',
          'Sector' => $r['Sector'] ?? '',
          'Sub_Sector' => $r['Sub_Sector'] ?? '',
          'ekram_total' => 0.0,
          'ekram_count' => 0,
          'last_updated' => null
        ];
      }
      $hofs[$hid]['ekram_total'] += isset($r['amount_assigned']) ? (float)$r['amount_assigned'] : 0.0;
      $hofs[$hid]['ekram_count']++;
      // Track last_updated as the max of assignment created_at and payments
      $assign_ts = isset($r['created_at']) ? $r['created_at'] : null;
      if ($assign_ts && (!$hofs[$hid]['last_updated'] || $assign_ts > $hofs[$hid]['last_updated'])) $hofs[$hid]['last_updated'] = $assign_ts;
    }

    // Add total_paid/pending_due by querying payments per HOF
    foreach ($hofs as $hid => &$hh) {
      $paidRow = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')->from('ekram_fund_payment')->where('hof_id', $hid)->get()->row_array();
      $paid = isset($paidRow['total_paid']) ? (float)$paidRow['total_paid'] : 0.0;
      $hh['total_paid'] = $paid;
      $hh['pending_due'] = max(0, $hh['ekram_total'] - $paid);
      // check payments last
      $paymentLastRow = $this->db->select('MAX(paid_at) AS payment_last')->from('ekram_fund_payment')->where('hof_id', $hid)->get()->row_array();
      $payment_last = isset($paymentLastRow['payment_last']) ? $paymentLastRow['payment_last'] : null;
      if ($payment_last && (!$hh['last_updated'] || $payment_last > $hh['last_updated'])) $hh['last_updated'] = $payment_last;
    }
    unset($hh);

    // Reindex hof list as array
    $hofs_out = array_values($hofs);

    $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'hofs' => $hofs_out, 'hof_fund_details' => $hof_fund_details]));
  }



  public function corpusfunds()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    // Menu view (cards linking to create & list)
    $this->load->view('Admin/CorpusFunds');
  }

  public function corpusfunds_create()
  {
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin/corpusfunds');
    }
    $title = trim((string)$this->input->post('title'));
    $amount = (float)$this->input->post('amount');
    $description = trim((string)$this->input->post('description'));
    if ($title === '' || $amount <= 0) {
      $this->session->set_flashdata('corpus_fund_error', 'Title and positive amount are required');
      redirect('admin/corpusfunds_new');
    }
    $this->load->model('CorpusFundM');
    $result = $this->CorpusFundM->create_fund($title, $amount, $description, isset($_SESSION['user']['ITS_ID']) ? $_SESSION['user']['ITS_ID'] : null);
    if (is_array($result) && isset($result['success']) && $result['success'] === true) {
      $fund_id = $result['id'];
      $assigned = $this->CorpusFundM->assign_to_all_hofs($fund_id, $amount);
      $this->session->set_flashdata('corpus_fund_message', 'Corpus fund created (ID ' . $fund_id . ') and assigned to ' . $assigned . ' HOFs.');
      redirect('admin/corpusfunds_list');
    } else {
      $errMsg = 'Failed to create corpus fund.';
      if (is_array($result) && isset($result['error']['message'])) {
        $errMsg .= ' DB Error: ' . $result['error']['message'];
      }
      $this->session->set_flashdata('corpus_fund_error', $errMsg);
      redirect('admin/corpusfunds_new');
    }
  }

  public function corpusfunds_new()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['message'] = $this->session->flashdata('corpus_fund_message');
    $data['error'] = $this->session->flashdata('corpus_fund_error');
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/CorpusFundsCreate', $data);
  }

  public function corpusfunds_list()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('CorpusFundM');
    $data['user_name'] = $_SESSION['user']['username'];
    $data['funds'] = $this->CorpusFundM->get_funds();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/CorpusFundsList', $data);
  }

  public function corpusfunds_hofs()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('CorpusFundM');
    $data['user_name'] = $_SESSION['user']['username'];
    $data['hofs'] = $this->CorpusFundM->get_active_hofs_with_totals();
    $data['hof_fund_details'] = $this->CorpusFundM->get_all_hof_fund_details();
    // Compute total paid and pending due per HOF
    if (!empty($data['hofs']) && is_array($data['hofs'])) {
      foreach ($data['hofs'] as $k => $h) {
        $hid = isset($h['HOF_ID']) ? (int)$h['HOF_ID'] : 0;
        $paidRow = $this->db->select('COALESCE(SUM(amount_paid),0) AS total_paid')
          ->from('corpus_fund_payment')
          ->where('hof_id', $hid)
          ->get()->row_array();
        $paid = isset($paidRow['total_paid']) ? (float)$paidRow['total_paid'] : 0.0;
        $assigned = isset($h['corpus_total']) ? (float)$h['corpus_total'] : 0.0;
        $data['hofs'][$k]['total_paid'] = $paid;
        $data['hofs'][$k]['pending_due'] = max(0, $assigned - $paid);
        // determine last updated timestamp for this HOF (assignments or payments)
        $assignLastRow = $this->db->select('MAX(a.created_at) AS assign_last')
          ->from('corpus_fund_assignment a')
          ->where('a.hof_id', $hid)
          ->get()->row_array();
        $paymentLastRow = $this->db->select('MAX(p.created_at) AS payment_last')
          ->from('corpus_fund_payment p')
          ->where('p.hof_id', $hid)
          ->get()->row_array();
        $assign_last = isset($assignLastRow['assign_last']) ? $assignLastRow['assign_last'] : null;
        $payment_last = isset($paymentLastRow['payment_last']) ? $paymentLastRow['payment_last'] : null;
        $last_updated = null;
        if ($assign_last && $payment_last) {
          $last_updated = ($assign_last > $payment_last) ? $assign_last : $payment_last;
        } else {
          $last_updated = $assign_last ?: $payment_last ?: null;
        }
        $data['hofs'][$k]['last_updated'] = $last_updated;
      }
    }
    // Compute hijri years per HOF for filtering
    $hof_hijri_years = [];
    $all_years = [];
    if (!empty($data['hof_fund_details'])) {
      foreach ($data['hof_fund_details'] as $hid => $funds) {
        foreach ($funds as $f) {
          $greg = substr($f['created_at'], 0, 10);
          $parts = $this->HijriCalendar->get_hijri_parts_by_greg_date($greg);
          if ($parts && isset($parts['hijri_year'])) {
            $yr = $parts['hijri_year'];
            if (!isset($hof_hijri_years[$hid])) {
              $hof_hijri_years[$hid] = [];
            }
            if (!in_array($yr, $hof_hijri_years[$hid])) {
              $hof_hijri_years[$hid][] = $yr;
            }
            if (!in_array($yr, $all_years)) {
              $all_years[] = $yr;
            }
          }
        }
      }
    }
    rsort($all_years); // descending
    $data['hof_hijri_years'] = $hof_hijri_years;
    $data['hijri_years'] = $all_years;
    // Distinct Sector / Sub-Sector lists and mapping
    $sectorSet = [];
    $subSectorMap = [];
    foreach ($data['hofs'] as $row) {
      $sector = trim($row['Sector']);
      $sub = trim($row['Sub_Sector']);
      if ($sector !== '') {
        $sectorSet[$sector] = true;
      }
      if ($sector !== '') {
        if (!isset($subSectorMap[$sector])) {
          $subSectorMap[$sector] = [];
        }
        if ($sub !== '' && !isset($subSectorMap[$sector][$sub])) {
          $subSectorMap[$sector][$sub] = true;
        }
      }
    }
    $sectors = array_keys($sectorSet);
    sort($sectors, SORT_NATURAL | SORT_FLAG_CASE);
    // Normalize sub sector arrays
    $subSectorMapOut = [];
    foreach ($subSectorMap as $sec => $subs) {
      $subList = array_keys($subs);
      sort($subList, SORT_NATURAL | SORT_FLAG_CASE);
      $subSectorMapOut[$sec] = $subList;
    }
    $data['sectors'] = $sectors;
    $data['sector_sub_map'] = $subSectorMapOut; // used in view JS
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/CorpusHofs', $data);
  }

  public function corpusfunds_hofs_import()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (empty($_FILES['csv_file']['tmp_name'])) {
        $this->session->set_flashdata('error', 'No file uploaded');
        redirect('admin/corpusfunds_hofs_import');
        return;
      }
      $fh = fopen($_FILES['csv_file']['tmp_name'], 'r');
      if (!$fh) {
        $this->session->set_flashdata('error', 'Unable to open uploaded file');
        redirect('admin/corpusfunds_hofs_import');
        return;
      }
      $processed = 0;
      $inserted = 0;
      $updated = 0;
      $first = fgetcsv($fh);
      $hasHeader = false;
      if ($first !== false) {
        $lower = array_map('strtolower', $first);
        if (in_array('hof_id', $lower) || in_array('hof id', $lower)) {
          $hasHeader = true;
        } else {
          // treat as data row
          $row = $first;
          if (!empty($row)) {
            $processed++;
            $hof_id = isset($row[0]) ? (int)trim($row[0]) : 0;
            $fund_id = isset($row[1]) ? (int)trim($row[1]) : 0;
            $amount = isset($row[2]) ? (float)str_replace(',', '', $row[2]) : 0;
            if ($hof_id > 0 && $fund_id > 0) {
              $exists = $this->db->get_where('corpus_fund_assignment', ['hof_id' => $hof_id, 'fund_id' => $fund_id])->row_array();
              if ($exists) {
                $this->db->where('id', $exists['id'])->update('corpus_fund_assignment', ['amount_assigned' => $amount]);
                $updated++;
              } else {
                $this->db->insert('corpus_fund_assignment', ['hof_id' => $hof_id, 'fund_id' => $fund_id, 'amount_assigned' => $amount, 'created_at' => date('Y-m-d H:i:s')]);
                $inserted++;
              }
            }
          }
        }
      }
      while (($row = fgetcsv($fh)) !== false) {
        $processed++;
        $hof_id = isset($row[0]) ? (int)trim($row[0]) : 0;
        $fund_id = isset($row[1]) ? (int)trim($row[1]) : 0;
        $amount = isset($row[2]) ? (float)str_replace(',', '', $row[2]) : 0;
        if ($hof_id > 0 && $fund_id > 0) {
          $exists = $this->db->get_where('corpus_fund_assignment', ['hof_id' => $hof_id, 'fund_id' => $fund_id])->row_array();
          if ($exists) {
            $this->db->where('id', $exists['id'])->update('corpus_fund_assignment', ['amount_assigned' => $amount]);
            $updated++;
          } else {
            $this->db->insert('corpus_fund_assignment', ['hof_id' => $hof_id, 'fund_id' => $fund_id, 'amount_assigned' => $amount, 'created_at' => date('Y-m-d H:i:s')]);
            $inserted++;
          }
        }
      }
      fclose($fh);
      $this->session->set_flashdata('message', "Import completed: $inserted inserted, $updated updated from $processed rows");
      redirect('admin/corpusfunds_hofs');
      return;
    }

    $data['message'] = $this->session->flashdata('message');
    $data['error'] = $this->session->flashdata('error');
    // Load existing assignments for display
    $rows = $this->db->select('a.*, f.title AS fund_title, u.Full_Name, u.ITS_ID')
      ->from('corpus_fund_assignment a')
      ->join('corpus_fund f', 'f.id = a.fund_id', 'left')
      ->join('user u', 'u.HOF_ID = a.hof_id', 'left')
      ->order_by('a.hof_id, a.fund_id')
      ->get()->result_array();
    $data['assignments'] = $rows;

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/CorpusHofsImport', $data);
  }

  public function corpusfunds_hofs_template()
  {
    $this->validateUser($_SESSION['user']);
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=corpusfunds_hofs_template.csv');
    echo "\xEF\xBB\xBF";
    $out = fopen('php://output', 'w');
    fputcsv($out, ['hof_id', 'fund_id', 'amount_assigned']);
    for ($i = 0; $i < 5; $i++) fputcsv($out, ['', '', '']);
    fclose($out);
    exit;
  }

  public function corpusfunds_update_assignments()
  {
    // AJAX: return JSON instead of redirect on auth failure
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $hof_id = (int)$this->input->post('hof_id');
    $raw = $this->input->post('assignments');
    $assignments = [];
    if (is_string($raw)) {
      $decoded = json_decode($raw, true);
      if (is_array($decoded)) {
        $assignments = $decoded;
      }
    } elseif (is_array($raw)) {
      $assignments = $raw;
    }
    if ($hof_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid hof_id']));
      return;
    }
    $this->load->model('CorpusFundM');
    $result = $this->CorpusFundM->update_hof_fund_assignments($hof_id, $assignments);
    $success = empty($result['errors']);
    $this->output->set_content_type('application/json')->set_output(json_encode([
      'success' => $success,
      'updated' => $result['updated'],
      'errors' => $result['errors'],
      'new_total' => isset($result['new_total']) ? $result['new_total'] : null
    ]));
  }

  public function corpusfunds_delete_assignment()
  {
    // AJAX: return JSON instead of redirect on auth failure
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $hof_id = (int)$this->input->post('hof_id');
    $fund_id = (int)$this->input->post('fund_id');
    if ($hof_id <= 0 || $fund_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid hof_id or fund_id']));
      return;
    }
    $this->load->model('CorpusFundM');
    $result = $this->CorpusFundM->delete_hof_fund_assignment($hof_id, $fund_id);
    $this->output->set_content_type('application/json')->set_output(json_encode($result));
  }

  // Reset a member's password to MD5(ITS_ID)
  public function reset_member_password()
  {
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin/managemembers');
      return;
    }
    $its_id = trim((string)$this->input->post('its_id'));
    if ($its_id === '') {
      $this->session->set_flashdata('error', 'Invalid ITS ID for password reset');
      redirect('admin/managemembers');
      return;
    }
    // Update login.password to md5(ITS_ID)
    $success = $this->AccountM->change_password($its_id, md5($its_id));
    if ($success) {
      $this->session->set_flashdata('success', 'Password reset for ITS ' . htmlspecialchars($its_id) . ' completed.');
    } else {
      $this->session->set_flashdata('error', 'Password reset failed or no login found for ITS ' . htmlspecialchars($its_id) . '.');
    }
    // Redirect back to manage members (keep any existing query params if referer present)
    $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('admin/managemembers');
    redirect($ref);
  }

  // Bulk reset: set all non-admin active users' passwords to MD5(username)
  public function reset_all_member_passwords()
  {
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin/managemembers');
      return;
    }
    // Optional confirmation flag
    $confirm = $this->input->post('confirm');
    if ($confirm !== null && (string)$confirm !== '1') {
      $this->session->set_flashdata('error', 'Bulk reset not confirmed.');
      redirect('admin/managemembers');
      return;
    }
    $this->db->trans_start();
    $this->db->set('password', 'MD5(username)', false);
    // Exclude Admin users (role = 1); and optionally only active accounts
    $this->db->where('role !=', 1);
    $this->db->where('active', 1);
    $this->db->update('login');
    $affected = $this->db->affected_rows();
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->session->set_flashdata('error', 'Bulk password reset failed.');
    } else {
      $this->session->set_flashdata('success', 'Bulk password reset completed for ' . (int)$affected . ' users.');
    }
    $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('admin/managemembers');
    redirect($ref);
  }


  public function corpusfunds_update_fund()
  {
    // AJAX endpoint to update a corpus fund's amount (and optionally propagate to all assignments)
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $fund_id = (int)$this->input->post('fund_id');
    $amount_raw = $this->input->post('amount');
    $title = $this->input->post('title'); // optional
    $description = $this->input->post('description'); // optional
    $propagate = $this->input->post('propagate');
    $propagate_flag = ($propagate === '0' || $propagate === 0) ? false : true; // default true

    if ($fund_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid fund_id']));
      return;
    }
    if ($amount_raw === null || $amount_raw === '') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Amount required']));
      return;
    }
    $new_amount = (float)$amount_raw;
    if (!is_numeric($amount_raw) || $new_amount < 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid amount']));
      return;
    }
    $this->load->model('CorpusFundM');
    $result = $this->CorpusFundM->update_fund($fund_id, $new_amount, $title, $description, $propagate_flag);
    // Normalise response structure
    $resp = [
      'success' => isset($result['success']) ? (bool)$result['success'] : false,
      'fund_updated' => isset($result['fund_updated']) ? (int)$result['fund_updated'] : 0,
      'assignments_updated' => isset($result['assignments_updated']) ? (int)$result['assignments_updated'] : 0,
      'amount' => $new_amount
    ];
    if (!$resp['success']) {
      $resp['error'] = isset($result['error']) ? $result['error'] : 'Update failed';
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($resp));
  }

  public function corpusfunds_delete_fund()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid method']));
      return;
    }
    $fund_id = (int)$this->input->post('fund_id');
    if ($fund_id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Invalid fund id']));
      return;
    }
    $this->load->model('CorpusFundM');
    $result = $this->CorpusFundM->delete_fund($fund_id);
    $this->output->set_content_type('application/json')->set_output(json_encode($result));
  }

  /**
   * Export Members (CSV) - streams a UTF-8 CSV of all (optionally filtered) members.
   * GET params optionally: status=active|inactive, sector, sub_sector
   */
  public function exportmembers()
  {
    $this->validateUser($_SESSION['user']);
    $isTemplate = (int)$this->input->get('template') === 1;
    $filters = [
      'status' => $this->input->get('status'),
      'sector' => $this->input->get('sector'),
      'sub_sector' => $this->input->get('sub_sector'),
      'name' => $this->input->get('name'),
      'hof' => $this->input->get('hof')
    ];
    $rows = $this->AdminM->get_all_members_for_export($filters);
    if ($isTemplate) {
      // Keep only first 5 rows for sample (or pad header only if none)
      $rows = array_slice($rows, 0, 5);
    }
    $filename = ($isTemplate ? 'members_template_' : 'members_export_') . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    echo "\xEF\xBB\xBF"; // BOM
    $out = fopen('php://output', 'w');
    if (empty($rows)) {
      // Provide a minimal header for template even if no data
      $header = ['ITS_ID', 'Full_Name'];
      fputcsv($out, $header);
      if ($isTemplate) {
        // Provide 5 blank sample lines
        for ($i = 0; $i < 5; $i++) {
          fputcsv($out, ['', '']);
        }
      }
      fclose($out);
      return;
    }
    $header = array_keys($rows[0]);
    fputcsv($out, $header);
    foreach ($rows as $r) {
      $line = [];
      foreach ($header as $col) {
        $line[] = isset($r[$col]) ? $r[$col] : '';
      }
      fputcsv($out, $line);
    }
    // If template requested but fewer than 5 rows, pad blank rows
    if ($isTemplate && count($rows) < 5) {
      for ($i = count($rows); $i < 5; $i++) {
        $blank = [];
        foreach ($header as $col) {
          $blank[] = '';
        }
        fputcsv($out, $blank);
      }
    }
    fclose($out);
  }

  /**
   * Import Latest Data: GET shows upload form, POST processes CSV file.
   * CSV must contain at minimum ITS_ID and Full_Name. Upserts existing members by ITS_ID.
   */
  public function importlatest()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3)) {
      redirect('/accounts');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $summary = ['processed' => 0, 'inserted' => 0, 'updated' => 0, 'skipped' => 0, 'moved_out' => 0, 'errors' => []];
      $importedIds = [];
      if (empty($_FILES['data_file']['tmp_name'])) {
        $summary['errors'][] = 'No file uploaded';
      } else {
        $ext = strtolower(pathinfo($_FILES['data_file']['name'], PATHINFO_EXTENSION));
        $rows = [];
        if ($ext === 'xlsx') {
          $this->load->library('SimpleXLSX');
          if ($xlsx = SimpleXLSX::parse($_FILES['data_file']['tmp_name'])) {
            $rows = $xlsx->rows();
          } else {
            $summary['errors'][] = SimpleXLSX::parseError();
          }
        } else {
          $fh = fopen($_FILES['data_file']['tmp_name'], 'r');
          if (!$fh) {
            $summary['errors'][] = 'Cannot open uploaded file';
          } else {
            while (($row = fgetcsv($fh)) !== false) {
              $rows[] = $row;
            }
            fclose($fh);
          }
        }

        if (empty($summary['errors'])) {
          if (empty($rows)) {
            $summary['errors'][] = 'Empty file';
          } else {
            $header = array_shift($rows);
            $normalizedHeader = array_map(function ($h) {
              $h = is_string($h) ? $h : '';
              $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
              return trim($h);
            }, $header);
            $colIndex = array_flip($normalizedHeader);
            $required = ['ITS_ID', 'Full_Name'];
            foreach ($required as $req) {
              if (!isset($colIndex[$req])) $summary['errors'][] = "Missing required column: $req";
            }
            if (empty($summary['errors'])) {
              $createdSectorLogins = [];
              $createdSubSectorLogins = [];
              foreach ($rows as $row) {
                $summary['processed']++;
                $assoc = [];
                foreach ($normalizedHeader as $idxName) {
                  $idx = $colIndex[$idxName];
                  $assoc[$idxName] = isset($row[$idx]) ? trim((string)$row[$idx]) : '';
                }
                if ($assoc['ITS_ID'] === '' || $assoc['Full_Name'] === '') {
                  $summary['skipped']++;
                  continue;
                }

                $importedIds[] = $assoc['ITS_ID'];

                if (isset($assoc['Sector']) && isset($assoc['Sub_Sector']) && $assoc['Sector'] !== '' && $assoc['Sub_Sector'] !== '') {
                  $assoc['Sector'] = trim($assoc['Sector']);
                  $assoc['Sub_Sector'] = trim($assoc['Sub_Sector']);
                }

                try {
                  $result = $this->AdminM->upsert_member_from_row($assoc);
                  if ($result === 'inserted') $summary['inserted']++;
                  elseif ($result === 'updated') $summary['updated']++;
                  else $summary['skipped']++;

                  // Create login credentials for processed members if not present
                  if ($result === 'inserted' || $result === 'updated') {
                    $itsId = trim((string)$assoc['ITS_ID']);
                    if ($itsId !== '') {
                      $existingLogin = $this->db->where('username', $itsId)->get('login')->row_array();
                      if (!$existingLogin) {
                        // Try to get HOF_ID from user record; fallback to ITS_ID
                        $userRow = $this->db->where('ITS_ID', $itsId)->get('user')->row_array();
                        $hof = !empty($userRow['HOF_ID']) ? $userRow['HOF_ID'] : $itsId;
                        $loginRow = [
                          'username' => $itsId,
                          'password' => md5($itsId),
                          'role'     => 0,
                          'hof'      => $hof,
                          'active'   => 1
                        ];
                        $this->db->insert('login', $loginRow);
                      } else {
                        $userRow = $this->db->where('ITS_ID', $itsId)->get('user')->row_array();
                        $hof = !empty($userRow['HOF_ID']) ? $userRow['HOF_ID'] : $itsId;
                        if ($existingLogin['hof'] !== $hof) {
                          $this->db->where('username', $itsId)->update('login', ['hof' => $hof]);
                        }
                      }
                    }

                    // Create sector-level login (username = Sector) with fixed password and role=16
                    $sector = isset($assoc['Sector']) ? trim((string)$assoc['Sector']) : '';
                    if ($sector !== '' && !in_array($sector, $createdSectorLogins, true)) {
                      $createdSectorLogins[] = $sector;
                      $existingSectorLogin = $this->db->where('username', $sector)->get('login')->row_array();
                      if (!$existingSectorLogin) {
                        $sectorLogin = [
                          'username' => $sector,
                          'password' => md5('515253'),
                          'role'     => 16,
                          'hof'      => '',
                          'active'   => 1
                        ];
                        $this->db->insert('login', $sectorLogin);
                      }
                    }

                    // Create sub-sector-level login (username = Sub_Sector) with fixed password and role=16
                    $sub = isset($assoc['Sub_Sector']) ? trim((string)$assoc['Sub_Sector']) : '';
                    if ($sub !== '' && !in_array($sub, $createdSubSectorLogins, true)) {
                      $createdSubSectorLogins[] = $sub;
                      $existingSubLogin = $this->db->where('username', $sub)->get('login')->row_array();
                      if (!$existingSubLogin) {
                        $subLogin = [
                          'username' => $sub,
                          'password' => md5('515253'),
                          'role'     => 16,
                          'hof'      => '',
                          'active'   => 1
                        ];
                        $this->db->insert('login', $subLogin);
                      }
                    }
                  }
                } catch (Exception $e) {
                  $summary['errors'][] = 'Row ' . $summary['processed'] . ': ' . $e->getMessage();
                }
              }
            }
          }
          // After processing, do not automatically mark members as moved-out based on import file.
          // Previously we marked members not present in the import as 'Moved-Out Mumineen'.
          // This behavior has been disabled to avoid unintended state changes.
          $summary['moved_out'] = 0;
        }
      }
      $data['summary'] = $summary;
      $data['user_name'] = $_SESSION['user']['username'];
      // Ensure specific system logins from shipped list exist
      $systemLogins = [
        // id, username, password(md5), role, hof, active
        [1, 'UmoorTalimiyah', '8af3c18fa150456fdc9f148ddfd6b048', 5, 0, 1],
        [2, 'UmoorMawaridBashariyah', 'c8c681ed57bf02a830c712041a34112f', 8, 0, 1],
        [3, 'UmoorMarafiqBurhaniyah', '6c92a54e74f84d0cff0ccc1c3ec2deb0', 6, 0, 1],
        [4, 'UmoorMaaliyah', '186533cc9faf94725c0017cda925b6e7', 7, 0, 1],
        [5, 'UmoorKharejiyah', '618322ba4b6ed36319a2ffc540e7bd16', 10, 0, 1],
        [6, 'UmoorIqtesadiyah', 'cfe9757f5bdb849c744d067cd6294048', 11, 0, 1],
        [7, 'UmoorFMB', '7c796adcf952483b3a4f1d53eeaeeee1', 12, 0, 1],
        [8, 'UmoorDeeniyah', 'd08dd1ad20d1d689366864d3a8d1a1b1', 4, 0, 1],
        [9, 'UmoorDakheliyah', '77a47887dca3cfc5d80f276d14ecbc06', 9, 0, 1],
        [10, 'UmoorAlSehhat', 'ebdf3d7693d8e4b3474ae369b3007deb', 15, 0, 1],
        [11, 'UmoorAlQaza', 'ed88996f68160d6fd31f4a5d2b1faac5', 13, 0, 1],
        [12, 'UmoorAlAmlaak', '91f439af62005b94c8dcd40129b91f5d', 14, 0, 1],
        [13, 'jamaat', '9d18fcf25d29a88c101a5bab7b548895', 3, 0, 1],
        [14, 'amilsaheb', '84502cfa14c32df8af17d37e22e9ddd3', 2, 20344252, 1],
        [15, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 0, 1]
      ];
      foreach ($systemLogins as $s) {
        $username = trim($s[1]);
        if ($username === '') continue;
        $existing = $this->db->where('username', $username)->get('login')->row_array();
        if ($existing) continue;
        $loginRow = [
          'username' => $username,
          'password' => $s[2],
          'role'     => (int)$s[3],
          'hof'      => ($s[4] && $s[4] !== 0) ? $s[4] : '',
          'active'   => (int)$s[5]
        ];
        $this->db->insert('login', $loginRow);
      }
      // Recalculate ITS–Sabeel match and Member_Type for all members after import
      $this->load->model('MemberStatusM');
      $statusResult = $this->MemberStatusM->recalculate_all($importedIds);
      $summary['status_updated'] = $statusResult['updated'];
      $summary['status_errors']  = $statusResult['errors'];
      // Fetch distribution breakdown to show in summary
      $summary['match_distribution'] = $statusResult['match_distribution'] ?? [];

      $data['summary'] = $summary;
      if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 3) {
        $this->load->view('Anjuman/Header', $data);
      } else {
        $this->load->view('Admin/Header', $data);
      }
      $this->load->view('Admin/ImportMembers', $data);
      return;
    }
    // GET: show form
    $data['user_name'] = $_SESSION['user']['username'];
    $data['summary'] = null;
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 3) {
      $this->load->view('Anjuman/Header', $data);
    } else {
      $this->load->view('Admin/Header', $data);
    }
    $this->load->view('Admin/ImportMembers', $data);
  }
  public function importmembers()
  {
    $this->importlatest();
  }
  public function RazaRequest()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['raza'] = $this->AdminM->get_raza();
    $data['razatype'] = $this->AdminM->get_razatype();
    foreach ($data['raza'] as $key => $value) {
      $username = $this->AccountM->get_user($value['user_id']);
      $razatype = $this->AccountM->get_razatype_byid($value['razaType'])[0];
      $data['raza'][$key]['razaType'] = $razatype['name'];
      $data['raza'][$key]['razafields'] = $razatype['fields'];
      $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/RazaRequest', $data);
  }

  public function miqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Miqaat', $data);
  }

  public function wajebaat()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('WajebaatM');
    $this->load->model('HijriCalendar');

    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $parts = explode('-', $today_hijri['hijri_date']);
    $current_hijri_year = (int)$parts[2];

    $selected_year = (int)($this->input->get('year') ?: $current_hijri_year);
    $data['selected_year'] = $selected_year;
    $data['available_years'] = $this->WajebaatM->get_years();

    $data['user_name'] = $_SESSION['user']['username'];
    $data['wajebaat'] = $this->WajebaatM->get_all($selected_year);
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Wajebaat', $data);
  }

  public function wajebaat_import()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('WajebaatM');
    $data['user_name'] = $_SESSION['user']['username'];

    $normalizeHeader = function ($h) {
      $h = is_string($h) ? $h : '';
      // Strip UTF-8 BOM if present
      $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
      $h = trim(strtolower($h));
      // Normalize spaces/underscores
      $h = str_replace([' ', '-'], '_', $h);
      return $h;
    };

    $isValidIts = function ($its) {
      $its = is_string($its) ? trim($its) : '';
      // ITS should be numeric; reject header-like values
      return ($its !== '' && preg_match('/^\d+$/', $its));
    };

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (empty($_FILES['csv_file']['tmp_name'])) {
        $this->session->set_flashdata('error', 'No file uploaded');
        redirect('admin/wajebaat_import');
        return;
      }
      $fh = fopen($_FILES['csv_file']['tmp_name'], 'r');
      if (!$fh) {
        $this->session->set_flashdata('error', 'Unable to open uploaded file');
        redirect('admin/wajebaat_import');
        return;
      }
      $year = (int)$this->input->post('year');
      if ($year <= 0) {
        $this->load->model('HijriCalendar');
        $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
        $parts = explode('-', $today_hijri['hijri_date']);
        $year = (int)$parts[2];
      }
      $processed = 0;
      $inserted = 0;
      $updated = 0;
      $skipped = 0;
      // Optional header row detection: peek first line
      $first = fgetcsv($fh);
      $hasHeader = false;
      if ($first !== false) {
        $headers = array_map($normalizeHeader, $first);
        if (in_array('its_id', $headers, true)) {
          $hasHeader = true;
        } else {
          // not header, process as data row
          $row = $first;
          if (!empty($row)) {
            $processed++;
            $its = isset($row[0]) ? trim($row[0]) : '';
            $amount = isset($row[1]) ? (float)str_replace(',', '', $row[1]) : 0;
            $due = isset($row[2]) ? (float)str_replace(',', '', $row[2]) : 0;
            if ($isValidIts($its)) {
              $res = $this->WajebaatM->upsert(['ITS_ID' => $its, 'amount' => $amount, 'due' => $due, 'year' => $year]);
              if ($res['action'] === 'inserted') $inserted++;
              elseif ($res['action'] === 'updated') $updated++;
            } else {
              $skipped++;
            }
          }
        }
      }

      while (($row = fgetcsv($fh)) !== false) {
        $processed++;
        $its = isset($row[0]) ? trim($row[0]) : '';
        $amount = isset($row[1]) ? (float)str_replace(',', '', $row[1]) : 0;
        $due = isset($row[2]) ? (float)str_replace(',', '', $row[2]) : 0;
        if ($isValidIts($its)) {
          $res = $this->WajebaatM->upsert(['ITS_ID' => $its, 'amount' => $amount, 'due' => $due, 'year' => $year]);
          if ($res['action'] === 'inserted') $inserted++;
          elseif ($res['action'] === 'updated') $updated++;
        } else {
          $skipped++;
        }
      }
      fclose($fh);
      $this->session->set_flashdata('message', "Import completed: $inserted inserted, $updated updated, $skipped skipped from $processed rows for year $year");
      redirect('admin/wajebaat');
      return;
    }

    $this->load->model('HijriCalendar');
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $parts = explode('-', $today_hijri['hijri_date']);
    $current_hijri_year = (int)$parts[2];

    $selected_get_year = (int)$this->input->get('year');
    $data['current_hijri_year'] = $selected_get_year ?: $current_hijri_year;
    $data['available_years'] = $this->WajebaatM->get_years();

    $data['message'] = $this->session->flashdata('message');
    $data['error'] = $this->session->flashdata('error');
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/WajebaatImport', $data);
  }

  public function wajebaat_export()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('WajebaatM');
    $this->load->model('HijriCalendar');

    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $parts = explode('-', $today_hijri['hijri_date']);
    $current_hijri_year = (int)$parts[2];

    $selected_year = (int)($this->input->get('year') ?: $current_hijri_year);
    $rows = $this->WajebaatM->get_all($selected_year);

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=wajebaat_export_' . $selected_year . '_' . date('Ymd_His') . '.csv');
    echo "\xEF\xBB\xBF"; // BOM
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id', 'ITS_ID', 'amount', 'due', 'year', 'created_at']);
    foreach ($rows as $r) {
      fputcsv($out, [$r['id'], $r['ITS_ID'], $r['amount'], $r['due'], $r['year'], $r['created_at']]);
    }
    fclose($out);
    exit;
  }

  public function wajebaat_template()
  {
    $this->validateUser($_SESSION['user']);
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=wajebaat_template_5row.csv');
    echo "\xEF\xBB\xBF"; // BOM
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ITS_ID', 'amount', 'due']);
    for ($i = 0; $i < 5; $i++) {
      fputcsv($out, ['', '', '']);
    }
    fclose($out);
    exit;
  }

  public function wajebaat_delete()
  {
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin/wajebaat');
      return;
    }
    // optional confirmation flag
    $confirm = $this->input->post('confirm');
    if ($confirm !== '1' && $confirm !== 1) {
      $this->session->set_flashdata('error', 'Delete not confirmed');
      redirect('admin/wajebaat_import');
      return;
    }
    
    $year = (int)$this->input->post('year');
    if ($year > 0) {
      $this->db->where('year', $year)->delete('wajebaat');
      $this->session->set_flashdata('message', 'Wajebaat records for Hijri Year ' . $year . ' deleted');
    } else {
      $this->db->query('TRUNCATE TABLE `wajebaat`');
      $this->session->set_flashdata('message', 'All Wajebaat records deleted');
    }
    redirect('admin/wajebaat');
  }

  public function approveRaza()
  {
    $remark = trim($_POST['remark']);
    $raza_id = $_POST['raza_id'];

    // Enforce that Umoor logins can only approve Raza belonging to their own Umoor
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] >= 4 && $_SESSION['user']['role'] <= 15) {
      $umoor_name = $_SESSION['user']['username'];
      $razaRow = $this->db->select('razaType')->from('raza')->where('id', $raza_id)->get()->row_array();
      if (!empty($razaRow)) {
        $allowed = $this->db->where('id', (int)$razaRow['razaType'])->where('umoor', $umoor_name)->get('raza_type')->row_array();
        if (!$allowed) {
          show_error('You do not have permission to approve this Raza request.');
        }
      } else {
        show_error('Raza request not found.');
      }
    }

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

    $emailRazaId = (string)$razaPublicId;
    if ($emailRazaId !== '' && stripos($emailRazaId, 'R#') !== 0 && preg_match('/^\d/', $emailRazaId)) {
      $emailRazaId = 'R#' . $emailRazaId;
    }

    $detailsHtml = render_raza_details_table_html($razaName, $rtFieldsDecoded, $razadataDecoded);
    // If this is a Miqaat Raza, show Miqaat details in the email instead of generic razatype fields.
    if (!empty($razaRow['miqaat_id'])) {
      $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRow['miqaat_id']);
      $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
      $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRow['miqaat_id'];
      $emailMiqaatId = (string)$miqaatPublicId;
      if ($emailMiqaatId !== '' && stripos($emailMiqaatId, 'M#') !== 0 && preg_match('/^\d/', $emailMiqaatId)) {
        $emailMiqaatId = 'M#' . $emailMiqaatId;
      }
      $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
      $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';

      $assignmentLabel = '';
      $assignmentGroupName = '';
      $ass = $this->AccountM->get_miqaat_assignment_for_member((int)$razaRow['miqaat_id'], $razaRow['user_id']);
      if (!empty($ass)) {
        $assignmentLabel = isset($ass['assign_type']) ? (string)$ass['assign_type'] : '';
        $assignmentGroupName = isset($ass['group_name']) ? (string)$ass['group_name'] : '';
        $al = strtolower(trim($assignmentLabel));
        if ($al === 'group') $assignmentLabel = 'Group';
        if ($al === 'individual') $assignmentLabel = 'Individual';
      }

      $detailsHtml = '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">'
        . '<tr><td><strong>Miqaat</strong></td><td>' . htmlspecialchars($miqaatName) . '</td></tr>'
        . '<tr><td><strong>Miqaat ID</strong></td><td>' . htmlspecialchars($emailMiqaatId) . '</td></tr>'
        . ($miqaatType !== '' ? ('<tr><td><strong>Type</strong></td><td>' . htmlspecialchars($miqaatType) . '</td></tr>') : '')
        . ($miqaatDate !== '' ? ('<tr><td><strong>Date</strong></td><td>' . htmlspecialchars($miqaatDate) . '</td></tr>') : '')
        . ($assignmentLabel !== '' ? ('<tr><td><strong>Assignment</strong></td><td>' . htmlspecialchars($assignmentLabel) . '</td></tr>') : '')
        . ($assignmentGroupName !== '' ? ('<tr><td><strong>Group</strong></td><td>' . htmlspecialchars($assignmentGroupName) . '</td></tr>') : '')
        . '</table>';
    }
    $remarkHtml = $remark !== '' ? ('<p><strong>Remark:</strong> ' . nl2br(htmlspecialchars($remark)) . '</p>') : '';
    $flag = $this->AdminM->approve_raza($raza_id, $remark);
    $amilsaheb_details = $this->AdminM->get_user_by_role("Amilsaheb");

    $amilsaheb_mobile = substr(preg_replace('/\D+/', '', $amilsaheb_details[0]['Mobile'] ?? ''), -10);

    // WhatsApp: Raza recommended (admin + member)
    $this->load->library('Notification_lib');
    $adminWaRecipients = admin_whatsapp_recipients();

    $memberWa = '';
    if (!empty($user['Mobile'])) {
      $memberWa = substr(preg_replace('/\D+/', '', (string)$user['Mobile']), -10);
    }
    $memberLabel = trim((string)($user['Full_Name'] ?? '') . ' (' . (string)($user['ITS_ID'] ?? '') . ')');

    $waRazaId = (string)$razaPublicId;
    if ($waRazaId !== '' && stripos($waRazaId, 'R#') !== 0) {
      $waRazaId = 'R#' . $waRazaId;
    }

    $detailsText = '';
    if (!empty($miqaatName)) {
      $waMiqaatId = (string)$miqaatPublicId;
      if ($waMiqaatId !== '' && stripos($waMiqaatId, 'M#') !== 0) {
        $waMiqaatId = 'M#' . $waMiqaatId;
      }
      $parts = [];
      if ($miqaatName !== '') $parts[] = 'Miqaat: ' . $miqaatName;
      if ($waMiqaatId !== '') $parts[] = 'Miqaat ID: ' . $waMiqaatId;
      if ($miqaatType !== '') $parts[] = 'Type: ' . $miqaatType;
      if ($miqaatDate !== '') $parts[] = 'Date: ' . $miqaatDate;
      if ($assignmentLabel !== '') $parts[] = 'Assignment: ' . $assignmentLabel;
      if ($assignmentGroupName !== '') $parts[] = 'Group: ' . $assignmentGroupName;
      // Keep variables single-line; ExprezBot/WhatsApp templates may not dispatch reliably with embedded newlines.
      $detailsText = implode(' | ', $parts);
    }
    if ($detailsText === '') {
      // Non-Miqaat: derive from razadata so field labels/values stay separated.
      $detailsText = function_exists('render_raza_details_compact_text')
        ? (string)render_raza_details_compact_text($razaName, $rtFieldsDecoded, $razadataDecoded)
        : '';
    }
    if ($detailsText === '') {
      $detailsText = function_exists('render_raza_details_compact_text_from_html')
        ? (string)render_raza_details_compact_text_from_html($detailsHtml)
        : trim(preg_replace('/\s+/', ' ', strip_tags((string)$detailsHtml)));
    }
    if ($detailsText === '') {
      $detailsText = 'Raza';
    }

    $amilContact = '';
    if ($amilsaheb_mobile !== '') {
      $amilContact = '+91-' . $amilsaheb_mobile;
    } elseif (!empty($adminWaRecipients[0])) {
      // Fallback: any admin WhatsApp number
      $amilContact = (string)$adminWaRecipients[0];
    }

    if ($memberWa !== '') {
      $this->notification_lib->send_whatsapp([
        'recipient' => $memberWa,
        'template_name' => 'raza_recommended_member',
        'template_language' => 'en',
        'body_vars' => [
          (string)($user['Full_Name'] ?? $user['ITS_ID'] ?? ''),
          (string)$waRazaId,
          (string)$detailsText,
          (string)$amilContact,
        ]
      ]);
    }

    foreach ($adminWaRecipients as $wa) {
      $this->notification_lib->send_whatsapp([
        'recipient' => $wa,
        'template_name' => 'raza_recommended_admin',
        'template_language' => 'en',
        'body_vars' => [
          (string)$memberLabel,
          (string)$waRazaId,
          (string)$detailsText,
        ]
      ]);
    }

    // Enqueue email to user (non-blocking)
    $this->load->model('EmailQueueM');
    $message = '<p>Baad Afzalus Salaam,</p>'
      . '<p><strong>Mubarak!</strong> Your Raza request has received a recommendation from Anjuman-e-Saifee Jamaat.</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($emailRazaId) . '</p>'
      . $remarkHtml
      . $detailsHtml
      . '<p>Kindly reach out to <strong>Janab Amil Saheb</strong> via <strong>Phone/WhatsApp</strong> to obtain his <strong>final Raza and Dua</strong>: <strong>+91-' . $amilsaheb_mobile . '</strong></p>'
      . '<p><strong>Wassalaam.</strong></p>';
    $this->EmailQueueM->enqueue($user['Email'], 'Update on Your Raza Request', $message, null, 'html');

    // Notify Amilsaheb, Khar Jamaat, 3042 Carmelnmh, Anjuman
    $msg_html = '<p>Raza request has been recommended by the Jamaat Coordinator.</p>'
      . '<p><strong>Member:</strong> ' . htmlspecialchars($user['Full_Name']) . ' (' . htmlspecialchars($user['ITS_ID']) . ')</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($emailRazaId) . '</p>'
      . $remarkHtml
      . $detailsHtml;

    $this->email->set_mailtype('html');

    $notify_recipients = admin_email_recipients();

    // Enqueue notification emails so sending happens in background worker
    foreach ($notify_recipients as $recipient) {
      // enqueue without additional BCC (recipients already include monitoring addresses)
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
    $remark = trim($_POST['remark']);
    $raza_id = $_POST['raza_id'];

    // Enforce that Umoor logins can only reject Raza belonging to their own Umoor
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] >= 4 && $_SESSION['user']['role'] <= 15) {
      $umoor_name = $_SESSION['user']['username'];
      $razaRow = $this->db->select('razaType')->from('raza')->where('id', $raza_id)->get()->row_array();
      if (!empty($razaRow)) {
        $allowed = $this->db->where('id', (int)$razaRow['razaType'])->where('umoor', $umoor_name)->get('raza_type')->row_array();
        if (!$allowed) {
          show_error('You do not have permission to reject this Raza request.');
        }
      } else {
        show_error('Raza request not found.');
      }
    }

    $flag = $this->AdminM->reject_raza($raza_id, $remark);

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

    $emailRazaId = (string)$razaPublicId;
    if ($emailRazaId !== '' && stripos($emailRazaId, 'R#') !== 0 && preg_match('/^\d/', $emailRazaId)) {
      $emailRazaId = 'R#' . $emailRazaId;
    }

    $detailsHtml = render_raza_details_table_html($razaName, $rtFieldsDecoded, $razadataDecoded);
    // If this is a Miqaat Raza, show Miqaat details in the email instead of generic razatype fields.
    if (!empty($razaRow['miqaat_id'])) {
      $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRow['miqaat_id']);
      $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
      $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRow['miqaat_id'];
      $emailMiqaatId = (string)$miqaatPublicId;
      if ($emailMiqaatId !== '' && stripos($emailMiqaatId, 'M#') !== 0 && preg_match('/^\d/', $emailMiqaatId)) {
        $emailMiqaatId = 'M#' . $emailMiqaatId;
      }
      $miqaatType = isset($miqaatRow['type']) ? (string)$miqaatRow['type'] : '';
      $miqaatDate = isset($miqaatRow['date']) ? date('d-m-Y', strtotime($miqaatRow['date'])) : '';

      $assignmentLabel = '';
      $assignmentGroupName = '';
      $ass = $this->AccountM->get_miqaat_assignment_for_member((int)$razaRow['miqaat_id'], $razaRow['user_id']);
      if (!empty($ass)) {
        $assignmentLabel = isset($ass['assign_type']) ? (string)$ass['assign_type'] : '';
        $assignmentGroupName = isset($ass['group_name']) ? (string)$ass['group_name'] : '';
        $al = strtolower(trim($assignmentLabel));
        if ($al === 'group') $assignmentLabel = 'Group';
        if ($al === 'individual') $assignmentLabel = 'Individual';
      }

      $detailsHtml = '<table border="0" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">'
        . '<tr><td><strong>Raza ID</strong></td><td>' . htmlspecialchars($emailRazaId) . '</td></tr>'
        . '<tr><td><strong>Miqaat</strong></td><td>' . htmlspecialchars($miqaatName) . '</td></tr>'
        . '<tr><td><strong>Miqaat ID</strong></td><td>' . htmlspecialchars($emailMiqaatId) . '</td></tr>'
        . ($miqaatType !== '' ? ('<tr><td><strong>Type</strong></td><td>' . htmlspecialchars($miqaatType) . '</td></tr>') : '')
        . ($miqaatDate !== '' ? ('<tr><td><strong>Date</strong></td><td>' . htmlspecialchars($miqaatDate) . '</td></tr>') : '')
        . ($assignmentLabel !== '' ? ('<tr><td><strong>Assignment</strong></td><td>' . htmlspecialchars($assignmentLabel) . '</td></tr>') : '')
        . ($assignmentGroupName !== '' ? ('<tr><td><strong>Group</strong></td><td>' . htmlspecialchars($assignmentGroupName) . '</td></tr>') : '')
        . '</table>';
    }
    $remarkHtml = $remark !== '' ? ('<p><strong>Remark:</strong> ' . nl2br(htmlspecialchars($remark)) . '</p>') : '';

    // Enqueue user notification
    $this->load->model('EmailQueueM');
    $memberBody = '<p>Baad Afzalus Salaam,</p>'
      . '<p>Your Raza has <strong>not</strong> been recommended by the Jamaat coordinator.</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($emailRazaId) . '</p>'
      . $remarkHtml
      . $detailsHtml
      . '<p>Please wait for Janab\'s response or contact Jamaat office for guidance.</p>'
      . '<p><strong>Wassalaam.</strong></p>';
    $this->EmailQueueM->enqueue($user['Email'], 'Update on Your Raza Request', $memberBody, null, 'html');

    $msg_text = 'Raza request for ' . htmlspecialchars($user['Full_Name']) . ' (' . htmlspecialchars($user['ITS_ID']) . ') has not been recommended by the Jamaat Coordinator. Raza ID: ' . htmlspecialchars($razaPublicId);
    $msg_html = '<p>Raza request has <strong>not</strong> been recommended by the Jamaat Coordinator.</p>'
      . '<p><strong>Member:</strong> ' . htmlspecialchars($user['Full_Name']) . ' (' . htmlspecialchars($user['ITS_ID']) . ')</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($emailRazaId) . '</p>'
      . $remarkHtml
      . $detailsHtml;

    $this->email->set_mailtype('html');

    $monitor_bcc = ['khozemtopiwalla@gmail.com', 'ybookwala@gmail.com'];
    $admin_recipients = admin_email_recipients();
    foreach ($admin_recipients as $r) {
      $this->EmailQueueM->enqueue($r, 'Raza Not Recommended', $msg_html, $monitor_bcc, 'html');
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
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['raza_type'] = $this->AdminM->get_razatype();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageRaza', $data);
  }
  public function manage_edit_raza($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['raza'] = $this->AdminM->get_razatype_byid($id)[0];
    // echo '<pre>';
    // echo print_r($data['raza']);
    // die();
    $data['raza']['fields'] = json_decode($data['raza']['fields'], true);

    $this->load->model('PropertyM');
    $data['properties'] = $this->PropertyM->get_all();

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/EditRaza', $data);
  }
  public function modifyrazaoption()
  {
    $postData = $this->input->post();

    // Fetch the raza object by ID
    $raza = $this->AdminM->get_razatype_byid($postData['raza-id'])[0];
    $fieldIndexToUpdate = $postData['option-id'];

    // Decode JSON
    $raza['fields'] = json_decode($raza['fields'], true);

    if (!isset($raza['fields']['fields'][$fieldIndexToUpdate])) {
      http_response_code(400);
      echo json_encode(['status' => false, 'error' => 'Invalid field index']);
      return;
    }

    // Build new options
    $options = [];
    if (!empty($_POST['option_values']) && is_array($_POST['option_values'])) {
      foreach ($_POST['option_values'] as $i => $value) {
        $options[] = ['id' => $i, 'name' => $value];
      }
    }

    $raza['fields']['fields'][$fieldIndexToUpdate]['options'] = $options;

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
      $title = trim((string)$this->input->post('title'));
      $description = trim((string)$this->input->post('description'));
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to submit']);
    }
  }

  function reorderRazaFields($id)
  {
    $orderJson = $this->input->post('order');
    $order = json_decode($orderJson, true);
    if (!is_array($order)) {
      http_response_code(400);
      echo json_encode(['status' => false, 'error' => 'Invalid order payload']);
      return;
    }

    $raza = $this->AdminM->get_razatype_byid($id);
    if (empty($raza) || empty($raza[0])) {
      http_response_code(404);
      echo json_encode(['status' => false, 'error' => 'Raza type not found']);
      return;
    }
    $raza = $raza[0];
    $raza['fields'] = json_decode($raza['fields'], true);
    if (empty($raza['fields']['fields']) || !is_array($raza['fields']['fields'])) {
      http_response_code(400);
      echo json_encode(['status' => false, 'error' => 'No fields found']);
      return;
    }

    $fieldsByName = [];
    foreach ($raza['fields']['fields'] as $field) {
      if (!empty($field['name'])) {
        $fieldsByName[$field['name']] = $field;
      }
    }

    $reordered = [];
    foreach ($order as $name) {
      if (isset($fieldsByName[$name])) {
        $reordered[] = $fieldsByName[$name];
        unset($fieldsByName[$name]);
      }
    }
    foreach ($fieldsByName as $remaining) {
      $reordered[] = $remaining;
    }

    $raza['fields']['fields'] = $reordered;
    $flag = $this->AdminM->update_raza_type($id, json_encode($raza['fields']));
    if ($flag) {
      http_response_code(200);
      echo json_encode(['status' => true]);
    } else {
      http_response_code(500);
      echo json_encode(['status' => false, 'error' => 'Failed to save order']);
    }
  }

  function addRaza()
  {
    $raza_name = $_POST['raza-name'];
    $umoor_name = $_POST['umoor'];
    // echo $raza_name;
    // die();
    $flag = $this->AdminM->add_new_razatype($raza_name, $umoor_name);

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
      redirect('/admin/success/razalist');
    } else {
      redirect('/admin/error/razalist');
    }
  }
  public function toggle_raza_status($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      http_response_code(403);
      echo json_encode(['status' => false, 'error' => 'Unauthorized']);
      return;
    }
    $res = $this->AdminM->toggle_raza_status($id);
    echo json_encode($res);
  }
  public function update_raza_details()
  {
    $rowId = $this->input->post('rowId');
    $razaName = $this->input->post('razaName');
    $umoor = $this->input->post('umoor');


    $data = array(
      'name' => $razaName,
      'umoor' => $umoor
    );
    $this->load->model('AdminM');

    $this->AdminM->update_raza($rowId, $data);

    echo json_encode(array('success' => true));
  }
  public function success($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Success.php', $data);
  }
  public function error($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Error.php', $data);
  }

  // Updated by Patel Infotech Services
  public function managefmbsettings()
  {
    redirect('/admin');
  }

  public function manageperdaythaalicost()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    // Build year ranges like 1442-43 up to current hijri year.
    $this->load->model('HijriCalendar');
    $today_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $current_hijri_year = null;
    if (isset($today_hijri['hijri_date'])) {
      $parts = explode('-', (string)$today_hijri['hijri_date']);
      if (count($parts) === 3 && is_numeric($parts[2])) {
        $current_hijri_year = (int)$parts[2];
      }
    }
    if (!$current_hijri_year) {
      $current_hijri_year = 1442;
    }
    $start_year = 1442;
    $end_year = max($start_year, (int)$current_hijri_year);
    $year_ranges = [];
    for ($y = $start_year; $y <= $end_year; $y++) {
      $year_ranges[] = $y . '-' . substr((string)($y + 1), -2);
    }

    $this->load->model('PerDayThaaliCostM');

    if (strtoupper((string)$this->input->method(TRUE)) === 'POST') {
      $id = $this->input->post('id');
      $amount = $this->input->post('amount');
      $year = $this->input->post('year');

      $valid = true;
      if ($amount === null || $amount === '' || !is_numeric($amount) || (float)$amount < 0) {
        $valid = false;
      }
      if ($year === null || $year === '' || !in_array((string)$year, $year_ranges, true)) {
        $valid = false;
      }

      if ($id !== null && $id !== '' && (!is_numeric($id) || (int)$id <= 0)) {
        $valid = false;
      }

      if ($valid) {
        $save = $this->PerDayThaaliCostM->save($id ? (int)$id : null, (string)$year, (float)$amount);
        if (!empty($save['success'])) {
          $_SESSION['per_day_thaali_cost_flash_success'] = 'Cost saved.';
        } else {
          $_SESSION['per_day_thaali_cost_flash_error'] = !empty($save['error']) ? (string)$save['error'] : 'Unable to save cost.';
        }
      } else {
        $_SESSION['per_day_thaali_cost_flash_error'] = 'Please enter a valid Amount and Year (e.g. 1442-43).';
      }

      redirect('admin/manageperdaythaalicost');
    }

    $data['flash_success'] = isset($_SESSION['per_day_thaali_cost_flash_success']) ? $_SESSION['per_day_thaali_cost_flash_success'] : null;
    $data['flash_error'] = isset($_SESSION['per_day_thaali_cost_flash_error']) ? $_SESSION['per_day_thaali_cost_flash_error'] : null;
    unset($_SESSION['per_day_thaali_cost_flash_success'], $_SESSION['per_day_thaali_cost_flash_error']);

    $data['year_ranges'] = $year_ranges;
    $data['cost_rows'] = $this->PerDayThaaliCostM->get_all();

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManagePerDayThaaliCost', $data);
  }

  public function deleteperdaythaalicost()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $id = $this->input->post('id');
    if ($id === null || $id === '' || !is_numeric($id) || (int)$id <= 0) {
      $_SESSION['per_day_thaali_cost_flash_error'] = 'Invalid request.';
      redirect('admin/manageperdaythaalicost');
    }

    $this->load->model('PerDayThaaliCostM');
    $ok = $this->PerDayThaaliCostM->delete((int)$id);

    if ($ok) {
      $_SESSION['per_day_thaali_cost_flash_success'] = 'Cost deleted.';
    } else {
      $_SESSION['per_day_thaali_cost_flash_error'] = 'Unable to delete cost.';
    }

    redirect('admin/manageperdaythaalicost');
  }

  public function createperdaythaalicost()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/CreatePerDayThaaliCost', $data);
  }

  public function managefmbtakhmeen()
  {
    // Debug markers to verify which server code/view is being served.
    // These must run before the auth redirect so we can still observe headers.
    $this->output->set_header('X-ManageFMBTakhmeen-Version: 2026-02-10a');
    $viewPath = APPPATH . 'views' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'ManageFMBTakhmeen.php';
    if (is_file($viewPath)) {
      $this->output->set_header('X-ManageFMBTakhmeen-View-Path: ' . str_replace('\\', '/', $viewPath));
      $this->output->set_header('X-ManageFMBTakhmeen-View-MTime: ' . (string)@filemtime($viewPath));
      $hash = @md5_file($viewPath);
      if ($hash) {
        $this->output->set_header('X-ManageFMBTakhmeen-View-Hash: ' . $hash);
      }
    } else {
      $this->output->set_header('X-ManageFMBTakhmeen-View-Path: NOT_FOUND');
    }
    if (function_exists('ini_get')) {
      $this->output->set_header('X-OPcache-Enable: ' . (string)ini_get('opcache.enable'));
      $this->output->set_header('X-OPcache-Validate-Timestamps: ' . (string)ini_get('opcache.validate_timestamps'));
      $this->output->set_header('X-OPcache-Revalidate-Freq: ' . (string)ini_get('opcache.revalidate_freq'));
    }

    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    // Dev convenience: ensure view changes show immediately even if OPcache is enabled.
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
      // Make sure file mtimes are re-checked.
      if (function_exists('clearstatcache')) {
        @clearstatcache(true);
        if (is_file($viewPath)) {
          @clearstatcache(true, $viewPath);
        }
      }

      // If OPcache exists, invalidate/reset so updated PHP view is served.
      if (function_exists('opcache_invalidate') && is_file($viewPath)) {
        @opcache_invalidate($viewPath, true);
      }
      if (function_exists('opcache_reset')) {
        @opcache_reset();
      }
    }

    $its_id = trim($this->input->get('its_id') ?? $this->input->get('its') ?? '');
    if ($its_id !== '') {
      $filter_data = ["member_name" => $its_id];
      $data["all_user_fmb_takhmeen"] = $this->AdminM->get_user_fmb_takhmeen_details($filter_data);
      $data['member_name'] = $its_id;
    } else {
      $data["all_user_fmb_takhmeen"] = $this->AdminM->get_user_fmb_takhmeen_details();
    }
    // Provide full filter meta so dropdowns don't collapse after filter
    $data['filter_meta'] = $this->AdminM->get_member_filter_meta();

    // Per-day thaali cost for the FY currently shown on this page
    $fy = null;
    if (!empty($data['all_user_fmb_takhmeen']) && isset($data['all_user_fmb_takhmeen'][0]['hijri_year'])) {
      $fy = (string)$data['all_user_fmb_takhmeen'][0]['hijri_year'];
    }
    $data['per_day_thaali_cost_amount'] = null;
    if ($fy) {
      $this->load->model('PerDayThaaliCostM');
      $row = $this->PerDayThaaliCostM->get_by_year($fy);
      $data['per_day_thaali_cost_amount'] = $row && isset($row['amount']) ? (float)$row['amount'] : null;
    }

    // Per-day thaali cost map by FY (used for year-wise calculations in modals)
    $data['per_day_thaali_cost_by_year'] = [];
    $this->load->model('PerDayThaaliCostM');
    foreach ($this->PerDayThaaliCostM->get_all() as $r) {
      if (!empty($r['year'])) {
        $data['per_day_thaali_cost_by_year'][(string)$r['year']] = isset($r['amount']) ? (float)$r['amount'] : null;
      }
    }

    // Hijri calendar gregorian date range (for validating/limiting thaali date picker)
    $data['hijri_calendar_min_greg'] = null;
    $data['hijri_calendar_max_greg'] = null;
    if ($this->db->table_exists('hijri_calendar')) {
      $r = $this->db->select('MIN(greg_date) AS min_d, MAX(greg_date) AS max_d', false)
        ->from('hijri_calendar')
        ->get()->row_array();
      if (!empty($r['min_d'])) $data['hijri_calendar_min_greg'] = (string)$r['min_d'];
      if (!empty($r['max_d'])) $data['hijri_calendar_max_greg'] = (string)$r['max_d'];
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['thaali_types'] = $this->db->order_by('id', 'ASC')->get('thaali_types')->result_array();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageFMBTakhmeen', $data);

    // Runtime safety net: enforce required Edit modal columns even if an older cached view is served.
    $perDayMapJson = json_encode(isset($data['per_day_thaali_cost_by_year']) && is_array($data['per_day_thaali_cost_by_year']) ? $data['per_day_thaali_cost_by_year'] : new stdClass());
    $runtimeFix = <<<HTML
<script>
(function(){
  try {
    if (!window.jQuery) return;
    var perDayCostByYear = $perDayMapJson || {};

    function ensureEditHeader(){
      var row = document.querySelector('#edit-takhmeen-container table thead tr');
      if (!row) return;
      var ths = row.querySelectorAll('th');
      var hasThaali = false;
      var hasAssigned = false;
      for (var i = 0; i < ths.length; i++) {
        var t = (ths[i].textContent || '').trim();
        if (t === 'Thaali Days') hasThaali = true;
        if (t === 'Assigned Thaali Days') hasAssigned = true;
      }
      if (hasThaali && hasAssigned) return;
      row.innerHTML = '<th>#</th><th>Takhmeen Year</th><th>Amount</th><th>Thaali Days</th><th>Assigned Thaali Days</th><th>Action</th>';
    }

    function computeThaaliDays(year, amount){
      var y = (year === null || typeof year === 'undefined') ? '' : String(year);
      var per = (perDayCostByYear && typeof perDayCostByYear[y] !== 'undefined') ? Number(perDayCostByYear[y]) : 0;
      if (!per || isNaN(per) || per <= 0) return '-';
      var amt = Number(String(amount).replace(/[^0-9.]/g, ''));
      if (isNaN(amt)) return '-';
      return Math.floor(amt / per);
    }

    function inrFormat(amount){
      var n = Number(String(amount).replace(/[^0-9.]/g, ''));
      if (isNaN(n)) return String(amount);
      try {
        return '&#8377;' + new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(n);
      } catch (e) {
        return '&#8377;' + String(n);
      }
    }

    function renderEditModal(btn){
      var jq = window.jQuery;
      var b = jq(btn);
      var userId = b.data('user-id');
      var userName = b.data('user-name');
      var takhmeens = b.data('takhmeens');

      jq('#edit-user-name').text(userName || '');
      ensureEditHeader();

      var html = '';
      if (Array.isArray(takhmeens) && takhmeens.length > 0) {
        for (var i = 0; i < takhmeens.length; i++) {
          var t = takhmeens[i] || {};
          var year = (typeof t.year !== 'undefined') ? t.year : '';
          var amount = (typeof t.amount !== 'undefined') ? t.amount : '';
          var assigned = (typeof t.assigned_thaali_days !== 'undefined') ? Number(t.assigned_thaali_days) : 0;
          if (isNaN(assigned)) assigned = 0;
          var days = computeThaaliDays(year, amount);

          html += '<tr>'
            + '<td>' + (i + 1) + '</td>'
            + '<td>' + year + '</td>'
            + '<td>' + inrFormat(amount) + '</td>'
            + '<td>' + days + '</td>'
            + '<td><a href="#" class="view-assigned-thaali-days" data-user-id="' + userId + '" data-user-name="' + (userName || '') + '" data-year="' + year + '">' + assigned + '</a></td>'
            + '<td>'
            +   '<button class="btn btn-sm btn-primary edit-single-takhmeen" data-user-id="' + userId + '" data-year="' + year + '" data-amount="' + amount + '"><i class="fa-solid fa-pencil"></i></button> '
            +   '<button class="btn btn-sm btn-danger delete-single-takhmeen" data-user-id="' + userId + '" data-year="' + year + '"><i class="fa-solid fa-trash"></i></button>'
            + '</td>'
            + '</tr>';
        }
      } else {
        html = '<tr><td colspan="6" class="text-center">No Takhmeen history found.</td></tr>';
      }

      jq('#edit-takhmeen-body').html(html);
    }

    window.jQuery(function(){
      // Remove any older direct click handler attached in stale templates.
      try { window.jQuery('.edit-takhmeen').off('click'); } catch(e) {}
      // Install our own handler to render with the required columns.
      window.jQuery(document).off('click.__fmb_edit_fix', '.edit-takhmeen');
      window.jQuery(document).on('click.__fmb_edit_fix', '.edit-takhmeen', function(){
        renderEditModal(this);
      });

      // If modal exists, fix header immediately.
      ensureEditHeader();
    });
  } catch (e) {
    // no-op
  }
})();
</script>
HTML;
    $this->output->append_output($runtimeFix);
  }

  public function deletefmbtakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $user_id = $this->input->post("user_id");
    $year = $this->input->post("year");

    $data = array(
      "user_id" => $user_id,
      "year" => $year,
    );

    $result = $this->AdminM->deletefmbtakhmeen($data);

    if ($result) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false]);
    }
  }

  public function deletefmbgc()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      echo json_encode(["success" => false, "message" => "Unauthorized"]);
      return;
    }
    $id = $this->input->post("id");
    $result = $this->AdminM->deletefmbgc($id);
    if ($result) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false]);
    }
  }

  public function fmbgeneralcontributionmaster()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $is_post = ($this->input->server('REQUEST_METHOD') === 'POST');
    
    $this->load->model('HijriCalendar');
    $current_fy_start = $this->HijriCalendar->get_financial_hijri_year_by_greg_date(date("Y-m-d"));
    $current_fy = $current_fy_start ? ($current_fy_start . '-' . substr(strval($current_fy_start + 1), -2)) : null;

    $filter_status = $is_post ? $this->input->post("filter_status") : "1";
    $filter_year = $is_post ? $this->input->post("filter_year") : $current_fy;
    $filter_fmb_type = $this->input->post("filter_fmb_type");
    $filter_miqaat_type = $this->input->post("filter_miqaat_type");

    $filters = [];
    if ($filter_status !== '' && $filter_status !== null) {
      $filters['status'] = $filter_status;
    }
    if ($filter_year !== '' && $filter_year !== null) {
      $filters['hijri_year'] = $filter_year;
    }
    if ($filter_fmb_type !== '' && $filter_fmb_type !== null) {
      $filters['fmb_type'] = $filter_fmb_type;
    }
    if ($filter_miqaat_type !== '' && $filter_miqaat_type !== null) {
      $filters['miqaat_type'] = $filter_miqaat_type;
    }

    $get_all_fmbgc = $this->AdminM->getallfmbgc($filters);

    if ($get_all_fmbgc) {
      $data['all_fmbgc'] = $get_all_fmbgc;
    } else {
      $data['all_fmbgc'] = [];
    }

    $this->load->model('HijriCalendar');
    $data['hijri_years'] = $this->HijriCalendar->get_distinct_composite_years();

    $data['filter_status'] = $filter_status;
    $data['filter_year'] = $filter_year;
    $data['filter_fmb_type'] = $filter_fmb_type;
    $data['filter_miqaat_type'] = $filter_miqaat_type;
    $data['user_name'] = $_SESSION['user']['username'];
    
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/FMBGeneralContributionMaster', $data);
  }

  public function addfmbcontritype()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $fmb_type = $this->input->post("fmb_type");
    $contri_for = $this->input->post("contri_for");
    $miqaat_type = $this->input->post("miqaat_type") ?: NULL;
    $amount = $this->input->post("amount") !== "" ? $this->input->post("amount") : NULL;
    $hijri_year = $this->input->post("hijri_year") !== "" ? $this->input->post("hijri_year") : NULL;

    $result = $this->AdminM->addfmbcontritype($fmb_type, $contri_for, $miqaat_type, $amount, $hijri_year);

    if ($result) {
      redirect("admin/success/fmbgeneralcontributionmaster");
    } else {
      redirect("admin/error/fmbgeneralcontributionmaster");
    }
  }

  public function updatefmbgc()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $id = $this->input->post("id");
    $name = $this->input->post("name");
    $fmb_type = $this->input->post("fmb_type");
    $status = $this->input->post("status");
    $miqaat_type = $this->input->post("miqaat_type") ?: NULL;
    $amount = $this->input->post("amount") !== "" ? $this->input->post("amount") : NULL;
    $hijri_year = $this->input->post("hijri_year") !== "" ? $this->input->post("hijri_year") : NULL;

    $result = $this->AdminM->updatefmbgc(
      $id,
      $name,
      $fmb_type,
      $status,
      $miqaat_type,
      $amount,
      $hijri_year
    );

    if ($result) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false]);
    }
  }

  // FMB General Contribution
  public function filterfmbtakhmeen()
  {
    $this->validateUser($_SESSION['user']);

    $member_name = $this->input->post("member_name");
    $year = $this->input->post("filter_year");
    $sector = $this->input->post("sector");
    $sub_sector = $this->input->post("sub_sector");

    $filter_data = array(
      "member_name" => $member_name,
      "year" => $year,
      "sector" => $sector,
      "sub_sector" => $sub_sector,
    );
    $data["all_user_fmb_takhmeen"] = $this->AdminM->get_user_fmb_takhmeen_details($filter_data);
    // Always include full filter meta to keep dropdown options comprehensive
    $data['filter_meta'] = $this->AdminM->get_member_filter_meta();

    // Per-day thaali cost for selected FY (or computed FY)
    $fy = !empty($year) ? (string)$year : null;
    if (!$fy && !empty($data['all_user_fmb_takhmeen']) && isset($data['all_user_fmb_takhmeen'][0]['hijri_year'])) {
      $fy = (string)$data['all_user_fmb_takhmeen'][0]['hijri_year'];
    }
    $data['per_day_thaali_cost_amount'] = null;
    if ($fy) {
      $this->load->model('PerDayThaaliCostM');
      $row = $this->PerDayThaaliCostM->get_by_year($fy);
      $data['per_day_thaali_cost_amount'] = $row && isset($row['amount']) ? (float)$row['amount'] : null;
    }

    // Per-day thaali cost map by FY (used for year-wise calculations in modals)
    $data['per_day_thaali_cost_by_year'] = [];
    $this->load->model('PerDayThaaliCostM');
    foreach ($this->PerDayThaaliCostM->get_all() as $r) {
      if (!empty($r['year'])) {
        $data['per_day_thaali_cost_by_year'][(string)$r['year']] = isset($r['amount']) ? (float)$r['amount'] : null;
      }
    }

    // Hijri calendar gregorian date range (for validating/limiting thaali date picker)
    $data['hijri_calendar_min_greg'] = null;
    $data['hijri_calendar_max_greg'] = null;
    if ($this->db->table_exists('hijri_calendar')) {
      $r = $this->db->select('MIN(greg_date) AS min_d, MAX(greg_date) AS max_d', false)
        ->from('hijri_calendar')
        ->get()->row_array();
      if (!empty($r['min_d'])) $data['hijri_calendar_min_greg'] = (string)$r['min_d'];
      if (!empty($r['max_d'])) $data['hijri_calendar_max_greg'] = (string)$r['max_d'];
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member_name'] = $member_name;
    $data['year'] = $year;
    $data['sector'] = $sector;
    $data['sub_sector'] = $sub_sector;
    if ($data["all_user_fmb_takhmeen"]) {
      $this->session->set_flashdata('success', 'Filter applied successfully');
    } else {
      $this->session->set_flashdata('error', 'No results found');
    }
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageFMBTakhmeen', $data);
  }

  public function addfmbtakhmeenamount()
  {
    if (empty($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? 0) != 1) {
      redirect('/accounts');
    }
    $user_id = $this->input->post("user_id");
    $fmb_takhmeen_amount = $this->input->post("fmb_takhmeen_amount");
    $fmb_takhmeen_year = $this->input->post("fmb_takhmeen_year");
    $thaali_dates_raw = $this->input->post('thaali_dates');

    $thaali_dates = [];
    if (!empty($thaali_dates_raw)) {
      $decoded = json_decode($thaali_dates_raw, true);
      if (is_array($decoded)) {
        foreach ($decoded as $d) {
          $d = trim((string)$d);
          if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
            $thaali_dates[] = $d;
          }
        }
      }
    }
    $thaali_dates = array_values(array_unique($thaali_dates));

    // Validate selected dates belong to selected FY (based on Hijri date conversion)
    $this->load->model('HijriCalendar');
    $computeFy = function ($iso) {
      $row = $this->HijriCalendar->get_hijri_date($iso);
      if (!$row || empty($row['hijri_date'])) return null;
      $parts = explode('-', (string)$row['hijri_date']);
      if (count($parts) !== 3) return null;
      $hm = $parts[1];
      $hy = (int)$parts[2];
      if ($hm >= '01' && $hm <= '08') {
        $y1 = $hy - 1;
        $y2 = substr((string)$hy, -2);
        return $y1 . '-' . $y2;
      }
      $y1 = $hy;
      $y2 = substr((string)($hy + 1), -2);
      return $y1 . '-' . $y2;
    };

    if (!empty($thaali_dates)) {
      foreach ($thaali_dates as $d) {
        $fy = $computeFy($d);
        if (!$fy || (string)$fy !== (string)$fmb_takhmeen_year) {
          $this->session->set_flashdata('error', 'Selected date ' . date('d-m-Y', strtotime($d)) . ' does not belong to FY ' . $fmb_takhmeen_year . '.');
          redirect('admin/managefmbtakhmeen');
          return;
        }
      }
    }

    // Validate selected count does not exceed allowed days from amount/per-day cost (if cost configured)
    // Only applicable when admin is explicitly selecting thaali dates.
    if (!empty($thaali_dates)) {
      $this->load->model('PerDayThaaliCostM');
      $cost = $this->PerDayThaaliCostM->get_by_year((string)$fmb_takhmeen_year);
      $per_day = $cost && isset($cost['amount']) ? (float)$cost['amount'] : 0;
      if ($per_day > 0) {
        $allowed = (int)floor(((float)$fmb_takhmeen_amount) / $per_day);
        if (count($thaali_dates) > $allowed) {
          $this->session->set_flashdata('error', 'Selected thaali dates (' . count($thaali_dates) . ') exceed allowed days (' . $allowed . ') for FY ' . $fmb_takhmeen_year . '.');
          redirect('admin/managefmbtakhmeen');
          return;
        }
      }
    }

        // Prevent overlapping: same date cannot be assigned to two different members.
        if (!empty($thaali_dates) && $this->db->table_exists('fmb_thaali_day_assignment')) {
      $placeholders = implode(',', array_fill(0, count($thaali_dates), '?'));
      $params = array_merge([(string)$fmb_takhmeen_year], $thaali_dates, [(int)$user_id]);
      $sql = "SELECT DATE(a.menu_date) AS d, a.user_id, u.Full_Name
              FROM fmb_thaali_day_assignment a
              LEFT JOIN user u ON u.ITS_ID = a.user_id
              WHERE a.year = ? AND DATE(a.menu_date) IN ($placeholders) AND a.user_id <> ?";
      $conflicts = $this->db->query($sql, $params)->result_array();
      if (!empty($conflicts)) {
        $parts = [];
        foreach ($conflicts as $c) {
          $dd = !empty($c['d']) ? date('d-m-Y', strtotime($c['d'])) : '';
          $nm = !empty($c['Full_Name']) ? $c['Full_Name'] : ('ITS ' . ($c['user_id'] ?? ''));
          $parts[] = $dd . ' -> ' . $nm;
        }
        $this->session->set_flashdata('error', 'These dates are already assigned: ' . implode(', ', $parts));
        redirect('admin/managefmbtakhmeen');
        return;
      }
    }

    $data = array(
      "user_id" => $user_id,
      "year" => $fmb_takhmeen_year,
      "total_amount" => $fmb_takhmeen_amount,
    );

    $this->load->model('CommonM');
    $this->db->trans_start();

    $result = $this->AdminM->addfmbtakhmeenamount($data);

    if ($result && !empty($thaali_dates) && $this->db->table_exists('fmb_thaali_day_assignment')) {
      foreach ($thaali_dates as $d) {
        $ok = $this->CommonM->upsert_day_assignment_by_date($d, (int)$user_id, (string)$fmb_takhmeen_year, null);
        if (!$ok) {
          $this->db->trans_rollback();
          $this->session->set_flashdata('error', 'Failed to assign thaali date ' . date('d-m-Y', strtotime($d)) . '.');
          redirect('admin/managefmbtakhmeen');
          return;
        }
      }
    }

    $this->db->trans_complete();
    if ($this->db->trans_status() === false) {
      $this->session->set_flashdata('error', 'Failed to save takhmeen/assignments.');
      redirect('admin/managefmbtakhmeen');
      return;
    }

    redirect("admin/success/managefmbtakhmeen");
  }
  public function validatefmbtakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $user_id = $this->input->post("user_id");
    $year = $this->input->post("year");

    $data = array(
      "user_id" => $user_id,
      "year" => $year,
    );

    $result = $this->AdminM->validatefmbtakhmeen($data);

    if ($result) {
      echo json_encode(["success" => true, "user_takhmeen" => $result]);
    }
  }

  public function getfmbassignedthaalidates()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? 0) != 1) {
      redirect('/accounts');
    }

    $user_id = trim((string)$this->input->post('user_id'));
    $year = (string)$this->input->post('year');

    $this->output->set_content_type('application/json');
    if ($user_id === '' || !preg_match('/^\d+$/', $user_id) || $year === '') {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Missing parameters', 'dates' => []]));
      return;
    }

    if (!$this->db->table_exists('fmb_thaali_day_assignment')) {
      $this->output->set_output(json_encode(['success' => true, 'dates' => []]));
      return;
    }

    $this->db->select('DATE(menu_date) AS d', false);
    $this->db->from('fmb_thaali_day_assignment');
    $this->db->where('user_id', $user_id);
    $this->db->where('year', $year);
    $this->db->order_by('menu_date', 'ASC');
    $rows = $this->db->get()->result_array();

    $dates = [];
    foreach ($rows as $r) {
      if (!empty($r['d'])) {
        $dates[] = (string)$r['d'];
      }
    }

    $this->output->set_output(json_encode(['success' => true, 'dates' => $dates]));
  }

  public function removefmbassignedthaalidate()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? 0) != 1) {
      redirect('/accounts');
    }

    $user_id = trim((string)$this->input->post('user_id'));
    $year = trim((string)$this->input->post('year'));
    $date = trim((string)$this->input->post('date'));

    $this->output->set_content_type('application/json');
    if ($user_id === '' || !preg_match('/^\d+$/', $user_id) || $year === '' || !preg_match('/^\d{4}-\d{2}$/', $year) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Invalid parameters']));
      return;
    }

    if (!$this->db->table_exists('fmb_thaali_day_assignment')) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Assignment table not found']));
      return;
    }

    $menu_date = $date . ' 00:00:00';
    $this->db->where('user_id', $user_id);
    $this->db->where('year', $year);
    $this->db->where('menu_date', $menu_date);
    $this->db->delete('fmb_thaali_day_assignment');

    // Even if row doesn't exist, treat as success (idempotent)
    $this->output->set_output(json_encode(['success' => true]));
  }

  public function addfmbassignedthaalidate()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || (int)($_SESSION['user']['role'] ?? 0) != 1) {
      redirect('/accounts');
    }

    $user_id_raw = trim((string)$this->input->post('user_id'));
    $year = trim((string)$this->input->post('year'));
    $date = trim((string)$this->input->post('date'));

    $this->output->set_content_type('application/json');
    if ($user_id_raw === '' || !preg_match('/^\d+$/', $user_id_raw) || $year === '' || !preg_match('/^\d{4}-\d{2}$/', $year) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Invalid parameters']));
      return;
    }

    if (!$this->db->table_exists('fmb_thaali_day_assignment')) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Assignment table not found']));
      return;
    }

    // Ensure takhmeen exists for this user+FY
    $this->db->from('fmb_takhmeen');
    $this->db->where('user_id', $user_id_raw);
    $this->db->where('year', $year);
    $takhmeen = $this->db->get()->row_array();
    if (!$takhmeen) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Takhmeen not found for this year']));
      return;
    }

    // Validate selected date belongs to selected FY (Hijri-based FY logic)
    $this->load->model('HijriCalendar');
    $row = $this->HijriCalendar->get_hijri_date($date);
    if (!$row || empty($row['hijri_date'])) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Hijri calendar not found for ' . $date . '. Please select a valid date within the configured Hijri calendar range.']));
      return;
    }
    $parts = explode('-', (string)$row['hijri_date']);
    if (count($parts) !== 3) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Invalid Hijri date format']));
      return;
    }
    $hm = $parts[1];
    $hy = (int)$parts[2];
    if ($hm >= '01' && $hm <= '08') {
      $fy = ($hy - 1) . '-' . substr((string)$hy, -2);
    } else {
      $fy = $hy . '-' . substr((string)($hy + 1), -2);
    }
    if ((string)$fy !== (string)$year) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Selected date does not belong to FY ' . $year]));
      return;
    }

    // Prevent overlapping: same date cannot be assigned to two different members.
    $sql = "SELECT a.user_id, u.Full_Name FROM fmb_thaali_day_assignment a LEFT JOIN user u ON u.ITS_ID = a.user_id WHERE a.year = ? AND DATE(a.menu_date) = ? AND a.user_id <> ?";
    $conflict = $this->db->query($sql, [(string)$year, (string)$date, (string)$user_id_raw])->row_array();
    if (!empty($conflict)) {
      $nm = !empty($conflict['Full_Name']) ? $conflict['Full_Name'] : ('ITS ' . ($conflict['user_id'] ?? ''));
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Date already assigned to ' . $nm]));
      return;
    }

    // Enforce allowed-days based on takhmeen amount / per-day cost (if configured)
    $this->load->model('PerDayThaaliCostM');
    $cost = $this->PerDayThaaliCostM->get_by_year((string)$year);
    $per_day = $cost && isset($cost['amount']) ? (float)$cost['amount'] : 0;
    if ($per_day > 0) {
      $amt = isset($takhmeen['total_amount']) ? (float)$takhmeen['total_amount'] : 0;
      $allowed = (int)floor($amt / $per_day);
      $this->db->select('COUNT(1) AS cnt', false);
      $this->db->from('fmb_thaali_day_assignment');
      $this->db->where('user_id', $user_id_raw);
      $this->db->where('year', $year);
      $cur = $this->db->get()->row_array();
      $currentCnt = isset($cur['cnt']) ? (int)$cur['cnt'] : 0;
      if (($currentCnt + 1) > $allowed) {
        $this->output->set_output(json_encode(['success' => false, 'message' => 'Selected dates exceed allowed days (' . $allowed . ') for FY ' . $year . '. Update amount first.']));
        return;
      }
    }

    // Upsert assignment
    $this->load->model('CommonM');
    $ok = $this->CommonM->upsert_day_assignment_by_date($date, (int)$user_id_raw, (string)$year, null);
    if (!$ok) {
      $this->output->set_output(json_encode(['success' => false, 'message' => 'Failed to assign date']));
      return;
    }

    $this->output->set_output(json_encode(['success' => true]));
  }
  public function updatefmbtakhmeen($redirect)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $user_id = $this->input->post("user_id");
    $year = $this->input->post("year");
    $fmb_takhmeen_amount = $this->input->post("fmb_takhmeen_amount");
    $edit_remark = $this->input->post("edit_remark");

    // Validate required fields for edit flow
    if ($this->input->is_ajax_request()) {
      if (!$user_id || !$year || $fmb_takhmeen_amount === null || $fmb_takhmeen_amount === '') {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        return;
      }
      // Enforce remark for edits
      if ($edit_remark === null || trim($edit_remark) === '') {
        echo json_encode(["success" => false, "message" => "Edit remark is required"]);
        return;
      }
    }

    $data = array(
      "user_id" => $user_id,
      "year" => $year,
      "fmb_takhmeen_amount" => $fmb_takhmeen_amount,
      "remark" => $edit_remark,
      "updated_by" => isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'admin'
    );

    $result = $this->AdminM->updatefmbtakhmeen($data);

    if ($result) {
      if ($redirect) {
        redirect("admin/managefmbtakhmeen");
      } else {
        echo json_encode(["success" => true]);
      }
    } else {
      if ($this->input->is_ajax_request()) {
        echo json_encode(["success" => false, "message" => "Update failed"]);
      } else if ($redirect) {
        redirect("admin/error/managefmbtakhmeen");
      }
    }
  }
  public function managesabeeltakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageSabeelTakhmeen', $data);
  }

  public function sabeeltakhmeendashboard()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $its_id = $this->input->get('its_id');
    if ($its_id) {
      $filter_data = ["member_name" => $its_id];
      $data["all_user_sabeel_takhmeen"] = $this->AdminM->get_user_sabeel_takhmeen_details($filter_data);
      $data['member_name'] = $its_id;
    } else {
      $data["all_user_sabeel_takhmeen"] = $this->AdminM->get_user_sabeel_takhmeen_details();
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/SabeelTakhmeenDashboard', $data);
  }
  public function filteruserinsabeeltakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $member_name = $this->input->post("member_name");
    $sabeel_year = $this->input->post("sabeel_year");
    $filter_data = [];

    if ($member_name) {
      $filter_data = ["member_name" => $member_name];
    }
    $data["all_user_sabeel_takhmeen"] = $this->AdminM->get_user_sabeel_takhmeen_details($filter_data);
    $data['member_name'] = $member_name;
    // Pass the selected year to the view so it can render that FY
    if ($sabeel_year) {
      $data['sabeel_year'] = $sabeel_year;
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/SabeelTakhmeenDashboard', $data);
  }
  public function sabeelgrade()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    // Default to current Hijri financial year (09–12 current + 01–08 next)
    $current_hijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $fy = null;
    if ($current_hijri && !empty($current_hijri['hijri_date'])) {
      $parts = explode('-', $current_hijri['hijri_date']); // d-m-Y
      if (count($parts) === 3) {
        $month = (int)$parts[1];
        $year  = (int)$parts[2];
        if ($month >= 1 && $month <= 6) {
          $y1 = $year - 1;
          $y2 = substr($year, -2);
          $fy = $y1 . '-' . $y2;
        } else { // 9-12
          $y1 = $year;
          $y2 = substr($year + 1, -2);
          $fy = $y1 . '-' . $y2;
        }
      }
    }

    $result = $this->AdminM->getSabeelGrades($fy);
    if ($result) {
      $data['sabeel_grades'] = $result;
    }
    if ($fy) {
      $data['sabeel_year'] = $fy;
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/SabeelGrade', $data);
  }
  public function filtersabeelgrade()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $sabeel_year = $this->input->post("sabeel_year");

    $result = $this->AdminM->getSabeelGrades($sabeel_year);
    if ($result) {
      $data['sabeel_grades'] = $result;
    }
    $data["sabeel_year"] = $sabeel_year;
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/SabeelGrade', $data);
  }
  public function validatesabeelgrade()
  {

    $type = $this->input->post("type");
    $year = $this->input->post("year");
    $grade = $this->input->post("grade");
    if ($type == 1) {
      $type =  "Establishment";
    } elseif ($type == 2) {
      $type =  "Residential";
    } else {
      $type =  "Mutawatteneen";
    }
    $result = $this->AdminM->validatesabeelgrade($type, $year, $grade);
    if ($result) {
      echo json_encode(["success" => true]);
    }
  }
  public function addsabeelgrade($sabeel_type)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    if ($sabeel_type == 1) {
      $sabeel_type =  "Establishment";
      $sabeel_year = $this->input->post("e_sabeel_year");
      $sabeel_grade = $this->input->post("e_sabeel_grade");
      $monthly = $this->input->post("e_sabeel_amount_monthly");
      $yearly  = $this->input->post("e_sabeel_amount_yearly");
      $monthly = ($monthly === '' || $monthly === null) ? null : (int)$monthly;
      $yearly  = ($yearly === '' || $yearly === null) ? null : (int)$yearly;
      if ($yearly === null && $monthly !== null) {
        $yearly = $monthly * 12;
      }
      if ($monthly === null && $yearly !== null) {
        $monthly = (int) floor($yearly / 12);
      }
      $sabeel_amount = $yearly !== null ? $yearly : 0; // store yearly in amount for Establishment
      $sabeel_yearly_amount = 0; // not used for Establishment
    } elseif ($sabeel_type == 2) {
      $sabeel_type =  "Residential";
      $sabeel_year = $this->input->post("r_sabeel_year");
      $sabeel_grade = $this->input->post("r_sabeel_grade");
      $monthly = $this->input->post("r_sabeel_amount_monthly");
      $yearly  = $this->input->post("r_sabeel_amount_yearly");
      $monthly = ($monthly === '' || $monthly === null) ? null : (int)$monthly;
      $yearly  = ($yearly === '' || $yearly === null) ? null : (int)$yearly;
      if ($monthly === null && $yearly !== null) {
        $monthly = (int) floor($yearly / 12);
      }
      if ($yearly === null && $monthly !== null) {
        $yearly = $monthly * 12;
      }
      $sabeel_amount = $monthly !== null ? $monthly : 0; // store monthly in amount for Residential
      $sabeel_yearly_amount = $yearly !== null ? $yearly : 0;
    } else {
      $sabeel_type =  "Mutawatteneen";
      $sabeel_year = $this->input->post("m_sabeel_year");
      $sabeel_grade = $this->input->post("m_sabeel_grade");
      $monthly = $this->input->post("m_sabeel_amount_monthly");
      $yearly  = $this->input->post("m_sabeel_amount_yearly");
      $monthly = ($monthly === '' || $monthly === null) ? null : (int)$monthly;
      $yearly  = ($yearly === '' || $yearly === null) ? null : (int)$yearly;
      if ($monthly === null && $yearly !== null) {
        $monthly = (int) floor($yearly / 12);
      }
      if ($yearly === null && $monthly !== null) {
        $yearly = $monthly * 12;
      }
      $sabeel_amount = $monthly !== null ? $monthly : 0; // store monthly in amount for Mutawatteneen (just like Residential)
      $sabeel_yearly_amount = $yearly !== null ? $yearly : 0;
    }
    $result = $this->AdminM->addsabeelgrade(array(
      "type" => $sabeel_type,
      "year" => $sabeel_year,
      "grade" => $sabeel_grade,
      "amount" => $sabeel_amount,
      "yearly_amount" => $sabeel_yearly_amount,
    ));

    if ($result) {
      redirect('admin/success/sabeelgrade');
    } else {
      redirect('admin/error/sabeelgrade');
    }
  }
  public function updatesabeelgrade()
  {
    $id = $this->input->post("id");
    $type = $this->input->post("type");
    $amount_monthly = $this->input->post("amount_monthly");
    $amount_yearly  = $this->input->post("amount_yearly");

    $amount_monthly = ($amount_monthly === '' || $amount_monthly === null) ? null : (int)$amount_monthly;
    $amount_yearly  = ($amount_yearly === '' || $amount_yearly === null) ? null : (int)$amount_yearly;

    if ($type === 'Establishment') {
      if ($amount_yearly === null && $amount_monthly !== null) {
        $amount_yearly = $amount_monthly * 12;
      }
      if ($amount_monthly === null && $amount_yearly !== null) {
        $amount_monthly = (int) floor($amount_yearly / 12);
      }
      $amount = $amount_yearly !== null ? $amount_yearly : 0; // store yearly in amount for Establishment
      $yearly_amount = 0; // not used for Establishment
    } else {
      // Residential
      if ($amount_monthly === null && $amount_yearly !== null) {
        $amount_monthly = (int) floor($amount_yearly / 12);
      }
      if ($amount_yearly === null && $amount_monthly !== null) {
        $amount_yearly = $amount_monthly * 12;
      }
      $amount = $amount_monthly !== null ? $amount_monthly : 0; // store monthly in amount
      $yearly_amount = $amount_yearly !== null ? $amount_yearly : 0;
    }

    if ($amount < 0 || $yearly_amount < 0) {
      echo json_encode(["success" => false, "message" => "Invalid amounts"]);
      return;
    }

    $data = array("id" => $id, "amount" => $amount, "yearly_amount" => $yearly_amount);
    $result = $this->AdminM->updatesabeelgrade($data);

    if ($result) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false]);
    }
  }
  public function deletesabeelgrade($id)
  {
    $result = $this->AdminM->deletesabeelgrade($id);

    if ($result) {
      redirect('admin/success/sabeelgrade');
    } else {
      redirect('admin/error/sabeelgrade');
    }
  }

  // Sabeel Takhmeen Dashboard
  public function getsabeelgrades()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $sabeel_year = $this->input->post("sabeel_year");

    $result = $this->AdminM->getSabeelGrades($sabeel_year);
    if ($result) {
      echo json_encode($result);
    }
  }

  public function addsabeeltakhmeenamount()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $year = $this->input->post("sabeel_takhmeen_year");
    $establishment_grade = $this->input->post("establishment_grade");
    $residential_grade = $this->input->post("residential_grade");
    $mutawatteneen_grade = $this->input->post("mutawatteneen_grade");

    $data = array(
      "user_id" => $user_id,
      "year" => $year,
      "establishment_grade" => $establishment_grade ?: null,
      "residential_grade" => $residential_grade ?: null,
      "mutawatteneen_grade" => $mutawatteneen_grade ?: null,
    );
    $hasAnyGrade = !empty($establishment_grade) || !empty($residential_grade) || !empty($mutawatteneen_grade);
    if ($user_id && $year && $hasAnyGrade) {
      $result = $this->AdminM->addsabeeltakhmeenamount($data);
      if ($result) {
        redirect("admin/success/sabeeltakhmeendashboard");
        return;
      }
      // Insert failed
      $this->session->set_flashdata('error', 'Could not add Sabeel Takhmeen.');
      redirect('admin/sabeeltakhmeendashboard');
      return;
    }
    // Missing required fields or no grade selected
    $this->session->set_flashdata('warning', 'Please select at least one grade (Establishment, Residential or Mutawatteneen).');
    redirect('admin/sabeeltakhmeendashboard');
  }
  public function checkSabeelTakhmeenExists()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $year = $this->input->post("year");

    $result = $this->AdminM->checkSabeelTakhmeenExists($user_id, $year);
    echo $result;
  }
  public function getUserTakhmeenDetails()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $year = $this->input->post("year");

    $result = $this->AdminM->getUserTakhmeenDetails($user_id, $year);
    echo json_encode($result);
  }
  public function updatesabeeltakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $takhmeen_id = $this->input->post("takhmeen_id");
    $establishment_grade = $this->input->post("establishment_grade");
    $residential_grade = $this->input->post("residential_grade");
    $mutawatteneen_grade = $this->input->post("mutawatteneen_grade");

    $data = array(
      "user_id" => $user_id,
      "takhmeen_id" => $takhmeen_id,
      "establishment_grade" => $establishment_grade ?: null,
      "residential_grade" => $residential_grade ?: null,
      "mutawatteneen_grade" => $mutawatteneen_grade ?: null,
    );
    $hasAnyGrade = !empty($establishment_grade) || !empty($residential_grade) || !empty($mutawatteneen_grade);
    if ($user_id && $takhmeen_id && $hasAnyGrade) {
      $result = $this->AdminM->updatesabeeltakhmeen($data);
      if ($result) {
        if ($this->input->is_ajax_request()) {
          echo json_encode(["success" => true]);
          return;
        } else {
          redirect("admin/success/sabeeltakhmeendashboard");
          return;
        }
      }
      if ($this->input->is_ajax_request()) {
        echo json_encode(["success" => false, "message" => "Update failed."]);
        return;
      }
      $this->session->set_flashdata('error', 'Update failed.');
      redirect('admin/sabeeltakhmeendashboard');
      return;
    }
    if ($this->input->is_ajax_request()) {
      echo json_encode(["success" => false, "message" => "Select at least one grade."]);
      return;
    }
    $this->session->set_flashdata('warning', 'Please select at least one grade (Establishment or Residential).');
    redirect('admin/sabeeltakhmeendashboard');
  }
  public function deletesabeeltakhmeen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $takhmeen_id = $this->input->post('takhmeen_id');
    if (!$takhmeen_id) {
      echo json_encode(["success" => false, "error" => "Missing takhmeen id"]);
      return;
    }

    $result = $this->AdminM->deletesabeeltakhmeen($takhmeen_id);
    if ($result) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "error" => "Delete failed"]);
    }
  }

  public function managemembers()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    
    $this->load->model('AmilsahebM');

    // Load users list (support filtering via GET or POST search)
    if ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      $data['users'] = $this->AmilsahebM->search_users($keyword);
    } elseif (!empty($this->input->get())) {
      $data['users'] = $this->AmilsahebM->get_users_filtered($this->input->get());
    } else {
      $data['users'] = $this->AmilsahebM->get_all_users();
    }
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
    // Provide view params for shared directory view
    $data['back_url'] = base_url('admin');
    $data['update_user_url'] = base_url('admin/update_user_details');

    $this->load->view('Admin/Header', $data);
    $this->load->view('Amilsaheb/Mumineendirectory', $data);
  }
  // Updated by Patel Infotech Services

  public function managemiqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Miqaat', $data);
  }
  public function addmiqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/AddMiqaat');
  }
  public function submitmiqaat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
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
      redirect('/admin/success/miqaat');
    } else {
      redirect('/admin/error/miqaat');
    }
  }
  public function modifymiqaat($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ModifyMiqaat', $data);
  }
  public function submitmodifymiqaat($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
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
      redirect('/admin/success/managemiqaat');
    } else {
      redirect('/admin/error/managemiqaat');
    }
  }
  function deletemiqaat($id)
  {
    $check = $this->AdminM->delete_miqaat($id);
    if ($check) {
      redirect('/admin/success/managemiqaat');
    } else {
      redirect('/admin/error/managemiqaat');
    }
  }
  public function miqaatattendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
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
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/MiqaatAttendance', $data);
  }
  public function addmumineen()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/AddMumineen', $data);
  }
  public function submitaddmumineen()
  {
    $its_id = $this->input->post('itsId');

    if (empty($its_id)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'ITS ID is required'
      ]);
      return;
    }

    // Check duplicate ITS ID
    $exists = $this->db->where('ITS_ID', $its_id)->get('user')->row_array();
    if ($exists) {
      echo json_encode([
        'status' => 'error',
        'message' => 'ITS ID already exists'
      ]);
      return;
    }

    $data = array(
      'Full_Name' => $this->input->post('fullName'),
      'ITS_ID' => $its_id,
      'Mobile' => $this->input->post('contact'),
      'Email' => $this->input->post('email'),
      'HOF_FM_TYPE' => $this->input->post('isHOF') ? 'HOF' : 'FM',
      'HOF_ID' => $this->input->post('isHOF') ? $its_id : $this->input->post('hofItsId')
    );
    $logindata = array(
      'username' => $its_id,
      'password' => md5($its_id),
      'hof' => $this->input->post('isHOF') ? $its_id : $this->input->post('hofItsId')
    );

    $check = $this->AdminM->addMumineen($data, $logindata);

    if ($check) {
      $response = array(
        'status' => 'success',
        'message' => 'User added successfully!'
      );
    } else {
      $response = array(
        'status' => 'error',
        'message' => 'Error adding user.'
      );
    }
    echo json_encode($response);
  }

  public function bulk_update_living_status()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $this->load->model('MemberStatusM');
    $residingStatus = 'Residing in ' . jamaat_place();
    $this->db->update('user', [
      'deeni_status' => 'Normal',
      'health_status' => 'Healthy',
      'residential_status' => $residingStatus,
    ]);
    $this->session->set_flashdata('success', 'Successfully updated all members to Normal, Healthy, and ' . $residingStatus . '.');
    redirect('admin/managemembers');
  }

  // ================= Member Update =================
  public function editmember($its_id = null)
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3)) {
      redirect('/accounts');
    }
    if (!$its_id) {
      redirect('admin/managemembers');
      return;
    }
    $member = $this->AdminM->get_member_by_its($its_id);
    if (!$member) {
      redirect('admin/managemembers');
      return;
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['member'] = $member;
    // Populate HOF select with family members for this member (family-scoped HOF candidates).
    // Use HOF_FM_TYPE to pick the correct source ID:
    // - If the member is a Family Member (FM), use their HOF_ID.
    // - If the member is an HOF, use the member's ITS_ID to fetch their family members.
    // If neither applies or the expected id is missing, fall back to full HOF list.
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
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/EditMember', $data);
  }

  public function search_hofs_autocomplete()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3)) {
      http_response_code(403);
      echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
      return;
    }

    $q = trim((string)$this->input->get('q'));

    $this->db->select('ITS_ID, Full_Name, Sector, Sub_Sector, Sector_Incharge_ITSID, Sector_Incharge_Name, Sector_Incharge_Female_ITSID, Sector_Incharge_Female_Name, Sub_Sector_Incharge_ITSID, Sub_Sector_Incharge_Name, Sub_Sector_Incharge_Female_ITSID, Sub_Sector_Incharge_Female_Name');
    $this->db->from('user');

    if ($q !== '') {
      $this->db->group_start();
      $this->db->like('ITS_ID', $q);
      $this->db->or_like('Full_Name', $q);
      $this->db->group_end();
    }
    $this->db->limit(20);
    $results = $this->db->get()->result_array();

    echo json_encode($results);
  }


  


  // ─────────────────────────────────────────────────────────────────────────────
// updatemember — update existing member
// ─────────────────────────────────────────────────────────────────────────────
  public function updatemember()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 2 && $_SESSION['user']['role'] != 3)) {
      redirect('/accounts');
    }

    $its_id = $this->input->post('its_id');

    if (!$its_id) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Missing ITS ID'
      ]);
      return;
    }

    // Email validation
    $email = $this->input->post('Email');

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email format'
      ]);
      return;
    }

    $isHof = $this->input->post('hof_type') === 'HOF';

    $hofReference = $isHof
      ? $its_id
      : $this->input->post('HOF_ID');

    // Allowed fields
    $fields = [
      // ── Identity & Contact ──
      'Full_Name',
      'Full_Name_Arabic',
      'First_Name',
      'Surname',
      'First_Prefix',
      'Prefix_Year',
      'Father_Prefix',
      'Father_Name',
      'Father_Surname',
      'Husband_Prefix',
      'Husband_Name',
      'Gender',
      'Age',
      'Mobile',
      'Email',
      'WhatsApp_No',
      // ── Portal-managed / Jamaat-internal ──
      'HOF_FM_TYPE',
      'HOF_ID',
      'Registered_Family_Mobile',
      'Family_ID',
      'TanzeemFile_No',
      // ── Family relationships ──
      'Father_ITS_ID',
      'Mother_ITS_ID',
      'Spouse_ITS_ID',
      // ── Marital & Personal ──
      'Misaq',
      'Marital_Status',
      'Blood_Group',
      'Warakatul_Tarkhis',
      // ── Nikah & Religious Dates ──
      'Date_Of_Nikah',
      'Date_Of_Nikah_Hijri',
      // ── Origin & Community ──
      'Organisation',
      'Organisation_CSV',
      'Vatan',
      'Nationality',
      'Jamaat',
      'Jamiaat',
      // ── Education & Skills ──
      'Qualification',
      'Languages',
      'Hunars',
      // ── Occupation ──
      'Occupation',
      'Sub_Occupation',
      'Sub_Occupation2',
      // ── Religious Milestones ──
      'Quran_Sanad',
      'Qadambosi_Sharaf',
      'Raudat_Tahera_Ziyarat',
      'Karbala_Ziyarat',
      'Ashara_Mubaraka',
      // ── Housing & Address ──
      'Housing',
      'Type_of_House',
      'Address',
      'Building',
      'Street',
      'Area',
      'State',
      'City',
      'Pincode',
      // ── Sector Hierarchy ──
      'Sector',
      'Sub_Sector',
      'Sector_Incharge_ITSID',
      'Sector_Incharge_Name',
      'Sector_Incharge_Female_ITSID',
      'Sector_Incharge_Female_Name',
      'Sub_Sector_Incharge_ITSID',
      'Sub_Sector_Incharge_Name',
      'Sub_Sector_Incharge_Female_ITSID',
      'Sub_Sector_Incharge_Female_Name',
      // ── Verification & Scan ──
      'Data_Verifcation_Status',
      'Data_Verification_Date',
      'Photo_Verifcation_Status',
      'Photo_Verification_Date',
      'Last_Scanned_Event',
      'Last_Scanned_Place',
      'Title',
      'Category',
      'Idara',
      'Inactive_Status',
    ];

    $payload = [];

    foreach ($fields as $f) {

      $val = $this->input->post($f);

      if ($val !== null) {
        $payload[$f] = is_string($val)
          ? trim($val)
          : $val;
      }
    }

    // HOF handling
    $payload['HOF_FM_TYPE'] = $isHof ? 'HOF' : 'FM';

    $payload['HOF_ID'] = $hofReference
      ?: ($isHof ? $its_id : null);

    // Nullable fields cleanup
    foreach (['Sector', 'Sub_Sector', 'Inactive_Status'] as $nullableField) {

      if (
        array_key_exists($nullableField, $payload)
        && $payload[$nullableField] === ''
      ) {
        unset($payload[$nullableField]);
      }
    }

    // Save living-status fields separately (these are always portal-managed)
    $livingFields = [
      'activity_status'    => $this->input->post('activity_status'),
      'deeni_status'       => $this->input->post('deeni_status'),
      'residential_status' => $this->input->post('residential_status'),
      'health_status'      => $this->input->post('health_status'),
    ];
    // Filter out nulls (not submitted)
    $livingFields = array_filter($livingFields, fn($v) => $v !== null);
    // Nullify empty string = clear the status
    $livingFields = array_map(fn($v) => (trim($v) === '') ? null : trim($v), $livingFields);

    $updated = $this->AdminM->update_member($its_id, $payload);

    // Save living statuses + recompute activity_status
    if (!empty($livingFields)) {
      $this->load->model('MemberStatusM');
      $this->MemberStatusM->update_living_status((int)$its_id, $livingFields);
    } elseif ($updated) {
      $this->load->model('MemberStatusM');
      $this->MemberStatusM->recalculate_one((int)$its_id);
    }

    if ($updated || !empty($livingFields)) {
      echo json_encode([
        'status' => 'success',
        'message' => 'Member updated',
      ]);

    } else {

      echo json_encode([
        'status' => 'error',
        'message' => 'No changes or update failed'
      ]);
    }
  }

  // ── Living Status AJAX endpoint ───────────────────────────────────────────
  public function update_living_status()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3)) {
      http_response_code(403);
      echo json_encode(['status' => 'error', 'message' => 'Unauthorised']);
      return;
    }
    $its_id = (int)$this->input->post('its_id');
    if (!$its_id) {
      echo json_encode(['status' => 'error', 'message' => 'Missing ITS ID']);
      return;
    }
    $this->load->model('MemberStatusM');
    $ok = $this->MemberStatusM->update_living_status($its_id, [
      'activity_status'    => $this->input->post('activity_status'),
      'deeni_status'       => $this->input->post('deeni_status'),
      'residential_status' => $this->input->post('residential_status'),
      'health_status'      => $this->input->post('health_status'),
    ]);
    echo json_encode(['status' => $ok ? 'success' : 'error']);
  }

  // ── Recalculate all statuses (Admin action) ───────────────────────────────
  public function recalculate_all_statuses()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      http_response_code(403);
      echo json_encode(['status' => 'error', 'message' => 'Unauthorised']);
      return;
    }
    $this->load->model('MemberStatusM');
    $result = $this->MemberStatusM->recalculate_all();
    $this->session->set_flashdata('success',
      'Status recalculation complete. Updated: ' . $result['updated'] .
      ', Errors: ' . $result['errors']);
    redirect('admin/preferences');
  }

  // ================= Member Create =================
  public function addmember()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3)) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['hof_list'] = $this->AdminM->get_all_hofs();
    // Provide an empty member structure for reuse of view logic
    $data['member'] = [];
    $data['sector_map'] = $this->AdminM->get_sector_hierarchy();
    $data['sector_list'] = array_keys($data['sector_map']);
    $data['incharges_map'] = $this->AdminM->get_sector_incharges_map();
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 3) {
      $this->load->view('Anjuman/Header', $data);
    } else {
      $this->load->view('Admin/Header', $data);
    }
    $this->load->view('Admin/AddMember', $data);
  }

  // View single member details (read-only)
  public function viewmember($its_id = null)
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3 && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15))) {
      redirect('/accounts');
    }
    if (!$its_id) {
      redirect('admin/managemembers');
      return;
    }
    $member = $this->AdminM->get_member_by_its($its_id);
    if (!$member) {
      redirect('admin/managemembers');
      return;
    }
    $hof_id = !empty($member['HOF_ID']) ? $member['HOF_ID'] : $its_id;
    $data['user_name']         = $_SESSION['user']['username'];
    $data['member']            = $member;
    $data['family_members']    = $this->AdminM->get_family_members_by_hof_id($hof_id);
    $data['family_financials'] = $this->AdminM->get_family_financial_data($hof_id);
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 3) {
      $this->load->view('Anjuman/Header', $data);
    } else {
      $this->load->view('Admin/Header', $data);
    }
    $this->load->view('Admin/ViewMember', $data);
  }

  // JSON endpoint for modal view
  public function memberjson($its_id = null)
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3)) {
      http_response_code(403);
      echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
      return;
    }
    if (!$its_id) {
      http_response_code(400);
      echo json_encode(['status' => 'error', 'message' => 'ITS ID required']);
      return;
    }
    $member = $this->AdminM->get_member_by_its($its_id);
    if (!$member) {
      http_response_code(404);
      echo json_encode(['status' => 'error', 'message' => 'Member not found']);
      return;
    }
    echo json_encode(['status' => 'success', 'member' => $member]);
  }

  public function savemember()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3)) {
      redirect('/accounts');
    }

    $its_id = $this->input->post('ITS_ID');

    if (!$its_id) {
      echo json_encode([
        'status' => 'error',
        'message' => 'ITS ID required'
      ]);
      return;
    }

    $email = $this->input->post('Email');

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email'
      ]);
      return;
    }

    $payload = $this->input->post(NULL, true);

    $result = $this->AdminM->create_member($payload);

    echo json_encode($result);
  }

  public function expense()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Expense');
  }

  public function expense_items()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->model('ExpenseItemM');
    $data['items'] = $this->ExpenseItemM->get_all();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ExpenseItems', $data);
  }

  // AJAX: create new expense item
  public function expense_item_create()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid method']));
      return;
    }
    $sector_name = trim((string)$this->input->post('sector_name'));
    $sector_code = trim((string)$this->input->post('sector_code')) ?: null;
    $sub_sector_name = trim((string)$this->input->post('sub_sector_name'));
    $sub_sector_code = trim((string)$this->input->post('sub_sector_code')) ?: null;
    $item_name = trim((string)$this->input->post('item_name'));
    $item_code = trim((string)$this->input->post('item_code')) ?: null;
    $status = trim((string)$this->input->post('status')) ?: 'Active';

    if ($sector_name === '' || $sub_sector_name === '' || $item_name === '') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Sector, Sub Sector, and Item Name are required']));
      return;
    }

    $this->load->model('ExpenseItemM');
    $id = $this->ExpenseItemM->create([
      'sector_name' => $sector_name,
      'sector_code' => $sector_code,
      'sub_sector_name' => $sub_sector_name,
      'sub_sector_code' => $sub_sector_code,
      'item_name' => $item_name,
      'item_code' => $item_code,
      'status' => $status
    ]);

    if ($id) {
      $this->output->set_content_type('application/json')->set_output(json_encode([
        'status' => 'success',
        'id' => $id,
        'sector_name' => $sector_name,
        'sector_code' => $sector_code,
        'sub_sector_name' => $sub_sector_name,
        'sub_sector_code' => $sub_sector_code,
        'item_name' => $item_name,
        'item_code' => $item_code,
        'item_status' => $status
      ]));
    } else {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Create failed']));
    }
  }

  // AJAX: update existing expense item
  public function expense_item_update()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid method']));
      return;
    }
    $id = (int)$this->input->post('id');
    $sector_name = trim((string)$this->input->post('sector_name'));
    $sector_code = trim((string)$this->input->post('sector_code')) ?: null;
    $sub_sector_name = trim((string)$this->input->post('sub_sector_name'));
    $sub_sector_code = trim((string)$this->input->post('sub_sector_code')) ?: null;
    $item_name = trim((string)$this->input->post('item_name'));
    $item_code = trim((string)$this->input->post('item_code')) ?: null;
    $status = trim((string)$this->input->post('status')) ?: 'Active';

    if ($id <= 0 || $sector_name === '' || $sub_sector_name === '' || $item_name === '') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid input']));
      return;
    }

    $this->load->model('ExpenseItemM');
    $ok = $this->ExpenseItemM->update($id, [
      'sector_name' => $sector_name,
      'sector_code' => $sector_code,
      'sub_sector_name' => $sub_sector_name,
      'sub_sector_code' => $sub_sector_code,
      'item_name' => $item_name,
      'item_code' => $item_code,
      'status' => $status
    ]);

    if ($ok) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    } else {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Update failed']));
    }
  }

  // AJAX: delete expense item
  public function expense_item_delete()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid method']));
      return;
    }
    $id = (int)$this->input->post('id');
    if ($id <= 0) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid id']));
      return;
    }
    $this->load->model('ExpenseItemM');
    $ok = $this->ExpenseItemM->delete_with_expenses($id);
    if ($ok) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    } else {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Delete failed']));
    }
  }

  // AJAX: member search autocomplete (name or ITS ID)
  public function searchmembers()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] != 1 && $_SESSION['user']['role'] != 3 && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15))) {
      http_response_code(403);
      echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
      return;
    }
    $q = trim((string)$this->input->get('q'));
    if ($q === '' || strlen($q) < 2) {
      echo json_encode(['status' => 'ok', 'results' => []]);
      return;
    }
    $this->db->select('ITS_ID, Full_Name, First_Name, Surname, Sector, Sub_Sector, Gender, HOF_FM_TYPE, Mobile');
    $this->db->from('user');
    $this->db->group_start();
    $this->db->like('Full_Name', $q);
    $this->db->or_like('ITS_ID', $q);
    $this->db->group_end();
    $this->db->where('Inactive_Status IS NULL', null, false);
    $this->db->order_by('Full_Name', 'ASC');
    $this->db->limit(15);
    $rows = $this->db->get()->result_array();
    $results = [];
    foreach ($rows as $r) {
      $name = trim((string)($r['Full_Name'] ?? ''));
      if ($name === '') {
        $name = trim((string)($r['First_Name'] ?? '') . ' ' . (string)($r['Surname'] ?? ''));
      }
      $results[] = [
        'its_id'    => $r['ITS_ID'],
        'name'      => $name,
        'sector'    => trim((string)($r['Sector'] ?? '') . ($r['Sub_Sector'] ? ' - ' . $r['Sub_Sector'] : '')),
        'gender'    => $r['Gender'] ?? '',
        'hof_type'  => $r['HOF_FM_TYPE'] ?? '',
        'mobile'    => $r['Mobile'] ?? '',
      ];
    }
    $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'ok', 'results' => $results]));
  }

  public function preferences()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->model('SettingsM');

    if ($this->input->server('REQUEST_METHOD') === 'POST') {
      $jamaat_name = $this->input->post('jamaat_name', true);
      $jamaat_place = $this->input->post('jamaat_place', true);
      $address_line = $this->input->post('address_line', true);
      $city_state = $this->input->post('city_state', true);
      $pincode = $this->input->post('pincode', true);
      $support_email = $this->input->post('support_email', true);
      $registration_email = $this->input->post('registration_email', true);
      $receipt_jamaat_name = $this->input->post('receipt_jamaat_name', true);
      $trust_regn_no = $this->input->post('trust_regn_no', true);
      $managed_by = $this->input->post('managed_by', true);
      $admin_emails = $this->input->post('admin_emails', true);

      if ($jamaat_name !== null) $this->SettingsM->set('jamaat_name', $jamaat_name);
      if ($jamaat_place !== null) $this->SettingsM->set('jamaat_place', $jamaat_place);
      if ($address_line !== null) $this->SettingsM->set('address_line', $address_line);
      if ($city_state !== null) $this->SettingsM->set('city_state', $city_state);
      if ($pincode !== null) $this->SettingsM->set('pincode', $pincode);
      if ($support_email !== null) $this->SettingsM->set('support_email', $support_email);
      if ($registration_email !== null) $this->SettingsM->set('registration_email', $registration_email);
      if ($receipt_jamaat_name !== null) $this->SettingsM->set('receipt_jamaat_name', $receipt_jamaat_name);
      if ($trust_regn_no !== null) $this->SettingsM->set('trust_regn_no', $trust_regn_no);
      if ($managed_by !== null) $this->SettingsM->set('managed_by', $managed_by);
      if ($admin_emails !== null) $this->SettingsM->set('admin_emails', $admin_emails);

      $this->session->set_flashdata('success', 'Preferences updated successfully.');
      redirect('admin/preferences');
      return;
    }

    $data['jamaat_name'] = $this->SettingsM->get('jamaat_name', jamaat_name());
    $data['jamaat_place'] = $this->SettingsM->get('jamaat_place', jamaat_place());
    $data['address_line'] = $this->SettingsM->get('address_line', '');
    $data['city_state'] = $this->SettingsM->get('city_state', '');
    $data['pincode'] = $this->SettingsM->get('pincode', '');
    $data['support_email'] = $this->SettingsM->get('support_email', '');
    $data['registration_email'] = $this->SettingsM->get('registration_email', 'anjuman@kharjamaat.in');
    $data['receipt_jamaat_name'] = $this->SettingsM->get('receipt_jamaat_name', 'Anjuman-e-Saifee Dawoodi Bohra Jamaat, KHAR');
    $data['trust_regn_no'] = $this->SettingsM->get('trust_regn_no', 'E/24158 (Mumbai)');
    $data['managed_by'] = $this->SettingsM->get('managed_by', 'Anjuman-e-Saifee');
    $data['admin_emails'] = $this->SettingsM->get('admin_emails', "amilsaheb@kharjamaat.in,\n3042@carmelnmh.in,\nkharjamaat@gmail.com,\nkharamilsaheb@gmail.com,\nkharjamaat786@gmail.com,\nkhozemtopiwalla@gmail.com,\nybookwala@gmail.com");

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Preferences', $data);
  }

  public function notification_settings()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->model('SettingsM');

    if ($this->input->server('REQUEST_METHOD') === 'POST') {
      $notification_keys = [
        'miqaat_assignment',
        'miqaat_activation',
        'raza_submission',
        'raza_recommendation',
        'raza_approval',
        'rsvp_on_raza_approval',
        'fmb_signup_10am',
        'fmb_feedback_10pm',
        'monthly_due_reminder',
        'corpus_funds_weekly_reminder',
        'appointment_schedule',
        'appointment_report_nightly',
        'amil_3days_before',
        'amil_1day_before',
        'amil_event_day',
        'amil_3days_before_private',
        'amil_1day_before_private',
        'amil_event_day_private',
        'amil_3days_before_non',
        'amil_1day_before_non',
        'amil_event_day_non'
      ];

      $notification_settings = [];
      foreach ($notification_keys as $key) {
        $notification_settings[$key] = [
          'member_whatsapp' => $this->input->post("notify_member_whatsapp_{$key}") ? 1 : 0,
          'member_email'    => $this->input->post("notify_member_email_{$key}") ? 1 : 0,
          'admin_whatsapp'  => $this->input->post("notify_admin_whatsapp_{$key}") ? 1 : 0,
          'admin_email'     => $this->input->post("notify_admin_email_{$key}") ? 1 : 0
        ];
      }
      $this->SettingsM->set('notification_settings', json_encode($notification_settings));

      $this->session->set_flashdata('success', 'Notification settings updated successfully.');
      redirect('admin/notification_settings');
      return;
    }

    $data['notification_settings'] = json_decode((string)$this->SettingsM->get('notification_settings', '{}'), true);

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/NotificationSettings', $data);
  }

  public function status_options()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['options'] = $this->db->order_by('type ASC, status_label ASC')->get('status_options')->result_array();

    $data['deeni_options'] = array_filter($data['options'], fn($o) => $o['type'] === 'deeni');
    $data['health_options'] = array_filter($data['options'], fn($o) => $o['type'] === 'health');
    $data['residential_options'] = array_filter($data['options'], fn($o) => $o['type'] === 'residential');

    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/StatusOptions', $data);
  }

  public function save_status_option()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $id = $this->input->post('id');
    $type = $this->input->post('type');
    $status_key = trim((string)$this->input->post('status_key'));
    $is_inactive_trigger = $this->input->post('is_inactive_trigger') ? 1 : 0;

    if (empty($type) || empty($status_key)) {
      $this->session->set_flashdata('error', 'All fields are required.');
      redirect('admin/status_options');
      return;
    }

    $status_label = $status_key . ' (' . ($is_inactive_trigger ? 'Inactive' : 'Active') . ')';

    $db_data = [
      'type' => $type,
      'status_key' => $status_key,
      'status_label' => $status_label,
      'is_inactive_trigger' => $is_inactive_trigger
    ];

    if ($id) {
      $this->db->where('id', $id)->update('status_options', $db_data);
      $this->session->set_flashdata('success', 'Status option updated successfully.');
    } else {
      $exists = $this->db->where(['type' => $type, 'status_key' => $status_key])->get('status_options')->num_rows();
      if ($exists > 0) {
        $this->session->set_flashdata('error', 'A status option with this key already exists.');
      } else {
        $this->db->insert('status_options', $db_data);
        $this->session->set_flashdata('success', 'Status option added successfully.');
      }
    }

    // Force run dynamic triggers on all users to apply the changes
    $this->db->query("UPDATE user SET ITS_ID = ITS_ID");

    redirect('admin/status_options');
  }

  public function delete_status_option($id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    if ($id) {
      $this->db->where('id', $id)->delete('status_options');
      $this->session->set_flashdata('success', 'Status option deleted successfully.');
      
      // Force run dynamic triggers on all users to apply the changes
      $this->db->query("UPDATE user SET ITS_ID = ITS_ID");
    }

    redirect('admin/status_options');
  }

  public function add_confidential_comment()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
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
    $created_by_name = 'Jamaat Administration';
    if (!empty($_SESSION['user_data']['First_Name']) || !empty($_SESSION['user_data']['Surname'])) {
      $created_by_name = trim(($_SESSION['user_data']['First_Name'] ?? '') . ' ' . ($_SESSION['user_data']['Surname'] ?? ''));
    }

    $this->load->model('ConfidentialCommentM');
    $ok = $this->ConfidentialCommentM->add_comment($its_id, $comment, $created_by, $created_by_name);
    echo json_encode(['success' => $ok]);
  }

  /* ─────────────────────────────────────────────────────────────
   * Thaali Types CRUD (AJAX)
   * ───────────────────────────────────────────────────────────── */

  /** AJAX POST – create a new custom thaali type */
  public function add_thaali_type()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized']); return;
    }
    $name        = trim($this->input->post('name') ?? '');
    $description = trim($this->input->post('description') ?? '');
    $amount      = (float)($this->input->post('amount') ?? 0);
    $status      = $this->input->post('status') === 'Inactive' ? 'Inactive' : 'Active';

    if ($name === '') {
      echo json_encode(['success' => false, 'message' => 'Thaali Type Name is required']); return;
    }
    $exists = $this->db->where('name', $name)->count_all_results('thaali_types');
    if ($exists > 0) {
      echo json_encode(['success' => false, 'message' => 'A thaali type with this name already exists']); return;
    }
    $this->db->insert('thaali_types', [
      'name'        => $name,
      'description' => $description,
      'amount'      => $amount,
      'status'      => $status,
    ]);
    $id = $this->db->insert_id();
    echo json_encode(['success' => true, 'message' => 'Thaali type added', 'id' => $id]);
  }

  /** AJAX POST – update an existing thaali type */
  public function edit_thaali_type()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized']); return;
    }
    $id          = (int)($this->input->post('id') ?? 0);
    $name        = trim($this->input->post('name') ?? '');
    $description = trim($this->input->post('description') ?? '');
    $amount      = (float)($this->input->post('amount') ?? 0);
    $status      = $this->input->post('status') === 'Inactive' ? 'Inactive' : 'Active';

    if (!$id || $name === '') {
      echo json_encode(['success' => false, 'message' => 'Invalid request']); return;
    }
    $exists = $this->db->where('name', $name)->where('id !=', $id)->count_all_results('thaali_types');
    if ($exists > 0) {
      echo json_encode(['success' => false, 'message' => 'A thaali type with this name already exists']); return;
    }
    $this->db->where('id', $id)->update('thaali_types', [
      'name'        => $name,
      'description' => $description,
      'amount'      => $amount,
      'status'      => $status,
    ]);
    echo json_encode(['success' => true, 'message' => 'Thaali type updated']);
  }

  /** AJAX POST – delete a custom thaali type (default types are protected) */
  public function delete_thaali_type()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized']); return;
    }
    $id = (int)($this->input->post('id') ?? 0);
    if (!$id) {
      echo json_encode(['success' => false, 'message' => 'Invalid request']); return;
    }
    $row = $this->db->where('id', $id)->get('thaali_types')->row_array();
    if (!$row) {
      echo json_encode(['success' => false, 'message' => 'Thaali type not found']); return;
    }
    $this->db->where('id', $id)->delete('thaali_types');
    echo json_encode(['success' => true, 'message' => 'Thaali type deleted']);
  }

}
