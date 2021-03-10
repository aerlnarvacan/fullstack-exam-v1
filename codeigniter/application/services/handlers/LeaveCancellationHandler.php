<?php
namespace App\Services\Handlers;

use App\Models\Leave;
use App\Services\Exceptions\LeaveStatusException;
use App\Services\Exceptions\UsedLeaveException;
use Sourcefit\Application\LeaveManagement\CancelLeave;
use Sourcefit\Domain\LeaveManagement\Repository\EmployeeRepository;
use Sourcefit\Domain\LeaveManagement\Repository\LeaveRepository;

class LeaveCancellationHandler implements \Sourcefit\Application\LeaveManagement\Handler\LeaveCancellationHandler
{
    private $leaveRepository;
    private $employeeRepository;
  
    public function __construct(LeaveRepository $leaveRepository, EmployeeRepository $employeeRepository)
    {
        $this->leaveRepository = $leaveRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function handleThis(CancelLeave $command): void
    {
        $leave = $this->leaveRepository->findOne($command->leaveId());

        if ($leave->status !== 'PENDING') {
            throw new LeaveStatusException();
        }
    
        $dateDiff = (new \DateTime(date_format($command->cancelledOn(), 'Y-m-d')))->diff(new \DateTime($leave->leaveDate));
        if ($dateDiff->format('%r%a') < 1) {
            throw new UsedLeaveException();
        }
    
        $leaveData = array(
            'id' => $command->leaveId(),
            'updatedBy' => $command->approvedBy(),
            'updatedAt' => $command->cancelledOn()
        );

        $this->leaveRepository->update(Leave::create((object) $leaveData), 'CANCEL');
        $this->employeeRepository->addLeaveCredit($leave->employeeId);
    }
}
