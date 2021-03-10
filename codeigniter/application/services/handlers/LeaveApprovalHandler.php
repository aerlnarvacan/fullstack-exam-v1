<?php
namespace App\Services\Handlers;

use App\Models\Leave;
use App\Services\Exceptions\LeaveStatusException;
use App\Services\Exceptions\UsedLeaveException;
use App\Services\Exceptions\Forbidden;
use Sourcefit\Application\LeaveManagement\ApproveLeave;
use Sourcefit\Domain\LeaveManagement\Repository\EmployeeRepository;
use Sourcefit\Domain\LeaveManagement\Repository\LeaveRepository;

class LeaveApprovalHandler implements \Sourcefit\Application\LeaveManagement\Handler\LeaveApprovalHandler
{
    private $leaveRepository;
    private $employeeRepository;
  
    public function __construct(LeaveRepository $leaveRepository, EmployeeRepository $employeeRepository)
    {
        $this->leaveRepository = $leaveRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function handleThis(ApproveLeave $command): void
    {
        $leave = $this->leaveRepository->findOne($command->leaveId());
        $employee = $this->employeeRepository->findOne($command->approvedBy());

        if ($employee->role !== 'admin') {
            throw new Forbidden();
        }

        if ($leave->status !== 'PENDING') {
            throw new LeaveStatusException();
        }
        
        $leaveData = array(
            'id' => $command->leaveId(),
            'updatedBy' => $command->approvedBy(),
            'updatedAt' => $command->approvedOn()
        );

        $this->leaveRepository->update(Leave::create((object) $leaveData), 'APPROVE');
    }
}
