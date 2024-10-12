<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Profile;
use App\Models\Role;
use App\Models\WorkOrder;
use App\Services\Interfaces\WorkOrderRepositoryInterface;

class WorkOrderService implements WorkOrderRepositoryInterface
{
    public function getEmployees()
    {
        $RoleEmployee = Role::where('rol_name', 'Empleado')->first();

        if(!$RoleEmployee){
            return collect();
        }

        return Profile::whereHas('roles', function ($query) use ($RoleEmployee) {
            $query->where('tbl_role.rol_id', $RoleEmployee->rol_id)
                ->where('rol_status', 'A');
        })->get([
            'us_id',
            'us_name',
            'us_lastName'
        ]);

    }
    public function getClients()
    {
        $RoleClient = Role::where('rol_name', 'Cliente')->first();

        if(!$RoleClient){
            return collect();
        }

        return Profile::whereHas('roles', function ($query) use ($RoleClient) {
            $query->where('tbl_role.rol_id', $RoleClient->rol_id)
                ->where('rol_status', 'A');
        })->get([
            'us_id',
            'us_name',
            'us_lastName'
        ]);

    }

    public function getAllWorkOrders()
    {
        return WorkOrder::with(['user', 'client', 'services.service'])->get();
    }



    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $workOrder = WorkOrder::create([
            'wo_start_date' => $data['wo_start_date'],
            'wo_final_date' => $data['wo_final_date'],
            'us_id' => $data['us_id'],
            'cli_id' => $data['cli_id'],
            'wo_description' => $data['wo_description'],
            'wo_order_code' => $this->generateOrderCode(),
            'wo_total' => $data['wo_total'],
        ]);

        return $workOrder;
    }



    public function addProducts(int $workOrderId, array $products)
    {
        $workOrder = WorkOrder::findOrFail($workOrderId);
        foreach ($products as $product) {
            $workOrder->details()->create([
                'pro_id' => $product['id'],
                'dwo_amount' => $product['quantity'],
            ]);
        }
    }

    public function addServices(int $workOrderId, array $services)
    {
        $workOrder = WorkOrder::findOrFail($workOrderId);
        foreach ($services as $service) {
            // Crear el servicio para la orden de trabajo
            $workOrderService = $workOrder->services()->create([
                'service_id' => $service['id'],
                'price_service' => $service['price']
            ]);

            // Si hay tareas asociadas al servicio, agregarlas
            if (isset($service['tasks']) && is_array($service['tasks'])) {
                foreach ($service['tasks'] as $taskId) {
                    $workOrder->tasks()->create([
                        'task_id' => $taskId,
                        'status' => 'pendiente',
                        'service_id' => $service['id'] // Asociar la tarea con el servicio
                    ]);
                }
            }
        }
    }

    public function addTasks(int $workOrderId, array $tasks)
    {
        $workOrder = WorkOrder::findOrFail($workOrderId);
        foreach ($tasks as $task) {
            $workOrder->tasks()->create([
                'task_id' => $task['id'],
                'task_status' => 'pendiente'
            ]);
        }
    }

    private function generateOrderCode(): string
    {
        $lastWorkOrder = WorkOrder::orderBy('wo_order_code', 'desc')->first();

        if (!$lastWorkOrder) {
            return 'WO-0001';
        }

        $lastCode = $lastWorkOrder->wo_order_code;
        $numericPart = intval(substr($lastCode, 3));
        $newNumericPart = $numericPart + 1;

        return 'WO-' . str_pad($newNumericPart, 4, '0', STR_PAD_LEFT);
    }

    public function existsWorkOrderByCode($code): bool
    {
        return WorkOrder::where('wo_order_code', $code)->exists();

    }

    public function updateStock($productId, $quantity)
    {

        $product = Product::findOrFail($productId);
        $product->pro_amount = $product->pro_amount - $quantity;
        $product->save();

    }
}