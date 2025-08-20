<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Umoor extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->model('UmoorM');
    $this->load->model('HijriCalendar');
    $this->load->library('email', $this->config->item('email'));
  }
  public function index()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/Home');
  }
  public function success($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/Success.php', $data);
  }
  public function error($redirectto)
  {
    $data['user_name'] = $_SESSION['user']['username'];
    $data['redirect'] = $redirectto;
    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/Error.php', $data);
  }
  public function RazaRequest()
  {
    try {
      if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
        redirect('/accounts');
      }


      // Load necessary models
      $this->load->model('UmoorM');
      $this->load->model('AccountM');

      // Fetch user_id from the session
      $user_id = $_SESSION['user']['username'];

      // Fetch data from UmoorM model
      $data['raza'] = $this->UmoorM->get_raza($user_id);
      $data['razatype'] = $this->UmoorM->get_razatype($user_id);


      // Process data
      foreach ($data['raza'] as $key => $value) {
        $username = $this->AccountM->get_user($value['user_id']);
        $razatype = $this->UmoorM->get_razatype_byid($value['razaType']);


        if (!empty($razatype)) {
          $razatype = $razatype[0];
          $data['raza'][$key]['razaType'] = $razatype['name'];
          $data['raza'][$key]['razafields'] = $razatype['fields'];
          $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
        } else {
          // Handle the case where razatype is not found
          // You may want to log this or handle it according to your application's requirements
        }
      }

      foreach ($data['raza'] as $key => $value) {
        $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
        $data['raza'][$key]['chat_count'] = $chatCount;
      }
      $data['user_name'] = $_SESSION['user']['username'];

      // Load views
      $this->load->view('Umoor/Header', $data);
      $this->load->view('Umoor/Raza/RazaRequest', $data);
    } catch (Exception $e) {
      // Handle exceptions
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }

  // Updated by Patel Infotech Services
  public function get_hijri_day_month($greg_date)
  {
    $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($greg_date)));
    $hijri_month_name = $this->HijriCalendar->hijri_month_name($hijri_date["hijri_month_id"]);
    return substr($hijri_date["hijri_date"], 8, 2) . " " . $hijri_month_name["hijri_month"];
  }

  public function menulist()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];

    $data['menu'] = $this->UmoorM->get_month_wise_menu();

    foreach ($data["menu"] as $key => $value) {
      $data['menu'][$key]['hijri_date'] = $this->get_hijri_day_month($value["date"], $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"]);
    }
    $data["hijri_months"] = $this->HijriCalendar->get_hijri_month();

    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/MenuList', $data);
  }

  public function create_menu()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data["menu_dates"] = $this->UmoorM->get_menu_dates();
    $data['menu_items'] = $this->UmoorM->get_menu_items();
    $data['edit_mode'] = false;

    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/CreateMenu', $data);
  }

  public function add_menu()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role']) > 15) {
      redirect('/accounts');
    }

    $edit_mode = $this->input->post('edit_mode') == 'true' ? true : false;
    $menu_date = $this->input->post('menu_date');

    $selected_item_ids = json_decode($this->input->post('selected_item_ids'), true);

    if ($edit_mode) {
      $menu_id = $this->input->post('menu_id');
      if ($menu_id) {
        $menu_date ? $this->UmoorM->update_menu($menu_id, $menu_date) : 0;
        if (!empty($selected_item_ids)) {
          $this->UmoorM->remove_items_from_menu($menu_id);
          $this->UmoorM->add_items_to_menu($menu_id, $selected_item_ids);
          $this->session->set_flashdata('success', 'Menu created successfully!');
        }
      } else {
        $this->session->set_flashdata('error', 'Failed to create menu.');
      }
    } else {
      if (!empty($menu_date) && !empty($selected_item_ids)) {
        $menu_id = $this->UmoorM->insert_menu($menu_date);
        if ($menu_id) {
          $this->UmoorM->add_items_to_menu($menu_id, $selected_item_ids);
          $this->session->set_flashdata('success', 'Menu created successfully!');
        } else {
          $this->session->set_flashdata('error', 'Failed to create menu.');
        }
      } else {
        $this->session->set_flashdata('error', 'Please provide a name and select at least one item.');
      }
    }

    redirect('Umoor/menulist');
  }

  public function verify_menu_date()
  {
    $menu_date = $this->input->post('menu_date');

    if ($this->UmoorM->validate_menu_date($menu_date)) {
      echo json_encode(['status' => 'available', 'hijri_date' => date("d-m-Y", strtotime($this->HijriCalendar->get_hijri_date($menu_date)["hijri_date"]))]);
    } else {
      echo json_encode(['status' => 'exists']);
    }
  }

  public function insert_menu()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];

    $menu_name = $this->input->post('menu_name');
    $data['menu_name'] = $menu_name;

    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/CreateMenu', $data);
  }

  public function search_items()
  {
    $keyword = $this->input->post('keyword');
    $items = $this->UmoorM->search_items($keyword);
    echo json_encode($items);
  }

  public function edit_menu($id)
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }

    $data['edit_mode'] = true;

    $menu = $this->UmoorM->get_menu_by_id($id);


    if ($menu) {
      $data['menu'] = $menu;
      $data["mapped_menu_items"]['items'] = $this->UmoorM->get_menu_items_by_menu_id($menu['id']);
    } else {
      $data['menu'] = [];
    }

    $data["menu_dates"] = $this->UmoorM->get_menu_dates();
    $data['user_name'] = $_SESSION['user']['username'];

    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/CreateMenu', $data);
  }

  public function filter_menu()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    $hijri_month_id = $this->input->post('hijri_month');
    $today_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"];
    if ($hijri_month_id == -1) {
      $this_hijri_year = substr($today_hijri_date, 0, 4);
    } else if ($hijri_month_id == -2) {
      $this_hijri_year = substr($today_hijri_date, 0, 4) + 1;
    } else if ($hijri_month_id == -3) {
      $this_hijri_year = substr($today_hijri_date, 0, 4) - 1;
    } else {
      $this_hijri_year = substr($today_hijri_date, 0, 4);
    }

    $fitler_data = [
      'hijri_month_id' => $this->input->post('hijri_month'),
      'hijri_year' => $this_hijri_year,

      // 'start_date' => $this->input->post('start_date'),
      // 'end_date' => $this->input->post('end_date'),
      // 'sort_type' => $this->input->post('sort_type')
    ];

    $data['menu'] = $this->UmoorM->get_month_wise_menu($fitler_data);

    if (isset($data['menu']) && !empty($data['menu'])) {
      foreach ($data["menu"] as $key => $value) {
        $data['menu'][$key]['hijri_date'] = $this->get_hijri_day_month($value["date"]);
      }
    }
    $data["hijri_months"] = $this->HijriCalendar->get_hijri_month();

    $data['user_name'] = $_SESSION['user']['username'];
    $data['hijri_month_id'] = $this->input->post('hijri_month');
    // $data['start_date'] = $this->input->post('start_date');
    // $data['end_date'] = $this->input->post('end_date');
    // $data['sort_type'] = $this->input->post('sort_type');

    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/MenuList', $data);
  }

  // public function duplicate_last_month_menu()
  // {
  //   if (!empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
  //     redirect('/accounts');
  //   }

  //   $result = $this->UmoorM->duplicate_last_month_menu();

  //   if ($result) {
  //     $this->session->set_flashdata('success', 'Menu duplicated successfully.');
  //   } else {
  //     $this->session->set_flashdata('error', 'No records found for last month or duplication failed.');
  //   }

  //   redirect('Umoor/MenuList'); // Adjust this to your actual menu listing route
  // }


  // Menu Item Management
  public function add_menu_item()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $data['menu_items'] = $this->UmoorM->get_menu_items();
    $data['item_types'] = $this->UmoorM->get_item_types();

    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/AddMenuItem', $data);
  }

  public function insert_item_type()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];

    $type_name = $this->input->post('type_name');

    $data = array(
      'type_name' => $type_name,
    );

    $check = $this->UmoorM->insert_item_type($data);
    if ($check) {
      redirect('/Umoor/add_menu_item');
    } else {
      redirect('/Umoor/add_menu_item');
    }

    redirect('Umoor/menulist');
  }

  public function insert_menu_item()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    $data['user_name'] = $_SESSION['user']['username'];

    $item_name = $this->input->post('item_name');
    $item_type = $this->input->post('item_type');

    $data = array(
      'name' => $item_name,
      'type' => $item_type,
    );

    $check = $this->UmoorM->insert_menu_item($data);
    if ($check) {
      redirect('/Umoor/add_menu_item');
    } else {
      redirect('/Umoor/add_menu_item');
    }

    redirect('Umoor/menulist');
  }

  public function edit_menu_item()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }

    $data = [
      'id' => $this->input->post('id'),
      'name' => $this->input->post('name'),
      'type' => $this->input->post('type')
    ];

    $result = $this->UmoorM->edit_menu_item($data);

    echo json_encode(['status' => $result ? 'success' : 'failed']);
  }

  public function delete_menu_item()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }

    $id = $this->input->post('id');

    $result = $this->UmoorM->delete_menu_item($id);

    echo json_encode(['status' => 'success']);
  }

  public function filter_menu_item()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }

    $fitler_data = [
      'filter_type' => $this->input->post('filter-type'),
      'search_item' => $this->input->post('search-item'),
      'sort_type' => $this->input->post('sort-type')
    ];

    $data['user_name'] = $_SESSION['user']['username'];
    $data['filter_type'] = $this->input->post('filter-type');
    $data['search_item'] = $this->input->post('search-item');
    $data['sort_type'] = $this->input->post('sort-type');
    $data['item_types'] = $this->UmoorM->get_item_types();
    $data['menu_items'] = $this->UmoorM->filter_menu_item($fitler_data);
    $this->load->view('Umoor/Header', $data);
    $this->load->view('Umoor/AddMenuItem', $data);
  }

  public function deliverydashboard()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }
    $pendingDaysInYear = $this->HijriCalendar->getPendingDaysInYear();
    $hof_count = $this->UmoorM->get_hof_count()["hof_count"];

    if (isset($pendingDaysInYear)) {
      $data["pending_days_in_year"] = $pendingDaysInYear;
      foreach ($data["pending_days_in_year"] as $key => $value) {
        $get_signup_data = $this->UmoorM->getsignupcount($value["greg_date"]);
        $data["pending_days_in_year"][$key]["signup_count"] = (int)$get_signup_data["hof_signup_count"];
        $data["pending_days_in_year"][$key]["not_signup_count"] = (int)$hof_count - (int)$get_signup_data["hof_signup_count"];
        $data["pending_days_in_year"][$key]["delivery_person_count"] = (int)$get_signup_data["delivery_person_count"];
      }
    }
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view("Umoor/Header", $data);
    $this->load->view("Umoor/DeliveryDashboard", $data);
  }

  public function permanentassignment()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }

    $all_users = $this->UmoorM->get_all_users();
    $data['all_users'] = $all_users;
    $all_dp = $this->UmoorM->get_all_dp();
    $data['all_dp'] = $all_dp;
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view("Umoor/Header", $data);
    $this->load->view("Umoor/PermanentAssignment", $data);
  }

  public function signupforaday($date)
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }
    $data['date'] = $date;
    $thali_taken = $this->input->post("thali_taken");
    $reg_dp_id = $this->input->post("reg_dp_id");
    $sub_dp_id = $this->input->post("sub_dp_id");

    $data["thali_taken"] = $thali_taken;
    $data["reg_dp_id"] = $reg_dp_id;
    $data["sub_dp_id"] = $sub_dp_id;

    $all_dp = $this->UmoorM->get_all_dp();
    $data['all_dp'] = $all_dp;

    $signupforaday = $this->UmoorM->getsignupforaday($data);
    if ($signupforaday) {
      $data["signupdata"] = $signupforaday;
    }
    $data["filter_data"] = array(
      "thali_taken" => $thali_taken,
      "reg_dp_id" => $reg_dp_id,
      "sub_dp_id" => $sub_dp_id,
    );
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view("Umoor/Header", $data);
    $this->load->view("Umoor/SignUpForDay", $data);
  }

  public function updatedpmapping()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }

    $user_id = $this->input->post("user_id");
    $dp_id = $this->input->post("dp_id");
    $start_date = date("Y-m-d");

    $data = array(
      "user_id" => $user_id,
      "dp_id" => $dp_id,
      "start_date" => $start_date,
    );

    $result = $this->UmoorM->updatedpmapping($data);

    echo json_encode(["success" => true]);
  }

  public function managedeliveryperson()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }

    $all_dp = $this->UmoorM->get_all_dp();
    $data['all_dp'] = $all_dp;
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view("Umoor/Header", $data);
    $this->load->view("Umoor/ManageDeliveryPerson", $data);
  }
  public function adddeliveryperson()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }

    $dp_name = $this->input->post("delivery_person_name");
    $dp_phone = $this->input->post("delivery_person_phone");

    if (isset($dp_name) && !empty($dp_name)) {
      $dp_data = array(
        "name" => $dp_name,
        "phone" => $dp_phone,
      );
      $result = $this->UmoorM->adddeliveryperson($dp_data);
    }

    $data['user_name'] = $_SESSION['user']['username'];

    if ($result) {
      redirect('/Umoor/success/managedeliveryperson');
    } else {
      redirect("/Umoor/error/managedeliveryperson");
    }
  }
  public function updatedeliveryperson()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }

    $dp_id = $this->input->post("dp_id");
    $name = $this->input->post("name");
    $phone = $this->input->post("phone");
    $status = $this->input->post("status");

    $data = array(
      "dp_id" => $dp_id,
      "name" => $name,
      "phone" => $phone,
      "status" => $status,
    );

    $result = $this->UmoorM->updatedeliveryperson($data);
    $data['user_name'] = $_SESSION['user']['username'];

    echo json_encode(["success" => true]);
  }
  public function substitutedeliveryperson()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }
    $substituteDate = $this->input->post("substitute-date");
    $data["substitute_date"] = $substituteDate;
    $all_users = $this->UmoorM->get_all_users();
    $data['all_users'] = $all_users;
    $all_dp = $this->UmoorM->get_all_dp();
    $data['all_dp'] = $all_dp;
    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view("Umoor/Header", $data);
    $this->load->view("Umoor/substituteDeliveryPerson", $data);
  }
  public function assigndeliveryperson()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] <= 4 || $_SESSION['user']['role'] >= 15)) {
      redirect('/accounts');
    }
    $start_date = $this->input->post("start_date");
    $end_date = $this->input->post("end_date");
    $sub_data = $this->input->post("sub-data");

    $data["start_date"] = $start_date;
    $data["end_date"] = $end_date;
    if ($sub_data) {
      foreach ($sub_data as $key => $value) {
        if ($value) {
          $data["user_id"] = explode("|", $value)[0];
          $data["dp_id"] = explode("|", $value)[1];
          $result = $this->UmoorM->substitutedeliveryperson($data);
        }
      }
    }

    redirect("Umoor/DeliveryDashboard");
  }
  // Updated by Patel Infotech Services
}
