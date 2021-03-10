<?php
namespace App\Services\Dto;

use DateTimeInterface;

class CancelLeave implements \Sourcefit\Application\LeaveManagement\CancelLeave
{
    public $leaveId;
    public $approvedBy;
    public $cancelledOn;

    public function __construct($leaveId, $approvedBy, $cancelledOn)
    {
        $this->leaveId = $leaveId;
        $this->approvedBy = $approvedBy;
        $this->cancelledOn = $cancelledOn;
    }

    public function leaveId(): string
    {
        return $this->leaveId;
    }

    public function approvedBy(): string
    {
        return $this->approvedBy;
    }

    public function cancelledOn(): DateTimeInterface
    {
        return $this->cancelledOn;
    }
}
