<?php

namespace App\Services\Interfaces;

interface EmployeeServiceInterface
{

    public function getAllEmployees();
    public function getEmployeeById($id);
    public function createEmployee(array $data);
    public function updateEmployee($id, array $data);
    public function deleteEmployee($id);
}
