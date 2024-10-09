<?php

namespace App\Services\Interfaces;

interface TaskServiceInterface
{

    public function getTasksByServiceId($serviceId);
    public function createTask(array $taskData);
    public function updateTask($id, array $taskData);
    public function deleteTask($id);

}
