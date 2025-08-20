<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('AccountM');
    }
    public function index()
    {
        if (!empty($_SESSION['user'])) {
            $data['user_name'] = $_SESSION['user']['username'];
            $this->load->view('Accounts/Header', $data);
        } else {
            $this->load->view('Home/Header');

        }
        $this->load->view('Home/Home');
    }

}
?>