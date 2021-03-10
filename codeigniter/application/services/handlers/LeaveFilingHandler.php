<?php
namespace App\Services\Handlers;

use App\Services\Exceptions\InsufficientLeaveCreditException;
use App\Services\Exceptions\LateFilingOfLeaveException;
use App\Services\Exceptions\OverlappingLeaveException;
use App\Models\Leave;
use Sourcefit\Application\LeaveManagement\FileLeave;
use Sourcefit\Domain\LeaveManagement\Repository\EmployeeRepository;
use Sourcefit\Domain\LeaveManagement\Repository\LeaveRepository;

class LeaveFilingHandler implements \Sourcefit\Application\LeaveManagement\Handler\LeaveFilingHandler
{
    private $leaveRepository;
    private $employeeRepository;
  
    public function __construct(LeaveRepository $leaveRepository, EmployeeRepository $employeeRepository)
    {
        $this->leaveRepository = $leaveRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function handleThis(FileLeave $command): void
    {
        $employee = $this->employeeRepository->findOne($command->employeeId());
    
        if ($employee->leaves == 0) {
            throw new InsufficientLeaveCreditException();
        }

        $dateDiff = (new \DateTime())->diff($command->leaveDate());
        if ($dateDiff->format('%r%a') < 7) {
            throw new LateFilingOfLeaveException();
        }

        $sameDateLeave = $this->leaveRepository->checkLeaveDate($command->employeeId(), $command->leaveDate());
        if (!is_null($sameDateLeave)) {
            throw new OverlappingLeaveException();
        }
    
        $leaveData = array(
            'id' => $command->leaveId(),
            'employeeId' => $command->employeeId(),
            'leaveDate' => $command->leaveDate()
        );

        $this->leaveRepository->store(Leave::create((object) $leaveData));
        $this->employeeRepository->deductLeaveCredit($command->employeeId());
    }
}
