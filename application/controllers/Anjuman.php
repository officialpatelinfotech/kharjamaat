<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Anjuman extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->model('AmilsahebM');
    $this->load->model('MasoolMusaidM');
    $this->load->model('AnjumanM');
    $this->load->library('email', $this->config->item('email'));
  }
  public function index()
  {
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Home');
  }
  public function RazaRequest()
  {
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $this->load->model('AmilsahebM');
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $data['umoor'] = "Event Raza Applications";
    $data['raza'] = $this->AmilsahebM->get_raza_event();
    $data['razatype'] = $this->AdminM->get_eventrazatype();

    // Fetch total chat count for each raza_id
    foreach ($data['raza'] as $key => $value) {
      $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
      // echo json_encode($data['raza'][$key]['miqaat_details']); exit;
      $data['raza'][$key]['chat_count'] = $chatCount;
    }

    // Fetch user details and raza type
    foreach ($data['raza'] as $key => $value) {
      $username = $this->AccountM->get_user($value['user_id']);
      $razatype = $this->AdminM->get_razatype_byid($value['razaType'])[0];
      $data['raza'][$key]['razaType'] = $razatype['name'];
      $data['raza'][$key]['razaType_id'] = $razatype['id'];
      $data['raza'][$key]['razafields'] = $razatype['fields'];
      $data['raza'][$key]['umoor'] = $razatype['umoor'];
      $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
      $data['raza'][$key]['miqaat_id'] = $value['miqaat_id'];
      if (!empty($value['miqaat_id'])) {
        $data['raza'][$key]['miqaat_details'] = json_encode($this->AnjumanM->get_miqaat_by_id($value['miqaat_id']));
      } else {
        $data['raza'][$key]['miqaat_details'] = "";
      }
    }
    // exit;

    // Set user name
    $data['user_name'] = $_SESSION['user']['username'];

    // Load the view
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/RazaRequest', $data);
  }

  public function UmoorRazaRequest()
  {
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $this->load->model('AmilsahebM');
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $data['umoor'] = "12 Umoor Raza Applications";
    $data['raza'] = $this->AmilsahebM->get_raza_umoor();
    $data['razatype'] = $this->AdminM->get_umoorrazatype();
    // $data['umoortype'] = $this->AdminM->get_umoortype();

    foreach ($data['raza'] as $key => $value) {
      $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
      $data['raza'][$key]['chat_count'] = $chatCount;
    }

    foreach ($data['raza'] as $key => $value) {
      $username = $this->AccountM->get_user($value['user_id']);
      $razatype = $this->AdminM->get_razatype_byid($value['razaType'])[0];
      $data['raza'][$key]['razaType'] = $razatype['name'];
      $data['raza'][$key]['razafields'] = $razatype['fields'];
      $data['raza'][$key]['umoor'] = $razatype['umoor'];

      $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
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
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
    $flag = $this->AdminM->approve_raza($raza_id, $remark);
    $this->email->from('raza@kharjamaat.in', 'Admin');
    $this->email->to($user['Email']);
    $this->email->subject('Raza Status');
    $this->email->message('Congratulation. Your Raza has been recommended by jamaat coordinator');

    $this->email->send();
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

    $this->email->from('raza@kharjamaat.in', 'Admin');
    $this->email->to($user['Email']);
    $this->email->subject('Raza Status');
    $this->email->message('Sorry. Your Raza has not recommended by jamaat coordinator. wait for janab response');
    $this->email->send();
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
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/CreateMiqaat', $data);
  }
  public function addmiqaat()
  {
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/AddMiqaat');
  }
  public function submitmiqaat()
  {
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/Miqaat/ModifyMiqaat', $data);
  }
  public function submitmodifymiqaat($id)
  {
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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
    if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 3) {
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

  public function asharaohbat()
  {
    $username = $_SESSION['user']['username'];

    // Fetch users based on search or get all
    if ($this->input->post('search')) {
      $users = $this->AmilsahebM->search_all_ashara($this->input->post('search'));
    } else {
      $users = $this->AmilsahebM->get_all_ashara();
    }

    // Fetch overall sector and sub-sector stats
    $sectorsData = $this->AmilsahebM->get_all_sector_stats();
    $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats();

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
      'current_sub_sector' => ''
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

    // Fetch all sectors and sub-sectors for dropdowns
    $all_sectors = $this->MasoolMusaidM->get_all_sectors();
    $all_sub_sectors = $sel_sector ? $this->MasoolMusaidM->get_all_sub_sectors($sel_sector) : [];

    // Fetch attendance data
    if ($this->input->post('search')) {
      $kw = $this->input->post('search', true);
      $users = $this->MasoolMusaidM->search_attendance_by_sector($kw, $sel_sector, $sel_sub);
    } else {
      $users = $this->MasoolMusaidM->get_attendance_by_sector($sel_sector, $sel_sub);
    }

    // Stats
    $stats = $this->MasoolMusaidM->get_sector_stats($sel_sector, $sel_sub);

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
    ];

    // Load view
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }

  // Updated by Patel Infotech Services
  public function generate_pdf()
  {
    $this->load->library('dompdf_lib');
    $dompdf = $this->dompdf_lib->load();

    $payment_id = $this->input->post("id");
    $for = $this->input->post("for");

    $result = $this->AnjumanM->get_payment_details($payment_id, $for);

    $data = [];

    if ($result) {
      $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
      $amount_words = $f->format($result["amount"]);

      $data = array(
        "date" => $result["payment_date"],
        "name" => $result["First_Name"] . " " . $result["Surname"],
        "address" => $result["Address"],
        "amount" => $result["amount"],
        "amount_words" => $amount_words,
      );
    }

    $html = $this->load->view('pdf_template', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Download as file
    // $dompdf->stream("myfile.pdf", array("Attachment" => 1));

    // Or show in browser
    $dompdf->stream("myfile.pdf", array("Attachment" => 0));
  }

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
    $data["all_user_fmb_takhmeen"] = $this->AnjumanM->get_user_takhmeen_details();
    $data["user_name"] = $username;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/FMBThaali', $data);
  }

  // FMB General Contribution section
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
      $user_id &&
      $contri_type
    ) {
      $result = $this->AnjumanM->validatefmbgc(
        $contri_year,
        $user_id,
        $contri_type
      );
    }

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
      redirect('Anjuman/success/FMBThaali');
    } else {
      redirect('Anjuman/error/FMBThaali');
    }
  }
  public function sabeeltakhmeendashboard()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $data["all_user_sabeel_takhmeen"] = $this->AnjumanM->get_user_sabeel_takhmeen_details();

    $data["user_name"] = $username;
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
    if ($member_name) {
      $filter_data = ["member_name" => $member_name];
      $data["all_user_sabeel_takhmeen"] = $this->AnjumanM->get_user_sabeel_takhmeen_details($filter_data);
    }

    $data["user_name"] = $username;
    $data["member_name"] = $member_name;
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
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;
    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/MiqaatInvoicePayment', $data);
  }

  public function miqaatinvoice()
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

  public function newmiqaatinvoice()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 3) {
      redirect('/accounts');
    }
    $username = $_SESSION['user']['username'];
    $data["user_name"] = $username;

    $this->load->view('Anjuman/Header', $data);
    $this->load->view('Anjuman/NewMiqaatInvoice', $data);
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

    $year        = $this->input->post('year'); // hijri year
    $miqaat_type = $this->input->post('miqaat_type');
    $miqaat_id   = $this->input->post('miqaat_id');
    $assigned_to = $this->input->post('assigned_to');
    $member_id   = $this->input->post('member_id'); // array of member IDs
    $amount      = $this->input->post('amount');
    $description = $this->input->post('description');
    $date        = $this->input->post('date');

    // ✅ Case 1: Shehrullah + Fala ni Niyaz + Hijri year logic (per family)
    if ($miqaat_type == 'Shehrullah' && strtolower($assigned_to) == 'fala ni niyaz' && $year) {
      // Step 1: Get gregorian dates for hijri year
      $dates = $this->db->select('greg_date')
        ->from('hijri_calendar')
        ->like('hijri_date', $year)
        ->get()
        ->result_array();

      $greg_dates = array_column($dates, 'greg_date');

      if (!empty($greg_dates)) {
        // Step 2: Fetch Shehrullah miqaāts in that Hijri year
        $miqaats = $this->db->select('id')
          ->from('miqaat')
          ->where_in('date', $greg_dates)
          ->where('type', 'Shehrullah')
          ->get()
          ->result_array();

        $miqaat_ids = array_column($miqaats, 'id');

        if (!empty($miqaat_ids)) {
          // Step 3: Get all families (HOF IDs)
          $all_hofs = $this->db
            ->distinct()
            ->select('HOF_ID')
            ->from('user')
            ->where('Inactive_Status IS NULL')
            ->get()
            ->result_array();
          $all_hof_ids = array_column($all_hofs, 'HOF_ID');

          // Step 4: Get participated members
          // Step 4: Get participated members & group leaders
          $sql = "SELECT DISTINCT member_id AS participant_id
                  FROM miqaat_assignments
                  WHERE miqaat_id IN (" . implode(',', $miqaat_ids) . ")
                    AND member_id IS NOT NULL

                  UNION

                  SELECT DISTINCT group_leader_id AS participant_id
                  FROM miqaat_assignments
                  WHERE miqaat_id IN (" . implode(',', $miqaat_ids) . ")
                    AND group_leader_id IS NOT NULL";

          $participated = $this->db->query($sql)->result_array();

          $participated_ids = array_column($participated, 'participant_id');

          // Step 5: Map those members to their Hof_id
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

          // Step 6: Families that did not participate
          $not_participated_hofs = array_diff($all_hof_ids, $participated_hof_ids);

          // Step 7: Raise invoices once per HOF
          foreach ($not_participated_hofs as $hof_id) {
            if (!$hof_id) continue; // skip null/empty
            $data = [
              'date'        => $date,
              'miqaat_id'   => $miqaat_id,
              'user_id'   => $hof_id, // store Hof_id as invoice owner
              'amount'      => $amount,
              'description' => $description
            ];
            $this->AnjumanM->create_miqaat_invoice($data);
          }
        }
      }

      redirect('Anjuman/miqaatinvoice');
      return;
    }

    // ✅ Case 2: Any other miqaat type → invoice for ALL families
    if (!empty($miqaat_type) && strtolower($miqaat_type) !== 'shehrullah' && strtolower($assigned_to) == 'fala ni niyaz') {
      $all_hofs = $this->db
        ->distinct()
        ->select('HOF_ID')
        ->from('user')
        ->where('Inactive_Status IS NULL')
        ->get()
        ->result_array();

      foreach ($all_hofs as $hof) {
        if (!$hof['HOF_ID']) continue; // skip null/empty
        $data = [
          'date'        => $date,
          'miqaat_id'   => $miqaat_id,
          'user_id'   => $hof['HOF_ID'], // per family invoice
          'amount'      => $amount,
          'description' => $description
        ];
        $this->AnjumanM->create_miqaat_invoice($data);
      }

      redirect('Anjuman/miqaatinvoice');
      return;
    }

    // ✅ Case 3: Normal manual invoice (form-selected members → families)
    if ($miqaat_id && $assigned_to && !empty($member_id) && $amount && $date) {
      // Convert selected members → Hof_ids
      $hofs = $this->db
        ->distinct()
        ->select('HOF_ID')
        ->from('user')
        ->where_in('ITS_ID', $member_id)
        ->get()
        ->result_array();

      foreach ($hofs as $hof) {
        $data = [
          'date'        => $date,
          'miqaat_id'   => $miqaat_id,
          'user_id'   => $hof['HOF_ID'], // store Hof_id instead of ITS_ID
          'amount'      => $amount,
          'description' => $description
        ];
        $this->AnjumanM->create_miqaat_invoice($data);
      }

      $this->session->set_flashdata('success', 'Invoice(s) created successfully.');
      redirect('Anjuman/miqaatinvoice');
    } else {
      $this->session->set_flashdata('error', 'Failed to create invoice.');
      redirect('Anjuman/miqaatinvoice');
    }
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
    $result = $this->AnjumanM->update_miqaat_payment_amount($payment_id, $amount);
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
  // Updated by Patel Infotech Services
}
