<?php
defined('BASEPATH') or exit('No direct script access allowed');

use App\Services\Handlers\EmployeeHandler;
use App\Services\Handlers\LeaveQueryHandler;

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('repositories/employeeRepository');
        $this->load->library('repositories/leaveRepository');
    }

    public function index()
    {
        if (!$this->session->has_userdata('uid')) {
            redirect('/login');
        }
        
        $empId = $this->session->userdata('uid');

        $employeeHandler = new EmployeeHandler($this->employeerepository);
        $leaveQueryHandler = new LeaveQueryHandler($this->leaverepository);

        $employee = $employeeHandler->getById($empId);
        $leaves = $leaveQueryHandler->getLeaves($empId);
                            
        $this->load->view('dashboard', array('employee' => $employee, 'leaves' => $leaves));
    }
}
