<?php
defined('BASEPATH') or exit('No direct script access allowed');

use App\Services\Exceptions\InsufficientLeaveCreditException;
use App\Services\Exceptions\LateFilingOfLeaveException;
use App\Services\Exceptions\LeaveStatusException;
use App\Services\Exceptions\OverlappingLeaveException;
use App\Services\Exceptions\UsedLeaveException;
use App\Services\Handlers\LeaveFilingHandler;
use App\Services\Handlers\LeaveApprovalHandler;
use App\Services\Handlers\LeaveCancellationHandler;
use App\Services\Handlers\LeaveDenialHandler;
use App\Services\Dto\FileLeave;
use App\Services\Dto\CancelLeave;
use App\Services\Dto\ApproveLeave;
use App\Services\Dto\DenyLeave;

class LeaveActions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('repositories/employeeRepository');
        $this->load->library('repositories/leaveRepository');
    }

    public function create()
    {
        $err = $this->validate();
        if (!empty($err)) {
            return $this->toResponseJSON($err['status'], $err['message']);
        }

        $leaveDate = $this->input->post('leaveDate');
        $userId = $this->session->userdata('uid');

        try {
            $leaveFiliingHandler = new LeaveFilingHandler($this->leaverepository, $this->employeerepository);
            $fileLeave = new FileLeave(
                uuid(),
                $userId,
                new DateTime($leaveDate)
            );

            $leaveFiliingHandler->handleThis($fileLeave);
        } catch (InsufficientLeaveCreditException $e) {
            return $this->toResponseJSON(400, array('message' => 'insufficient leave credit'));
        } catch (LateFilingOfLeaveException $e) {
            return $this->toResponseJSON(400, array('message' => 'date less than a week'));
        } catch (OverlappingLeaveException $e) {
            return $this->toResponseJSON(400, array('message' => 'overlapping leave'));
        } catch (Exception $e) {
            return $this->toResponseJSON(500, array('message' => 'internal server error'));
        }

        return $this->toResponseJSON(200, array());
    }

    public function cancel()
    {
        $err = $this->validate();
        if (!empty($err)) {
            return $this->toResponseJSON($err['status'], $err['message']);
        }

        $leaveId = $this->input->post('leaveId');
        $userId = $this->session->userdata('uid');

        if (is_null($leaveId)) {
            return $this->toResponseJSON(400, 'invalid parameter');
        }

        try {
            $leaveCancellationHandler = new LeaveCancellationHandler($this->leaverepository, $this->employeerepository);
            $cancelLeave = new CancelLeave(
                $leaveId,
                $userId,
                new DateTime()
            );

            $leaveCancellationHandler->handleThis($cancelLeave);
        } catch (LeaveStatusException $e) {
            return $this->toResponseJSON(400, array('message' => 'cannot cancel leave'));
        } catch (UsedLeaveException $e) {
            return $this->toResponseJSON(400, array('message' => 'leave already in-use or used'));
        } catch (Exception $e) {
            return $this->toResponseJSON(500, array('message' => 'internal server error'));
        }

        return $this->toResponseJSON(200, array());
    }

    public function approve()
    {
        $err = $this->validate();
        if (!empty($err)) {
            return $this->toResponseJSON($err['status'], $err['message']);
        }

        $leaveId = $this->input->post('leaveId');
        $userId = $this->session->userdata('uid');

        if (is_null($leaveId)) {
            return $this->toResponseJSON(400, 'invalid parameter');
        }

        try {
            $leaveApprovalHandler = new LeaveApprovalHandler($this->leaverepository, $this->employeerepository);
            $approveLeave = new ApproveLeave(
                $leaveId,
                $userId,
                new DateTime()
            );

            $leaveApprovalHandler->handleThis($approveLeave);
        } catch (Forbidden $e) {
            return $this->toResponseJSON(400, array('message' => 'cannot update status'));
        } catch (LeaveStatusException $e) {
            return $this->toResponseJSON(400, array('message' => 'cannot update status'));
        } catch (Exception $e) {
            return $this->toResponseJSON(500, array('message' => 'internal server error'));
        }

        return $this->toResponseJSON(200, array());
    }

    public function deny()
    {
        $err = $this->validate();
        if (!empty($err)) {
            return $this->toResponseJSON($err['status'], $err['message']);
        }

        $leaveId = $this->input->post('leaveId');
        $userId = $this->session->userdata('uid');

        if (is_null($leaveId)) {
            return $this->toResponseJSON(400, 'invalid parameter');
        }

        try {
            $leaveDenialHandler = new LeaveDenialHandler($this->leaverepository, $this->employeerepository);
            $denyLeave = new DenyLeave(
                $leaveId,
                $userId,
                new DateTime()
            );

            $leaveDenialHandler->handleThis($denyLeave);
        } catch (Forbidden $e) {
            return $this->toResponseJSON(400, array('message' => 'cannot update status'));
        } catch (LeaveStatusException $e) {
            return $this->toResponseJSON(400, array('message' => 'cannot update status'));
        } catch (Exception $e) {
            return $this->toResponseJSON(500, array('message' => 'internal server error'));
        }

        return $this->toResponseJSON(200, array());
    }

    private function toResponseJSON($code, $data)
    {
        return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
        'status' => $code == 200 ? 'success' : 'error',
        'code' => $code,
        'data' => $data
        )));
    }

    private function validate()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return array('status' => 404, 'message' => 'invalid method');
        }

        if (!$this->session->has_userdata('uid')) {
            return array('status' => 401, 'message' => 'unauthorized');
        }

        return null;
    }
}
