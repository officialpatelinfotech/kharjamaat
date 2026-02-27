<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Madresa extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('MadresaM');
    $this->load->model('HijriCalendar');
    $this->load->helper('url');
  }

  private function requireLogin()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
  }

  private function isJamaatUser()
  {
    return !empty($_SESSION['user']['username']) && $_SESSION['user']['username'] === 'jamaat';
  }

  private function isAmilsahebUser()
  {
    return !empty($_SESSION['user']['role']) && (int)$_SESSION['user']['role'] === 2;
  }

  private function isReadOnlyMadresaUser()
  {
    return $this->isJamaatUser() || $this->isAmilsahebUser();
  }

  private function canReceiveMadresaPayment()
  {
    // Only Jamaat login can receive payments.
    return $this->isJamaatUser();
  }

  private function isAdminUser()
  {
    if (!empty($_SESSION['user']['role']) && (int)$_SESSION['user']['role'] === 1) {
      return true;
    }
    return !empty($_SESSION['user']['username']) && $_SESSION['user']['username'] === 'admin';
  }

  private function isAdminNamespaceRequest()
  {
    return strtolower((string)$this->uri->segment(1)) === 'admin';
  }

  private function madresaBasePath()
  {
    // Admin should stay inside /admin URL space (even if they hit /madresa directly).
    if ($this->isAdminUser() || $this->isAdminNamespaceRequest()) {
      return 'admin/madresa';
    }
    return 'madresa';
  }

  private function madresaPath($suffix = '')
  {
    $base = $this->madresaBasePath();
    $suffix = trim((string)$suffix, '/');
    return $suffix === '' ? $base : ($base . '/' . $suffix);
  }

  private function madresaViewPrefix()
  {
    // Keep Jamaat views in Madresa/, and admin management views in Admin/Madresa/.
    return ($this->isAdminUser() || $this->isAdminNamespaceRequest()) ? 'Admin/Madresa' : 'Madresa';
  }

  private function loadHeader()
  {
    $data = [];
    $data['user_name'] = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';
    $data['from'] = strtoupper($data['user_name']);
    $this->load->view('Accounts/Header', $data);
  }

  private function getCurrentHijriYear()
  {
    $todayHijri = $this->HijriCalendar->get_hijri_date(date('Y-m-d'));
    $parts = $todayHijri && isset($todayHijri['hijri_date']) ? explode('-', $todayHijri['hijri_date']) : [];
    return !empty($parts) ? (int)$parts[2] : (int)date('Y');
  }

  public function index()
  {
    $this->requireLogin();
    $this->loadHeader();

    // Jamaat + Amilsaheb should land on read-only classes list.
    if ($this->isReadOnlyMadresaUser()) {
      // If Amilsaheb hits /admin/madresa, keep them in the read-only module.
      if ($this->isAmilsahebUser() && $this->isAdminNamespaceRequest()) {
        $qs = !empty($_SERVER['QUERY_STRING']) ? ('?' . $_SERVER['QUERY_STRING']) : '';
        redirect('madresa' . $qs);
      }

      $data = [];
      $currentHy = $this->getCurrentHijriYear();

      $requestedYear = (int)$this->input->get('year');
      $selectedYear = $requestedYear > 0 ? $requestedYear : $currentHy;

      // Support a simple class name search in Jamaat module.
      $selectedClassQuery = trim((string)$this->input->get('class'));
      $selectedStudentQuery = trim((string)$this->input->get('student_name'));

      // Backward-compatible: if a class_id is provided, it takes precedence.
      $selectedClassId = (int)$this->input->get('class_id');
      if ($selectedClassId <= 0) $selectedClassId = 0;

      $data['current_hijri_year'] = $currentHy;
      $data['selected_hijri_year'] = $selectedYear;
      $data['selected_class_query'] = $selectedClassQuery;
      $data['selected_student_query'] = $selectedStudentQuery;

      $years = $this->HijriCalendar->get_distinct_hijri_years();
      if (!empty($years)) {
        $data['hijri_years'] = array_map('intval', $years);
        rsort($data['hijri_years']);
      } else {
        $data['hijri_years'] = [];
        for ($y = $currentHy + 2; $y >= $currentHy - 2; $y--) {
          $data['hijri_years'][] = (int)$y;
        }
      }

      // Read-only listing for selected year (no NULL-year legacy classes here)
      $all = $this->MadresaM->list_classes_by_year($selectedYear, false);

      $all = is_array($all) ? $all : [];

      if ($selectedClassId > 0) {
        $filtered = [];
        foreach ($all as $row) {
          if (!empty($row['id']) && (int)$row['id'] === $selectedClassId) {
            $filtered[] = $row;
            break;
          }
        }
        $data['classes'] = $filtered;
      } elseif ($selectedClassQuery !== '' || $selectedStudentQuery !== '') {
        $q_class = mb_strtolower($selectedClassQuery);
        $q_student = mb_strtolower($selectedStudentQuery);
        
        $filtered = [];
        foreach ($all as $row) {
          $matchClass = true;
          $matchStudent = true;

          if ($q_class !== '') {
            $name = !empty($row['class_name']) ? (string)$row['class_name'] : '';
            if ($name === '' || mb_strpos(mb_strtolower($name), $q_class) === false) {
              $matchClass = false;
            }
          }

          if ($matchClass && $q_student !== '') {
            $students = $this->MadresaM->get_class_students($row['id']);
            $studentFound = false;
            if (!empty($students)) {
              foreach ($students as $s) {
                $sname = !empty($s['Full_Name']) ? (string)$s['Full_Name'] : '';
                if ($sname !== '' && mb_strpos(mb_strtolower($sname), $q_student) !== false) {
                  $studentFound = true;
                  break;
                }
              }
            }
            if (!$studentFound) {
              $matchStudent = false;
            }
          }

          if ($matchClass && $matchStudent) {
            $filtered[] = $row;
          }
        }
        $data['classes'] = $filtered;
      } else {
        $data['classes'] = $all;
      }

      $this->load->view('Madresa/JamaatClasses', $data);
      return;
    }

    // For non-jamaat users, keep the module under /admin/madresa.
    if (!$this->isAdminNamespaceRequest()) {
      redirect('admin/madresa');
    }

    $data = [];
    $data['madresa_base'] = $this->madresaBasePath();
    $data['madresa_view_prefix'] = $this->madresaViewPrefix();
    $this->load->view($this->madresaViewPrefix() . '/Index', $data);
  }

  // Manage classes (list)
  public function classes()
  {
    $this->requireLogin();
    // Jamaat/Amilsaheb should not access management screens.
    if ($this->isReadOnlyMadresaUser()) {
      redirect('madresa');
    }

    if (!$this->isAdminNamespaceRequest() && $this->isAdminUser()) {
      $qs = !empty($_SERVER['QUERY_STRING']) ? ('?' . $_SERVER['QUERY_STRING']) : '';
      redirect('admin/madresa/classes' . $qs);
    }
    $this->loadHeader();

    $data = [];
    $currentHy = $this->getCurrentHijriYear();

    $requestedYear = (int)$this->input->get('year');
    $selectedYear = $requestedYear > 0 ? $requestedYear : $currentHy;

    $selectedClassQuery = trim((string)$this->input->get('class'));
    $selectedStudentQuery = trim((string)$this->input->get('student_name'));

    $data['current_hijri_year'] = $currentHy;
    $data['selected_hijri_year'] = $selectedYear;
    $data['selected_class_query'] = $selectedClassQuery;
    $data['selected_student_query'] = $selectedStudentQuery;

    // Year dropdown options from calendar (fallback to +/- 2 years)
    $years = $this->HijriCalendar->get_distinct_hijri_years();
    if (!empty($years)) {
      $data['hijri_years'] = array_map('intval', $years);
      rsort($data['hijri_years']);
    } else {
      $data['hijri_years'] = [];
      for ($y = $currentHy + 2; $y >= $currentHy - 2; $y--) {
        $data['hijri_years'][] = (int)$y;
      }
    }

    // Include legacy classes created before hijri_year existed (NULL year)
    $all = $this->MadresaM->list_classes_by_year($selectedYear, true);
    $all = is_array($all) ? $all : [];

    if ($selectedClassQuery !== '' || $selectedStudentQuery !== '') {
      $q_class = mb_strtolower($selectedClassQuery);
      $q_student = mb_strtolower($selectedStudentQuery);
      
      $filtered = [];
      foreach ($all as $row) {
        $matchClass = true;
        $matchStudent = true;

        if ($q_class !== '') {
          $name = !empty($row['class_name']) ? (string)$row['class_name'] : '';
          if ($name === '' || mb_strpos(mb_strtolower($name), $q_class) === false) {
            $matchClass = false;
          }
        }

        if ($matchClass && $q_student !== '') {
          $students = $this->MadresaM->get_class_students($row['id']);
          $studentFound = false;
          if (!empty($students)) {
            foreach ($students as $s) {
              $sname = !empty($s['Full_Name']) ? (string)$s['Full_Name'] : '';
              if ($sname !== '' && mb_strpos(mb_strtolower($sname), $q_student) !== false) {
                $studentFound = true;
                break;
              }
            }
          }
          if (!$studentFound) {
            $matchStudent = false;
          }
        }

        if ($matchClass && $matchStudent) {
          $filtered[] = $row;
        }
      }
      $data['classes'] = $filtered;
    } else {
      $data['classes'] = $all;
    }
    $data['madresa_base'] = $this->madresaBasePath();
    $data['madresa_view_prefix'] = $this->madresaViewPrefix();
    $this->load->view($this->madresaViewPrefix() . '/ManageClasses', $data);
  }

  public function classes_view($classId)
  {
    $this->requireLogin();
    $this->loadHeader();

    $class = $this->MadresaM->get_class($classId);
    if (empty($class)) {
      redirect($this->madresaPath('classes') . '?message=' . urlencode('Class not found'));
    }

    $data = [];
    $data['class'] = $class;
    $data['financials'] = $this->MadresaM->get_class_financials($classId);
    $data['students'] = $this->MadresaM->get_class_students_financials($classId);
    $data['is_jamaat'] = $this->isReadOnlyMadresaUser();
    $data['can_receive_payment'] = $this->canReceiveMadresaPayment();
    $data['madresa_base'] = $this->madresaBasePath();
    $data['madresa_view_prefix'] = $this->madresaViewPrefix();
    $this->load->view($this->madresaViewPrefix() . '/ViewClass', $data);
  }


  public function classes_receive_payment($classId, $studentItsId)
  {
    $this->requireLogin();

    if (!$this->canReceiveMadresaPayment()) {
      redirect($this->madresaPath('classes/view/' . (int)$classId) . '?error=' . urlencode('Not authorized'));
    }

    // Keep admins inside /admin URL space.
    if (!$this->isAdminNamespaceRequest() && $this->isAdminUser()) {
      redirect('admin/madresa/classes/receive-payment/' . (int)$classId . '/' . (int)$studentItsId);
    }

    $this->loadHeader();

    $classId = (int)$classId;
    $studentItsId = (int)$studentItsId;
    if ($classId <= 0 || $studentItsId <= 0) {
      redirect($this->madresaPath('classes') . '?error=' . urlencode('Invalid request'));
    }

    $class = $this->MadresaM->get_class($classId);
    if (empty($class)) {
      redirect($this->madresaPath('classes') . '?error=' . urlencode('Class not found'));
    }

    $student = $this->MadresaM->get_class_student_financials($classId, $studentItsId);
    if (empty($student)) {
      redirect($this->madresaPath('classes/view/' . $classId) . '?error=' . urlencode('Student not found in this class'));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $amount = (float)$this->input->post('amount');
      $paidOn = trim((string)$this->input->post('paid_on'));
      $paymentMode = trim((string)$this->input->post('payment_mode'));
      $reference = trim((string)$this->input->post('reference'));
      $notes = trim((string)$this->input->post('notes'));

      if ($amount <= 0) {
        redirect($this->madresaPath('classes/receive-payment/' . $classId . '/' . $studentItsId) . '?error=' . urlencode('Amount must be greater than 0'));
      }

      if ($paidOn !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $paidOn)) {
        redirect($this->madresaPath('classes/receive-payment/' . $classId . '/' . $studentItsId) . '?error=' . urlencode('Paid On date is invalid'));
      }

      $createdBy = !empty($_SESSION['user']['username']) ? (string)$_SESSION['user']['username'] : null;

      $res = $this->MadresaM->create_class_payment($classId, $studentItsId, $amount, $paidOn === '' ? null : $paidOn, $paymentMode === '' ? null : $paymentMode, $reference === '' ? null : $reference, $notes === '' ? null : $notes, $createdBy);
      if (empty($res) || empty($res['success'])) {
        $errMsg = 'Failed to save payment.';
        if (is_array($res) && !empty($res['error']['message'])) {
          $errMsg .= ' ' . $res['error']['message'];
        }
        redirect($this->madresaPath('classes/receive-payment/' . $classId . '/' . $studentItsId) . '?error=' . urlencode($errMsg));
      }

      $paymentId = (int)($res['id'] ?? 0);
      $qs = $paymentId > 0 ? ('?payment_id=' . $paymentId) : '';
      redirect($this->madresaPath('classes/payment-receipt/' . $classId) . $qs);
    }

    $data = [];
    $data['error'] = $this->input->get('error');
    $data['message'] = $this->input->get('message');
    $data['class'] = $class;
    $data['student'] = $student;
    $data['is_jamaat'] = $this->isJamaatUser();
    $data['madresa_base'] = $this->madresaBasePath();
    $data['madresa_view_prefix'] = $this->madresaViewPrefix();
    $this->load->view($this->madresaViewPrefix() . '/ReceivePayment', $data);
  }

  public function classes_payment_receipt($classId)
  {
    $this->requireLogin();

    $this->loadHeader();

    $classId = (int)$classId;
    $class = $this->MadresaM->get_class($classId);
    if (empty($class)) {
      redirect($this->madresaPath('classes') . '?message=' . urlencode('Class not found'));
    }

    $paymentId = (int)$this->input->get('payment_id');
    $payment = null;
    if ($paymentId > 0) {
      $payment = $this->MadresaM->get_class_payment($paymentId);
      if (!empty($payment) && (int)($payment['m_class_id'] ?? 0) !== $classId) {
        $payment = null;
      }
    }
    if (empty($payment)) {
      $payment = $this->MadresaM->get_latest_class_payment($classId);
    }

    $data = [];
    $data['class'] = $class;
    $data['payment'] = $payment;
    $data['is_jamaat'] = $this->isReadOnlyMadresaUser();
    $data['madresa_base'] = $this->madresaBasePath();
    $data['madresa_view_prefix'] = $this->madresaViewPrefix();
    $this->load->view($this->madresaViewPrefix() . '/PaymentReceipt', $data);
  }

  public function classes_edit($classId)
  {
    $this->requireLogin();
    if ($this->isReadOnlyMadresaUser()) {
      redirect('madresa?message=' . urlencode('Not authorized'));
    }

    if (!$this->isAdminNamespaceRequest() && $this->isAdminUser()) {
      redirect('admin/madresa/classes/edit/' . (int)$classId);
    }
    $this->loadHeader();

    $class = $this->MadresaM->get_class($classId);
    if (empty($class)) {
      redirect($this->madresaPath('classes') . '?message=' . urlencode('Class not found'));
    }

    $currentHy = $this->getCurrentHijriYear();
    $hijriYears = [];
    for ($y = $currentHy - 2; $y <= $currentHy + 2; $y++) {
      $hijriYears[] = $y;
    }

    $assigned = $this->MadresaM->get_class_students($classId);
    $assignedIds = [];
    foreach ($assigned as $r) {
      if (!empty($r['ITS_ID'])) $assignedIds[] = (int)$r['ITS_ID'];
    }

    $data = [];
    $data['error'] = $this->input->get('error');
    $data['message'] = $this->input->get('message');
    $data['class'] = $class;
    $data['hijri_years'] = $hijriYears;
    $data['students'] = $this->MadresaM->list_students();
    $data['assigned_ids'] = $assignedIds;
    $data['madresa_base'] = $this->madresaBasePath();

    $data['madresa_view_prefix'] = $this->madresaViewPrefix();

    $this->load->view($this->madresaViewPrefix() . '/EditClass', $data);
  }

  public function classes_update($classId)
  {
    $this->requireLogin();

    if ($this->isReadOnlyMadresaUser()) {
      redirect('madresa?message=' . urlencode('Not authorized'));
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect($this->madresaPath('classes/edit/' . (int)$classId));
    }

    $name = trim((string)$this->input->post('class_name'));
    $fees = $this->input->post('fees');
    $status = trim((string)$this->input->post('status'));
    $hijriYear = (int)$this->input->post('hijri_year');
    $studentIds = $this->input->post('student_ids');

    if ($name === '') {
      redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?error=' . urlencode('Class name is required'));
    }
    if ($hijriYear <= 0) {
      redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?error=' . urlencode('Hijri year is required'));
    }
    if (empty($studentIds) || !is_array($studentIds)) {
      redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?error=' . urlencode('Please assign at least one student'));
    }

    if ($status !== 'Active' && $status !== 'Inactive') {
      redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?error=' . urlencode('Status is invalid'));
    }

    $this->db->trans_begin();
    $upd = $this->MadresaM->update_class($classId, $name, $hijriYear, $fees, $status);
    if (!is_array($upd) || empty($upd['success'])) {
      $this->db->trans_rollback();
      $errMsg = 'Failed to update class.';
      if (is_array($upd) && isset($upd['error']['message'])) {
        $errMsg .= ' DB Error: ' . $upd['error']['message'];
      }
      redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?error=' . urlencode($errMsg));
    }

    $assign = $this->MadresaM->assign_students_to_class($classId, $studentIds);
    if (!is_array($assign) || empty($assign['success'])) {
      $this->db->trans_rollback();
      $errMsg = 'Failed to assign students.';
      if (is_array($assign) && isset($assign['error']['message'])) {
        $errMsg .= ' DB Error: ' . $assign['error']['message'];
      }
      redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?error=' . urlencode($errMsg));
    }

    if ($this->db->trans_status() === false) {
      $this->db->trans_rollback();
      redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?error=' . urlencode('Database transaction failed'));
    }

    $this->db->trans_commit();
    redirect($this->madresaPath('classes/edit/' . (int)$classId) . '?message=' . urlencode('Class updated successfully'));
  }

  // Create class form (GET)
  public function classes_new()
  {
    $this->requireLogin();
    if ($this->isReadOnlyMadresaUser()) {
      redirect('madresa?message=' . urlencode('Not authorized'));
    }

    if (!$this->isAdminNamespaceRequest() && $this->isAdminUser()) {
      redirect('admin/madresa/classes/new');
    }
    $this->loadHeader();

    $data = [];
    $data['error'] = $this->input->get('error');
    $data['message'] = $this->input->get('message');

    // Hijri year options (current +/- a few years)
    $currentHy = $this->getCurrentHijriYear();
    $data['default_hijri_year'] = $currentHy;
    $data['hijri_years'] = [];
    for ($y = $currentHy - 2; $y <= $currentHy + 2; $y++) {
      $data['hijri_years'][] = $y;
    }

    // Students list (active members)
    $data['students'] = $this->MadresaM->list_students();
    $data['madresa_base'] = $this->madresaBasePath();
    $data['madresa_view_prefix'] = $this->madresaViewPrefix();

    $this->load->view($this->madresaViewPrefix() . '/CreateClass', $data);
  }

  // Create class (POST)
  public function classes_create()
  {
    $this->requireLogin();

    if ($this->isReadOnlyMadresaUser()) {
      redirect('madresa?message=' . urlencode('Not authorized'));
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect($this->madresaPath('classes/new'));
    }

    $name = trim((string)$this->input->post('class_name'));
    $fees = $this->input->post('fees');
    $status = trim((string)$this->input->post('status'));
    if ($status === '') {
      $status = 'Active';
    }
    $hijriYear = (int)$this->input->post('hijri_year');
    $studentIds = $this->input->post('student_ids');

    if ($name === '') {
      redirect($this->madresaPath('classes/new') . '?error=' . urlencode('Class name is required'));
    }

    if ($hijriYear <= 0) {
      redirect($this->madresaPath('classes/new') . '?error=' . urlencode('Hijri year is required'));
    }

    if (empty($studentIds) || !is_array($studentIds)) {
      redirect($this->madresaPath('classes/new') . '?error=' . urlencode('Please assign at least one student'));
    }

    if ($status !== 'Active' && $status !== 'Inactive') {
      redirect($this->madresaPath('classes/new') . '?error=' . urlencode('Status is invalid'));
    }

    $this->db->trans_begin();
    $result = $this->MadresaM->create_class($name, $hijriYear, $fees, $status);
    if (is_array($result) && isset($result['success']) && $result['success'] === true) {
      $classId = (int)$result['id'];
      $assign = $this->MadresaM->assign_students_to_class($classId, $studentIds);
      if (!is_array($assign) || empty($assign['success'])) {
        $this->db->trans_rollback();
        $errMsg = 'Failed to assign students.';
        if (is_array($assign) && isset($assign['error']['message'])) {
          $errMsg .= ' DB Error: ' . $assign['error']['message'];
        }
        redirect($this->madresaPath('classes/new') . '?error=' . urlencode($errMsg));
      }

      if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        redirect($this->madresaPath('classes/new') . '?error=' . urlencode('Database transaction failed'));
      }

      $this->db->trans_commit();
      redirect($this->madresaPath('classes') . '?message=' . urlencode('Class created successfully'));
    }

    $this->db->trans_rollback();

    $errMsg = 'Failed to create class.';
    if (is_array($result) && isset($result['error']['message'])) {
      $errMsg .= ' DB Error: ' . $result['error']['message'];
    }
    redirect($this->madresaPath('classes/new') . '?error=' . urlencode($errMsg));
  }

  // Admin-only: Delete a class (POST)
  public function classes_delete($classId)
  {
    $this->requireLogin();

    if (!$this->isAdminUser()) {
      redirect($this->madresaPath('classes') . '?error=' . urlencode('Not authorized'));
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect($this->madresaPath('classes') . '?error=' . urlencode('Invalid request'));
    }

    $classId = (int)$classId;
    if ($classId <= 0) {
      redirect($this->madresaPath('classes') . '?error=' . urlencode('Invalid class id'));
    }

    $year = (int)$this->input->post('year');
    $yearParam = $year > 0 ? ('?year=' . $year) : '';

    $class = $this->MadresaM->get_class($classId);
    if (empty($class)) {
      redirect($this->madresaPath('classes') . $yearParam . (empty($yearParam) ? '?' : '&') . 'error=' . urlencode('Class not found'));
    }

    $this->db->trans_begin();
    $del = $this->MadresaM->delete_class($classId);
    if (!is_array($del) || empty($del['success'])) {
      $this->db->trans_rollback();
      $errMsg = 'Failed to delete class.';
      if (is_array($del) && isset($del['error']['message'])) {
        $errMsg .= ' DB Error: ' . $del['error']['message'];
      }
      redirect($this->madresaPath('classes') . $yearParam . (empty($yearParam) ? '?' : '&') . 'error=' . urlencode($errMsg));
    }

    if ($this->db->trans_status() === false) {
      $this->db->trans_rollback();
      redirect($this->madresaPath('classes') . $yearParam . (empty($yearParam) ? '?' : '&') . 'error=' . urlencode('Database transaction failed'));
    }

    $this->db->trans_commit();
    redirect($this->madresaPath('classes') . $yearParam . (empty($yearParam) ? '?' : '&') . 'message=' . urlencode('Class deleted successfully'));
  }

  // AJAX: Fetch student details for selected ITS IDs
  public function student_details()
  {
    $this->requireLogin();

    $idsParam = trim((string)$this->input->get('ids'));
    $ids = [];

    if ($idsParam !== '') {
      foreach (explode(',', $idsParam) as $part) {
        $v = (int)trim($part);
        if ($v > 0) $ids[] = $v;
      }
      $ids = array_values(array_unique($ids));
      if (count($ids) > 200) {
        $ids = array_slice($ids, 0, 200);
      }
    }

    $students = $this->MadresaM->get_students_details($ids);

    $payload = [
      'success' => true,
      'count' => is_array($students) ? count($students) : 0,
      'students' => is_array($students) ? $students : [],
    ];

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($payload));
  }

  // AJAX: Fetch payment history for a student in a class
  public function get_payment_history()
  {
    $this->requireLogin();

    $classId = (int)$this->input->post('class_id');
    $studentItsId = (int)$this->input->post('students_its_id');

    if ($classId <= 0 || $studentItsId <= 0) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(['error' => 'Invalid parameters']));
      return;
    }

    $payments = $this->MadresaM->list_class_payments($classId, $studentItsId);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($payments));
  }

  // AJAX: Receive payment for a student in a class
  public function ajax_receive_payment()
  {
    $this->requireLogin();

    if (!$this->canReceiveMadresaPayment()) {
      $this->output->set_status_header(403)->set_output(json_encode(['success' => false, 'error' => 'Not authorized']));
      return;
    }

    $classId = (int)$this->input->post('class_id');
    $studentItsId = (int)$this->input->post('students_its_id');
    $amount = (float)$this->input->post('amount');
    $paymentMode = trim((string)$this->input->post('payment_mode'));
    $paidOn = trim((string)$this->input->post('paid_on'));
    $notes = trim((string)$this->input->post('notes'));

    if ($classId <= 0 || $studentItsId <= 0 || $amount <= 0) {
      $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'error' => 'Invalid parameters']));
      return;
    }
    
    // Validate date format if provided
    if ($paidOn !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $paidOn)) {
      $this->output->set_status_header(400)->set_output(json_encode(['success' => false, 'error' => 'Invalid date format']));
      return;
    }

    $createdBy = !empty($_SESSION['user']['username']) ? (string)$_SESSION['user']['username'] : null;

    $res = $this->MadresaM->create_class_payment($classId, $studentItsId, $amount, $paidOn === '' ? null : $paidOn, $paymentMode === '' ? null : $paymentMode, null, $notes === '' ? null : $notes, $createdBy);
    
    if (empty($res) || empty($res['success'])) {
      $errMsg = 'Failed to save payment.';
      if (is_array($res) && !empty($res['error']['message'])) {
        $errMsg .= ' ' . $res['error']['message'];
      }
      $this->output->set_output(json_encode(['success' => false, 'error' => $errMsg]));
      return;
    }

    // Fetch updated totals and student info
    $student = $this->MadresaM->get_class_student_financials($classId, $studentItsId);
    $financials = $this->MadresaM->get_class_financials($classId);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => true,
        'message' => 'Payment received successfully',
        'student' => $student,
        'class_financials' => $financials
      ]));
  }
}
