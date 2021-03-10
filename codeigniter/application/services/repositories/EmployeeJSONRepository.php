<?php
namespace App\Services\Repositories;

use App\Models\EmployeeJSON;

class EmployeeJSONRepository implements \Sourcefit\Domain\LeaveManagement\Repository\EmployeeRepository
{
    private $employee;
    public function __construct()
    {
        $this->employee = new EmployeeJSON();
    }

    public function findOne(string $employeeId): ?\Sourcefit\Domain\LeaveManagement\Employee
    {
        return $this->employee->findById($employeeId);
    }

    public function store(\Sourcefit\Domain\LeaveManagement\Employee $employee): void
    {
    }

    public function addLeaveCredit(string $employeeId): void
    {
        $this->employee->adjustLeaveCredit($employeeId, 'INCR');
    }

    public function deductLeaveCredit(string $employeeId): void
    {
        $this->employee->adjustLeaveCredit($employeeId, 'DECR');
    }

    public function getByUsername(string $username): ?\Sourcefit\Domain\LeaveManagement\Employee
    {
        return $this->employee->findByUsername($username);
    }

    private function getByParams(array $params)
    {
        return $this->employee->getByParams($params);
    }
}
