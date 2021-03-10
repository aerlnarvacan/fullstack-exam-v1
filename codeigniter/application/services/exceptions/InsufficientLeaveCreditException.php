<?php
namespace App\Services\Exceptions;

class InsufficientLeaveCreditException extends \Exception implements \Sourcefit\Domain\LeaveManagement\Exceptions\InsufficientLeaveCreditsException
{
}
