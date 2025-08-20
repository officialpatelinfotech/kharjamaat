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




}
