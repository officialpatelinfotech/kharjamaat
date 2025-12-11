<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Legal extends CI_Controller {
  public function __construct() {
    parent::__construct();
  }

  private function baseData() {
    $data = [];
    $data['last_updated'] = date('d M Y');
    $data['org_name'] = 'Anjuman-e-Saifee Khar';
    $data['address_line'] = '3RFP+FV4, SV Rd, Khar';
    $data['city_state'] = 'Khar West, Mumbai, Maharashtra';
    $data['pincode'] = '400052';
    $data['support_email'] = 'anjuman@kharjamaat.in';
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
