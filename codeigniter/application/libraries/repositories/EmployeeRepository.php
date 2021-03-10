<?php
defined('BASEPATH') or exit('No direct script access allowed');

use App\Models\Employee;

class EmployeeRepository implements Sourcefit\Domain\LeaveManagement\Repository\EmployeeRepository
{
    private $employee;
    public function __construct()
    {
        $this->employee = new Employee();
    }

    public function findOne(string $employeeId): ?Sourcefit\Domain\LeaveManagement\Employee
    {
        return $this->employee->findOne(['id' => $employeeId]);
    }

    public function store(Sourcefit\Domain\LeaveManagement\Employee $employee): void
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

    public function getByUsername(string $username): ?Sourcefit\Domain\LeaveManagement\Employee
    {
        return $this->employee->findOne(['username' => $username]);
    }

    private function getByParams(array $params)
    {
        return $this->employee->getByParams($params);
    }
}
