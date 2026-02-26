<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Reports extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('CommonM');
  }

  private function validateUser($user)
  {
    // Keep consistent with other shared pages: allow admin/anjudman/amilsaheb/masoolmusaid
    if (empty($user) || ($user['role'] != 1 && $user['role'] != 3 && $user['role'] != 2 && $user['role'] != 16)) {
      redirect('/accounts');
    }
  }

  private function loadHeaderForRole($data)
  {
    $role = isset($_SESSION['user']['role']) ? (int)$_SESSION['user']['role'] : 0;
    if ($role === 1) {
      $this->load->view('Admin/Header', $data);
      return;
    }
    if ($role === 3) {
      $this->load->view('Anjuman/Header', $data);
      return;
    }
    if ($role === 2) {
      $this->load->view('Amilsaheb/Header', $data);
      return;
    }
    if ($role === 16) {
      $this->load->view('MasoolMusaid/Header', $data);
      return;
    }

    // Fallback
    $this->load->view('Accounts/Header', $data);
  }

  public function sabeel_takhmeen_diff()
  {
    $this->validateUser($_SESSION['user']);

    $data = [];
    $data['user_name'] = $_SESSION['user']['username'] ?? '';
    $data['active_controller'] = base_url('reports/sabeel-diff');

    $this->loadHeaderForRole($data);
    $this->load->view('Reports/SabeelTakhmeenDiff', $data);
  }

  private function compute_previous_composite_year($compositeYear)
  {
    $compositeYear = trim((string)$compositeYear);
    if ($compositeYear === '') return null;

    // Expected format: 1446-47
    if (preg_match('/^(\d{4})-(\d{2})$/', $compositeYear, $m)) {
      $start = (int)$m[1];
      if ($start <= 0) return null;
      $prevStart = $start - 1;
      $prevEndTwo = ($prevStart + 1) % 100;
      return sprintf('%d-%02d', $prevStart, $prevEndTwo);
    }

    return null;
  }

  public function sabeel_takhmeen_diff_data()
  {
    $this->validateUser($_SESSION['user']);

    $currentYear = $this->input->get('year'); // optional: '1446-47'
    if ($currentYear !== null) {
      $currentYear = trim((string)$currentYear);
      if ($currentYear === '') $currentYear = null;
    }

    // Default to latest year present in sabeel_takhmeen
    if (empty($currentYear)) {
      $row = $this->db->select('DISTINCT year', false)->from('sabeel_takhmeen')->order_by('year', 'DESC')->get()->row_array();
      $currentYear = !empty($row['year']) ? (string)$row['year'] : null;
    }

    $previousYear = $this->compute_previous_composite_year($currentYear);

    // For dropdown: all available composite years
    $yearRows = $this->db->select('DISTINCT year', false)->from('sabeel_takhmeen')->order_by('year', 'DESC')->get()->result_array();
    $years = array_values(array_filter(array_map(function ($r) {
      return isset($r['year']) ? (string)$r['year'] : '';
    }, $yearRows), function ($y) {
      return $y !== '';
    }));

    $rows = [];
    if (!empty($currentYear) && !empty($previousYear)) {
      $rows = $this->CommonM->get_sabeel_member_year_over_year($currentYear, $previousYear);
    }

    $out = [];
    foreach ($rows as $r) {
      $estPrev = (float)($r['est_prev'] ?? 0);
      $estCurr = (float)($r['est_curr'] ?? 0);
      $resPrev = (float)($r['res_prev'] ?? 0);
      $resCurr = (float)($r['res_curr'] ?? 0);

      $totalPrev = $estPrev + $resPrev;
      $totalCurr = $estCurr + $resCurr;
      $totalDiff = $totalCurr - $totalPrev;
      $totalPct = null;
      if (abs($totalPrev) > 0.000001) {
        $totalPct = ($totalDiff / $totalPrev) * 100.0;
      }

      $out[] = [
        'its_id' => (string)($r['ITS_ID'] ?? ''),
        'full_name' => (string)($r['Full_Name'] ?? ''),
        'sector' => (string)($r['Sector'] ?? ''),
        'sub_sector' => (string)($r['Sub_Sector'] ?? ''),
        'previous_year' => (string)$previousYear,
        'current_year' => (string)$currentYear,
        'total_previous' => $totalPrev,
        'total_current' => $totalCurr,
        'total_diff' => $totalDiff,
        'total_diff_percent' => $totalPct,
      ];
    }

    $payload = [
      'success' => true,
      'current_year' => $currentYear,
      'previous_year' => $previousYear,
      'years' => $years,
      'rows' => $out,
    ];

    return $this->output->set_content_type('application/json')->set_output(json_encode($payload));
  }
}
