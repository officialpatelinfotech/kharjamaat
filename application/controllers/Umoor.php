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

    // Block write/edit access for UmoorFMB (role 12) and UmoorDakheliyah (role 9)
    if (!empty($_SESSION['user']) && in_array((int)$_SESSION['user']['role'], [9, 12], true)) {
      $method = strtolower($this->router->fetch_method());
      $write_methods = [
        'create_menu', 'add_menu', 'edit_menu', 'add_menu_item', 
        'insert_item_type', 'insert_menu_item', 'edit_menu_item', 
        'delete_menu_item'
      ];
      if (in_array($method, $write_methods, true)) {
        show_error('Edit access is disabled for this login.');
      }
      if ((int)$_SESSION['user']['role'] === 9 && $method === 'qardanhasana') {
        show_error('Edit access is disabled for this login.');
      }
    }
  }
  public function index()
  {
    if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }

    $role = (int)$_SESSION['user']['role'];
    $data['user_name'] = $_SESSION['user']['username'];

    if ($role === 4 || $role === 11 || $role === 12 || $role === 9 || $role === 5) {
      // Early JSON endpoints: handle AJAX requests before rendering any views
      $fmt = $this->input->get('format');
      if ($fmt === 'json') {
        // Miqaat RSVP counts/lists
        $miqaat_rsvp = $this->input->get('miqaat_rsvp');
        if ($miqaat_rsvp) {
          $miqaat_id = $this->input->get('miqaat_id');
          $m = $miqaat_id ? $this->CommonM->get_next_miqaat_rsvp_stats((int)$miqaat_id)
            : $this->CommonM->get_next_miqaat_rsvp_stats();
          $payload = ['success' => true, 'miqaat_rsvp' => $m];
          return $this->output->set_content_type('application/json')->set_output(json_encode($payload));
        }

        // Previous miqaat before a given date
        $miqaat_prev = $this->input->get('miqaat_prev');
        if ($miqaat_prev) {
          $before_date = $this->input->get('before_date');
          $payload = ['success' => false, 'miqaat' => null];
          if ($before_date) {
            $row = $this->db->query(
              "SELECT id, name, type, date, assigned_to FROM miqaat WHERE date < ? ORDER BY date DESC LIMIT 1",
              [$before_date]
            )->row_array();
            if ($row) {
              $hparts = $this->HijriCalendar->get_hijri_parts_by_greg_date($row['date']);
              if ($hparts && isset($hparts['hijri_day'])) {
                $row['hijri_label'] = trim((($hparts['hijri_day'] ?? '')) . ' ' . (($hparts['hijri_month_name'] ?? $hparts['hijri_month'] ?? '')) . ' ' . (($hparts['hijri_year'] ?? '')));
              } else {
                $row['hijri_label'] = '';
              }
              $row['hijri_parts'] = $hparts;
              $payload = ['success' => true, 'miqaat' => $row];
            }
          }
          return $this->output->set_content_type('application/json')->set_output(json_encode($payload));
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'error' => 'Unknown request']));
      }

      // Demographic & overall stats (non-financial)
      $sectorsData = $this->AmilsahebM->get_resident_sector_stats();
      $subSectorsData = $this->AmilsahebM->get_all_sub_sector_stats();
      $residentOverview = $this->AmilsahebM->get_resident_overview_counts(true);

      // Calculate Ashara Ohbat counts for default year
      $today_date = date('Y-m-d');
      $h_cal = $this->HijriCalendar->get_hijri_date($today_date);
      $h_parts = explode('-', $h_cal['hijri_date']);
      $curr_hijri_year = (int)$h_parts[2];
      $curr_hijri_month = (int)$h_parts[1];
      $def_year = $curr_hijri_year;

      $ohbat_counts = [];
      if ($role === 4 || $role === 9) {
        $ashara_users = $this->AmilsahebM->get_all_ashara($def_year);
        $possibleStatuses = [
          'Will attend all 9 Days',
          'Not answering calls or messages',
          "Musaaid didn't Contacted Yet",
          'Will attend few Days only',
          'Will not attend any Day',
          'Ashara with Maula tus'
        ];
        foreach ($possibleStatuses as $st) {
          $ohbat_counts[$st] = 0;
        }
        foreach ($ashara_users as $u) {
          $st = (!empty($u['LeaveStatus']) && $u['LeaveStatus'] !== 'Unknown') ? $u['LeaveStatus'] : "Musaaid didn't Contacted Yet";
          if (in_array(strtolower(trim($st)), ['bed ridden', 'not in town', 'married outcaste', 'wafaat'])) {
            continue;
          }
          if (isset($ohbat_counts[$st])) {
            $ohbat_counts[$st]++;
          }
        }
      }

      $stats = [
        'HOF' => (int)($residentOverview['hof'] ?? 0),
        'FM' => (int)($residentOverview['fm'] ?? 0),
        'Mardo' => (int)($residentOverview['male'] ?? 0),
        'Bairo' => (int)($residentOverview['female'] ?? 0),
        'Age_0_4' => (int)($residentOverview['age_0_4'] ?? 0),
        'Age_5_15' => (int)($residentOverview['age_5_15'] ?? 0),
        'Age_16_25' => (int)($residentOverview['age_16_25'] ?? 0),
        'Age_26_65' => (int)($residentOverview['age_26_65'] ?? 0),
        'Buzurgo' => (int)($residentOverview['seniors'] ?? 0),
        'LeaveStatus' => [],
        'Sectors' => $sectorsData,
        'SubSectors' => $subSectorsData,
        'deeni_eligible' => $this->AmilsahebM->get_deeni_eligible_count(),
        'deeni_taking' => $this->AmilsahebM->get_deeni_taking_count(),
        'madresa_deprived' => $this->AmilsahebM->get_madresa_deprived_count(),
        'singles_21_40' => $this->AmilsahebM->get_singles_21_40_count(),
        'status_counts' => $this->AmilsahebM->get_status_counts(),
        'active_inactive' => $this->AmilsahebM->get_active_inactive_counts(),
        'ashara_ohbat_counts' => $ohbat_counts,
      ];

      $data['stats'] = $stats;
      $data['current_sector'] = '';
      $data['current_sub_sector'] = '';
      $data['marital_status_counts'] = $this->AmilsahebM->get_marital_status_distribution();
      $data['year_daytype_stats'] = $this->CommonM->get_year_calendar_daytypes();

      // Raza Summary
      $data['raza_summary'] = $this->get_raza_summary($data['user_name']);

      // Madresa Hijri Year
      $data['dashboard_madresa_hijri_year'] = isset($data['year_daytype_stats']['hijri_year']) ? (int)$data['year_daytype_stats']['hijri_year'] : null;

      // Upcoming miqaats enriched
      $upcoming_miqaats = $this->get_upcoming_miqaats(5);
      if (!empty($upcoming_miqaats)) {
        foreach ($upcoming_miqaats as &$um) {
          $um_date = isset($um['date']) ? $um['date'] : null;
          $hparts = null;
          if ($um_date) {
            $hparts = $this->HijriCalendar->get_hijri_parts_by_greg_date($um_date);
          }
          if ($hparts && isset($hparts['hijri_day'])) {
            $um['hijri_label'] = trim(($hparts['hijri_day'] ?? '') . ' ' . ($hparts['hijri_month_name'] ?? $hparts['hijri_month'] ?? '') . ' ' . ($hparts['hijri_year'] ?? ''));
          } else {
            $um['hijri_label'] = '';
          }
          $um['hijri_parts'] = $hparts;
        }
        unset($um);
      }

      $data['dashboard_data'] = [
        'upcoming_miqaats' => $upcoming_miqaats,
        'miqaat_rsvp' => $this->CommonM->get_next_miqaat_rsvp_stats(),
        'raza_summary' => $data['raza_summary'],
      ];

      if ($role === 11) {
        $this->load->model('QardanHasanaM');
        $qh_moh = (float)$this->QardanHasanaM->get_scheme_total_amount('mohammedi');
        $qh_tah = (float)$this->QardanHasanaM->get_scheme_total_amount('taher');
        $qh_hus = (float)$this->QardanHasanaM->get_scheme_total_amount('husain');
        $data['qh_all_schemes_totals'] = [
          'mohammedi' => $qh_moh,
          'taher' => $qh_tah,
          'husain' => $qh_hus,
          'total' => $qh_moh + $qh_tah + $qh_hus,
        ];
      } elseif ($role === 12) {
        $today = date('Y-m-d');
        $hijri_today = $this->HijriCalendar->get_hijri_date($today);
        $year = null;
        if (!empty($hijri_today) && !empty($hijri_today['hijri_date'])) {
          $parts = explode('-', (string) $hijri_today['hijri_date']);
          $hmon = isset($parts[1]) ? (int) ltrim((string) $parts[1], '0') : 0;
          $hyr = isset($parts[2]) ? (int) $parts[2] : 0;
          if ($hmon >= 1 && $hmon <= 12 && $hyr > 0) {
            $base_start = ($hmon >= 7) ? $hyr : ($hyr - 1);
            if ($hmon >= 4 && $hmon <= 6) {
              $year = $base_start + 1;
            } else if ($hmon >= 1 && $hmon <= 3) {
              $year = $base_start - 1;
            } else {
              $year = $base_start;
            }
          }
        }
        if (empty($year)) {
          $year = 1446;
        }
        $data['fmb_summary'] = $this->CommonM->get_fmb_takhmeen_reports($year);
        $data['selected_fmb_year'] = $year;
      } elseif ($role === 9) {
        $today = date('Y-m-d');
        $hijri_today = $this->HijriCalendar->get_hijri_date($today);
        $hijri_year = null;
        if ($hijri_today && isset($hijri_today['hijri_date'])) {
          $parts = explode('-', $hijri_today['hijri_date']);
          $hijri_year = isset($parts[2]) ? (int)$parts[2] : null;
        }
        if (!$hijri_year) {
          $hijri_year = 1446;
        }
        $sel_hijri_year = $this->input->get('hijri_year') ? trim($this->input->get('hijri_year')) : $hijri_year;
        $sel_hijri_month = $this->input->get('hijri_month') ? trim($this->input->get('hijri_month')) : null;
        $data['dashboard_data'] = $this->get_dashboard_summary_data($sel_hijri_month, $sel_hijri_year);
      }
 
      $this->load->view('Umoor/Header', $data);
      if ($role === 11) {
        $this->load->view('Umoor/Home_Iqtesadiyah', $data);
      } elseif ($role === 12) {
        $this->load->view('Umoor/Home_Fmb', $data);
      } elseif ($role === 9) {
        $this->load->view('Umoor/Home_Dakheliyah', $data);
      } elseif ($role === 5) {
        $this->load->view('Umoor/Home_Talimiyah', $data);
      } else {
        $this->load->view('Umoor/Home_Deeniyah', $data);
      }
    } else {
      $this->load->view('Umoor/Header', $data);
      $this->load->view('Umoor/Home');
    }
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
    if (empty($_SESSION['user']) || !in_array((int)$_SESSION['user']['role'], [4, 9], true)) {
      redirect('/accounts');
    }



    $username = $_SESSION['user']['username'];

    // Year selection (Hijri) — default to next year if current Hijri month >= 10
    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts[2];
    $current_hijri_month = (int)$hijri_parts[1];
    $default_year = $current_hijri_year;
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
    if (empty($_SESSION['user']) || !in_array((int)$_SESSION['user']['role'], [4, 9], true)) {
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
    $default_year_att = $current_hijri_year;
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
        'Not attended anywhere'
      ],
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load view
    $this->load->view('Umoor/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }

  public function ashara_attendance_list()
  {
    if (empty($_SESSION['user']) || !in_array((int)$_SESSION['user']['role'], [4, 9], true)) {
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
    $default_year_att = $current_hijri_year;
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
        'Not attended anywhere'
      ],
      // Year dropdown support (UI only)
      'selected_year' => $selected_year,
      'year_options' => $year_options,
    ];

    // Load view
    $this->load->view('Umoor/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendanceList', $data);
  }
  public function mumineendirectory()
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }

    $get = $this->input->get();
    if (!empty($get)) {
      $data['users'] = $this->AmilsahebM->get_users_filtered($get);
    } elseif ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      $data['users'] = $this->AmilsahebM->get_users_filtered(['name' => $keyword]);
    } else {
      $data['users'] = $this->AmilsahebM->get_all_users();
    }
    $data['all_users'] = $this->AmilsahebM->get_all_users();

    $today = date('Y-m-d');
    $h = $this->HijriCalendar->get_hijri_date($today);
    $hijri_parts = explode('-', $h['hijri_date']);
    $current_hijri_year = (int)$hijri_parts[2];
    $current_hijri_month = (int)$hijri_parts[1];
    $default_year = $current_hijri_year;
    $selected_year = (int)($this->input->get('year') ?: $default_year);

    $ohbat_rows = $this->db->where('year', $selected_year)->get('ashara_ohbat')->result_array();
    $ohbat_map = [];
    foreach ($ohbat_rows as $row) {
      $ohbat_map[$row['ITS']] = $row['LeaveStatus'];
    }

    if (isset($data['users']) && is_array($data['users'])) {
      foreach ($data['users'] as &$u) {
        $its = is_object($u)
          ? (isset($u->ITS_ID) ? $u->ITS_ID : (isset($u->ITS) ? $u->ITS : null))
          : (isset($u['ITS_ID']) ? $u['ITS_ID'] : (isset($u['ITS']) ? $u['ITS'] : null));
        $val = isset($ohbat_map[$its]) && !empty($ohbat_map[$its]) ? $ohbat_map[$its] : "Musaaid didn't Contacted Yet";
        if (is_object($u)) $u->LeaveStatus = $val;
        else $u['LeaveStatus'] = $val;
      }
      unset($u);
    }

    if (isset($data['all_users']) && is_array($data['all_users'])) {
      foreach ($data['all_users'] as &$u) {
        $its = is_object($u)
          ? (isset($u->ITS_ID) ? $u->ITS_ID : (isset($u->ITS) ? $u->ITS : null))
          : (isset($u['ITS_ID']) ? $u['ITS_ID'] : (isset($u['ITS']) ? $u['ITS'] : null));
        $val = isset($ohbat_map[$its]) && !empty($ohbat_map[$its]) ? $ohbat_map[$its] : "Musaaid didn't Contacted Yet";
        if (is_object($u)) $u->LeaveStatus = $val;
        else $u['LeaveStatus'] = $val;
      }
      unset($u);
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $data['is_umoor'] = true;

    $this->load->view('Umoor/Header', $data);
    $this->load->view('MasoolMusaid/Mumineendirectory', $data);
  }

  private function get_raza_summary($umoor = null)
  {
    if ($umoor) {
      $row = $this->db->query(
        "SELECT 
            SUM(CASE WHEN r.status = 1 THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN r.status = 2 THEN 1 ELSE 0 END) AS approved,
            SUM(CASE WHEN r.status = 3 THEN 1 ELSE 0 END) AS rejected
          FROM raza r
          JOIN raza_type rt ON rt.id = r.razaType
          WHERE r.active = 1 AND rt.umoor = ?",
        [$umoor]
      )->row_array();
    } else {
      $row = $this->db->query(
        "SELECT 
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS approved,
            SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) AS rejected
          FROM raza
          WHERE active = 1"
      )->row_array();
    }
    return [
      'pending' => (int)($row['pending'] ?? 0),
      'approved' => (int)($row['approved'] ?? 0),
      'rejected' => (int)($row['rejected'] ?? 0)
    ];
  }

  private function get_upcoming_miqaats($limit = 5)
  {
    $limit = (int)$limit;
    $sql = "SELECT m.id, m.name, m.type, m.date, m.assigned_to
        FROM miqaat m
        WHERE m.date >= CURDATE()
        ORDER BY m.date ASC
        LIMIT $limit";
    return $this->db->query($sql)->result_array();
  }

  public function qardanhasana($scheme = null, $action = null, $id = null)
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }

    $data = [];
    $data['user_name'] = $_SESSION['user']['username'] ?? '';
    $data['qh_prefix'] = 'umoor';
    $data['can_manage'] = false;
    $data['can_import'] = false;

    $scheme = $scheme !== null ? strtolower(trim((string)$scheme)) : null;
    $action = $action !== null ? strtolower(trim((string)$action)) : null;

    $this->load->view('Umoor/Header', $data);

    $this->load->model('QardanHasanaM');
    $qh_moh = (float)$this->QardanHasanaM->get_scheme_total_amount('mohammedi');
    $qh_tah = (float)$this->QardanHasanaM->get_scheme_total_amount('taher');
    $qh_hus = (float)$this->QardanHasanaM->get_scheme_total_amount('husain');
    $data['qh_scheme_totals'] = [
      'mohammedi' => $qh_moh,
      'taher' => $qh_tah,
      'husain' => $qh_hus,
    ];
    $data['qh_total_all'] = $qh_moh + $qh_tah + $qh_hus;

    if ($scheme === null || $scheme === '') {
      $this->load->view('Admin/QardanHasana', $data);
      return;
    }

    if (!in_array($scheme, ['mohammedi', 'taher', 'husain'], true)) {
      redirect('umoor/qardanhasana');
      return;
    }

    $titles = [
      'mohammedi' => 'Mohammedi Scheme',
      'taher' => 'Taher Scheme',
      'husain' => 'Husain Scheme'
    ];
    $data['scheme'] = $scheme;
    $data['scheme_key'] = $scheme;
    $data['scheme_title'] = $titles[$scheme] ?? ucfirst($scheme) . ' Scheme';

    // Delete disabled (read-only)
    if ($action === 'delete') {
      $this->session->set_flashdata('qh_import_error', 'Delete is not allowed.');
      redirect('umoor/qardanhasana/' . $scheme);
      return;
    }

    // Update disabled (read-only)
    if ($action === 'update') {
      $this->session->set_flashdata('qh_import_error', 'Update is not allowed.');
      redirect('umoor/qardanhasana/' . $scheme);
      return;
    }

    // Import CSV disabled
    if ($action === 'import') {
      $this->session->set_flashdata('qh_import_error', 'Import is not allowed.');
      redirect('umoor/qardanhasana/' . $scheme);
      return;
    }

    // Filters (GET) - keep same shape as Admin/Anjuman for shared view
    $data['filters'] = [
      'miqaat_id' => $this->input->get('miqaat_id'),
      'hijri_date' => $this->input->get('hijri_date'),
      'greg_date' => $this->input->get('greg_date'),
      'deposit_date' => $this->input->get('deposit_date'),
      'maturity_date' => $this->input->get('maturity_date'),
      'duration' => $this->input->get('duration'),
      'its' => $this->input->get('its'),
      'member_name' => $this->input->get('member_name')
    ];

    // Miqaat list for filters
    $data['miqaats'] = $this->db
      ->select('m.id, m.name, m.date, hc.hijri_date')
      ->from('miqaat m')
      ->join('hijri_calendar hc', 'hc.greg_date = m.date', 'left')
      ->order_by('m.date', 'DESC')
      ->get()->result_array();

    // Import messages
    $data['qh_import_message'] = $this->session->flashdata('qh_import_message');
    $data['qh_import_error'] = $this->session->flashdata('qh_import_error');

    // Resolve miqaat_id -> miqaat_name for filtering scheme table (which stores miqaat_name as text)
    $miqaatNameFilter = '';
    $miqaatId = isset($data['filters']['miqaat_id']) ? trim((string)$data['filters']['miqaat_id']) : '';
    if ($miqaatId !== '') {
      $row = $this->db->select('name')->from('miqaat')->where('id', (int)$miqaatId)->get()->row_array();
      $miqaatNameFilter = isset($row['name']) ? (string)$row['name'] : '';
    }

    if ($scheme === 'mohammedi') {
      $data['records'] = $this->QardanHasanaM->get_mohammedi_records([
        'miqaat_name' => $miqaatNameFilter,
        'hijri_date' => isset($data['filters']['hijri_date']) ? trim((string)$data['filters']['hijri_date']) : '',
        'eng_date' => isset($data['filters']['greg_date']) ? trim((string)$data['filters']['greg_date']) : ''
      ]);
    } elseif ($scheme === 'taher') {
      $data['records'] = $this->QardanHasanaM->get_taher_records([
        'miqaat_name' => $miqaatNameFilter,
        'ITS' => isset($data['filters']['its']) ? trim((string)$data['filters']['its']) : '',
        'member_name' => isset($data['filters']['member_name']) ? trim((string)$data['filters']['member_name']) : ''
      ]);
    } elseif ($scheme === 'husain') {
      $depositDate = isset($data['filters']['deposit_date']) ? trim((string)$data['filters']['deposit_date']) : '';
      if ($depositDate === '') {
        $depositDate = isset($data['filters']['greg_date']) ? trim((string)$data['filters']['greg_date']) : '';
      }
      $maturityDate = isset($data['filters']['maturity_date']) ? trim((string)$data['filters']['maturity_date']) : '';
      $duration = isset($data['filters']['duration']) ? trim((string)$data['filters']['duration']) : '';
      $data['records'] = $this->QardanHasanaM->get_husain_records([
        'deposit_date' => $depositDate,
        'maturity_date' => $maturityDate,
        'duration' => $duration,
        'ITS' => isset($data['filters']['its']) ? trim((string)$data['filters']['its']) : '',
        'member_name' => isset($data['filters']['member_name']) ? trim((string)$data['filters']['member_name']) : ''
      ]);
    }

    // Total amount for header display (sum of fetched records)
    $total = 0.0;
    if (!empty($data['records']) && is_array($data['records'])) {
      foreach ($data['records'] as $row) {
        if ($scheme === 'husain') {
          $total += (float)($row['amount'] ?? 0);
        } else {
          if (isset($row['collection_amount'])) {
            $total += (float)$row['collection_amount'];
          } else {
            $unit = (float)($row['unit'] ?? 0);
            $units = (int)($row['units'] ?? 0);
            if ($unit > 0 && $units > 0) {
              $total += $unit * $units;
            }
          }
        }
      }
    }
    $data['total_amount'] = $total;

    $this->load->view('Admin/QardanHasanaScheme', $data);
  }

  public function viewmember($its_id = null)
  {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role'] < 4 || $_SESSION['user']['role'] > 15)) {
      redirect('/accounts');
    }
    if (!$its_id) {
      redirect('Umoor/mumineendirectory');
      return;
    }
    $member = $this->AdminM->get_member_by_its($its_id);
    if (!$member) {
      redirect('Umoor/mumineendirectory');
      return;
    }
    $data['user_name']         = $_SESSION['user']['username'];
    $data['member']            = $member;
    $data['family_members']    = $this->AdminM->get_family_members($its_id);
    $data['family_financials'] = $this->AdminM->get_family_financial_data($its_id, array_column($data['family_members'], 'ITS_ID'));
    $this->load->view('Umoor/Header', $data);
    $this->load->view('Admin/ViewMember', $data);
  }

  private function get_dashboard_summary_data($sel_hijri_month = null, $sel_hijri_year = null)
  {
    $raza_summary = $this->get_raza_summary($this->session->userdata('user')['username']);
    $upcoming_miqaats = $this->get_upcoming_miqaats(5);
    if (!empty($upcoming_miqaats)) {
      foreach ($upcoming_miqaats as &$um) {
        $um_date = isset($um['date']) ? $um['date'] : null;
        $hparts = null;
        if ($um_date) {
          $hparts = $this->HijriCalendar->get_hijri_parts_by_greg_date($um_date);
        }
        if ($hparts && isset($hparts['hijri_day'])) {
          $um['hijri_label'] = trim(($hparts['hijri_day'] ?? '') . ' ' . ($hparts['hijri_month_name'] ?? $hparts['hijri_month'] ?? '') . ' ' . ($hparts['hijri_year'] ?? ''));
        } else {
          $um['hijri_label'] = '';
        }
        $um['hijri_parts'] = $hparts;
      }
      unset($um);
    }

    $this_week_sector_signup_avg = $this->get_this_week_sector_signup_avg();
    $no_thaali_families = $this->get_no_thaali_families_this_week();

    $month_signed_up = 0;
    $no_thaali_month_list = [];
    if ($sel_hijri_month && $sel_hijri_year) {
      $mstats = $this->CommonM->get_monthly_thaali_stats($sel_hijri_month, $sel_hijri_year);
      $month_signed_up = (int)($mstats['signed_up_count'] ?? 0);
      $no_thaali_month_list = $mstats['no_thaali_list'] ?? [];
    }

    return [
      'raza_summary' => $raza_summary,
      'upcoming_miqaats' => $upcoming_miqaats,
      'miqaat_rsvp' => $this->CommonM->get_next_miqaat_rsvp_stats(),
      'this_week_sector_signup_avg' => $this_week_sector_signup_avg,
      'no_thaali_families' => $no_thaali_families,
      'this_month_signed_up' => $month_signed_up,
      'no_thaali_families_month' => $no_thaali_month_list,
      'fmb_takhmeen_sector' => [],
      'sabeel_takhmeen_sector' => [],
      'wajebaat_summary' => ['count' => 0, 'total' => 0, 'received' => 0, 'due' => 0]
    ];
  }

  private function get_this_week_sector_signup_avg()
  {
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));
    $start = $monday;
    $end = $sunday;
    $dates = [];
    $cursor = strtotime($start);
    $endTs = strtotime($end);
    while ($cursor <= $endTs) {
      $dates[] = date('Y-m-d', $cursor);
      $cursor = strtotime('+1 day', $cursor);
    }
    $days = count($dates);
    if ($days <= 0) {
      return ['start' => $start, 'end' => $end, 'days' => 0, 'sectors' => []];
    }
    $agg = [];
    $rangeRows = $this->CommonM->getsignupcount_by_sector_range($start, $end);
    foreach ($rangeRows as $r) {
      $sector = isset($r['Sector']) ? trim($r['Sector']) : '';
      if ($sector === '' || strtolower($sector) === 'unassigned') continue;
      $cnt = (int)($r['hof_signup_count'] ?? 0);
      if (!isset($agg[$sector])) $agg[$sector] = 0;
      $agg[$sector] += $cnt;
    }
    $sectors = [];
    foreach ($agg as $sector => $total) {
      $sectors[] = [
        'sector' => $sector,
        'total' => (int)$total,
        'avg' => round($total / $days, 2),
      ];
    }
    usort($sectors, function ($a, $b) {
      if ($a['avg'] == $b['avg']) return strcmp($a['sector'], $b['sector']);
      return ($a['avg'] < $b['avg']) ? 1 : -1;
    });
    return [
      'start' => $start,
      'end' => $end,
      'days' => $days,
      'sectors' => $sectors,
    ];
  }

  private function get_no_thaali_families_this_week()
  {
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));
    $start = $monday;
    $end = $sunday;

    $dates = [];
    $cursor = strtotime($start);
    $endTs = strtotime($end);
    while ($cursor <= $endTs) {
      $dates[] = date('Y-m-d', $cursor);
      $cursor = strtotime('+1 day', $cursor);
    }

    $signedHofs = $this->CommonM->get_signed_up_hofs_range($start, $end);
    $allHofs = $this->CommonM->get_all_users();
    $no = [];
    foreach ($allHofs as $h) {
      $its = isset($h['ITS_ID']) ? $h['ITS_ID'] : null;
      if (!$its) continue;
      if (!isset($signedHofs[$its])) {
        $no[] = $h;
      }
    }
    return $no;
  }
}
