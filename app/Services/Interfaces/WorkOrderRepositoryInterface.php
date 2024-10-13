<?php

namespace App\Services\Interfaces;

interface WorkOrderRepositoryInterface
{
    public function create(array $data);
    public function addProducts(int $workOrderId, array $products);
    public function addServices(int $workOrderId, array $services);
    public function addTasks(int $workOrderId, array $tasks);

    public function getAllWorkOrders();

    public function getWorkOrderById($workOrderId);

}
