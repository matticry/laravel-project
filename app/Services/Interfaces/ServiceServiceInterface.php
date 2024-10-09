<?php

namespace App\Services\Interfaces;

interface ServiceServiceInterface
{
    public function getAllServices();
    public function getServiceById($id);
    public function createService(array $serviceData, array $tasksData);
    public function updateService($id, array $serviceData, array $tasksData);
    public function deleteService($id);

}
