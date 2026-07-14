<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Legal extends CI_Controller {
  public function __construct() {
    parent::__construct();
  }

  private function baseData() {
    $data = [];
    $data['last_updated'] = date('d M Y');
    $data['org_name'] = app_setting('managed_by', 'Anjuman-e-Saifee') . ' ' . jamaat_place();
    $data['address_line'] = app_setting('address_line', '');
    $data['city_state'] = app_setting('city_state', '');
    $data['pincode'] = app_setting('pincode', '');
    $data['support_email'] = app_setting('support_email', 'anjuman@' . ($_SERVER['HTTP_HOST'] ?? 'kharjamaat.in'));
    return $data;
  }

  public function terms() {
    $data = $this->baseData();
    $data['page_title'] = 'Terms & Conditions';
    $this->load->view('Home/Header', $data);
    $this->load->view('Legal/Terms', $data);
    $this->load->view('Common/Footer');
  }

  public function privacy() {
    $data = $this->baseData();
    $data['page_title'] = 'Privacy Policy';
    $this->load->view('Home/Header', $data);
    $this->load->view('Legal/Privacy', $data);
    $this->load->view('Common/Footer');
  }

  public function refund() {
    $data = $this->baseData();
    $data['page_title'] = 'Refund & Cancellation Policy';
    $this->load->view('Home/Header', $data);
    $this->load->view('Legal/Refund', $data);
    $this->load->view('Common/Footer');
  }

  public function contact() {
    $data = $this->baseData();
    $data['page_title'] = 'Contact Us';
    $this->load->view('Home/Header', $data);
    $this->load->view('Legal/Contact', $data);
    $this->load->view('Common/Footer');
  }
}
