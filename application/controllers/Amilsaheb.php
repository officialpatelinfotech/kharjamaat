<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Amilsaheb extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('AccountM');
        $this->load->model('AdminM');
        $this->load->model('AmilsahebM');
        $this->load->model('MasoolMusaidM');
        $this->load->library('email', $this->config->item('email'));
    }
    public function index()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Home');
    }
    public function EventRazaRequest()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }

        // Fetch counts for Janab-status=0 and Janab-status=1
        $janabStatus0Count = $this->AmilsahebM->get_raza_count_event(['Janab-status' => 0], true);
        $janabStatus1Count = $this->AmilsahebM->get_raza_count_event(['Janab-status' => 1]);

        // Fetch count for coordinator-status=0
        $coordinatorStatus0Count = $this->AmilsahebM->get_raza_count_event(['coordinator-status' => 0], true);

        // Additional data for view
        $data['umoor'] = "Event Raza Applications";
        $data['raza'] = $this->AmilsahebM->get_raza_event();
        $data['razatype'] = $this->AdminM->get_eventrazatype();

        foreach ($data['raza'] as $key => $value) {
            $chatCount = $this->AccountM->get_chat_count($value['id']); // Assuming id is the raza_id
            $data['raza'][$key]['chat_count'] = $chatCount;

            $username = $this->AccountM->get_user($value['user_id']);
            $razatype = $this->AdminM->get_razatype_byid($value['razaType'])[0];
            $data['raza'][$key]['razaType'] = $razatype['name'];
            $data['raza'][$key]['razafields'] = $razatype['fields'];
            $data['raza'][$key]['umoor'] = $razatype['umoor'];
            $data['raza'][$key]['user_name'] = $username[0]['Full_Name'];
        }

        $data['user_name'] = $_SESSION['user']['username'];
        $data['janab_status_0_count'] = $janabStatus0Count;
        $data['janab_status_1_count'] = $janabStatus1Count;
        $data['coordinator_status_0_count'] = $coordinatorStatus0Count;

        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/RazaRequest', $data);
    }

    public function UmoorRazaRequest()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $data['umoor'] = "12 Umoor Raza Applications";
        $data['raza'] = $this->AmilsahebM->get_raza_umoor();
        $data['razatype'] = $this->AdminM->get_umoorrazatype();
        // $data['umoortype'] = $this->AdminM->get_umoortype();

        // Fetch counts for Janab-status=0 and Janab-status=1
        $janabStatus0Count = $this->AmilsahebM->get_raza_count_umoor(['Janab-status' => 0], true);
        $janabStatus1Count = $this->AmilsahebM->get_raza_count_umoor(['Janab-status' => 1]);

        // Fetch count for coordinator-status=0
        $coordinatorStatus0Count = $this->AmilsahebM->get_raza_count_umoor(['coordinator-status' => 0], true);

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
        $data['janab_status_0_count'] = $janabStatus0Count;
        $data['janab_status_1_count'] = $janabStatus1Count;
        $data['coordinator_status_0_count'] = $coordinatorStatus0Count;
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/RazaRequest', $data);
    }
    public function miqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Miqaat/Home', $data);
    }

    public function approveRaza()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $remark = trim($_POST['remark']);
        $raza_id = $_POST['raza_id'];
        $flag = $this->AmilsahebM->approve_raza($raza_id, $remark);
        $user = $this->AdminM->get_user_by_raza_id($raza_id);

        $this->email->from('info@kharjamaat.in', 'Admin');
        $this->email->to($user['Email']);
        $this->email->subject('Raza Status');
        $this->email->message('Mubarak. Your Raza has been Approved by Amil Saheb');
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('amilsaheb@kharjamaat.in');
        $this->email->subject('Raza Approved');
        $this->email->message('Mubarak. Your Raza has been Approved by Amil Saheb');
        $this->email->send();

        $msg = $user['Full_Name'] . ' (' . $user['ITS_ID'] . ').Raza has been Approved by Amil Saheb';

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('kharjamaat@gmail.com');
        $this->email->subject('Raza Approved');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('3042@carmelnmh.in');
        $this->email->subject('Raza Approved');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('anjuman@kharjamaat.in');
        $this->email->subject('Raza Approved');
        $this->email->message($msg);
        $this->email->send();

        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }
    }
    public function DeleteRaza($id)
    {
        // Retrieve the value of $umoor from the URL parameters
        $umoor = $this->input->get('umoor');

        $flag = $this->AccountM->delete_raza($id);

        if ($flag) {
            // Check the value of $umoor and redirect accordingly
            if ($umoor == 'Event Raza Applications') {
                redirect('/amilsaheb/success/EventRazaRequest');
            } else {
                redirect('/amilsaheb/success/UmoorRazaRequest');
            }
        } else {
            // Check the value of $umoor and redirect to the appropriate error URL
            if ($umoor == 'Event Raza Applications') {
                redirect('/amilsaheb/error/EventRazaRequest');
            } else {
                redirect('/amilsaheb/error/UmoorRazaRequest');
            }
        }
    }
    public function success($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('amilsaheb/Header', $data);
        $this->load->view('amilsaheb/Success.php', $data);
    }
    public function error($redirectto)
    {
        $data['user_name'] = $_SESSION['user']['username'];
        $data['redirect'] = $redirectto;
        $this->load->view('amilsaheb/Header', $data);
        $this->load->view('amilsaheb/Error.php', $data);
    }
    public function rejectRaza()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = str_replace(["\r", "\n", "\r\n"], ' ', $value);
        }
        $remark = trim($_POST['remark']);
        $raza_id = $_POST['raza_id'];
        $flag = $this->AmilsahebM->reject_raza($raza_id, $remark);
        $user = $this->AdminM->get_user_by_raza_id($raza_id);

        $this->email->from('info@kharjamaat.in', 'Admin');
        $this->email->to($user['Email']);
        $this->email->subject('Raza Status');
        $this->email->message('Sorry. Your Raza has been Rejected by Amil Saheb. Contact jamaat office for further assistance');
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('amilsaheb@kharjamaat.in');
        $this->email->subject('Raza Recommended');
        $this->email->message('Sorry. Your Raza has been Rejected by Amil Saheb. Contact jamaat office for further assistance');
        $this->email->send();

        $msg = $user['Full_Name'] . ' (' . $user['ITS_ID'] . ').Raza has been Rejected by Amil Saheb';

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('kharjamaat@gmail.com');
        $this->email->subject('Raza Rejected');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('3042@carmelnmh.in');
        $this->email->subject('Raza Rejected');
        $this->email->message($msg);
        $this->email->send();

        $this->email->from('admin@kharjamaat.in', 'Admin');
        $this->email->to('anjuman@kharjamaat.in');
        $this->email->subject('Raza Rejected');
        $this->email->message($msg);
        $this->email->send();

        if ($flag) {
            http_response_code(200);
            echo json_encode(['status' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'error' => 'Failed to submit']);
        }
    }
    public function managemiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['rsvp_list'] = $this->AccountM->get_all_rsvp();
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Miqaat/CreateMiqaat', $data);
    }
    public function addmiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Miqaat/AddMiqaat');
    }
    public function submitmiqaat()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
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
            redirect('/amilsaheb/success/managemiqaat');
        } else {
            redirect('/amilsaheb/error/managemiqaat');
        }
    }
    public function modifymiqaat($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['rsvp'] = $this->AdminM->get_rsvp_byid($id)[0];
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Miqaat/ModifyMiqaat', $data);
    }
    public function submitmodifymiqaat($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
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
            redirect('/amilsaheb/success/managemiqaat');
        } else {
            redirect('/amilsaheb/error/managemiqaat');
        }
    }
    function deletemiqaat($id)
    {
        $check = $this->AdminM->delete_miqaat($id);
        if ($check) {
            redirect('/amilsaheb/success/managemiqaat');
        } else {
            redirect('/amilsaheb/error/managemiqaat');
        }
    }
    public function miqaatattendance()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
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
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Miqaat/MiqaatAttendance', $data);
    }
    public function mumineendirectory()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        if ($this->input->post('search')) {
            $keyword = $this->input->post('search');
            $data['users'] = $this->AmilsahebM->search_users($keyword);
        } else {
            $data['users'] = $this->AmilsahebM->get_all_users();
        }
        $data['user_name'] = $_SESSION['user']['username'];

        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Mumineendirectory', $data);
    }
    public function update_user_details()
    {
        // Load model
        $this->load->model('AmilsahebM');

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
        $updated = $this->AmilsahebM->update_user_by_its_id($its_id, $data);

        echo json_encode(['success' => $updated]);
    }

    public function appointment()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }

        $data['user_name'] = $_SESSION['user']['username'];

        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Appointment/Home', $data);
    }
    public function manage_slots()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $this->load->model('AmilsahebM');
        $_SESSION['slotdate'] = date('Y-m-d');
        $data['user_name'] = $_SESSION['user']['username'];
        $data['slots'] = $this->AmilsahebM->getSlotsData();

        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Appointment/AddSlot', $data);
    }
    public function save_slots()
    {
        if ($this->input->post('selected_date') && $this->input->post('selected_time_slot')) {
            $selectedDate = $this->input->post('selected_date');
            $_SESSION['slotdate'] = $selectedDate;
            $selectedTimeSlots = $this->input->post('selected_time_slot');

            $old_slot = $this->AmilsahebM->getExistingTimeSlots($selectedDate);
            foreach ($old_slot as $key => $os) {
                // Check if the existing slot is not present in the selected time slots
                if (!in_array($os['time'], $selectedTimeSlots)) {
                    // Delete this slot as it is not present in selected time slots
                    $this->AmilsahebM->deleteSlot($os['slot_id']);
                    $this->AmilsahebM->unassignSlot($os['slot_id']);
                }
            }

            $new_slot = $this->AmilsahebM->getExistingTimeSlots($selectedDate);
            foreach ($selectedTimeSlots as $selectedSlot) {
                // Check if the selected slot is not present in the existing time slots
                $slotExists = false;
                foreach ($new_slot as $ns) {
                    if ($ns['time'] == $selectedSlot) {
                        $slotExists = true;
                        break;
                    }
                }
                // If the slot doesn't exist, add it
                if (!$slotExists) {
                    $this->AmilsahebM->addSlot($selectedDate, $selectedSlot);
                    $this->AmilsahebM->addSlot($selectedDate, $selectedSlot);
                    $this->AmilsahebM->addSlot($selectedDate, $selectedSlot);
                }
            }

            // Save new slots
            // $this->AmilsahebM->saveSlots($selectedDate, $selectedTimeSlots);

            $data['user_name'] = $_SESSION['user']['username'];
            $data['slots'] = $this->AmilsahebM->getSlotsData();

            $this->load->view('Amilsaheb/Header', $data);
            $this->load->view('Amilsaheb/Appointment/AddSlot', $data);
        } else {
            $selectedDate = $this->input->post('selected_date');
            $_SESSION['slotdate'] = $selectedDate;
            $this->load->model('AmilsahebM');
            $old_slot = $this->AmilsahebM->getExistingTimeSlots($selectedDate);
            foreach ($old_slot as $key => $os) {
                $this->AmilsahebM->deleteSlot($os['slot_id']);
                $this->AmilsahebM->unassignSlot($os['slot_id']);
            }
            $data['user_name'] = $_SESSION['user']['username'];
            $data['slots'] = $this->AmilsahebM->getSlotsData();

            $this->load->view('Amilsaheb/Header', $data);
            $this->load->view('Amilsaheb/Appointment/AddSlot', $data);
            // Show an alert using JavaScript
            echo '<script>alert("All Slots Deleted successful!");</script>';
        }
    }

    public function getExistingTimeSlots()
    {
        // Get the selected date from the AJAX request
        $selectedDate = $this->input->get('date');

        // Fetch existing time slots from the model
        $existingTimeSlots = $this->AmilsahebM->getExistingTimeSlots($selectedDate);

        // Return the result as JSON
        echo json_encode($existingTimeSlots);
    }

    public function manage_appointment()
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $data['user_name'] = $_SESSION['user']['username'];
        $data['appointment_list'] = $this->AmilsahebM->get_all_appointment();
        $data['total'] = count($data['appointment_list']);

        $count = 0;
        foreach ($data['appointment_list'] as $item) {
            if ($item['status'] == 0) {
                $count++;
            }
        }

        $data['pending'] = $count;
        $data['attended'] = count($data['appointment_list']) - $data['pending'];

        $current = [];
        $upcoming = [];
        $remaining = [];

        $today = date('Y-m-d');

        foreach ($data['appointment_list'] as $appointment) {
            if ($appointment['date'] == $today) {
                $current[] = $appointment;
            } elseif ($appointment['date'] > $today) {
                $upcoming[] = $appointment;
            } else {
                $remaining[] = $appointment;
            }
        }
        $data['appointment_list'] = array_merge($current, $upcoming, $remaining);

        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('Amilsaheb/Appointment/ManageAppointments', $data);
    }
    public function update_appointment_list($id)
    {
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] != 2) {
            redirect('/accounts');
        }
        $check = $this->AmilsahebM->update_appointment_list($id);
        if ($check) {
            redirect('/amilsaheb/manage_appointment');
        } else {
            redirect('/amilsaheb/error/manage_appointment');
        }
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

        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('MasoolMusaid/AsharaOhbat', $data);
    }
    
    public function ashara_attendance()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
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
        $this->load->view('Amilsaheb/Header', $data);
        $this->load->view('MasoolMusaid/AsharaAttendance', $data);
    }


}
