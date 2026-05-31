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
    $this->load->model('CommonM');
    $this->load->model('AmilsahebM');
    $this->load->model('MasoolMusaidM');
    $this->load->model('UmoorM');
    $this->load->model('HijriCalendar');
    $this->load->library('email', $this->config->item('email'));
  }
  public function index()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
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
      if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
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
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
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
  // Updated by Patel Infotech Services


  public function asharaohbat()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 4) {
      redirect('/accounts');
    }



    $username = $_SESSION['user']['username'];

    // Year selection (Hijri) — default to next year if current Hijri month >= 10
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts[2];
    $current_hijri_month = (int)$hijri_parts[1];
    $default_year = ($current_hijri_month >= 10) ? ($current_hijri_year + 1) : $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year);
    // Fetch years directly from HijriCalendar for the dropdown
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      $year_options = [$current_hijri_year - 1, $current_hijri_year, $current_hijri_year + 1];
    }
    if (!in_array($selected_year, $year_options, true)) {
      array_unshift($year_options, $selected_year);
    }
    $yearColumnExists = $this->db->field_exists('year', 'ashara_ohbat');

    // Fetch users based on search or get all
    if ($this->input->post('search')) {
      $users = $this->AmilsahebM->search_all_ashara($this->input->post('search'), $selected_year);
    } else {
      $users = $this->AmilsahebM->get_all_ashara($selected_year);
    }

    if (!$yearColumnExists && $selected_year !== $current_hijri_year) {
      // No year column: emulate "no data for selected year" by clearing status/comment
      foreach ($users as &$u) {
        $u['LeaveStatus'] = '';
        $u['Comment'] = '';
      }
      unset($u);
    }

    // Default empty/null/Unknown LeaveStatus to "Musaaid didn't Contacted Yet"
    foreach ($users as &$u) {
      if (empty($u['LeaveStatus']) || $u['LeaveStatus'] === 'Unknown') {
        $u['LeaveStatus'] = "Musaaid didn't Contacted Yet";
      }
    }
    unset($u);

    // Fetch overall sector and sub-sector stats
    $sectorsData = $this->AmilsahebM->get_all_sector_stats($selected_year);
    $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats($selected_year);
    if (!$yearColumnExists && $selected_year !== $current_hijri_year) {
      $sectorsData = [];
      $subSectorsData = [];
    }

    // Initialize stats
    $stats = [
      'HOF' => 0,
      'FM' => 0,
      'Mardo' => 0,
      'Bairo' => 0,
      'Age_0_4' => 0,
      'Age_5_15' => 0,
      'Age_16_25' => 0,
      'Age_26_65' => 0,
      'Buzurgo' => 0,
      'LeaveStatus' => [],
      'Sectors' => $sectorsData,
      'SubSectors' => $subSectorsData
    ];

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
      if ($age >= 16 && $age <= 25)
        $stats['Age_16_25']++;
      if ($age >= 26 && $age <= 65)
        $stats['Age_26_65']++;
      if ($age > 65)
        $stats['Buzurgo']++;

      if (!isset($stats['LeaveStatus'][$status])) {
        $stats['LeaveStatus'][$status] = 0;
      }
      $stats['LeaveStatus'][$status]++;
    }

    $data = [
      'user_name' => $username,
      'users' => $users,
      'stats' => $stats,
      'current_sector' => '',
      'current_sub_sector' => '',
      'selected_year' => $selected_year,
      'year_options' => $year_options,
      'back_url' => base_url('umoor')
    ];

    $this->load->view('Umoor/Header', $data);
    $this->load->view('MasoolMusaid/AsharaOhbat', $data);
  }

  public function ashara_attendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 4) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Use GET parameters if available
    $sel_sector = $this->input->get('sector');
    $sel_sub = $this->input->get('subsector');

    // Hijri Year selection (UI scope only; attendance table not year-scoped) — default to next year if month >= 10
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts_att = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts_att[2];
    $current_hijri_month_att = (int)$hijri_parts_att[1];
    $default_year_att = ($current_hijri_month_att >= 10) ? ($current_hijri_year + 1) : $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year_att);
    $year_options = $this->HijriCalendar->get_distinct_hijri_years();
    $year_options = is_array($year_options) ? array_map('intval', $year_options) : [];
    if (empty($year_options)) {
      $year_options = [$current_hijri_year - 1, $current_hijri_year, $current_hijri_year + 1];
    }
    if (!in_array($selected_year, $year_options, true)) {
      array_unshift($year_options, $selected_year);
    }

    // Fetch all sectors and sub-sectors for dropdowns
    $all_sectors = $this->MasoolMusaidM->get_all_sectors();
    $all_sub_sectors = $sel_sector ? $this->MasoolMusaidM->get_all_sub_sectors($sel_sector) : [];

    // Fetch attendance data
    if ($this->input->post('search')) {
      $kw = $this->input->post('search', true);
      $users = $this->MasoolMusaidM->search_attendance_by_sector($kw, $sel_sector, $sel_sub, $selected_year);
    } else {
      $users = $this->MasoolMusaidM->get_attendance_by_sector($sel_sector, $sel_sub, $selected_year);
    }

    // Stats
    $stats = $this->MasoolMusaidM->get_sector_stats($sel_sector, $sel_sub, $selected_year);

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
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load view
    $this->load->view('Umoor/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }
}
