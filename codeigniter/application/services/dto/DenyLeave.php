<?php
namespace App\Services\Dto;

use DateTimeInterface;

class DenyLeave implements \Sourcefit\Application\LeaveManagement\DenyLeave
{
    public $leaveId;
    public $deniedBy;
    public $deniedOn;

    public function __construct($leaveId, $deniedBy, $deniedOn)
    {
        $this->leaveId = $leaveId;
        $this->deniedBy = $deniedBy;
        $this->deniedOn = $deniedOn;
    }

    public function leaveId(): string
    {
        return $this->leaveId;
    }

    public function deniedBy(): string
    {
        return $this->deniedBy;
    }

    public function deniedOn(): DateTimeInterface
    {
        return $this->deniedOn;
    }
}
