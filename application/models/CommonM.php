<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class CommonM extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  public function get_miqaat_by_id_and_date($id, $date)
  {
    $this->db->select('*');
    $this->db->from('miqaat');
    $this->db->where('id', $id);
    $this->db->where('date', $date);
    $query = $this->db->get();
    return $query->row_array();
  }

  /**
   * Get a single miqaat by date
   */
  public function get_miqaat_by_date($date)
  {
    $this->db->select('*');
    $this->db->from('miqaat');
    $this->db->where('date', $date);
    $query = $this->db->get();
    return $query->row_array();
  }

  /**
   * Update a miqaat by date
   */
  public function update_miqaat($date, $data)
  {
    $this->db->where('date', $date);
    return $this->db->update('miqaat', $data);
  }

  public function get_full_calendar($hijri_month_id = null, $sort_type = 'asc')
  {
    // Get hijri year and month filter
    $today = date('Y-m-d');
    $CI = &get_instance();
    $CI->load->model('HijriCalendar');
    $hijri_today = $CI->HijriCalendar->get_hijri_date($today);
    $hijri_year = explode('-', $hijri_today['hijri_date'])[2];

    // Filter by month/year if provided
    $CI->db->select('greg_date, hijri_date, hijri_month_id');
    $CI->db->from('hijri_calendar');
    if ($hijri_month_id) {
      if ($hijri_month_id == -3) {
        // Last year
        $filter_year = $hijri_year - 1;
        $CI->db->like('hijri_date', "-$filter_year");
      } elseif ($hijri_month_id == -2) {
        // Next year
        $filter_year = $hijri_year + 1;
        $CI->db->like('hijri_date', "-$filter_year");
      } elseif ($hijri_month_id == -1) {
        // This year
        $CI->db->like('hijri_date', "-$hijri_year");
      } else {
        // Specific month
        $CI->db->where('hijri_month_id', $hijri_month_id);
        $CI->db->like('hijri_date', "-$hijri_year");
      }
    } else {
      $CI->db->like('hijri_date', "-$hijri_year");
    }
    $CI->db->order_by('greg_date', $sort_type == 'desc' ? 'DESC' : 'ASC');
    $rows = $CI->db->get()->result_array();
    $dates = [];
    $greg_to_hijri = [];
    foreach ($rows as $row) {
      $greg_to_hijri[$row['greg_date']] = $row['hijri_date'];
      $dates[] = $row['greg_date'];
    }

    // Fetch all miqaat, menu, and assignments for the hijri year
    $this->db->select('m.id as miqaat_id, m.date as miqaat_date, m.name as miqaat_name, m.type as miqaat_type, m.assigned_to, u.Full_Name as contact');
    $this->db->from('miqaat m');
    $this->db->join('user u', 'u.ITS_ID = m.assigned_to', 'left');
    if (!empty($dates)) {
      $this->db->where_in('m.date', $dates);
    }
    $miqaats = $this->db->get()->result_array();
    $miqaat_map = [];
    foreach ($miqaats as $m) {
      $miqaat_map[$m['miqaat_date']] = $m;
    }

    $this->db->select('menu.date as menu_date, menu.id as menu_id');
    $this->db->from('menu');
    if (!empty($dates)) {
      $this->db->where_in('menu.date', $dates);
    }
    $menus = $this->db->get()->result_array();
    $menu_map = [];
    foreach ($menus as $menu) {
      $menu_map[$menu['menu_date']] = $menu['menu_id'];
    }

    // Fetch all menu items for the hijri year
    $this->db->select('menu_items_map.menu_id, menu_item.name as menu_item_name');
    $this->db->from('menu_items_map');
    $this->db->join('menu_item', 'menu_item.id = menu_items_map.item_id');
    $items = $this->db->get()->result_array();
    $items_map = [];
    foreach ($items as $item) {
      $items_map[$item['menu_id']][] = $item['menu_item_name'];
    }

    // Build calendar array for each date, flattening multiple miqaats/menu items
    $calendar = [];
    $CI->load->model('HijriCalendar');
    $month_name_cache = [];
    foreach ($dates as $date) {
      $hijri_date = isset($greg_to_hijri[$date]) ? $greg_to_hijri[$date] : '';
      $hijri_parts = explode('-', $hijri_date);
      $hijri_month = isset($hijri_parts[1]) ? $hijri_parts[1] : '';
      $hijri_month_name = '';
      if ($hijri_month) {
        if (!isset($month_name_cache[$hijri_month])) {
          $month_info = $CI->HijriCalendar->hijri_month_name($hijri_month);
          $month_name_cache[$hijri_month] = isset($month_info['hijri_month']) ? $month_info['hijri_month'] : '';
        }
        $hijri_month_name = $month_name_cache[$hijri_month];
      }
      // Get all menu entries for this date
      $menus_for_date = array_filter($menus, function ($menu) use ($date) {
        return $menu['menu_date'] == $date;
      });
      // Get all miqaats for this date
      $miqaats_for_date = array_filter($miqaats, function ($m) use ($date) {
        return $m['miqaat_date'] == $date;
      });

      // Add a row for each miqaat
      foreach ($miqaats_for_date as $miqaat) {
        $miqaat_id = isset($miqaat['miqaat_id']) ? $miqaat['miqaat_id'] : '';
        $assignments = [];
        if ($miqaat_id) {
          $miqaat_details = $this->get_miqaat_by_id($miqaat_id);
          $assignments = isset($miqaat_details['assignments']) ? $miqaat_details['assignments'] : [];
        }
        $calendar[] = [
          'greg_date' => $date,
          'hijri_date' => $hijri_date,
          'hijri_month_name' => $hijri_month_name,
          'day_type' => '',
          'miqaat_id' => $miqaat_id,
          'miqaat_name' => isset($miqaat['miqaat_name']) ? $miqaat['miqaat_name'] : '',
          'miqaat_type' => isset($miqaat['miqaat_type']) ? $miqaat['miqaat_type'] : '',
          'assigned_to' => isset($miqaat['assigned_to']) ? $miqaat['assigned_to'] : '',
          'menu_items' => [],
          'contact' => isset($miqaat['contact']) ? $miqaat['contact'] : '',
          'assignments' => $assignments,
        ];
      }

      // Add a row for each menu (thaali) for the date, always in a separate row
      foreach ($menus_for_date as $menu) {
        $menu_items = isset($items_map[$menu['menu_id']]) ? $items_map[$menu['menu_id']] : [];
        $calendar[] = [
          'greg_date' => $date,
          'hijri_date' => $hijri_date,
          'hijri_month_name' => $hijri_month_name,
          'day_type' => '',
          'menu_id' => isset($menu['menu_id']) ? $menu['menu_id'] : '',
          'miqaat_name' => 'Thaali Menu',
          'miqaat_type' => 'Thaali',
          'assigned_to' => '',
          'menu_items' => $menu_items,
          'contact' => '',
        ];
      }

      // If no miqaat and no menu, still add a blank row for the date
      if (empty($miqaats_for_date) && empty($menus_for_date)) {
        $calendar[] = [
          'greg_date' => $date,
          'hijri_date' => $hijri_date,
          'hijri_month_name' => $hijri_month_name,
          'day_type' => '',
          'miqaat_name' => '',
          'miqaat_type' => '',
          'assigned_to' => '',
          'menu_items' => [],
          'contact' => '',
        ];
      }
    }
    return $calendar;
  }

  function update_day($date, $day_type)
  {
    $date = date("Y-m-d", strtotime($date));
    $result = $this->db->insert("fmb_calendar_days", ["date" => $date, "day_type" => $day_type]);
    if ($result) {
      return true;
    }
    return false;
  }

  public function get_menu()
  {
    $currentYear = date('Y');
    $currentMonth = date('m');

    $this->db->from('menu');
    $this->db->where('YEAR(date)', $currentYear);
    $this->db->where('MONTH(date)', $currentMonth);
    $this->db->order_by('date', 'ASC');

    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_menu_dates()
  {
    $this->db->select("date");
    $this->db->from('menu');
    $this->db->order_by('date', 'ASC');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_month_wise_menu($data = [])
  {
    $startOfMonth = "";
    $endOfMonth = "";

    $today = date('Y-m-d');

    $this->db->select('hijri_date, hijri_month_id');
    $this->db->from('hijri_calendar');
    $this->db->where('greg_date', $today);
    $query = $this->db->get();
    $row = $query->row();

    if (!$row) {
      return [];
    }

    if (isset($data) && !empty($data)) {
      $hijri_month_id = $data["hijri_month_id"];
      $hijri_year = $data["hijri_year"];
    } else {
      $hijri_month_id = $row->hijri_month_id;
      $hijri_year = explode("-", $row->hijri_date)[2];
    }

    $this->db->from('hijri_calendar');
    if ($hijri_month_id > 0) {
      $this->db->where('hijri_month_id', $hijri_month_id);
      $this->db->like('hijri_date', $hijri_year);
    } else {
      $this->db->like('hijri_date', $hijri_year);
    }
    $this->db->order_by('greg_date', 'ASC');
    $result = $this->db->get();
    if ($result->num_rows() > 0) {
      $month_data = $result->result_array();
    } else {
      return false;
    }

    $first_hijri_day = $month_data[0]["greg_date"];
    $last_hijri_day = $month_data[count($month_data) - 1]["greg_date"];

    $startOfMonth = date('Y-m-d', strtotime($first_hijri_day));
    $endOfMonth = date('Y-m-d', strtotime($last_hijri_day));

    // Get all menu entries for the month
    $sql = "SELECT 
                m.*, 
                mim.item_id, 
                mi.name AS item_name
            FROM menu m 
            LEFT JOIN menu_items_map mim ON mim.menu_id = m.id 
            LEFT JOIN menu_item mi ON mi.id = mim.item_id 
            WHERE m.date BETWEEN ? AND ? 
            ORDER BY m.date ASC";

    $query = $this->db->query($sql, array($startOfMonth, $endOfMonth));
    $results = $query->result_array();

    // Group menu items by date
    $menu_by_date = [];
    foreach ($results as $row) {
      $date = date('Y-m-d', strtotime($row['date']));
      if (!isset($menu_by_date[$date])) {
        $menu_by_date[$date] = [
          'id' => $row['id'],
          'date' => $date,
          'items' => [],
        ];
      }
      if (!empty($row['item_id'])) {
        $menu_by_date[$date]['items'][] = $row['item_name'];
      }
    }

    // Loop through each day in the month
    $output = [];
    $period = new DatePeriod(
      new DateTime($startOfMonth),
      new DateInterval('P1D'),
      (new DateTime($endOfMonth))->modify('+1 day')
    );

    foreach ($period as $dt) {
      $date_str = $dt->format('Y-m-d');
      if (isset($menu_by_date[$date_str])) {
        $output[] = $menu_by_date[$date_str];
      } else {
        $output[] = [
          'id' => null,
          'date' => $date_str,
          'items' => [],
        ];
      }
    }
    return $output;
  }

  public function get_menu_by_id($id)
  {
    $this->db->select('*');
    $this->db->from('menu');
    $this->db->where('id', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return $query->row_array();
    } else {
      return null;
    }
  }

  public function get_menu_items_by_menu_id($menu_id)
  {
    $this->db->select('mi.*');
    $this->db->from('menu_items_map mim');
    $this->db->join('menu_item mi', 'mim.item_id = mi.id');
    $this->db->where('mim.menu_id', $menu_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  function search_items($keyword)
  {
    $this->db->like('name', $keyword);
    $query = $this->db->get('menu_item');
    return $query->result_array();
  }

  public function validate_menu_date($menu_date)
  {
    $this->db->select('id');
    $this->db->from('menu');
    $this->db->where('date', date('Y-m-d H:i:s', strtotime($menu_date)));
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return false;
    } else {
      return true;
    }
  }

  public function delete_menu($id)
  {
    // Remove related menu items first
    $this->db->where('menu_id', $id);
    $this->db->delete('menu_items_map');
    // Now delete the menu itself
    $this->db->where('id', $id);
    $this->db->delete('menu');
    return $this->db->affected_rows() > 0;
  }

  public function insert_menu($menu_date)
  {
    $data = [
      "date" => date('Y-m-d H:i:s', strtotime($menu_date)),
      'created_at' => date('Y-m-d H:i:s'),
    ];
    $this->db->insert('menu', $data);
    return $this->db->insert_id();
  }

  public function update_menu($menu_id, $menu_date)
  {
    $data = [
      "date" => date('Y-m-d H:i:s', strtotime($menu_date))
    ];
    $this->db->where('id', $menu_id);
    $this->db->update('menu', $data);
    return $this->db->affected_rows() > 0;
  }

  function filter_menu($data)
  {
    $this->db->select('*');
    $this->db->from('menu');

    $start_date = !empty($data['start_date']) ? date('Y-m-d', strtotime($data['start_date'])) : null;
    $end_date = !empty($data['end_date']) ? date('Y-m-d', strtotime($data['end_date'])) : null;

    if (!empty($data['start_date']) && !empty($data['end_date'])) {
      $this->db->where('date >=', $start_date);
      $this->db->where('date <=', $end_date);
    }

    if (!empty($data['sort_type'])) {
      if ($data['sort_type'] === 'asc') {
        $this->db->order_by('date', 'ASC');
      } else {
        $this->db->order_by('date', 'DESC');
      }
    }

    $query = $this->db->get();
    return $query->result_array();
  }

  public function remove_items_from_menu($menu_id)
  {
    $this->db->where('menu_id', $menu_id);
    $this->db->delete('menu_items_map');
    return $this->db->affected_rows();
  }

  public function add_items_to_menu($menu_id, $item_ids = [])
  {
    $insert_data = [];
    foreach ($item_ids as $item_id) {
      $insert_data[] = [
        'menu_id' => $menu_id,
        'item_id' => $item_id
      ];
    }

    return $this->db->insert_batch('menu_items_map', $insert_data);
  }

  // public function duplicate_last_month_menu()
  // {
  //   $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
  //   $currentMonth = date('Y-m');
  //   // Fetch last month's menu
  //   $this->db->select('*');
  //   $this->db->from('menu');
  //   $this->db->order_by('id', 'ASC');  // Assuming 'id' is the primary key
  //   $this->db->limit(31);
  //   $query = $this->db->get();
  //   $menus = $query->result_array();

  //   if (empty($menus)) {
  //     return false;
  //   }

  //   for ($day = 1; $day <= $daysInMonth; $day++) {
  //     $newDate = $currentMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);

  //     if (date('w', strtotime($newDate)) == 0) {
  //       continue;
  //     }

  //     if (isset($menus[$day])) :
  //       $menu = $menus[$day];

  //       $last_month_menu_id = $menu['id'];
  //       unset($menu['id']);
  //       $menu['date'] = $newDate;

  //       $this->db->insert('menu', $menu);
  //       $newMenuId = $this->db->insert_id();

  //       // Get menu items for this menu
  //       $items = $this->db
  //         ->where('menu_id', $last_month_menu_id)
  //         ->get('menu_items_map')
  //         ->result_array();

  //       foreach ($items as $item) {
  //         $newItem = $item;
  //         unset($newItem['id']);
  //         $newItem['menu_id'] = $newMenuId;

  //         // Insert new menu item
  //         $this->db->insert('menu_items_map', $newItem);
  //       }
  //     endif;
  //   }

  //   return true;
  // }

  function get_menu_items()
  {
    $sql = "SELECT * FROM `menu_item` WHERE status = 1 ORDER BY `id` DESC";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  function get_item_types()
  {
    $sql = "SELECT * FROM `item_type` ORDER BY `type_name`";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  function insert_item_type($data)
  {
    if (!empty($data)) {
      $this->db->insert('item_type', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function insert_menu_item($data)
  {
    if (!empty($data)) {
      $this->db->insert('menu_item', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function edit_menu_item($data)
  {
    if (!empty($data)) {
      $id = $data['id'];

      $updateData = array(
        'name' => $data['name'],
        'type' => $data['type'],
      );
      $this->db->where('id', $id);
      $this->db->update('menu_item', $updateData);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function delete_menu_item($id)
  {
    if (!empty($id)) {
      $this->db->where('id', $id);
      $this->db->delete('menu_item');
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  function filter_menu_item($data)
  {
    $this->db->select('*');
    $this->db->from('menu_item');
    $this->db->where('status', 1);

    if (!empty($data['filter_type'])) {
      $this->db->where('type', $data['filter_type']);
    }

    if (!empty($data['search_item'])) {
      $this->db->like('name', $data['search_item']);
    }

    if (!empty($data['sort_type'])) {
      if ($data['sort_type'] === 'asc') {
        $this->db->order_by('name', 'ASC');
      } else {
        $this->db->order_by('name', 'DESC');
      }
    }

    $query = $this->db->get();
    return $query->result_array();
  }

  // Miqaat management

  /**
   * Get all hijri dates for the current hijri month and all miqaat assignments for those dates
   * Accepts filter_data array with hijri_month_id, hijri_year, sort_type
   */
  public function get_miqaats_with_assignments($filter_data = [])
  {
    // Step 1: Get hijri month id and year for the given filter or today
    $hijri_month_id = isset($filter_data['hijri_month_id']) ? $filter_data['hijri_month_id'] : null;
    $hijri_year = isset($filter_data['hijri_year']) ? $filter_data['hijri_year'] : null;
    $sort_type = isset($filter_data['sort_type']) ? $filter_data['sort_type'] : 'asc';

    if (!$hijri_month_id || !$hijri_year) {
      $today = date('Y-m-d');
      $this->db->select('hijri_month_id, hijri_date');
      $this->db->from('hijri_calendar');
      $this->db->where('greg_date', $today);
      $row = $this->db->get()->row();
      if (!$row) return [];
      $hijri_month_id = $row->hijri_month_id;
      $hijri_year = explode('-', $row->hijri_date)[2];
    }

    // Step 2: Get all hijri dates for this month
    $this->db->select('greg_date, hijri_date');
    $this->db->from('hijri_calendar');
    if ($hijri_month_id > 0) {
      $this->db->where('hijri_month_id', $hijri_month_id);
      $this->db->like('hijri_date', $hijri_year);
    } else {
      $this->db->like('hijri_date', $hijri_year);
    }
    $this->db->order_by('greg_date', $sort_type == 'desc' ? 'DESC' : 'ASC');
    $hijri_dates = $this->db->get()->result_array();

    // Step 3: Get all miqaat assignments for these dates
    $greg_dates = array_column($hijri_dates, 'greg_date');
    if (empty($greg_dates)) return [];
    $this->db->select('
        m.id,
        m.name as name,
        m.type,
        m.date,
        m.assigned_to as assigned_to,
        a.assign_type,
        a.group_name,
        a.group_leader_id,
        leader.full_name as group_leader_name,
        member.ITS_ID as member_id,
        member.full_name as member_name
    ');
    $this->db->from('miqaat m');
    $this->db->join('miqaat_assignments a', 'm.id = a.miqaat_id', 'left');
    $this->db->join('user leader', 'leader.ITS_ID = a.group_leader_id', 'left');
    $this->db->join('user member', 'member.ITS_ID = a.member_id', 'left');
    $this->db->where_in('m.date', $greg_dates);
    $this->db->order_by('m.date', $sort_type == 'desc' ? 'DESC' : 'ASC');
    $query = $this->db->get();
    $rows = $query->result_array();

    // Step 4: Restructure into nested array, indexed by greg_date
    $miqaats_by_date = [];
    foreach ($hijri_dates as $hd) {
      $miqaats_by_date[$hd['greg_date']] = [
        'date' => $hd['greg_date'],
        'hijri_date' => $hd['hijri_date'],
        'miqaats' => []
      ];
    }
    foreach ($rows as $row) {
      $date = $row['date'];
      if (!isset($miqaats_by_date[$date])) continue;
      $mid = $row['id'];
      if (!isset($miqaats_by_date[$date]['miqaats'][$mid])) {
        $miqaats_by_date[$date]['miqaats'][$mid] = [
          'id' => $row['id'],
          'name' => $row['name'],
          'type' => $row['type'],
          'date' => $row['date'],
          'assigned_to' => $row['assigned_to'],
          'assignments' => []
        ];
      }
      if ($row['assign_type'] === "Group") {
        $gid = $row['group_name'] . '_' . $row['group_leader_id'];
        if (!isset($miqaats_by_date[$date]['miqaats'][$mid]['assignments'][$gid])) {
          $miqaats_by_date[$date]['miqaats'][$mid]['assignments'][$gid] = [
            'assign_type' => $row['assign_type'],
            'group_name' => $row['group_name'],
            'group_leader_id' => $row['group_leader_id'],
            'group_leader_name' => $row['group_leader_name'],
            'members' => []
          ];
        }
        if ($row['member_id']) {
          $miqaats_by_date[$date]['miqaats'][$mid]['assignments'][$gid]['members'][] = [
            'id' => $row['member_id'],
            'name' => $row['member_name']
          ];
        }
      } elseif ($row['assign_type'] === "Individual" && $row['member_id']) {
        $miqaats_by_date[$date]['miqaats'][$mid]['assignments'][] = [
          'assign_type' => 'Individual',
          'member_id' => $row['member_id'],
          'member_name' => $row['member_name']
        ];
      }
    }
    // Step 5: Return as array of days, each with hijri_date and miqaats
    return array_values($miqaats_by_date);
  }


  function insert_miqaat($data)
  {
    $this->db->insert('miqaat', $data);
    return $this->db->insert_id();
  }

  public function insert_assignment($data)
  {
    return $this->db->insert('miqaat_assignments', $data);
  }

  public function get_umoor_fmb_users()
  {
    return $this->db->where('role_id', '1')->get('user_roles')->result();
  }

  public function get_miqaat_by_id($miqaat_id)
  {
    $this->db->select('*');
    $this->db->from('miqaat');
    $this->db->where('id', $miqaat_id);
    $query = $this->db->get();
    $miqaat = $query->row_array();

    if ($miqaat) {
  $this->db->select('a.*, u.full_name as member_name, u.mobile as member_mobile, gl.full_name as group_leader_name, gl.mobile as group_leader_mobile');
  $this->db->from('miqaat_assignments a');
  $this->db->join('user u', 'a.member_id = u.ITS_ID', 'left');
  $this->db->join('user gl', 'a.group_leader_id = gl.ITS_ID', 'left');
      $this->db->where('a.miqaat_id', $miqaat_id);
      $assignments_raw = $this->db->get()->result_array();

      $assignments = [];
      $grouped = [];
      foreach ($assignments_raw as $assignment) {
        if ($assignment['assign_type'] === 'Group') {
          $group_key = $assignment['group_name'] . '_' . $assignment['group_leader_id'];
          if (!isset($grouped[$group_key])) {
            $grouped[$group_key] = [
              'assign_type' => 'Group',
              'group_name' => $assignment['group_name'],
              'group_leader_id' => $assignment['group_leader_id'],
              'group_leader_name' => $assignment['group_leader_name'],
              'group_leader_mobile' => $assignment['group_leader_mobile'],
              'members' => []
            ];
          }
          // Add member_id, member_name, member_mobile for each row
          if (!empty($assignment['member_id'])) {
            $grouped[$group_key]['members'][] = [
              'id' => $assignment['member_id'],
              'name' => $assignment['member_name'],
              'mobile' => $assignment['member_mobile']
            ];
          }
        } elseif ($assignment['assign_type'] === 'Individual') {
          $assignments[] = [
            'assign_type' => 'Individual',
            'member_id' => $assignment['member_id'],
            'member_name' => $assignment['member_name'],
            'member_mobile' => $assignment['member_mobile']
          ];
        }
      }
      // Add grouped assignments to final array
      foreach ($grouped as $g) {
        $assignments[] = $g;
      }
      $miqaat['assignments'] = $assignments;
    }
    return $miqaat;
  }

  public function get_hijri_from_gregorian($gregorian_date)
  {
    // Query hijri_calendar table for matching gregorian date
    $this->db->select('hijri_date');
    $this->db->from('hijri_calendar');
    $this->db->where('greg_date', $gregorian_date);
    $query = $this->db->get();
    if ($row = $query->row()) {
      return $row->hijri_date;
    }
    return '';
  }
  // Miqaat management

  /**
   * Update a miqaat by id
   */
  public function update_miqaat_by_id($miqaat_id, $data)
  {
    $this->db->where('id', $miqaat_id);
    if (isset($data['status'])) {
      $data['status'] = (int)$data['status'];
    }
    return $this->db->update('miqaat', $data);
  }

  /**
   * Remove all assignments for a miqaat
   */
  public function remove_assignments_from_miqaat($miqaat_id)
  {
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->delete('miqaat_assignments');
    return $this->db->affected_rows();
  }

  /**
   * Delete a miqaat and its assignments
   */
  public function delete_miqaat($miqaat_id)
  {
    // Delete assignments first
    $this->db->where('miqaat_id', $miqaat_id);
    $this->db->delete('miqaat_assignments');
    // Delete miqaat
    $this->db->where('id', $miqaat_id);
    $this->db->delete('miqaat');
    return $this->db->affected_rows();
  }
}
