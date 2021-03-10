<?php
namespace App\Services\Dto;

use DateTimeInterface;

class ApproveLeave implements \Sourcefit\Application\LeaveManagement\ApproveLeave
{
    public $leaveId;
    public $approvedBy;
    public $approvedOn;

    public function __construct($leaveId, $approvedBy, $approvedOn)
    {
        $this->leaveId = $leaveId;
        $this->approvedBy = $approvedBy;
        $this->approvedOn = $approvedOn;
    }

    public function leaveId(): string
    {
        return $this->leaveId;
    }

    public function approvedBy(): string
    {
        return $this->approvedBy;
    }

    public function approvedOn(): DateTimeInterface
    {
        return $this->approvedOn;
    }
}
