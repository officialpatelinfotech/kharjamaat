<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class UmoorM extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }
  function check_db()
  {
    $sql = " SELECT * from `login`";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function get_raza($umoor)
  {
    $sql = "SELECT * FROM `raza` 
                WHERE `active` = 1 
                AND `razaType` IN (SELECT `id` FROM `raza_type` WHERE `umoor` = '$umoor') 
                ORDER BY `time-stamp` DESC";

    $query = $this->db->query($sql);
    return $query->result_array();
  }


  function delet_raza_type($id)
  {
    $data = array(
      'active' => 0,
    );

    $this->db->where('id', $id);
    $this->db->update('raza_type', $data);
    return $this->db->affected_rows() > 0;
  }
  function add_new_razatype($razaname)
  {
    $data = array(
      'name' => $razaname,
      'fields' => '',
    );

    if (!empty($data)) {
      $this->db->insert('raza_type', $data);
      $lastId = $this->db->insert_id(); // Get the last inserted ID

      // Update the 'fields' column with the new ID
      $updatedField = '{"id":' . $lastId . ',"name":"' . $razaname . '","fields":[]}';
      $this->db->where('id', $lastId);
      $this->db->update('raza_type', array('fields' => $updatedField));

      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }


  function approve_raza($raza_id, $remark)
  {

    $remark = str_replace(array("\r", "\n"), '', $remark);
    $data = array(
      'coordinator-status' => 1,
      'status' => 1,
      'remark' => $remark
    );


    if (!empty($data)) {
      $this->db->where('id', $raza_id);
      $this->db->update('raza', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }
  function reject_raza($raza_id, $remark)
  {

    $remark = str_replace(array("\r", "\n"), '', $remark);
    $data = array(
      'coordinator-status' => 2,
      'status' => 4,
      'remark' => $remark
    );


    if (!empty($data)) {
      $this->db->where('id', $raza_id);
      $this->db->update('raza', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  public function get_user_by_raza_id($raza_id)
  {
    $this->db->select('u.*');
    $this->db->from('raza r');
    $this->db->join('user u', 'r.user_id = u.ITS_ID');
    $this->db->where('r.id', $raza_id);

    $query = $this->db->get();
    echo print_r($query);

    if ($query->num_rows() > 0) {
      return $query->row_array();
    } else {
      return null;
    }
  }
  public function get_razatype($umoor)
  {
    $sql = "SELECT * FROM `raza_type` WHERE `umoor` = '$umoor'";
    $query = $this->db->query($sql);
    return $query->result_array();
  }


  public function get_razatype_byid($id)
  {
    $sql = "SELECT * from `raza_type` where `id`= $id ";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
  public function update_raza_type($id, $raza)
  {
    $data = array(
      'fields' => $raza
    );
    if (!empty($data)) {
      $this->db->where('id', $id);
      $this->db->update('raza_type', $data);
      return $this->db->affected_rows() > 0;
    } else {
      return false;
    }
  }

  // Updated by Patel Infotech Service
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

  public function get_menu_dates() {
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
      // echo $hijri_month_id; die();
      // echo $hijri_year; die();
    } else {
      $hijri_month_id = $row->hijri_month_id;
      $hijri_year = substr($row->hijri_date, 0, 4);
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

    $grouped = [];

    foreach ($results as $row) {
      $menuId = $row['id'];

      if (!isset($grouped[$menuId])) {
        $grouped[$menuId] = [
          'id' => $row['id'],
          'date' => $row['date'],
          'items' => [],
        ];
      }

      if (!empty($row['item_id'])) {
        $grouped[$menuId]['items'][] = $row['item_name'];
      }
    }

    // Convert associative to indexed array
    return array_values($grouped);
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

  public function get_hof_count()
  {
    return $this->db->query("SELECT count(*) as hof_count FROM user u WHERE u.HOF_FM_TYPE = 'HOF' AND u.Inactive_Status IS NULL AND u.Sector IS NOT NULL")->result_array()[0];
  }

  public function get_all_users()
  {
    // return $this->db->query("SELECT * FROM user WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL AND Sector IS NOT NULL ORDER BY Sector, Sub_Sector, First_Name ASC")->result_array();
    // return $this->db->query("SELECT 
    //   u.ITS_ID,
    //   u.HOF_FM_TYPE,
    //   u.Inactive_Status,
    //   u.Sector,
    //   u.Sub_Sector,
    //   u.First_Name,
    //   u.Surname,
    //   MAX(dpm.dp_id) AS dp_id
    // FROM user u
    // LEFT JOIN delivery_person_mapping dpm
    //   ON u.ITS_ID = dpm.user_id
    // WHERE u.HOF_FM_TYPE = 'HOF'
    //   AND u.Inactive_Status IS NULL
    //   AND u.Sector IS NOT NULL
    //   AND dpm.end_date IS NULL
    // GROUP BY u.ITS_ID, u.HOF_FM_TYPE, u.Inactive_Status, u.Sector, u.Sub_Sector, u.First_Name, u.Surname
    // ORDER BY u.Sector, u.Sub_Sector, u.First_Name")->result_array();
    return $this->db->query("
    SELECT 
      u.ITS_ID,
      u.HOF_FM_TYPE,
      u.Inactive_Status,
      u.Sector,
      u.Sub_Sector,
      u.First_Name,
      u.Surname,
      MAX(dpm.dp_id) AS dp_id,
      dp.name AS delivery_person_name
    FROM user u
    LEFT JOIN delivery_person_mapping dpm
      ON u.ITS_ID = dpm.user_id
    LEFT JOIN delivery_person dp
      ON dp.id = dpm.dp_id
    WHERE u.HOF_FM_TYPE = 'HOF'
      AND u.Inactive_Status IS NULL
      AND u.Sector IS NOT NULL
      AND dpm.end_date IS NULL
    GROUP BY 
      u.ITS_ID, 
      u.HOF_FM_TYPE, 
      u.Inactive_Status, 
      u.Sector, 
      u.Sub_Sector, 
      u.First_Name, 
      u.Surname,
      dp.name
    ORDER BY 
      u.Sector, 
      u.Sub_Sector, 
      u.First_Name
    ")->result_array();
  }
  public function updatedpmapping($data)
  {
    if (empty($data["user_id"])) {
      return false;
    }
    $this->db->where("user_id", $data["user_id"]);
    $this->db->update("delivery_person_mapping", ["end_date" => date("Y-m-d")]);
    return $this->db->insert("delivery_person_mapping", $data);
  }
  public function get_all_dp()
  {
    return $this->db->query("SELECT * FROM delivery_person WHERE status = 1 ORDER BY created_at DESC")->result_array();
  }
  public function adddeliveryperson($data)
  {
    return $this->db->insert("delivery_person", $data);
  }
  public function updatedeliveryperson($data)
  {
    $name = $data["name"];
    $phone = $data["phone"];
    $status = $data["status"];

    $update_data = array(
      "name" => $name,
      "phone" => $phone,
      "status" => $status,
    );
    $this->db->where("id", $data["dp_id"]);
    return $this->db->update("delivery_person", $update_data);
  }

  // Signup count
  public function getsignupcount($greg_date)
  {
    $this->db->select('
        COUNT(DISTINCT u.ITS_ID) AS hof_signup_count,
        COUNT(DISTINCT COALESCE(do.dp_id, dpm.dp_id)) AS delivery_person_count
    ', false);

    $this->db->from('user u');
    $this->db->join('fmb_weekly_signup fmbws', 'u.ITS_ID = fmbws.user_id');
    $this->db->join(
      'delivery_overrides do',
      "u.ITS_ID = do.user_id AND do.start_date <= '$greg_date' AND do.end_date >= '$greg_date'",
      'left'
    );
    $this->db->join(
      'delivery_person_mapping dpm',
      "u.ITS_ID = dpm.user_id AND dpm.start_date <= '$greg_date' AND (dpm.end_date >= '$greg_date' OR dpm.end_date IS NULL)",
      'left'
    );

    $this->db->where('u.HOF_FM_TYPE', 'HOF');
    $this->db->where('fmbws.want_thali', 1);
    $this->db->where('fmbws.signup_date', $greg_date);

    return $this->db->get()->row_array();
  }

  // public function getsignupforaday($data)
  // {
  //   // Subquery for latest delivery override
  //   $latest_override_sql = "
  //       SELECT do1.*
  //       FROM delivery_overrides do1
  //       WHERE do1.start_date <= '" . $data["date"] . "'
  //         AND do1.end_date >= '" . $data["date"] . "'
  //         AND do1.id = (
  //             SELECT MAX(do2.id)
  //             FROM delivery_overrides do2
  //             WHERE do2.user_id = do1.user_id
  //               AND do2.start_date <= '" . $data["date"] . "'
  //               AND do2.end_date >= '" . $data["date"] . "'
  //         )
  //   ";

  //   // Subquery for latest delivery mapping
  //   $latest_mapping_sql = "
  //       SELECT dpm1.*
  //       FROM delivery_person_mapping dpm1
  //       WHERE dpm1.start_date <= '" . $data["date"] . "'
  //         AND (dpm1.end_date >= '" . $data["date"] . "' OR dpm1.end_date IS NULL)
  //         AND dpm1.id = (
  //             SELECT MAX(dpm2.id)
  //             FROM delivery_person_mapping dpm2
  //             WHERE dpm2.user_id = dpm1.user_id
  //               AND dpm2.start_date <= '" . $data["date"] . "'
  //               AND (dpm2.end_date >= '" . $data["date"] . "' OR dpm2.end_date IS NULL)
  //         )
  //   ";

  //   $this->db->select("
  //       u.ITS_ID,
  //       u.Full_Name,
  //       fmbws.thali_size,
  //       fmbws.want_thali,
  //       dp.name AS delivery_person_name,
  //       do.name AS substitute_delivery_person
  //   ", false);

  //   $this->db->from('user u');
  //   $this->db->where('u.Sector IS NOT NULL', null, false);
  //   $this->db->where('u.Inactive_Status IS NULL', null, false);

  //   // Thali signup
  //   $this->db->join(
  //     'fmb_weekly_signup fmbws',
  //     "u.ITS_ID = fmbws.user_id AND fmbws.signup_date = '" . $data["date"] . "'",
  //     'left'
  //   );

  //   // Latest override
  //   $this->db->join("($latest_override_sql) do", "u.ITS_ID = do.user_id", 'left');

  //   // Latest mapping
  //   $this->db->join("($latest_mapping_sql) dpm", "u.ITS_ID = dpm.user_id", 'left');

  //   // Delivery person name (from override if present, else mapping)
  //   // $this->db->join(
  //   //   'delivery_person dp',
  //   //   "dp.id = COALESCE(do.dp_id, dpm.dp_id)",
  //   //   'left'
  //   // );
  //   // if (!empty($data["dp_id"])) {
  //   //   $this->db->where('dp.id', $data["dp_id"]);
  //   // }

  //   $this->db->join(
  //     'delivery_person dp',
  //     'dp.id = do.dp_id',
  //     'left'
  //   );

  //   // Default Delivery Person
  //   $this->db->join(
  //     'delivery_person dp1',
  //     'dp1.id = dpm.dp_id',
  //     'left'
  //   );

  //   if (!empty($data["dp_id"])) {
  //     $this->db->group_start()
  //       ->where('dpm.dp_id', $data["dp_id"])
  //       ->or_where('do.dp_id', $data["dp_id"])
  //       ->group_end();
  //   }

  //   $this->db->where('u.HOF_FM_TYPE', 'HOF');
  //   if (isset($data["thali_taken"])) {
  //     if ($data["thali_taken"] == 1) {
  //       $this->db->where('fmbws.want_thali', '1');
  //     } else if ($data["thali_taken"] == 0) {
  //       $this->db->group_start()
  //         ->where('fmbws.want_thali !=', '1')
  //         ->or_where('fmbws.want_thali IS NULL', null, false)
  //         ->group_end();
  //     }
  //   }
  //   $this->db->order_by('u.Sector, u.Sub_Sector, u.Full_Name');

  //   return $this->db->get()->result_array();
  // }
  public function getsignupforaday($data)
  {
    // Subquery for latest delivery override
    $latest_override_sql = "
        SELECT do1.*
        FROM delivery_overrides do1
        WHERE do1.start_date <= '" . $data["date"] . "'
          AND do1.end_date >= '" . $data["date"] . "'
          AND do1.id = (
              SELECT MAX(do2.id)
              FROM delivery_overrides do2
              WHERE do2.user_id = do1.user_id
                AND do2.start_date <= '" . $data["date"] . "'
                AND do2.end_date >= '" . $data["date"] . "'
          )
    ";

    // Subquery for latest delivery mapping
    $latest_mapping_sql = "
        SELECT dpm1.*
        FROM delivery_person_mapping dpm1
        WHERE dpm1.start_date <= '" . $data["date"] . "'
          AND (dpm1.end_date >= '" . $data["date"] . "' OR dpm1.end_date IS NULL)
          AND dpm1.id = (
              SELECT MAX(dpm2.id)
              FROM delivery_person_mapping dpm2
              WHERE dpm2.user_id = dpm1.user_id
                AND dpm2.start_date <= '" . $data["date"] . "'
                AND (dpm2.end_date >= '" . $data["date"] . "' OR dpm2.end_date IS NULL)
          )
    ";

    $this->db->select("
        u.ITS_ID,
        u.Full_Name,
        fmbws.thali_size,
        fmbws.want_thali,
        dp_mapping.name AS delivery_person_name,
        dp_override.name AS sub_delivery_person_name,
    ", false);

    $this->db->from('user u');
    $this->db->where('u.Sector IS NOT NULL', null, false);
    $this->db->where('u.Inactive_Status IS NULL', null, false);

    // Thali signup
    $this->db->join(
      'fmb_weekly_signup fmbws',
      "u.ITS_ID = fmbws.user_id AND fmbws.signup_date = '" . $data["date"] . "'",
      'left'
    );

    // Latest override
    $this->db->join("($latest_override_sql) do", "u.ITS_ID = do.user_id", 'left');

    // Latest mapping
    $this->db->join("($latest_mapping_sql) dpm", "u.ITS_ID = dpm.user_id", 'left');

    // Delivery person from override
    $this->db->join(
      'delivery_person dp_override',
      'dp_override.id = do.dp_id',
      'left'
    );

    // Delivery person from mapping
    $this->db->join(
      'delivery_person dp_mapping',
      'dp_mapping.id = dpm.dp_id',
      'left'
    );

    if (!empty($data["reg_dp_id"]) || !empty($data["sub_dp_id"])) {
      $this->db->group_start()
        ->where('dpm.dp_id', $data["reg_dp_id"])
        ->or_where('do.dp_id', $data["sub_dp_id"])
        ->group_end();
    }

    $this->db->where('u.HOF_FM_TYPE', 'HOF');

    if (isset($data["thali_taken"])) {
      if ($data["thali_taken"] == 1) {
        $this->db->where('fmbws.want_thali', '1');
      } else if ($data["thali_taken"] == 0) {
        $this->db->group_start()
          ->where('fmbws.want_thali !=', '1')
          ->or_where('fmbws.want_thali IS NULL', null, false)
          ->group_end();
      }
    }

    $this->db->order_by('u.Sector, u.Sub_Sector, u.Full_Name');

    return $this->db->get()->result_array();
  }
  public function substitutedeliveryperson($data)
  {
    $start_date = $data["start_date"];
    $end_date   = $data["end_date"];
    $user_id    = $data["user_id"];
    // Step 1: Delete overlapping rows
    $this->db->where("user_id", $user_id);
    $this->db->where("start_date <=", $end_date);  // overlap condition
    $this->db->where("end_date >=", $start_date);  // overlap condition
    $this->db->delete("delivery_overrides");

    // Step 2: Insert new row
    return $this->db->insert("delivery_overrides", $data);
  }
  // Updated by Patel Infotech Services
}
