<?php
namespace App\Services\Handlers;

use App\Models\Leave;
use App\Services\Exceptions\LeaveStatusException;
use App\Services\Exceptions\Forbidden;
use Sourcefit\Application\LeaveManagement\DenyLeave;
use Sourcefit\Domain\LeaveManagement\Repository\EmployeeRepository;
use Sourcefit\Domain\LeaveManagement\Repository\LeaveRepository;

class LeaveDenialHandler implements \Sourcefit\Application\LeaveManagement\Handler\LeaveDenialHandler
{
    private $leaveRepository;
    private $employeeRepository;
  
    public function __construct(LeaveRepository $leaveRepository, EmployeeRepository $employeeRepository)
    {
        $this->leaveRepository = $leaveRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function handleThis(DenyLeave $command): void
    {
        $leave = $this->leaveRepository->findOne($command->leaveId());
        $employee = $this->employeeRepository->findOne($command->deniedBy());

        if ($employee->role !== 'admin') {
            throw new Forbidden();
        }

        if ($leave->status !== 'PENDING') {
            throw new LeaveStatusException();
        }
        
        $leaveData = array(
            'id' => $command->leaveId(),
            'updatedBy' => $command->deniedBy(),
            'updatedAt' => $command->deniedOn()
        );

        $this->leaveRepository->update(Leave::create((object) $leaveData), 'DENY');
    }
}
