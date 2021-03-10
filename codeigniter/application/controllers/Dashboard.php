<?php
defined('BASEPATH') or exit('No direct script access allowed');

use App\Services\Handlers\EmployeeHandler;
use App\Services\Handlers\LeaveQueryHandler;
use App\Services\Factories\EmployeeRepositoryFactory;
use App\Services\Factories\LeaveRepositoryFactory;

class Dashboard extends CI_Controller
{
    private $employeerepository;
    private $leaverepository;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        /**
         * Load Repositories
         */
        $this->employeerepository = EmployeeRepositoryFactory::getInstance($this->config->item('data_storage'));
        $this->leaverepository = LeaveRepositoryFactory::getInstance($this->config->item('data_storage'));

        error_reporting(E_ERROR);
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
        $leaves = $leaveQueryHandler->getLeaves($employee->role === 'user' ? $empId : null);
                            
        $this->load->view('dashboard', array('employee' => $employee, 'leaves' => $leaves));
    }
}
