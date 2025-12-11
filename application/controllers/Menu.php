<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('AccountM');
    $this->load->model('HijriCalendar');
    $this->load->helper('url');
  }

  // Public index: renders the hijri month menu. Optional GET param: hijri=YYYY-MM
  public function viewmenu()
  {
    $hijriParam = $this->input->get('hijri');
    $todayGreg = date('Y-m-d');
    $todayHijri = $this->HijriCalendar->get_hijri_date($todayGreg);
    $parts = $todayHijri && isset($todayHijri['hijri_date']) ? explode('-', $todayHijri['hijri_date']) : [];
    $currentHy = !empty($parts) ? $parts[2] : date('Y');
    $currentHm = !empty($parts) ? $parts[1] : date('m');
    if (preg_match('/^\d{4}-\d{2}$/', (string)$hijriParam)) {
      list($hy, $hm) = explode('-', $hijriParam);
    } else {
      $hy = $currentHy; $hm = $currentHm; $hijriParam = $hy . '-' . $hm;
    }

    $data = [];
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
    if (empty($data['menus'])) {
      $data['menus_debug'] = [
        'selected_hijri' => $hijriParam,
        'year' => $hy,
        'month' => $hm,
        'reason' => 'No records found for provided Hijri month (check hijri_calendar and menu entries)'
      ];
    }

    // Add formatted hijri label for each menu row
    foreach ($data['menus'] as $key => $value) {
      $h = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($value['date'])));
      if ($h && isset($h['hijri_date'])) {
        $parts = explode('-', $h['hijri_date']);
        $monthRow = $this->HijriCalendar->hijri_month_name((int)$parts[1]);
        $data['menus'][$key]['hijri_date'] = $parts[0] . ' ' . ($monthRow ? $monthRow['hijri_month'] : $parts[1]);
      } else {
        $data['menus'][$key]['hijri_date'] = '';
      }
    }

    // Ensure header placeholders exist so Accounts/Header can render for anonymous users
    $data['user_name'] = isset($data['user_name']) ? $data['user_name'] : '';
    $data['member_name'] = isset($data['member_name']) ? $data['member_name'] : '';
    $data['sector'] = isset($data['sector']) ? $data['sector'] : '';

    // Use Accounts header for consistent branding, but keep the page public
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/FMB/ViewMenu', $data);
  }
}
