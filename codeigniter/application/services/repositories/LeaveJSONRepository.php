<?php
namespace App\Services\Repositories;

use App\Models\LeaveJSON;

class LeaveJSONRepository implements \Sourcefit\Domain\LeaveManagement\Repository\LeaveRepository
{
    private $leave;

    public function __construct()
    {
        $this->leave = new LeaveJSON();
    }

    public function findOne(string $leaveId): ?\Sourcefit\Domain\LeaveManagement\Leave
    {
        return $this->leave->findOne(['id' => $leaveId]);
    }
  
    public function store(\Sourcefit\Domain\LeaveManagement\Leave $leave): void
    {
        $this->leave->file($leave->id, $leave->employeeId, $leave->leaveDate);
    }

    public function update(\Sourcefit\Domain\LeaveManagement\Leave $leave, string $action): void
    {
        switch ($action) {
          case 'CANCEL':
            $this->leave->cancel($leave->id, $leave->updatedBy, $leave->updatedAt);
            break;
          case 'APPROVE':
            $this->leave->approve($leave->id, $leave->updatedBy, $leave->updatedAt);
            break;
          case 'DENY':
            $this->leave->deny($leave->id, $leave->updatedBy, $leave->updatedAt);
            break;
          default:
            break;
        }
    }

    public function getEmployeeLeaves(string $employeeId = null): array
    {
        return $this->leave->findAllAndBuild($employeeId);
    }
  
    public function checkLeaveDate(string $employeeId, \DateTimeInterface $leaveDate): ?\Sourcefit\Domain\LeaveManagement\Leave
    {
        return $this->leave->findOne(['employeeId' => $employeeId, 'leaveDate' => date_format($leaveDate, 'Y-m-d'), 'status !=' => 'CANCELLED']);
    }
}
