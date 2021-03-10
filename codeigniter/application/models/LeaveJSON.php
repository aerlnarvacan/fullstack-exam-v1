<?php
namespace App\Models;

use App\Services\Handlers\JSONHandler;

class LeaveJSON implements \Sourcefit\Domain\LeaveManagement\Leave
{
    public $id;
    public $employeeId;
    public $leaveDate;
    public $status;
    public $updatedBy;
    public $createdAt;
    public $updatedAt;

    private $STORAGE_PATH = APPPATH.'../storage/';
    private $LEAVES_FILE = 'leaves.json';
    private $EMPLOYEES_FILE = 'employees.json';

    public static function create($leaveData): Leave
    {
        $leave = new Leave();
        $leave->id = $leaveData->id ?? null;
        $leave->employeeId = $leaveData->employeeId ?? null;
        $leave->leaveDate = $leaveData->leaveDate ?? null;
        $leave->status = $leaveData->status ?? null;
        $leave->updatedBy = $leaveData->updatedBy ?? null;
        $leave->createdAt = $leaveData->createdAt ?? null;
        $leave->updatedAt = $leaveData->updatedAt ?? null;
        return $leave;
    }

    public function file(string $leaveId, string $employeeId, \DateTimeInterface $leaveDate): void
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $leaves = $jsonHandler->read($this->LEAVES_FILE);

        $leaves[$leaveId] = array(
          'id' => $leaveId,
          'employeeId' => $employeeId,
          'leaveDate' => date_format($leaveDate, 'Y-m-d'),
          'status' => 'PENDING',
          'createdAt' => date_format(new \DateTime(), 'Y-m-d H:i:s.u')
        );

        $jsonHandler->store($this->LEAVES_FILE, $leaves);
    }

    public function deny(string $leaveId, string $deniedBy, \DateTimeInterface $deniedOn): void
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $leaves = $jsonHandler->read($this->LEAVES_FILE);

        $leaves[$leaveId]['updatedBy'] = $deniedBy;
        $leaves[$leaveId]['updatedAt'] = date_format($deniedOn, 'Y-m-d H:i:s.u');
        $leaves[$leaveId]['status'] = 'DENIED';
        
        $jsonHandler->store($this->LEAVES_FILE, $leaves);
    }

    public function approve(string $leaveId, string $approvedBy, \DateTimeInterface $approvedOn): void
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $leaves = $jsonHandler->read($this->LEAVES_FILE);

        $leaves[$leaveId]['updatedBy'] = $approvedBy;
        $leaves[$leaveId]['updatedAt'] = date_format($approvedOn, 'Y-m-d H:i:s.u');
        $leaves[$leaveId]['status'] = 'APPROVED';
        
        $jsonHandler->store($this->LEAVES_FILE, $leaves);
    }

    public function cancel(string $leaveId, string $cancelledBy, \DateTimeInterface $cancelledOn): void
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $leaves = $jsonHandler->read($this->LEAVES_FILE);

        $leaves[$leaveId]['updatedBy'] = $cancelledBy;
        $leaves[$leaveId]['updatedAt'] = date_format($cancelledOn, 'Y-m-d H:i:s.u');
        $leaves[$leaveId]['status'] = 'CANCELLED';
        
        $jsonHandler->store($this->LEAVES_FILE, $leaves);
    }

    public function findOne(array $params)
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $leaves = $jsonHandler->read($this->LEAVES_FILE);

        if (!is_null($params["id"])) {
            return is_null($leaves[$params["id"]]) ? null : Leave::create((object)$leaves[$params["id"]]);
        }

        foreach ($leaves as $leaveId => $details) {
            if ($details['employeeId'] === $params["employeeId"] &&
            $details['leaveDate'] === $params["leaveDate"] &&
            $details['status'] !== $params["status !="]) {
                return Leave::create((object)$leaves[$params["id"]]);
            }
        }

        return null;
    }

    public function findAllAndBuild(string $employeeId = null)
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $employees = $jsonHandler->read($this->EMPLOYEES_FILE);
        $leaves = $jsonHandler->read($this->LEAVES_FILE);

        $leaveRecords = array();
        $formattedLeaves = array();

        foreach ($leaves as $leaveId => $details) {
            if (!is_null($employeeId) && $details['employeeId'] === $employeeId) {
                array_push($formattedLeaves, $this->buildLeavesData($details, $employees));
            } elseif (is_null($employeeId)) {
                array_push($formattedLeaves, $this->buildLeavesData($details, $employees));
            }
        }

        return $formattedLeaves;
    }

    private function buildLeavesData($leave, $employeesData)
    {
        $employeeId = $leave['employeeId'];
        $updaterId = $leave['updatedBy'];
        $leave['empName'] = $employeesData[$employeeId]['firstName'].' '.$employeesData[$employeeId]['lastName'];
        if (!is_null($updaterId)) {
            $leave['updater'] = $employeesData[$updaterId]['firstName'].' '.$employeesData[$updaterId]['lastName'];
        }
        return (object) $leave;
    }
}
