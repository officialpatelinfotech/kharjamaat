<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Umoor12 extends CI_Controller
{

  public function home()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/Home');
  }
  public function MyRazaRequest()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $value = $this->input->get('value');

    $this->load->model('Umoor12M');
    $this->load->model('AccountM');
    $user_id = $_SESSION['user_data']['ITS_ID'];
    $data['raza'] = $this->Umoor12M->get_raza($user_id, $value);

    // Fetch chat counts for each raza
    foreach ($data['raza'] as $key => $raza) {
      $username = $this->Umoor12M->get_user($raza['user_id']);
      $razatype = $this->Umoor12M->get_razatype_byid($raza['razaType'], $value)[0];
      $data['raza'][$key]['razaType'] = $razatype['name'];
      $data['raza'][$key]['razaType_id'] = $razatype['id'];
      $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];

      // Fetch chat count
      $chatCount = $this->AccountM->get_chat_count($raza['id']); // Assuming id is the raza_id
      $data['raza'][$key]['chat_count'] = $chatCount;
    }

    $data['user_name'] = $_SESSION['user']['username'];

    // Pass $value to the view
    $data['value'] = $value;

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/12_Umoor/MyRaza/Home', $data);
  }

  public function NewRaza()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $this->load->model('Umoor12M');
    $value = $this->input->get('value');
    $data['value'] = $value;
    $data['razatype'] = $this->Umoor12M->get_razatype($value);
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/12_Umoor/MyRaza/NewRaza3', $data);
  }

  public function get_miqaat_by_its_id()
  {
    $this->load->model('Umoor12M');
    $user_id = $_SESSION['user']['username'];
    $result = $this->Umoor12M->get_miqaat_by_its_id($user_id);
    if ($result) {
      echo json_encode($result);
    } else {
      echo "There was an error fetching miqaat data.";
    }
  }

  public function NewRazaBySearch()
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }

    $this->load->model('Umoor12M');

    // Get value from URL and razaId from POST
    $value = $this->input->get('value');
    $razaId = $this->input->post('razaId');

    // Load data
    $data['value'] = $value;
    $data['razaId'] = $razaId; // Pass razaId to view
    $data['razatype'] = $this->Umoor12M->get_razatype($value);
    $data['user_name'] = $_SESSION['user']['username'];

    // Load views
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/12_Umoor/MyRaza/NewRaza3', $data);
  }

  public function updateraza($id)
  {
    $this->load->model('Umoor12M');
    $value = $this->input->get('value');
    $this->email->from('admin@kharjamaat.in', 'Raza Update');
    $this->email->to($_SESSION['user_data']['Email']);
    $this->email->subject('Raza Status');
    $this->email->message('Your Raza has been updated');
    $this->email->send();
    $this->load->model('Umoor12M');

    unset($_POST['raza-type']);
    $data = json_encode($_POST);
    $flag = $this->AccountM->update_raza($id, $data);
    if ($flag) {
      redirect("/umoor12/success/MyRazaRequest?value=$value");
    } else {
      redirect("/umoor12/error/MyRazaRequest?value=$value");
    }
  }

  public function DeleteRaza($id)
  {
    $this->load->model('Umoor12M');
    $value = $this->input->get('value');

    $flag = $this->Umoor12M->delete_raza($id);
    if ($flag) {
      redirect("/umoor12/success/MyRazaRequest?value=$value");
    } else {
      redirect("/umoor12/error/MyRazaRequest?value=$value");
    }
  }




  public function success($redirectto)
  {
    $this->load->model('Umoor12M');
    $value = $this->input->get('value');
    $data['value'] = $value;
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/12_Umoor/Success.php', $data);
  }
  public function error($redirectto)
  {
    $this->load->model('Umoor12M');
    $value = $this->input->get('value');
    $data['value'] = $value;
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/12_Umoor/Error.php', $data);
  }

  public function submit_raza()
  {
    $this->load->model('AccountM');
    foreach ($_POST as $key => $value) {
      $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
    }
    $razatypeid = $_POST['raza-type'];
    $sabil = $_POST['sabil'];
    $fmb = $_POST['fmb'];
    $fmbtameer = $_POST['fmbtameer'];
    unset($_POST['sabil']);
    unset($_POST['fmb']);
    $razatype = $this->AccountM->get_razatype_byid($razatypeid)[0];
    $razafields = json_decode($razatype['fields'], true);
    $table = '<table border="1" style="border: 2px solid black; border-collapse: collapse; border-radius: 20px;" cellspacing="0" cellpadding="0"><tbody>';
    $table = $table . '<tr><td align="center" style="border: 1px solid black"><p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: bold; font-family: Roboto, arial, sans-serif;">Raza For</p></td><td align="center" style="border: 1px solid black;"><p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: normal; font-family: Roboto, arial, sans-serif;">' . $razatype['name'] . '</p></td></tr>';
    foreach ($razafields['fields'] as $value) {
      $result = preg_replace(
        ['/[\s]/', '/[()]/', '/[\/?]/'],
        ['-', '_', '-'],
        strtolower($value['name'])
      );
      $v = "";
      if ($value['type'] == "select") {
        $v = $value['options'][$_POST[$result]]['name'];
      } else {
        $v = $_POST[$result];
      }
      $table = $table . '<tr><td align="center" style="border: 1px solid black"><p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: bold; font-family: Roboto, arial, sans-serif;">' . $value['name'] . '</p></td><td align="center" style="border: 1px solid black;"><p style="color: #000000; margin: 0px; padding: 10px; font-size: 15px; font-weight: normal; font-family: Roboto, arial, sans-serif;">' . $v . '</p></td></tr>';
    }
    $table = $table . '</tbody></table>';
    $user_data = $_SESSION['user_data'];

    $weekDateTime = date('l, j M Y, h:i:s A');

    $msg = '<div style="padding: 0 !important; margin: 0 !important; display: block !important; width: 100% !important; background: #ffffff;" bgcolor="#ffffff"><table style="width: 100%; table-layout: fixed" align="center" cellspacing="0" border="0"><tbody><tr><td bgcolor="#ebe7e7" background="https://ci6.googleusercontent.com/proxy/fimhfkE_9YDx8Rr6KswSYnADXdusNOjQyhQYjmj6Y--V1LSV4Ip4qtqX_ZGMMFZ0c0loFqvg0fFiScuVZC7BF0isbDlIixR6=s0-d-e1-ft#https://www.its52.com/imgs/Email/1438_security_bg.png" align="center"><table class="m_455932988559829971its-mailer-container" style="max-width: 600x" width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td><table style="max-width: 600px" width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td align="center"><p style="color: #555555; font-weight: normal; margin: 0px; padding: 5px; font-size: 12px; font-family: Roboto, arial, sans-serif;">To ensure delivery to your inbox, <br />kindly add <a style="color: #555555; text-decoration: underline;" href="mailto:info@kharjamaat.in" target="_blank">info@kharjamaat.in</a> and <a style="color: #555555; text-decoration: underline;" href="mailto:admin@kharjamaat.in" target="_blank">admin@kharjamaat.in</a> to your address book.</p></td></tr><tr><td align="center" valign="top" bgcolor="#d94235" class="m_455932988559829971two-column"><div class="m_455932988559829971column" style="width: 100%; max-width: 285px; display: inline-block; padding: 5px; vertical-align: middle; text-align: left;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="width: 100% !important"><tbody><tr><td align="center"><a href="https://www.kharjamaat.in" style="color: #ffffff !important; text-decoration: none; margin: 0px; padding: 10px; font-size: 22px; font-weight: normal; font-style: italic; font-family: Georgia, arial, sans-serif;" target="_blank">www.kharjamaat.in</a></td></tr></tbody></table></div><div class="m_455932988559829971column" style="width: 100%; max-width: 285px; display: inline-block; padding: 5px; vertical-align: middle; text-align: left;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="width: 100% !important"><tbody><tr><td align="center"><p style="color: #ffffff; margin: 0px; padding: 10px; font-size: 12px; font-weight: normal; font-family: Roboto, arial, sans-serif;">' . $weekDateTime . '</p></td></tr></tbody></table></div></td></tr><tr><td valign="top" align="center"><a href="https://www.kharjamaat.in" target="_blank"><img src="https://demo.kharjamaat.in/assets/home_banner.jpg" width="100%" height="170px" style="display: block; max-width: 600px; width: 100%; object-fit:cover" border="0" class="CToWUd" data-bit="iit" /></a></td></tr><tr><td align="center" valign="top" bgcolor="#f8dcda"><h1 style="color: #d94235; margin: 0px; padding: 30px 10px 30px 10px; font-size: 22px; font-weight: normal; font-style: italic; font-family: Georgia, arial, sans-serif;">New Raza Application</h1></td></tr><tr><td align="left" bgcolor="#ffffff"><h2 style="color: #000; margin: 0px; padding: 20px 20px 0px 20px; font-style: italic; font-weight: normal; line-height: 24px; font-size: 16px; font-family: Georgia, serif;"><b>Baad Afzalus Salaam,</b><br />' . $user_data['Full_Name'] . ' - ' . $user_data['ITS_ID'] . '<br /></h2></td></tr><tr><td bgcolor="#ffffff"><div style="color: #000; margin: 0px; padding: 20px; line-height: 24px; font-size: 14px; font-weight: normal; font-family: Roboto, arial, sans-serif;">Your Raza form has been submitted successfully as mentioned below.<br /><br />' . $table . '<br /><br />If you have not Applied for this Raza, inform us by email on <a href="mailto:admin@kharjamaat.in" target="_blank">admin@kharjamaat.in</a></div></td></tr><tr><td align="left" bgcolor="#ffffff"><h2 style="color: #000; margin: 0px; padding: 0px 20px 0px 20px; font-style: italic; font-weight: normal; line-height: 24px; font-size: 16px; font-family: Georgia, serif;"><b>Wasalaam,</b><br />Anjuman-e-Saifee </h2><br /></td></tr><tr><td align="center" valign="top" bgcolor="#d94235" class="m_455932988559829971two-column"><p style="color: #ffffff; margin: 0px; padding: 20px; line-height: 24px; font-size: 14px; font-weight: normal; font-family: Roboto, arial, sans-serif;">Powered by:<br /><span style="font-size: 18px">Anjuman-e-Saifee, Khar (Khar),</span><br />Bohra Masjid,<br />Khar - 312604, Rajasthan India.<br /><a href="tel:+912268075353" style="color: #ffffff !important; text-decoration: none; font-size: 14px; font-weight: normal; font-family: Roboto, arial, sans-serif;" target="_blank">+91 7023270086</a> | <a href="mailto:admin@kharjamaat.in" style="color: #ffffff !important; text-decoration: none; font-size: 14px; font-weight: normal; font-family: Roboto, arial, sans-serif;" target="_blank">admin@kharjamaat.in</a><br /></p><p style="border-top: dashed 1px #fff; color: #ffffff; margin: 0px; padding: 10px; line-height: 24px; font-size: 12px; font-weight: normal; font-family: Roboto, arial, sans-serif;">You received this mandatory email service announcement to update you about important changes to your account.</p></td></tr></tbody></table></td></tr></tbody></table></div>';

    $this->load->model('EmailQueueM');
    $razacc = ['khozemtopiwalla@gmail.com', 'ybookwala@gmail.com'];

    // Enqueue a single message to the user with monitoring BCCs
    $this->EmailQueueM->enqueue($_SESSION['user_data']['Email'], 'New Raza', $msg, $razacc, 'html');

    // Enqueue admin notifications (they'll also get monitored via the BCC list above if desired)
    $adminRecipients = ['anjuman@kharjamaat.in', 'amilsaheb@kharjamaat.in', 'kharjamaat@gmail.com'];
    foreach ($adminRecipients as $r) {
      $this->EmailQueueM->enqueue($r, 'New Raza', $msg, $razacc, 'html');
    }

    $userId = $_SESSION['user_data']['ITS_ID'];
    unset($_POST['raza-type']);
    $data = json_encode($_POST);
    if (isset($_POST["miqaat_id"])) {
      $miqaat_id = $_POST["miqaat_id"];
    } else {
      $miqaat_id = null;
    }
    $check = $this->AccountM->insert_raza($userId, $razatypeid, $data, $miqaat_id, $sabil, $fmb);
    if ($check) {
      redirect('/accounts/success/myrazarequest');
    } else {
      redirect('/accounts/error/myrazarequest');
    }
  }

  public function edit_raza($id)
  {
    if (empty($_SESSION['user'])) {
      redirect('/accounts');
    }
    $this->load->model('Umoor12M');
    $value = $this->input->get('value');
    $data['value'] = $value;

    $data['razatype'] = $this->Umoor12M->get_razatype($value);
    $data['raza'] = $this->Umoor12M->get_raza_byid($id)[0];

    // Join miqaat table if miqaat_id is present
    $data['raza_miqaat'] = null;
    if (!empty($data['raza']['miqaat_id'])) {
      $data['raza_miqaat'] = $this->Umoor12M->get_raza_miqaat_details($data['raza']['miqaat_id'], $id);
    }

    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->view('Accounts/Header', $data);
    $this->load->view('Accounts/12_Umoor/MyRaza/UpdateRaza', $data);
  }
}
