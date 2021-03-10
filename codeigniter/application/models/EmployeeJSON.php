<?php
namespace App\Models;

use App\Services\Handlers\JSONHandler;

class EmployeeJSON implements \Sourcefit\Domain\LeaveManagement\Employee
{
    public $id;
    public $username;
    public $password;
    public $firstName;
    public $lastName;
    public $status;
    public $leaves;
    public $role;
    public $createdAt;
    public $updatedAt;
    
    private $STORAGE_PATH = APPPATH.'../storage/';
    private $EMPLOYEES_FILE = 'employees.json';

    public static function create($employeeData): Employee
    {
        $employee = new Employee();
        $employee->id = $employeeData->id ?? null;
        $employee->username = $employeeData->username ?? null;
        $employee->password = $employeeData->password ?? null;
        $employee->firstName = $employeeData->firstName ?? null;
        $employee->lastName = $employeeData->lastName ?? null;
        $employee->status = $employeeData->status ?? null;
        $employee->leaves = $employeeData->leaves ?? null;
        $employee->role = $employeeData->role ?? null;
        $employee->createdAt = $employeeData->createdAt ?? null;
        $employee->updatedAt = $employeeData->updatedAt ?? null;
        return $employee;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function adjustLeaveCredit(string $employeeId, string $type): void
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $employees = $jsonHandler->read($this->EMPLOYEES_FILE);
        
        if ($type === 'INCR') {
            $employees[$employeeId]['leaves'] += 1;
        } else {
            $employees[$employeeId]['leaves'] -= 1;
        }

        $jsonHandler->store($this->EMPLOYEES_FILE, $employees);
    }

    public function findAll()
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        return $jsonHandler->read($this->EMPLOYEES_FILE);
    }
    
    public function findById(string $id)
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $employees = $jsonHandler->read($this->EMPLOYEES_FILE);

        foreach ($employees as $employeeId => $details) {
            if ($employeeId === $id) {
                return Employee::create((object)array_merge(['id' => $employeeId], $details));
            }
        }

        return null;
    }

    public function findByUsername(string $username)
    {
        $jsonHandler = new JSONHandler($this->STORAGE_PATH);
        $employees = $jsonHandler->read($this->EMPLOYEES_FILE);

        foreach ($employees as $employeeId => $details) {
            if ($details["username"] === $username) {
                return Employee::create((object)array_merge(['id' => $employeeId], $details));
            }
        }

        return null;
    }
}
