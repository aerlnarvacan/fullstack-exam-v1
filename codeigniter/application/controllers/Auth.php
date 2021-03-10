<?php
defined('BASEPATH') or exit('No direct script access allowed');

use App\Services\Handlers\EmployeeHandler;

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('repositories/employeeRepository');
    }

    public function login()
    {
        if ($this->session->has_userdata('uid')) {
            redirect('/home', 'refresh');
        }

        $err = [];

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $employeeHandler = new EmployeeHandler($this->employeerepository);
            $empId = $employeeHandler->verifyCredentialsAndGetId($username, $password);

            if (!is_null($empId)) {
                $this->session->set_userdata('uid', $empId);
                redirect('/home', 'refresh');
            }

            $err['error'] = 'invalid credentials';
        }

        return	$this->load->view('login', $err);
    }

    public function logout()
    {
        $this->session->unset_userdata('uid');
        redirect('/login', 'refresh');
    }
}
