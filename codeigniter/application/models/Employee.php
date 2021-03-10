<?php
namespace App\Models;

class Employee extends \CI_Model implements \Sourcefit\Domain\LeaveManagement\Employee
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
        $this->db->where('id', $employeeId);
        $this->db->set('leaves', 'leaves'.($type === 'INCR' ? '+1':'-1'), false);
        $this->db->update('employees');
    }

    public function findOne(array $params)
    {
        $this->db->where($params);
        $query = $this->db->get('employees', 1);

        if (empty($query->result())) {
            return null;
        }

        return Employee::create($query->result()[0]);
    }
}
