<?php
namespace App\Services\Handlers;

class LeaveQueryHandler
{
    private $leaveRepository;

    public function __construct($leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    /***
     * Get Employee Leaves
     */
    public function getLeaves(string $employeeId = null)
    {
        return $this->leaveRepository->getEmployeeLeaves($employeeId);
    }
}
