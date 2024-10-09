<?php

namespace App\Services;

use App\Models\Task;
use App\Services\Interfaces\TaskServiceInterface;

class TaskService implements TaskServiceInterface
{

    /**
     * @param $serviceId
     * @return mixed
     */
    public function getTasksByServiceId($serviceId)
    {
        return Task::where('id_service', $serviceId)->get();
    }
    /**
     * @param array $taskData
     * @return mixed
     */
    public function createTask(array $taskData)
    {
        return Task::create($taskData);
    }

    /**
     * @param $id
     * @param array $taskData
     * @return mixed
     */
    public function updateTask($id, array $taskData)
    {
        $task = Task::findOrFail($id);
        $task->update($taskData);
        return $task;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        return $task->delete();
    }
}
