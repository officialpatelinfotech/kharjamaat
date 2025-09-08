<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MasoolMusaid extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('MasoolMusaidM'); // ✅ Load your dedicated model
    $this->load->model('AdminM');
    $this->load->model('AccountM');
    $this->load->library('email', $this->config->item('email'));
    $this->load->model('HijriCalendar');
  }

  public function index()
  {
    // ✅ Restrict access strictly to role 16 (Masool/Musaid)
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $data['user_name'] = $_SESSION['user']['username'];
    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/Home');
  }

  public function mumineendirectory()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    if ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      $data['users'] = $this->MasoolMusaidM->search_users_by_sector($keyword, $sector, $subsector);
    } else {
      $data['users'] = $this->MasoolMusaidM->get_users_by_sector($sector, $subsector);
    }

    $data['user_name'] = $username;

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/Mumineendirectory', $data);
  }

  public function update_user_details()
  {
    // Load model
    $this->load->model('MasoolMusaidM');

    // Get posted data
    $data = $this->input->post();

    // Extract ITS_ID and remove it from update array
    $its_id = $data['ITS_ID'] ?? null;
    unset($data['ITS_ID']);

    // Validate ITS_ID
    if (!$its_id) {
      echo json_encode(['success' => false, 'error' => 'ITS_ID missing']);
      return;
    }

    // Update via model
    $updated = $this->MasoolMusaidM->update_user_by_its_id($its_id, $data);

    echo json_encode(['success' => $updated]);
  }


  public function asharaohbat()
  {
    // Authorization check
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Parse sector and optional sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = strtoupper($matches[2]); // Normalize to uppercase
    }

    // Handle search or fetch all
    if ($this->input->post('search')) {
      $keyword = $this->input->post('search');
      $users = $this->MasoolMusaidM->search_ashara_by_sector($keyword, $sector, $subsector);
    } else {
      $users = $this->MasoolMusaidM->get_ashara_by_sector($sector, $subsector);
    }

    // Get all sectors and sub-sectors data for the logged-in user's scope
    $sectorsData = $this->MasoolMusaidM->get_sectors_stats($sector, $subsector);
    $subSectorsData = $this->MasoolMusaidM->get_sub_sectors_stats($sector, $subsector);

    // Stats initialization
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

    // Populate stats
    foreach ($users as $u) {
      $type = $u['HOF_FM_TYPE'];
      $gender = strtolower($u['Gender']);
      $age = intval($u['Age']);
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

    // Pass data to views
    $data = [
      'user_name' => $username,
      'users' => $users,
      'stats' => $stats,
      'current_sector' => $sector,
      'current_sub_sector' => $subsector
    ];

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/AsharaOhbat', $data);
  }

  public function update_ashara_ohbat_details()
  {
    $ITS = $this->input->post('ITS');
    $leaveStatus = $this->input->post('LeaveStatus');

    $updateData = [
      'Type' => $this->input->post('Type'),
      'HOF' => $this->input->post('HOF'),
      'Name' => $this->input->post('Full_Name'), // <-- Full_Name used
      'Age' => $this->input->post('Age'),
      'Mobile' => $this->input->post('Mobile'),
      'Sector' => $this->input->post('Sector'),
      'Sub' => $this->input->post('Sub'),
      'LeaveStatus' => $leaveStatus,
      'Comment' => $this->input->post('Comment')
    ];

    $this->load->model('MasoolMusaidM');
    $result = $this->MasoolMusaidM->upsert_ashara_row($ITS, $updateData);

    // If LeaveStatus is special, also update ashara_attendance
    if (in_array($leaveStatus, ['Not in Town', 'Married Outcaste'])) {
      $this->MasoolMusaidM->update_attendance_leave_status($ITS, $leaveStatus);
    }

    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(['success' => $result]));
  }


  public function ashara_attendance()
  {
    // Authorization
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];

    // Extract sector and sub-sector from username
    preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $m);
    $user_sector = ucfirst(strtolower($m[1] ?? ''));
    $user_sub = strtoupper($m[2] ?? '');

    // Use GET parameters if available, else fallback to user's sector/sub-sector
    $sel_sector = $this->input->get('sector') ?? $user_sector;
    $sel_sub = $this->input->get('subsector') ?? $user_sub;

    // Validate selected sector
    $all_sectors = $this->MasoolMusaidM->get_all_sectors();
    if (!in_array($sel_sector, array_column($all_sectors, 'sector'))) {
      $sel_sector = $user_sector;
      $sel_sub = $user_sub;
    }

    // Determine whether to filter by sub-sector or allow search
    if (!empty($user_sub)) {
      $sel_sub = $user_sub;
      $users = $this->MasoolMusaidM->get_attendance_by_sub_sector($user_sector, $user_sub);
      $stats = $this->MasoolMusaidM->get_sub_sector_stats($user_sector, $user_sub);
    } else {
      if ($this->input->post('search')) {
        $kw = $this->input->post('search', true);
        $users = $this->MasoolMusaidM->search_attendance_by_sector($kw, $sel_sector, $sel_sub);
      } else {
        $users = $this->MasoolMusaidM->get_attendance_by_sector($sel_sector, $sel_sub);
      }
      $stats = $this->MasoolMusaidM->get_sector_stats($sel_sector, $sel_sub);
    }

    // Prepare view data
    $data = [
      'username' => $username,
      'user_sector' => $user_sector,
      'user_sub' => $user_sub,
      'sel_sector' => $sel_sector,
      'sel_sub' => $sel_sub,
      'all_sectors' => $all_sectors,
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
      'all_sub_sectors' => $this->MasoolMusaidM->get_all_sub_sectors($sel_sector),
    ];

    // Load views
    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/AsharaAttendance', $data);
  }




  public function update_attendance()
  {


    $its = $this->input->post('its');
    $dayInput = $this->input->post('day'); // 2–9 or "Ashura"
    $status = $this->input->post('status');
    $comment = $this->input->post('comment');

    if (!$its || !$dayInput || !$status) {
      http_response_code(400); // Bad request
      echo json_encode(['error' => 'Missing required fields']);
      return;
    }

    $dayColumn = ($dayInput === 'Ashura') ? 'Ashura' : 'Day' . $dayInput;
    $commentColumn = ($dayInput === 'Ashura') ? 'CommentAshura' : 'Comment' . $dayInput;

    $data = [
      $dayColumn => $status,
      $commentColumn => $comment
    ];

    // Update or insert
    $this->db->where('ITS', $its);
    $exists = $this->db->get('ashara_attendance')->row();

    if ($exists) {
      $this->db->where('ITS', $its);
      $result = $this->db->update('ashara_attendance', $data);
    } else {
      $data['ITS'] = $its;
      $result = $this->db->insert('ashara_attendance', $data);
    }

    if ($result) {
      echo json_encode(['success' => true]);
    } else {
      http_response_code(500); // Internal server error
      echo json_encode(['error' => 'Failed to update attendance']);
    }
  }


  public function bulk_update_attendance()
  {
    $data = json_decode($this->input->raw_input_stream, true);

    $its_list = $data['its_list'] ?? null;
    $day = $data['day'] ?? null;
    $status = $data['status'] ?? null;

    if (!$its_list || !$day || !$status) {
      show_error('Invalid data provided.', 400);
    }

    foreach ($its_list as $its) {
      $this->db->where('ITS', $its)->update('ashara_attendance', [
        $day => $status
      ]);

      // Optional: Log if no row affected (not found)
      // if ($this->db->affected_rows() == 0) {
      //     log_message('error', "Update failed or no row found for ITS: $its");
      // }
    }
    echo 'success';
  }

  // Updated by Patel Infotech Services

  public function rsvp_list()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    $data['user_name'] = $username;

    $miqaat_rsvp_counts = $this->MasoolMusaidM->get_rsvp_counts_by_miqaat($sector, $subsector);

    if (isset($miqaat_rsvp_counts)) {
      $data["miqaat_rsvp_counts"] = $miqaat_rsvp_counts;
      foreach ($miqaat_rsvp_counts as $key => $miqaat) {
        $hijri_date = $this->HijriCalendar->get_hijri_date($miqaat['miqaat_date']);
        $hijri_month = $this->HijriCalendar->hijri_month_name(explode("-", $hijri_date["hijri_date"])[1])["hijri_month"];
        $data["miqaat_rsvp_counts"][$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];

        $miqaat_raza_status = $this->AccountM->get_miqaat_raza_status($miqaat["miqaat_id"]);
        if ($miqaat_raza_status) {
          $data["miqaat_rsvp_counts"][$key]["raza_status"] = $miqaat_raza_status ?? 'Unknown';
        } else {
          $data["miqaat_rsvp_counts"][$key]["raza_status"] = 'Unknown';
        }
      }
    } else {
      $data["miqaat_rsvp_counts"] = [];
    }

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/RSVP/Home');
  }

  public function general_rsvp($miqaat_id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    $data['user_name'] = $username;

    // Fetch miqaat details
    $miqaat = $this->MasoolMusaidM->get_miqaat_by_id($miqaat_id);
    if (!$miqaat) {
      show_404();
    }
    $data['miqaat'] = $miqaat;

    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);
    $data['members'] = $members;

    $existing_rsvps = $this->MasoolMusaidM->get_rsvps_by_miqaat($miqaat_id);
    $data['existing_rsvps'] = [];
    foreach ($existing_rsvps as $rsvp) {
      $data['existing_rsvps'][$rsvp['user_id']] = $rsvp;
    }

    $data['miqaat_id'] = $miqaat_id;

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/RSVP/GeneralRSVP', $data);
  }

  public function submit_general_rsvp()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $miqaat_id = $this->input->post('miqaat_id');
    $rsvp_members = $this->input->post('rsvp_members') ?? [];

    if (!$miqaat_id) {
      show_error('Miqaat ID is required', 400);
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    // Fetch members for the sector/sub-sector
    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);
    $valid_member_ids = array_column($members, 'ITS_ID');

    // Filter submitted member IDs to only include valid ones
    $valid_rsvp_ids = array_intersect($rsvp_members, $valid_member_ids);

    // Fetch existing RSVPs for the miqaat
    $existing_rsvps = $this->MasoolMusaidM->get_rsvps_by_miqaat($miqaat_id);
    $existing_rsvp_ids = array_column($existing_rsvps, 'user_id');

    // Determine which RSVPs to add and which to remove
    $to_add = array_diff($valid_rsvp_ids, $existing_rsvp_ids);

    $to_remove = array_diff($existing_rsvp_ids, $valid_rsvp_ids);


    // Add new RSVPs (send hof_id as well)
    foreach ($to_add as $user_id) {
      // Find hof_id for this user_id from $members
      $hof_id = null;
      foreach ($members as $m) {
        if ($m['ITS_ID'] == $user_id) {
          $hof_id = $m['HOF_ID'] ?? null;
          break;
        }
      }
      $this->MasoolMusaidM->add_general_rsvp($miqaat_id, $user_id, $hof_id);
    }

    foreach ($to_remove as $user_id) {
      $this->MasoolMusaidM->remove_general_rsvp($miqaat_id, $user_id);
    }

    if (!empty($to_add)) {
      $this->session->set_flashdata('success', 'RSVPs updated successfully.');
    } else {
      $this->session->set_flashdata('info', 'No changes made to RSVPs.');
    }
    // Redirect
    redirect('/MasoolMusaid/general_rsvp/' . $miqaat_id);
  }

  public function miqaat_attendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }

    $data['user_name'] = $username;

    $miqaats = $this->MasoolMusaidM->get_upcoming_miqaats();
    if (!$miqaats) {
      $data['miqaats'] = [];
    } else {
      foreach ($miqaats as $key => $miqaat) {
        if (isset($miqaat['date'])) {
          $hijri_date = $this->HijriCalendar->get_hijri_date($miqaat['date']);
          $hijri_month = $this->HijriCalendar->hijri_month_name(explode("-", $hijri_date["hijri_date"])[1])["hijri_month"];
          $miqaats[$key]["hijri_date"] = explode("-", $hijri_date["hijri_date"])[0] . " " . $hijri_month . " " . explode("-", $hijri_date["hijri_date"])[2];
        }

        if (isset($miqaat['id'])) {
          $miqaats[$key]['member_count'] = count($this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector));
          // Get attendee count for this miqaat
          $miqaats[$key]['attendee_count'] = $this->MasoolMusaidM->get_miqaat_attendee_count($miqaat['id'], $sector, $subsector);

          $miqaat_raza_status = $this->AccountM->get_miqaat_raza_status($miqaat["id"]);
          if ($miqaat_raza_status) {
            $miqaats[$key]["raza_status"] = $miqaat_raza_status ?? 'Unknown';
          } else {
            $miqaats[$key]["raza_status"] = 'Unknown';
          }
        }

        unset($miqaats[$key]['sector']);
        unset($miqaats[$key]['subsector']);
      }
      $data['miqaats'] = $miqaats;
    }

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/Miqaat/Home', $data);
  }

  public function miqaat_attendance_details($miqaat_id)
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1];
      $subsector = $matches[2];
    }

    $data['user_name'] = $username;

    // Fetch miqaat details
    $miqaat = $this->MasoolMusaidM->get_miqaat_by_id($miqaat_id);
    if (!$miqaat) {
      show_404();
    }
    $data['miqaat'] = $miqaat;

    // Fetch members for the sector/sub-sector
    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);

    // Fetch existing attendance records for the miqaat
    $existing_attendance = $this->MasoolMusaidM->get_miqaat_attendance_by_miqaat($miqaat_id);
    $attendance_map = [];
    foreach ($existing_attendance as $attendance) {
      $attendance_map[$attendance['user_id']] = $attendance;
    }
    $data["existing_attendance"] = $existing_attendance;

    // Merge attendance info into members array
    foreach ($members as &$member) {
      $its = $member['ITS_ID'];
      if (isset($attendance_map[$its])) {
        foreach ($attendance_map[$its] as $k => $v) {
          if ($k !== 'user_id') {
            $member[$k] = $v;
          }
        }
      }
    }
    unset($member);

    $data['members'] = $members;
    $data['miqaat_id'] = $miqaat_id;

    $this->load->view('MasoolMusaid/Header', $data);
    $this->load->view('MasoolMusaid/Miqaat/AttendanceDetails', $data);
  }

  public function submit_miqaat_attendance()
  {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 16) {
      redirect('/accounts');
    }

    $miqaat_id = $this->input->post('miqaat_id');
    $attendance_members = $this->input->post('attendance_members') ?? [];
    $attendance_comments = $this->input->post('attendance_comments') ?? [];

    if (!$miqaat_id) {
      show_error('Miqaat ID is required', 400);
    }

    $username = $_SESSION['user']['username'];
    $sector = '';
    $subsector = '';

    // Extract sector and sub-sector from username
    if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z]?)$/i', $username, $matches)) {
      $sector = $matches[1]; // Burhani, Mohammedi, etc.
      $subsector = $matches[2]; // A, B, C or empty
    }

    // Fetch members for the sector/sub-sector
    $members = $this->MasoolMusaidM->get_members_by_sector_sub_sector($sector, $subsector);
    $valid_member_ids = array_column($members, 'ITS_ID');

    // Only allow valid member IDs
    $valid_attendance_ids = array_intersect($attendance_members, $valid_member_ids);

    // Fetch existing attendance records for the miqaat
    $existing_attendance = $this->MasoolMusaidM->get_miqaat_attendance_by_miqaat($miqaat_id);
    $existing_attendance_ids = array_column($existing_attendance, 'user_id');

    // Determine which attendance records to add and which to remove
    $to_add = array_diff($valid_attendance_ids, $existing_attendance_ids);
    $to_remove = array_diff($existing_attendance_ids, $valid_attendance_ids);

    // Add new attendance records (send comment, not status)
    foreach ($to_add as $user_id) {
      $comment = isset($attendance_comments[$user_id]) ? $attendance_comments[$user_id] : '';
      // Find hof_id for this user_id from $members
      $hof_id = null;
      foreach ($members as $m) {
        if ($m['ITS_ID'] == $user_id) {
          $hof_id = $m['HOF_ID'] ?? null;
          break;
        }
      }
      $this->MasoolMusaidM->add_miqaat_attendance($miqaat_id, $user_id, $hof_id, $comment);
    }

    // Remove attendance records
    foreach ($to_remove as $user_id) {
      $this->MasoolMusaidM->remove_miqaat_attendance($miqaat_id, $user_id);
    }

    $this->session->set_flashdata('success', 'Attendance updated successfully.');
    redirect('/MasoolMusaid/miqaat_attendance_details/' . $miqaat_id);
  }
}
