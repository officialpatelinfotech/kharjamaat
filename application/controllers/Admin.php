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
    $this->load->model('HijriCalendar');
    $this->load->library('email', $this->config->item('email'));
    $this->AdminM->updateHijriGregorianDates();
  }

  private function validateUser($user)
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
  }


  public function index()
  {
    $this->validateUser($_SESSION['user']);

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Home');
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
    $title = trim($this->input->post('title'));
    $amount = (float)$this->input->post('amount');
    $description = trim($this->input->post('description'));
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
      $processed = 0; $inserted = 0; $updated = 0;
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
              $exists = $this->db->get_where('corpus_fund_assignment', ['hof_id'=>$hof_id, 'fund_id'=>$fund_id])->row_array();
              if ($exists) {
                $this->db->where('id', $exists['id'])->update('corpus_fund_assignment', ['amount_assigned'=>$amount]);
                $updated++;
              } else {
                $this->db->insert('corpus_fund_assignment', ['hof_id'=>$hof_id, 'fund_id'=>$fund_id, 'amount_assigned'=>$amount, 'created_at'=>date('Y-m-d H:i:s')]);
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
          $exists = $this->db->get_where('corpus_fund_assignment', ['hof_id'=>$hof_id, 'fund_id'=>$fund_id])->row_array();
          if ($exists) {
            $this->db->where('id', $exists['id'])->update('corpus_fund_assignment', ['amount_assigned'=>$amount]);
            $updated++;
          } else {
            $this->db->insert('corpus_fund_assignment', ['hof_id'=>$hof_id, 'fund_id'=>$fund_id, 'amount_assigned'=>$amount, 'created_at'=>date('Y-m-d H:i:s')]);
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
    for ($i=0;$i<5;$i++) fputcsv($out, ['', '', '']);
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
    $its_id = trim($this->input->post('its_id'));
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
      'sub_sector' => $this->input->get('sub_sector')
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
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $summary = ['processed' => 0, 'inserted' => 0, 'updated' => 0, 'skipped' => 0, 'moved_out' => 0, 'errors' => []];
      $importedIds = [];
      if (empty($_FILES['data_file']['tmp_name'])) {
        $summary['errors'][] = 'No file uploaded';
      } else {
        $fh = fopen($_FILES['data_file']['tmp_name'], 'r');
        if (!$fh) {
          $summary['errors'][] = 'Cannot open uploaded file';
        } else {
          $header = fgetcsv($fh);
          if (!$header) {
            $summary['errors'][] = 'Empty file';
          } else {
            $normalizedHeader = array_map(function ($h) {
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
              while (($row = fgetcsv($fh)) !== false) {
                $summary['processed']++;
                $assoc = [];
                foreach ($normalizedHeader as $idxName) {
                  $idx = $colIndex[$idxName];
                  $assoc[$idxName] = isset($row[$idx]) ? trim($row[$idx]) : '';
                }
                if ($assoc['ITS_ID'] === '' || $assoc['Full_Name'] === '') {
                  $summary['skipped']++;
                  continue;
                }

                // Only process Residential / Resident Mumineen rows during import.
                $memberType = '';
                if (isset($assoc['Member_Type'])) $memberType = $assoc['Member_Type'];
                elseif (isset($assoc['member_type'])) $memberType = $assoc['member_type'];
                $memberType = trim(strtolower((string)$memberType));
                $allowed = ['resident mumineen', 'residential mumineen'];
                if ($memberType === '' || !in_array($memberType, $allowed, true)) {
                  // Skip rows that are not Residential/Resident Mumineen
                  $summary['skipped']++;
                  continue;
                }
                $importedIds[] = $assoc['ITS_ID'];
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
                          'password' => md5('525352'),
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
                          'password' => md5('525352'),
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
          fclose($fh);
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
      $this->load->view('Admin/Header', $data);
      $this->load->view('Admin/ImportMembers', $data);
      return;
    }
    // GET: show form
    $data['user_name'] = $_SESSION['user']['username'];
    $data['summary'] = null;
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ImportMembers', $data);
  }
  public function RazaRequest()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    $data['user_name'] = $_SESSION['user']['username'];
    $data['wajebaat'] = $this->WajebaatM->get_all();
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
              $res = $this->WajebaatM->upsert(['ITS_ID' => $its, 'amount' => $amount, 'due' => $due]);
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
          $res = $this->WajebaatM->upsert(['ITS_ID' => $its, 'amount' => $amount, 'due' => $due]);
          if ($res['action'] === 'inserted') $inserted++;
          elseif ($res['action'] === 'updated') $updated++;
        } else {
          $skipped++;
        }
      }
      fclose($fh);
      $this->session->set_flashdata('message', "Import completed: $inserted inserted, $updated updated, $skipped skipped from $processed rows");
      redirect('admin/wajebaat');
      return;
    }

    $data['message'] = $this->session->flashdata('message');
    $data['error'] = $this->session->flashdata('error');
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/WajebaatImport', $data);
  }

  public function wajebaat_export()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('WajebaatM');
    $rows = $this->WajebaatM->get_all();

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=wajebaat_export_' . date('Ymd_His') . '.csv');
    echo "\xEF\xBB\xBF"; // BOM
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id', 'ITS_ID', 'amount', 'due', 'created_at']);
    foreach ($rows as $r) {
      fputcsv($out, [$r['id'], $r['ITS_ID'], $r['amount'], $r['due'], $r['created_at']]);
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
    // Truncate table (remove all rows)
    $this->db->query('TRUNCATE TABLE `wajebaat`');
    $this->session->set_flashdata('message', 'All Wajebaat records deleted');
    redirect('admin/wajebaat');
  }

  public function qardan_hasana()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('QardanHasanaM');
    $data['user_name'] = $_SESSION['user']['username'];
    $data['qardan_hasana'] = $this->QardanHasanaM->get_all();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/QardanHasana', $data);
  }

  public function qardan_hasana_import()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('QardanHasanaM');
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
        redirect('admin/qardan_hasana_import');
        return;
      }
      $fh = fopen($_FILES['csv_file']['tmp_name'], 'r');
      if (!$fh) {
        $this->session->set_flashdata('error', 'Unable to open uploaded file');
        redirect('admin/qardan_hasana_import');
        return;
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
              $res = $this->QardanHasanaM->upsert(['ITS_ID' => $its, 'amount' => $amount, 'due' => $due]);
              if ($res['action'] === 'inserted') $inserted++;
              elseif ($res['action'] === 'updated') $updated++;
            }
            else {
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
          $res = $this->QardanHasanaM->upsert(['ITS_ID' => $its, 'amount' => $amount, 'due' => $due]);
          if ($res['action'] === 'inserted') $inserted++;
          elseif ($res['action'] === 'updated') $updated++;
        }
        else {
          $skipped++;
        }
      }
      fclose($fh);
      $this->session->set_flashdata('message', "Import completed: $inserted inserted, $updated updated, $skipped skipped from $processed rows");
      redirect('admin/qardan_hasana');
      return;
    }

    $data['message'] = $this->session->flashdata('message');
    $data['error'] = $this->session->flashdata('error');
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/QardanHasanaImport', $data);
  }

  public function qardan_hasana_export()
  {
    $this->validateUser($_SESSION['user']);
    $this->load->model('QardanHasanaM');
    $rows = $this->QardanHasanaM->get_all();

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=qardan_hasana_export_' . date('Ymd_His') . '.csv');
    echo "\xEF\xBB\xBF"; // BOM
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id', 'ITS_ID', 'amount', 'due', 'created_at']);
    foreach ($rows as $r) {
      fputcsv($out, [$r['id'], $r['ITS_ID'], $r['amount'], $r['due'], $r['created_at']]);
    }
    fclose($out);
    exit;
  }

  public function qardan_hasana_template()
  {
    $this->validateUser($_SESSION['user']);
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=qardan_hasana_template_5row.csv');
    echo "\xEF\xBB\xBF"; // BOM
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ITS_ID', 'amount', 'due']);
    for ($i = 0; $i < 5; $i++) {
      fputcsv($out, ['', '', '']);
    }
    fclose($out);
    exit;
  }

  public function qardan_hasana_delete()
  {
    $this->validateUser($_SESSION['user']);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('admin/qardan_hasana');
      return;
    }
    // optional confirmation flag
    $confirm = $this->input->post('confirm');
    if ($confirm !== '1' && $confirm !== 1) {
      $this->session->set_flashdata('error', 'Delete not confirmed');
      redirect('admin/qardan_hasana_import');
      return;
    }
    // Truncate table (remove all rows)
    $this->db->query('TRUNCATE TABLE `qardan_hasana`');
    $this->session->set_flashdata('message', 'All Qardan Hasana records deleted');
    redirect('admin/qardan_hasana');
  }

  public function approveRaza()
  {
    $remark = trim($_POST['remark']);
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
    // If this is a Miqaat Raza, show Miqaat details in the email instead of generic razatype fields.
    if (!empty($razaRow['miqaat_id'])) {
      $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRow['miqaat_id']);
      $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
      $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRow['miqaat_id'];
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
        . '<tr><td><strong>Miqaat ID</strong></td><td>' . htmlspecialchars($miqaatPublicId) . '</td></tr>'
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

    // Enqueue email to user (non-blocking)
    $this->load->model('EmailQueueM');
    $message = '<p>Baad Afzalus Salaam,</p>'
      . '<p><strong>Mubarak!</strong> Your Raza request has received a recommendation from Anjuman-e-Saifee Jamaat.</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($razaPublicId) . '</p>'
      . $remarkHtml
      . $detailsHtml
      . '<p>Kindly reach out to <strong>Janab Amil Saheb</strong> via <strong>Phone/WhatsApp</strong> to obtain his <strong>final Raza and Dua</strong>: <strong>+91-' . $amilsaheb_mobile . '</strong></p>'
      . '<p><strong>Wassalaam.</strong></p>';
    $this->EmailQueueM->enqueue($user['Email'], 'Update on Your Raza Request', $message, null, 'html');

    // Notify Amilsaheb, Khar Jamaat, 3042 Carmelnmh, Anjuman
    $msg_html = '<p>Raza request has been recommended by the Jamaat Coordinator.</p>'
      . '<p><strong>Member:</strong> ' . htmlspecialchars($user['Full_Name']) . ' (' . htmlspecialchars($user['ITS_ID']) . ')</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($razaPublicId) . '</p>'
      . $remarkHtml
      . $detailsHtml;

    $this->email->set_mailtype('html');

    $notify_recipients = [
      'kharjamaat@gmail.com',
      'kharjamaat786@gmail.com',
      'kharamilsaheb@gmail.com',
      '3042@carmelnmh.in',
      'khozemtopiwalla@gmail.com',
      'ybookwala@gmail.com'
    ];

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
    $detailsHtml = render_raza_details_table_html($razaName, $rtFieldsDecoded, $razadataDecoded);
    // If this is a Miqaat Raza, show Miqaat details in the email instead of generic razatype fields.
    if (!empty($razaRow['miqaat_id'])) {
      $miqaatRow = $this->AccountM->get_miqaat_by_id((int)$razaRow['miqaat_id']);
      $miqaatName = isset($miqaatRow['name']) ? (string)$miqaatRow['name'] : '';
      $miqaatPublicId = isset($miqaatRow['miqaat_id']) ? (string)$miqaatRow['miqaat_id'] : (string)$razaRow['miqaat_id'];
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
        . '<tr><td><strong>Raza ID</strong></td><td>' . htmlspecialchars($razaPublicId) . '</td></tr>'
        . '<tr><td><strong>Miqaat</strong></td><td>' . htmlspecialchars($miqaatName) . '</td></tr>'
        . '<tr><td><strong>Miqaat ID</strong></td><td>' . htmlspecialchars($miqaatPublicId) . '</td></tr>'
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
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($razaPublicId) . '</p>'
      . $remarkHtml
      . $detailsHtml
      . '<p>Please wait for Janab\'s response or contact Jamaat office for guidance.</p>'
      . '<p><strong>Wassalaam.</strong></p>';
    $this->EmailQueueM->enqueue($user['Email'], 'Update on Your Raza Request', $memberBody, null, 'html');

    $msg_text = 'Raza request for ' . htmlspecialchars($user['Full_Name']) . ' (' . htmlspecialchars($user['ITS_ID']) . ') has not been recommended by the Jamaat Coordinator. Raza ID: ' . htmlspecialchars($razaPublicId);
    $msg_html = '<p>Raza request has <strong>not</strong> been recommended by the Jamaat Coordinator.</p>'
      . '<p><strong>Member:</strong> ' . htmlspecialchars($user['Full_Name']) . ' (' . htmlspecialchars($user['ITS_ID']) . ')</p>'
      . '<p><strong>Raza ID:</strong> ' . htmlspecialchars($razaPublicId) . '</p>'
      . $remarkHtml
      . $detailsHtml;

    $this->email->set_mailtype('html');

    $monitor_bcc = ['khozemtopiwalla@gmail.com', 'ybookwala@gmail.com'];
    $admin_recipients = [
      'amilsaheb@kharjamaat.in',
      '3042@carmelnmh.in',
      'kharjamaat@gmail.com',
      'kharamilsaheb@gmail.com',
      'kharjamaat786@gmail.com',
      'khozemtopiwalla@gmail.com',
      'ybookwala@gmail.com'
    ];
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['raza_type'] = $this->AdminM->get_razatype();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageRaza', $data);
  }
  public function manage_edit_raza($id)
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['raza'] = $this->AdminM->get_razatype_byid($id)[0];
    // echo '<pre>';
    // echo print_r($data['raza']);
    // die();
    $data['raza']['fields'] = json_decode($data['raza']['fields'], true);
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
      http_response_code(200);
      echo json_encode(['status' => true]);
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageFMBSettings', $data);
  }

  public function managefmbtakhmeen()
  {
    if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $data["all_user_fmb_takhmeen"] = $this->AdminM->get_user_fmb_takhmeen_details();
    // Provide full filter meta so dropdowns don't collapse after filter
    $data['filter_meta'] = $this->AdminM->get_member_filter_meta();

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageFMBTakhmeen', $data);
  }

  public function deletefmbtakhmeen()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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

  public function fmbgeneralcontributionmaster()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $get_all_fmbgc = [];
    $filter_status = $this->input->post("filter_status");

    if (isset($filter_status)) {
      $get_all_fmbgc = $this->AdminM->getallfmbgc($filter_status);
    } else {
      $get_all_fmbgc = $this->AdminM->getallfmbgc();
    }

    if ($get_all_fmbgc) {
      $data['all_fmbgc'] = $get_all_fmbgc;
    }

    $data['filter_status'] = $filter_status;
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/FMBGeneralContributionMaster', $data);
  }

  public function addfmbcontritype()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $fmb_type = $this->input->post("fmb_type");
    $contri_for = $this->input->post("contri_for");

    $result = $this->AdminM->addfmbcontritype($fmb_type, $contri_for);

    if ($result) {
      redirect("admin/success/fmbgeneralcontributionmaster");
    } else {
      redirect("admin/error/fmbgeneralcontributionmaster");
    }
  }

  public function updatefmbgc()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $id = $this->input->post("id");
    $name = $this->input->post("name");
    $fmb_type = $this->input->post("fmb_type");
    $status = $this->input->post("status");

    $result = $this->AdminM->updatefmbgc(
      $id,
      $name,
      $fmb_type,
      $status
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $user_id = $this->input->post("user_id");
    $fmb_takhmeen_amount = $this->input->post("fmb_takhmeen_amount");
    $fmb_takhmeen_year = $this->input->post("fmb_takhmeen_year");

    $data = array(
      "user_id" => $user_id,
      "year" => $fmb_takhmeen_year,
      "total_amount" => $fmb_takhmeen_amount,
    );

    $result = $this->AdminM->addfmbtakhmeenamount($data);

    redirect("admin/success/managefmbtakhmeen");
  }
  public function validatefmbtakhmeen()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
  public function updatefmbtakhmeen($redirect)
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageSabeelTakhmeen', $data);
  }

  public function sabeeltakhmeendashboard()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $data["all_user_sabeel_takhmeen"] = $this->AdminM->get_user_sabeel_takhmeen_details();

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/SabeelTakhmeenDashboard', $data);
  }
  public function filteruserinsabeeltakhmeen()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
        if ($month >= 1 && $month <= 8) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    } else {
      $type =  "Residential";
    }
    $result = $this->AdminM->validatesabeelgrade($type, $year, $grade);
    if ($result) {
      echo json_encode(["success" => true]);
    }
  }
  public function addsabeelgrade($sabeel_type)
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    } else {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $year = $this->input->post("sabeel_takhmeen_year");
    $establishment_grade = $this->input->post("establishment_grade");
    $residential_grade = $this->input->post("residential_grade");

    $data = array(
      "user_id" => $user_id,
      "year" => $year,
      "establishment_grade" => $establishment_grade ?: null,
      "residential_grade" => $residential_grade ?: null,
    );
    $hasAnyGrade = !empty($establishment_grade) || !empty($residential_grade);
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
    $this->session->set_flashdata('warning', 'Please select at least one grade (Establishment or Residential).');
    redirect('admin/sabeeltakhmeendashboard');
  }
  public function checkSabeelTakhmeenExists()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $year = $this->input->post("year");

    $result = $this->AdminM->checkSabeelTakhmeenExists($user_id, $year);
    echo $result;
  }
  public function getUserTakhmeenDetails()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $year = $this->input->post("year");

    $result = $this->AdminM->getUserTakhmeenDetails($user_id, $year);
    echo json_encode($result);
  }
  public function updatesabeeltakhmeen()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $takhmeen_id = $this->input->post("takhmeen_id");
    $establishment_grade = $this->input->post("establishment_grade");
    $residential_grade = $this->input->post("residential_grade");

    $data = array(
      "user_id" => $user_id,
      "takhmeen_id" => $takhmeen_id,
      "establishment_grade" => $establishment_grade ?: null,
      "residential_grade" => $residential_grade ?: null,
    );
    $hasAnyGrade = !empty($establishment_grade) || !empty($residential_grade);
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    // Collect filters from query string
    $filters = [
      'name' => $this->input->get('name'),
      'sector' => $this->input->get('sector'),
      'sub_sector' => $this->input->get('sub_sector'),
      'status' => $this->input->get('status'),
      'hof' => $this->input->get('hof'),
    ];

    // Fetch filtered members & filter metadata
    $data['members'] = $this->AdminM->get_members_filtered($filters);
    $data['filter_meta'] = $this->AdminM->get_member_filter_meta();
    $data['applied_filters'] = $filters;

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ManageMembers', $data);
  }
  // Updated by Patel Infotech Services

  public function managemiqaat()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Miqaat', $data);
  }
  public function addmiqaat()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/AddMiqaat');
  }
  public function submitmiqaat()
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ModifyMiqaat', $data);
  }
  public function submitmodifymiqaat($id)
  {
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/AddMumineen', $data);
  }
  public function submitaddmumineen()
  {

    $data = array(
      'Full_Name' => $this->input->post('fullName'),
      'ITS_ID' => $this->input->post('itsId'),
      'Mobile' => $this->input->post('contact'),
      'Email' => $this->input->post('email'),
      'HOF_FM_TYPE' => $this->input->post('isHOF') ? 'HOF' : 'FM',
      'HOF_ID' => $this->input->post('isHOF') ? $this->input->post('itsId') : $this->input->post('hofItsId')
    );
    $logindata = array(
      'username' => $this->input->post('itsId'),
      'password' => md5($this->input->post('itsId')),
      'hof' => $this->input->post('isHOF') ? $this->input->post('itsId') : $this->input->post('hofItsId')
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

  // ================= Member Update =================
  public function editmember($its_id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
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
    // Debug log for diagnosing member_type fetch issues
    if (function_exists('log_message')) {
      $mt_dbg = isset($member['member_type']) ? $member['member_type'] : '(null)';
      log_message('debug', 'EditMember load: ITS_ID=' . $its_id . ' member_type=' . $mt_dbg);
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
    
    $data['sector_map'] = $this->AdminM->get_sector_hierarchy();
    $data['sector_list'] = array_keys($data['sector_map']);
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/EditMember', $data);
  }

  public function updatemember()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $its_id = $this->input->post('its_id');
    if (!$its_id) {
      echo json_encode(['status' => 'error', 'message' => 'Missing ITS ID']);
      return;
    }

    // Basic email validation if provided
    $email = $this->input->post('Email');
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
      return;
    }

    $isHof = $this->input->post('hof_type') === 'HOF';
    $hofReference = $isHof ? $its_id : $this->input->post('HOF_ID');

    // Collect all allowed fields (whitelist) from POST
    $fields = [
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
      'HOF_FM_TYPE',
      'HOF_ID',
      'Member_Type',
      'Registered_Family_Mobile',
      'Father_ITS_ID',
      'Mother_ITS_ID',
      'Spouse_ITS_ID',
      'Family_ID',
      'TanzeemFile_No',
      'Misaq',
      'Marital_Status',
      'Blood_Group',
      'Warakatul_Tarkhis',
      'Date_Of_Nikah',
      'Date_Of_Nikah_Hijri',
      'Organisation',
      'Organisation_CSV',
      'Vatan',
      'Nationality',
      'Jamaat',
      'Jamiaat',
      'Qualification',
      'Languages',
      'Hunars',
      'Occupation',
      'Sub_Occupation',
      'Sub_Occupation2',
      'Quran_Sanad',
      'Qadambosi_Sharaf',
      'Raudat_Tahera_Ziyarat',
      'Karbala_Ziyarat',
      'Ashara_Mubaraka',
      'Housing',
      'Type_of_House',
      'Address',
      'Building',
      'Street',
      'Area',
      'State',
      'City',
      'Pincode',
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
      'Data_Verifcation_Status',
      'Data_Verification_Date',
      'Photo_Verifcation_Status',
      'Photo_Verification_Date',
      'Last_Scanned_Event',
      'Last_Scanned_Place',
      'Title',
      'Category',
      'Idara',
      'Inactive_Status'
    ];

    $payload = [];
    foreach ($fields as $f) {
      $val = $this->input->post($f);
      if ($val !== null) {
        $payload[$f] = is_string($val) ? trim($val) : $val;
      }
    }

    // Enforce HOF fields
    $payload['HOF_FM_TYPE'] = $isHof ? 'HOF' : 'FM';
    $payload['HOF_ID'] = $hofReference ?: ($isHof ? $its_id : null);
    if (!$isHof && empty($payload['HOF_ID'])) {
      echo json_encode(['status' => 'error', 'message' => 'HOF selection required for family member']);
      return;
    }

    // Guard: normalize empties (empty string -> omit so DB keeps NULL)
    foreach (['Sector', 'Sub_Sector', 'Inactive_Status'] as $nullableField) {
      if (array_key_exists($nullableField, $payload) && $payload[$nullableField] === '') {
        unset($payload[$nullableField]);
      }
    }

    // Enumerated validation for member_type
    if (isset($payload['Member_Type']) && $payload['Member_Type'] !== '') {
      $allowed_member_types = [
        'Resident Mumineen',
        'External Sabeel Payers',
        'Moved-Out Mumineen',
        'Non-Sabeel Residents',
        'Temporary Mumineen/Visitors'
      ];
      if (!in_array($payload['Member_Type'], $allowed_member_types, true)) {
        echo json_encode([
          'status' => 'error',
          'field' => 'Member_Type',
          'allowed_values' => $allowed_member_types,
          'received' => $payload['Member_Type'],
          'message' => 'Invalid member type'
        ]);
        return;
      }
    }

    $updated = $this->AdminM->update_member($its_id, $payload);
    if ($updated) {
      // If member_type not explicitly in payload (unchanged), fetch existing value
      $mt_value = isset($payload['Member_Type']) ? $payload['Member_Type'] : null;
      if ($mt_value === null) {
        $existing_row = $this->AdminM->get_member_by_its($its_id);
        if ($existing_row && isset($existing_row['Member_Type'])) {
          $mt_value = $existing_row['Member_Type'];
        }
      }
      echo json_encode([
        'status' => 'success',
        'message' => 'Member updated',
        'Member_Type' => $mt_value
      ]);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'No changes or update failed']);
    }
  }

  // ================= Member Create =================
  public function addmember()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['hof_list'] = $this->AdminM->get_all_hofs();
    // Provide an empty member structure for reuse of view logic
    $data['member'] = [];
    $data['sector_map'] = $this->AdminM->get_sector_hierarchy();
    $data['sector_list'] = array_keys($data['sector_map']);
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/AddMember', $data);
  }

  // View single member details (read-only)
  public function viewmember($its_id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
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
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ViewMember', $data);
  }

  // JSON endpoint for modal view
  public function memberjson($its_id = null)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
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
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      redirect('/accounts');
    }
    $its_id = $this->input->post('ITS_ID');
    if (!$its_id) {
      echo json_encode(['status' => 'error', 'message' => 'ITS ID required']);
      return;
    }
    $email = $this->input->post('Email');
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode(['status' => 'error', 'message' => 'Invalid email']);
      return;
    }
    $payload = $this->input->post(NULL, true); // all sanitized
    if (isset($payload['Member_Type']) && $payload['Member_Type'] !== '') {
      $allowed_member_types = [
        'Resident Mumineen',
        'External Sabeel Payers',
        'Moved-Out Mumineen',
        'Non-Sabeel Residents',
        'Temporary Mumineen/Visitors'
      ];
      if (!in_array($payload['Member_Type'], $allowed_member_types, true)) {
        echo json_encode([
          'status' => 'error',
          'field' => 'Member_Type',
          'allowed_values' => $allowed_member_types,
          'received' => $payload['Member_Type'],
          'message' => 'Invalid member type'
        ]);
        return;
      }
    }
    $result = $this->AdminM->create_member($payload);
    if ($result['status'] === 'success') {
      $result['Member_Type'] = $payload['Member_Type'] ?? null;
    }
    echo json_encode($result);
  }

  public function expense()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/Expense');
  }

  public function expense_source_of_funds()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->model('ExpenseSourceM');
    $data['sources'] = $this->ExpenseSourceM->get_all();
    $this->load->view('Admin/Header', $data);
    $this->load->view('Admin/ExpenseSourceOfFunds', $data);
  }

  // AJAX: create new source
  public function expense_source_create()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $resp = ['status' => 'error', 'message' => 'Unauthorized'];
      if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        $resp['debug'] = [
          'session_user_present' => isset($_SESSION['user']),
          'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
          'cookies' => isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : null,
        ];
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($resp));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid method']));
      return;
    }
    $name = trim($this->input->post('name'));
    $status = trim($this->input->post('status')) ?: 'Active';
    if ($name === '') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Name required']));
      return;
    }
    $this->load->model('ExpenseSourceM');
    $id = $this->ExpenseSourceM->create(['name' => $name, 'status' => $status]);
    if ($id) {
      $this->output->set_content_type('application/json')->set_output(json_encode([
        'status' => 'success',
        'id' => $id,
        'name' => $name,
        'source_status' => $status
      ]));
    } else {
      $dberr = $this->db->error();
      $msg = 'Create failed';
      if (!empty($dberr['message'])) {
        $msg .= ': ' . $dberr['message'];
      }
      $resp = ['status' => 'error', 'message' => $msg];
      if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        $resp['debug'] = [
          'db_error' => $dberr,
          'session_user_present' => isset($_SESSION['user']),
          'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
          'cookies' => isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : null,
          'post' => $_POST,
        ];
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($resp));
    }
    return;
  }

  // AJAX: update existing source
  public function expense_source_update()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $resp = ['status' => 'error', 'message' => 'Unauthorized'];
      if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        $resp['debug'] = [
          'session_user_present' => isset($_SESSION['user']),
          'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
          'cookies' => isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : null,
        ];
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($resp));
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid method']));
      return;
    }
    $id = (int)$this->input->post('id');
    $name = trim($this->input->post('name'));
    $status = trim($this->input->post('status')) ?: 'Active';
    if ($id <= 0 || $name === '') {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'error', 'message' => 'Invalid input']));
      return;
    }
    $this->load->model('ExpenseSourceM');
    $ok = $this->ExpenseSourceM->update($id, ['name' => $name, 'status' => $status]);
    if ($ok) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    } else {
      $resp = ['status' => 'error', 'message' => 'Update failed'];
      if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        $resp['debug'] = [
          'db_error' => $this->db->error(),
          'session_user_present' => isset($_SESSION['user']),
          'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
          'post' => $_POST,
        ];
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($resp));
    }
    return;
  }

  // AJAX: delete source
  public function expense_source_delete()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
      $resp = ['status' => 'error', 'message' => 'Unauthorized'];
      if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        $resp['debug'] = [
          'session_user_present' => isset($_SESSION['user']),
          'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
          'cookies' => isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : null,
        ];
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($resp));
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
    $this->load->model('ExpenseSourceM');
    // Delete all related expenses first, then the source itself
    $ok = $this->ExpenseSourceM->delete_with_expenses($id);
    if ($ok) {
      $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success']));
    } else {
      $resp = ['status' => 'error', 'message' => 'Delete failed'];
      if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        $resp['debug'] = [
          'db_error' => $this->db->error(),
          'session_user_present' => isset($_SESSION['user']),
          'session_user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
          'post' => $_POST,
        ];
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($resp));
    }
    return;
  }
}
