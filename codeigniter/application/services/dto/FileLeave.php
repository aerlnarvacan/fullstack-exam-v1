<?php
namespace App\Services\Dto;

use DateTimeInterface;

class FileLeave implements \Sourcefit\Application\LeaveManagement\FileLeave
{
    public $leaveId;
    public $employeeId;
    public $leaveDate;

    public function __construct($leaveId, $employeeId, $leaveDate)
    {
        $this->leaveId = $leaveId;
        $this->employeeId = $employeeId;
        $this->leaveDate = $leaveDate;
    }

    public function leaveId(): string
    {
        return $this->leaveId;
    }

    public function employeeId(): string
    {
        return $this->employeeId;
    }

    public function leaveDate(): DateTimeInterface
    {
        return $this->leaveDate;
    }
}
