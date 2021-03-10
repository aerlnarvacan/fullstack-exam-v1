<?php
namespace App\Services\Factories;

use App\Services\Repositories\EmployeeRepository;
use App\Services\Repositories\EmployeeJSONRepository;

class EmployeeRepositoryFactory
{
    public static function getInstance(string $type = null): \Sourcefit\Domain\LeaveManagement\Repository\EmployeeRepository
    {
        switch ($type) {
            case 'database':
                return new EmployeeRepository();
            case 'json':
                return new EmployeeJSONRepository();
            default:
                throw new BadFunctionCallException();
        }
    }
}
