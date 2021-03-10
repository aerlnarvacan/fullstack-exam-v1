<?php
namespace App\Services\Handlers;

class EmployeeHandler
{
    private $employeeRepository;

    public function __construct($employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /***
     * Check username and password
     */
    public function getById(string $id)
    {
        $employee = $this->employeeRepository->findOne($id);
      
        if (is_null($employee)) {
            return null;
        }

        return $employee;
    }

    /***
     * Check username and password
     */
    public function verifyCredentialsAndGetId(string $username, string $password)
    {
        $employee = $this->employeeRepository->getByUsername($username);
      
        if (is_null($employee) || !compareHash($password, $employee->password)) {
            return null;
        }

        return $employee->id;
    }
}
