<?php
namespace App\Services\Factories;

use App\Services\Repositories\LeaveRepository;
use App\Services\Repositories\LeaveJSONRepository;

class LeaveRepositoryFactory
{
    public static function getInstance(string $type = null): \Sourcefit\Domain\LeaveManagement\Repository\LeaveRepository
    {
        switch ($type) {
            case 'database':
                return new LeaveRepository();
            case 'json':
                return new LeaveJSONRepository();
            default:
                throw new BadFunctionCallException();
        }
    }
}
