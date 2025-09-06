<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Common extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('CommonM');
    $this->load->model('HijriCalendar');
  }

  private function validateUser($user)
  {
    if (empty($user) || ($user['role'] != 1 && $user['role'] != 3 && $user['role'] != 2)) {
      redirect('/accounts');
    }
  }

  private function set_fmbcddt_session($date, $day_type)
  {
    $_SESSION["date"] = $date;
    $_SESSION["day_type"] = $day_type;
    $_SESSION["hijri_date"] = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($date)))["hijri_date"];
  }

  public function fmbcalendar()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $from = $this->input->get('from');
    $_SESSION["from"] = $from;
    $data["from"] = $from;
    $data['active_controller'] = $from ? base_url($from) : base_url();

    // Handle Month/Year filter
    $hijri_month_id = $this->input->post('hijri_month');
    $sort_type = $this->input->post('sort_type');
    $data['hijri_months'] = $this->HijriCalendar->get_hijri_month();
    if (empty($hijri_month_id)) {
      $hijri_month_id = -1;
    }

    $data['hijri_month_id'] = $hijri_month_id;
    $data['sort_type'] = $sort_type;
    // Fetch calendar data for table (assignments now included in model)
    $calendar = $this->CommonM->get_full_calendar($hijri_month_id, $sort_type);
    // After fetching $calendar, update each entry to add hijri month name to the date
    foreach ($calendar as &$entry) {
      if (!empty($entry['hijri_date'])) {
        $hijri_parts = explode('-', $entry['hijri_date']);
        $hijri_month_id = isset($hijri_parts[1]) ? $hijri_parts[1] : '';
        $hijri_month_name = '';
        if ($hijri_month_id) {
          $month_info = $this->HijriCalendar->hijri_month_name($hijri_month_id);
          $hijri_month_name = isset($month_info['hijri_month']) ? $month_info['hijri_month'] : '';
        }
        $entry['hijri_date_with_month'] = explode('-', $entry['hijri_date'])[0] . ' ' . $hijri_month_name . ' ' . explode('-', $entry['hijri_date'])[2];
      } else {
        $entry['hijri_date_with_month'] = '';
      }
    }
    $data['calendar'] = $calendar;

    $first_day_of_calendar = $calendar[0]["greg_date"];
    $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($first_day_of_calendar)));
    $data['hijri_year'] = explode('-', $hijri_date['hijri_date'])[2];

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/FMBCalendar', $data);
  }

  // public function update_day()
  // {
  //   $this->validateUser($_SESSION['user']);

  //   $greg_date = $this->input->post("greg_date");
  //   $day_type = $this->input->post("day_type");
  //   $this->set_fmbcddt_session($greg_date, $day_type);

  //   switch ($day_type) {
  //     case 'Thaali':
  //       redirect("common/createmenu");
  //       break;
  //     case 'Miqaat':
  //       redirect("common/createmiqaat");
  //       break;
  //     case 'Both':
  //       redirect("common/createmenu");
  //       break;
  //     case 'Holiday':
  //       $result = $this->CommonM->update_day($greg_date, $day_type);
  //       if ($result) {
  //         redirect("common/fmbcalendar");
  //       }
  //       break;

  //     default:
  //       break;
  //   }
  // }
  public function get_hijri_day_month($greg_date)
  {
    $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($greg_date)));
    $hijri_month_name = $this->HijriCalendar->hijri_month_name($hijri_date["hijri_month_id"]);
    return explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month_name["hijri_month"] . " " . explode("-", $hijri_date["hijri_date"])[2];
  }

  public function fmbthaalimenu()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    // Get current hijri year
    $today = date('Y-m-d');
    $hijri_today = $this->HijriCalendar->get_hijri_date($today);
    $hijri_year = explode('-', $hijri_today['hijri_date'])[2];
    $filter_data = [
      'hijri_month_id' => -1, // -1 means full year
      'hijri_year' => $hijri_year
    ];
    $data['menu'] = $this->CommonM->get_month_wise_menu($filter_data);
    foreach ($data["menu"] as $key => $value) {
      $data['menu'][$key]['hijri_date'] = $this->get_hijri_day_month($value["date"], $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"]);
    }

    $from = $this->input->get('from');
    $_SESSION["from"] = $from;
    $data['active_controller'] = $from ? base_url($from) : base_url();

    $data["hijri_months"] = $this->HijriCalendar->get_hijri_month();
    $data["hijri_year"] = explode(" ", $data['menu'][0]['hijri_date'])[3];

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/FMBThaaliMenu.php', $data);
  }

  // Create Menu
  public function createmenu()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data["menu_dates"] = $this->CommonM->get_menu_dates();

    $data['menu_items'] = $this->CommonM->get_menu_items();
    $data['edit_mode'] = false;
    $data['date'] = $this->input->get('date') ? date("d-m-Y", strtotime($this->input->get('date'))) : '';

    $from = $_SESSION["from"];
    $data['active_controller'] = $from ? base_url($from) : base_url();

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMenu', $data);
  }

  public function add_menu()
  {
    $this->validateUser($_SESSION['user']);
    $edit_mode = $this->input->post('edit_mode') == 'true' ? true : false;
    $menu_date = $this->input->post('menu_date');

    $selected_item_ids = json_decode($this->input->post('selected_item_ids'), true);

    if ($edit_mode) {
      $menu_id = $this->input->post('menu_id');
      if ($menu_id) {
        $menu_date ? $this->CommonM->update_menu($menu_id, $menu_date) : 0;
        if (!empty($selected_item_ids)) {
          $this->CommonM->remove_items_from_menu($menu_id);
          $this->CommonM->add_items_to_menu($menu_id, $selected_item_ids);
          $this->session->set_flashdata('success', 'Menu created successfully!');
        }
      } else {
        $this->session->set_flashdata('error', 'Failed to create menu.');
      }
    } else {
      if (!empty($menu_date) && !empty($selected_item_ids)) {
        $menu_id = $this->CommonM->insert_menu($menu_date);
        if ($menu_id) {
          $result = $this->CommonM->add_items_to_menu($menu_id, $selected_item_ids);
          $this->session->set_flashdata('success', 'Menu created successfully!');
        } else {
          $this->session->set_flashdata('error', 'Failed to create menu.');
        }
      } else {
        $this->session->set_flashdata('error', 'Please provide a name and select at least one item.');
      }
    }

    // if ($_SESSION["day_type"] == "Thaali") {
    //   $session_date = $_SESSION["date"];
    //   $session_day_type = $_SESSION["day_type"];
    //   $result = $this->CommonM->update_day(
    //     $session_date,
    //     $session_day_type
    //   );
    //   if ($result) {
    //     $this->session->unset_userdata(
    //       "date",
    //       "day_type"
    //     );
    //     redirect('Common/fmbcalendar');
    //   }
    // } else {
    //   redirect("Common/createmiqaat");
    // }
    redirect("common/fmbthaalimenu");
  }

  public function delete_menu()
  {
    $this->validateUser($_SESSION["user"]);
    if ($this->input->post('menu_id')) {
      $menu_id = $this->input->post('menu_id');
      $this->CommonM->delete_menu($menu_id);
      $this->session->set_flashdata('success', 'Menu deleted successfully!');
    } else {
      $this->session->set_flashdata('error', 'No Menu ID provided.');
    }
    redirect('common/fmbthaalimenu');
  }

  public function verify_menu_date()
  {
    $menu_date = $this->input->post('menu_date');

    if ($this->CommonM->validate_menu_date($menu_date)) {
      echo json_encode(['status' => 'available', 'hijri_date' => date("d-m-Y", strtotime($this->HijriCalendar->get_hijri_date($menu_date)["hijri_date"]))]);
    } else {
      echo json_encode(['status' => 'exists']);
    }
  }

  public function insert_menu()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $menu_name = $this->input->post('menu_name');
    $data['menu_name'] = $menu_name;

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMenu', $data);
  }

  public function search_items()
  {
    $keyword = $this->input->post('keyword');
    $items = $this->CommonM->search_items($keyword);
    echo json_encode($items);
  }

  public function edit_menu($id = null)
  {
    $this->validateUser($_SESSION['user']);

    if ($this->input->get('id')) {
      $id = $this->input->get('id');
    }
    $from = $this->input->get('from');
    if ($from) {
      $data["from"] = $from;
    }

    $data['edit_mode'] = true;

    $menu = $this->CommonM->get_menu_by_id($id);

    if ($menu) {
      $data['menu'] = $menu;
      $data["mapped_menu_items"]['items'] = $this->CommonM->get_menu_items_by_menu_id($menu['id']);
    } else {
      $data['menu'] = [];
    }

    $data["menu_dates"] = $this->CommonM->get_menu_dates();
    $data['user_name'] = $_SESSION['user']['username'];

    $data['active_controller'] = $_SESSION["from"];

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMenu', $data);
  }

  public function filter_menu()
  {
    $this->validateUser($_SESSION['user']);
    $hijri_month_id = $this->input->post('hijri_month');
    $today_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"];
    $today_hijri_year = explode("-", $today_hijri_date)[2];
    if ($hijri_month_id == -1) {
      $this_hijri_year = $today_hijri_year;
    } else if ($hijri_month_id == -2) {
      $this_hijri_year = $today_hijri_year + 1;
    } else if ($hijri_month_id == -3) {
      $this_hijri_year = $today_hijri_year - 1;
    } else {
      $this_hijri_year = $today_hijri_year;
    }

    $fitler_data = [
      'hijri_month_id' => $this->input->post('hijri_month'),
      'hijri_year' => $this_hijri_year,

      // 'start_date' => $this->input->post('start_date'),
      // 'end_date' => $this->input->post('end_date'),
      // 'sort_type' => $this->input->post('sort_type')
    ];

    $data['menu'] = $this->CommonM->get_month_wise_menu($fitler_data);

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
    $data['active_controller'] = $_SESSION["from"] ? base_url($_SESSION["from"]) : base_url();
    $data["hijri_year"] = explode(" ", $data['menu'][0]['hijri_date'])[3];

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/FMBThaaliMenu', $data);
  }

  // public function duplicate_last_month_menu()
  // {
  //   if (!empty($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
  //     redirect('/accounts');
  //   }

  //   $result = $this->CommonM->duplicate_last_month_menu();

  //   if ($result) {
  //     $this->session->set_flashdata('success', 'Menu duplicated successfully.');
  //   } else {
  //     $this->session->set_flashdata('error', 'No records found for last month or duplication failed.');
  //   }

  //   redirect('Common/fmbthaalimenu'); // Adjust this to your actual menu listing route
  // }


  // Menu Item Management
  public function add_menu_item()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];
    $data['menu_items'] = $this->CommonM->get_menu_items();
    $data['item_types'] = $this->CommonM->get_item_types();

    $data["active_controller"] = $_SESSION["from"];

    $data["from"] = $this->input->get('from');

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/AddMenuItem', $data);
  }

  public function insert_item_type()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $type_name = $this->input->post('type_name');

    $data = array(
      'type_name' => $type_name,
    );

    $check = $this->CommonM->insert_item_type($data);
    if ($check) {
      redirect('/Common/add_menu_item');
    } else {
      redirect('/Common/add_menu_item');
    }
  }

  public function insert_menu_item()
  {
    $this->validateUser($_SESSION['user']);
    $data['user_name'] = $_SESSION['user']['username'];

    $item_name = $this->input->post('item_name');
    $item_type = $this->input->post('item_type');

    $from = $this->input->get('from');

    $data = array(
      'name' => $item_name,
      'type' => $item_type,
    );

    $check = $this->CommonM->insert_menu_item($data);
    if ($check) {
      redirect('/common/add_menu_item?from=' . $from);
    } else {
      redirect('/common/add_menu_item?from=' . $from);
    }

    redirect('common/fmbthaalimenu');
  }

  public function edit_menu_item()
  {
    $this->validateUser($_SESSION['user']);

    $data = [
      'id' => $this->input->post('id'),
      'name' => $this->input->post('name'),
      'type' => $this->input->post('type')
    ];

    $result = $this->CommonM->edit_menu_item($data);

    echo json_encode(['status' => $result ? 'success' : 'failed']);
  }

  public function delete_menu_item()
  {
    $this->validateUser($_SESSION['user']);

    $id = $this->input->post('id');

    $result = $this->CommonM->delete_menu_item($id);

    echo json_encode(['status' => 'success']);
  }

  public function filter_menu_item()
  {
    $this->validateUser($_SESSION['user']);

    $fitler_data = [
      'filter_type' => $this->input->post('filter-type'),
      'search_item' => $this->input->post('search-item'),
      'sort_type' => $this->input->post('sort-type')
    ];

    $data["active_controller"] = $_SESSION["from"];
    $data['user_name'] = $_SESSION['user']['username'];
    $data['filter_type'] = $this->input->post('filter-type');
    $data['search_item'] = $this->input->post('search-item');
    $data['sort_type'] = $this->input->post('sort-type');
    $data['item_types'] = $this->CommonM->get_item_types();
    $data['menu_items'] = $this->CommonM->filter_menu_item($fitler_data);
    $data['from'] = $this->input->get('from');
    $this->load->view('Common/Header', $data);
    $this->load->view('Common/AddMenuItem', $data);
  }
  // Create Menu

  // Create Miqaat
  public  function managemiqaat()
  {
    $this->validateUser($_SESSION["user"]);
    $from = $this->input->get('from');
    $_SESSION["from"] = $from;
    $data["active_controller"] = $from ? base_url($from) : base_url();
    $data['user_name'] = $_SESSION['user']['username'];

    // Get filter options
    $hijri_month_id = $this->input->post('hijri_month');
    $sort_type = $this->input->post('sort_type');
    $today_hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d"))["hijri_date"];
    $today_hijri_year = explode("-", $today_hijri_date)[2];
    if (empty($hijri_month_id) || $hijri_month_id == '') {
      $hijri_month_id = -1;
    }
    if ($hijri_month_id == -1) {
      $this_hijri_year = $today_hijri_year;
    } else if ($hijri_month_id == -2) {
      $this_hijri_year = $today_hijri_year + 1;
    } else if ($hijri_month_id == -3) {
      $this_hijri_year = $today_hijri_year - 1;
    } else {
      $this_hijri_year = $today_hijri_year;
    }

    $filter_data = [
      'hijri_month_id' => $hijri_month_id,
      'hijri_year' => $this_hijri_year,
      'sort_type' => $sort_type
    ];

    $data['miqaats'] = $this->CommonM->get_miqaats_with_assignments($filter_data);
    $data["hijri_months"] = $this->HijriCalendar->get_hijri_month();
    $data['hijri_month_id'] = $hijri_month_id;
    $data['sort_type'] = $sort_type;


    foreach ($data["miqaats"] as &$entry) {
      if (!empty($entry['hijri_date'])) {
        $hijri_parts = explode('-', $entry['hijri_date']);
        $hijri_month_id = isset($hijri_parts[1]) ? $hijri_parts[1] : '';
        $hijri_month_name = '';
        if ($hijri_month_id) {
          $month_info = $this->HijriCalendar->hijri_month_name($hijri_month_id);
          $hijri_month_name = isset($month_info['hijri_month']) ? $month_info['hijri_month'] : '';
        }
        $entry['hijri_date_with_month'] = explode('-', $entry['hijri_date'])[0] . ' ' . $hijri_month_name . ' ' . explode('-', $entry['hijri_date'])[2];
      } else {
        $entry['hijri_date_with_month'] = '';
      }

      foreach ($entry["miqaats"] as $key => $value) {
        $assignment_count = count($value["assignments"]);
        $miqaat_invoice_status = $this->CommonM->get_miqaat_invoice_status($value["id"]);
        if ($miqaat_invoice_status) {
          $entry["miqaats"][$key]["invoice_status"] = $miqaat_invoice_status >= $assignment_count ? "Generated" : "Partially Generated";
        } else {
          $entry["miqaats"][$key]["invoice_status"] = "Not Generated";
        }
      }
    }

    $first_day_of_calendar = $data["miqaats"][0]["date"];
    $hijri_date = $this->HijriCalendar->get_hijri_date(date("Y-m-d", strtotime($first_day_of_calendar)));
    $data['hijri_year'] = explode('-', $hijri_date['hijri_date'])[2];

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/ManageMiqaat', $data);
  }

  public function createmiqaat()
  {
    $this->validateUser($_SESSION["user"]);

    $data["active_controller"] = $_SESSION["from"];
    $data['user_name'] = $_SESSION['user']['username'];

    $date = $this->input->get('date');
    $data['date'] = $date ? date('Y-m-d', strtotime($date)) : '';
    $data['edit_mode'] = false;

    $data['miqaats'] = $this->CommonM->get_miqaats_with_assignments();

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMiqaat', $data);
  }

  // AJAX endpoint to fetch hijri date for a given gregorian date
  public function get_hijri_date_ajax()
  {
    $greg_date = $this->input->post('greg_date');
    $hijri_date = '';
    if (!empty($greg_date)) {
      $result = $this->CommonM->get_hijri_date_by_greg_date($greg_date);
      if ($result && isset($result['hijri_date'])) {
        $hijri_date_with_month_name = explode("-", $result['hijri_date']);
        $hijri_date = $hijri_date_with_month_name[0] . " " . $result["hijri_month_name"] . " " . $hijri_date_with_month_name[2];
      }
    }
    echo json_encode(['status' => "success", 'hijri_date' => $hijri_date]);
    exit;
  }

  public function edit_miqaat()
  {
    $this->validateUser($_SESSION["user"]);

    $from = $this->input->get('from');
    if ($from) {
      $data["from"] = $from;
    }

    $data["active_controller"] = $_SESSION["from"];
    $data['user_name'] = $_SESSION['user']['username'];

    $miqaat_id = $this->input->get('id');
    $data['miqaat_id'] = $miqaat_id;

    $miqaat = null;
    if ($miqaat_id) {
      $miqaat = $this->CommonM->get_miqaat_by_id($miqaat_id);
      $data['date'] = isset($miqaat['date']) ? $miqaat['date'] : '';
      // Prepare assignment data for pre-filling
      $individual_ids = [];
      $group_leader_id = '';
      $group_member_ids = [];
      $group_name = '';
      if (isset($miqaat['assignments'])) {
        foreach ($miqaat['assignments'] as $assignment) {
          if ($assignment['assign_type'] === 'Individual') {
            $individual_ids[] = $assignment['member_id'];
          } elseif ($assignment['assign_type'] === 'Group') {
            $group_leader_id = $assignment['group_leader_id'];
            $group_name = $assignment['group_name'];
            if (!empty($assignment['members'])) {
              foreach ($assignment['members'] as $member) {
                $group_member_ids[] = $member['id'];
              }
            }
          }
        }
      }
      $data['individual_ids'] = implode(',', $individual_ids);
      $data['group_leader_id'] = $group_leader_id;
      $data['group_member_ids'] = implode(',', $group_member_ids);
      $data['group_name'] = $group_name;
    } else {
      $data['date'] = '';
    }
    $data['miqaat'] = $miqaat;
    $data['edit_mode'] = true;

    $data['miqaats'] = $this->CommonM->get_miqaats_with_assignments();

    $this->load->view('Common/Header', $data);
    $this->load->view('Common/CreateMiqaat', $data);
  }

  public function search_users()
  {
    $term = $this->input->get('term');

    $this->db->select('ITS_ID, Full_Name');
    $this->db->from('user');

    if (!empty($term)) {
      $this->db->like('Full_Name', $term);
    }

    $query = $this->db->get()->result_array();

    $results = [];
    foreach ($query as $row) {
      $results[] = [
        'id'   => $row['ITS_ID'],
        'name' => $row['Full_Name']
      ];
    }

    echo json_encode($results);
  }

  public function add_miqaat()
  {
    if ($this->input->post()) {
      $name = $this->input->post('name');
      $miqaat_type = $this->input->post('miqaat_type');
      $date = $this->input->post('date');
      $assign_type = $this->input->post('assign_to');

      $miqaat_id = $this->CommonM->insert_miqaat([
        'name' => $name,
        "type" => $miqaat_type,
        "assigned_to" => $assign_type,
        'date' => date("Y-m-d", strtotime($date)),
        'status' => $assign_type == "Fala ni Niyaz" ? 1 : 0,
      ]);

      if ($assign_type == 'Individual') {
        $ids = explode(",", $this->input->post('individual_ids'));
        foreach ($ids as $id) {
          $this->CommonM->insert_assignment([
            'miqaat_id' => $miqaat_id,
            'assign_type' => 'individual',
            'member_id' => $id
          ]);
        }
      } elseif ($assign_type == 'Group') {
        $group_name = $this->input->post('group_name');
        $leader_id = $this->input->post('group_leader_id');
        $members = explode(",", $this->input->post('group_member_ids'));

        foreach ($members as $member_id) {
          $this->CommonM->insert_assignment([
            'miqaat_id' => $miqaat_id,
            'assign_type' => 'group',
            'group_name' => $group_name,
            'group_leader_id' => $leader_id,
            'member_id' => $member_id
          ]);
        }
      } elseif ($assign_type == 'Fala ni Niyaz') {
        $fmb_users = $this->CommonM->get_umoor_fmb_users();
        foreach ($fmb_users as $user) {
          $assignment_status = $this->CommonM->insert_assignment([
            'miqaat_id' => $miqaat_id,
            'assign_type' => 'Fala ni Niyaz',
            'member_id' => $user->user_id
          ]);

          if ($assignment_status) {
            $this->CommonM->insert_raza([
              'user_id' => $user->user_id,
              'razaType' => 2,
              'razaData' => '{"miqaat_id": "' . $miqaat_id . '"}',
              'miqaat_id' => $miqaat_id,
              'status' => 0,
              'Janab-status' => 0,
              'coordinator-status' => 0,
              'active' => 1,
            ]);
          }
          break;
        }
      }

      $this->session->set_flashdata('success', 'Miqaat created successfully!');
      redirect('common/managemiqaat');
    } else {
      $this->load->view('common/managemiqaat');
    }
  }

  public function update_miqaat()
  {
    $this->validateUser($_SESSION["user"]);
    if ($this->input->post()) {
      $miqaat_id = $this->input->post('miqaat_id');
      $name = $this->input->post('name');
      $miqaat_type = $this->input->post('miqaat_type');
      $date = $this->input->post('date');
      $status = $this->input->post('status');
      $miqaat_data = [
        'name' => $name,
        'type' => $miqaat_type,
        'date' => date("Y-m-d", strtotime($date)),
      ];
      // Only update assignments if assignment fields are present and enabled
      if ($this->input->post('assign_to')) {
        $assign_type = $this->input->post('assign_to');
        $miqaat_data['assigned_to'] = $assign_type;

        $this->CommonM->update_miqaat_by_id($miqaat_id, $miqaat_data);
        $this->CommonM->remove_assignments_from_miqaat($miqaat_id);
        if ($assign_type == 'Individual') {
          $miqaat_data['status'] = 0;
          $ids = explode(",", $this->input->post('individual_ids'));

          $this->CommonM->delete_raza_by_miqaat_id($miqaat_id, $ids);

          foreach ($ids as $id) {
            $this->CommonM->insert_assignment([
              'miqaat_id' => $miqaat_id,
              'assign_type' => 'Individual',
              'member_id' => $id
            ]);
          }
        } elseif ($assign_type == 'Group') {
          $miqaat_data['status'] = 0;
          $group_name = $this->input->post('group_name');
          $leader_id = $this->input->post('group_leader_id');
          $members = explode(",", $this->input->post('group_member_ids'));

          $this->CommonM->delete_raza_by_miqaat_id($miqaat_id, [$leader_id]);

          foreach ($members as $member_id) {
            $this->CommonM->insert_assignment([
              'miqaat_id' => $miqaat_id,
              'assign_type' => 'Group',
              'group_name' => $group_name,
              'group_leader_id' => $leader_id,
              'member_id' => $member_id
            ]);
          }
        } elseif ($assign_type == 'Fala ni Niyaz') {
          $miqaat_data['status'] = 1;
          $fmb_users = $this->CommonM->get_umoor_fmb_users();
          foreach ($fmb_users as $user) {
            $assignment_status = $this->CommonM->insert_assignment([
              'miqaat_id' => $miqaat_id,
              'assign_type' => 'Fala ni Niyaz',
              'member_id' => $user->user_id
            ]);

            if ($assignment_status) {
              $result = $this->CommonM->delete_raza_by_miqaat_and_user($miqaat_id);
              if ($result) {
                $this->CommonM->insert_raza([
                  'user_id' => $user->user_id,
                  'razaType' => 2,
                  'razaData' => '{"miqaat_id": "' . $miqaat_id . '"}',
                  'miqaat_id' => $miqaat_id,
                  'status' => 0,
                  'Janab-status' => 0,
                  'coordinator-status' => 0,
                  'active' => 1,
                ]);
              }
            }
            break;
          }
        }
      } else {
        // Only update miqaat details, leave assignments untouched
        $this->CommonM->update_miqaat_by_id($miqaat_id, $miqaat_data);
      }
      $this->session->set_flashdata('success', 'Miqaat updated successfully!');
      redirect('common/managemiqaat');
    } else {
      $this->session->set_flashdata('error', 'No data received for update.');
      redirect('common/managemiqaat');
    }
  }

  public function delete_miqaat()
  {
    $this->validateUser($_SESSION["user"]);
    if ($this->input->post('miqaat_id')) {
      $miqaat_id = $this->input->post('miqaat_id');
      $result = $this->CommonM->delete_miqaat($miqaat_id);
      if ($result) {
        $this->session->set_flashdata('success', 'Miqaat deleted successfully!');
      } else {
        $this->session->set_flashdata('error', 'Cannot delete Miqaat with approved Raza. It can only be cancelled.');
      }
    } else {
      $this->session->set_flashdata('error', 'No Miqaat ID provided.');
    }
    redirect('common/managemiqaat');
  }
  // Create Miqaat

  public function cancel_miqaat()
  {
    $miqaat_id = $this->input->post('miqaat_id');
    if ($miqaat_id) {
      $this->load->model('CommonM');
      $this->CommonM->set_miqaat_status($miqaat_id, 2); // Set miqaat inactive
      $this->CommonM->set_raza_status_by_miqaat($miqaat_id, 0); // Set all related razas inactive
      $this->session->set_flashdata('success', 'Miqaat and related razas cancelled.');
    } else {
      $this->session->set_flashdata('error', 'Invalid Miqaat.');
    }
    redirect('common/managemiqaat');
  }

  public function activate_miqaat()
  {
    $miqaat_id = $this->input->post('miqaat_id');
    if ($miqaat_id) {
      $this->load->model('CommonM');
      $this->CommonM->set_miqaat_status($miqaat_id, 1); // Set miqaat active
      $this->session->set_flashdata('success', 'Miqaat activated.');
    } else {
      $this->session->set_flashdata('error', 'Invalid Miqaat.');
    }
    redirect('common/managemiqaat');
  }
}
