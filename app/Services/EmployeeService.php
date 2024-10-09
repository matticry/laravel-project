<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use App\Services\Interfaces\EmployeeServiceInterface;

class EmployeeService implements EmployeeServiceInterface
{

    public function getAllEmployees()
    {
        return Employee::all();
    }

    public function getEmployeeById($id)
    {
        return Employee::findOrFail($id);
    }

    public function createEmployee(array $data)
    {
        return Employee::create($data);
    }

    public function updateEmployee($id, array $data)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($data);
        return $employee;
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return $employee->delete();
    }

    public function existsEmployee(string $dni): bool
    {
        return Employee::where('dni_emplo', $dni)->exists();
    }

    public function existsEmployeeByEmail(string $email): bool
    {
        return Employee::where('email_emplo', $email)->exists();
    }
}
