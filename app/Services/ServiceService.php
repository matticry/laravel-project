<?php

namespace App\Services;

use App\Models\Service;
use App\Services\Interfaces\ServiceServiceInterface;
use App\Services\Interfaces\TaskServiceInterface;
use Illuminate\Support\Facades\DB;

class ServiceService implements ServiceServiceInterface
{

    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @return mixed
     */
    public function getAllServices()
    {
        return Service::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getServiceById($id)
    {
        return Service::findOrFail($id);
    }

    /**
     * @param array $serviceData
     * @param array $tasksData
     * @return mixed
     */
    public function createService(array $serviceData, array $tasksData)
    {
        return DB::transaction(function () use ($serviceData, $tasksData) {
            $service = Service::create($serviceData);

            foreach ($tasksData as $taskData) {
                $taskData['id_service'] = $service->id_serv;
                $this->taskService->createTask($taskData);
            }

            return $service;
        });
    }

    /**
     * @param $id
     * @param array $serviceData
     * @param array $tasksData
     * @return mixed
     */
    public function updateService($id, array $serviceData, array $tasksData)
    {
        return DB::transaction(function () use ($id, $serviceData, $tasksData) {
            $service = Service::findOrFail($id);
            $service->update($serviceData);

            // Eliminar tareas existentes y crear nuevas
            $service->tasks()->delete();
            foreach ($tasksData as $taskData) {
                $taskData['id_service'] = $service->id_serv;
                $this->taskService->createTask($taskData);
            }

            return $service;
        });
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteService($id)
    {
        return DB::transaction(function () use ($id) {
            $service = Service::findOrFail($id);
            $service->tasks()->delete();
            $service->delete();
            return true;
        });
    }
}
