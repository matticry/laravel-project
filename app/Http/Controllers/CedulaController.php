<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\WorkOrder;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CedulaController extends Controller
{

    public function getWorkOrderById($workOrderId)
    {
        $workOrder = WorkOrder::with(['services.tasks', 'details.product', 'client', 'user'])
            ->findOrFail($workOrderId);

        return response()->json($workOrder);

    }

    public function getEvents()
    {
        $events = WorkOrder::with('user')->get()->map(function ($workOrder) {
            return [
                'id' => $workOrder->wo_id,
                'title' => 'SUP: ' . $workOrder->user->us_name ?? 'Usuario no asignado',
                'start' => $workOrder->wo_start_date->format('Y-m-d\TH:i:s'),
                'end' => $workOrder->wo_final_date->format('Y-m-d\TH:i:s'),
                'extendedProps' => [
                    'workOrderStatus' => $workOrder->wo_status
                ]
            ];
        });

        return response()->json($events);
    }
    // Obtener información de una orden de trabajo
    public function JsonWorkOrder($idWorkOrder)
    {
        $workOrder = WorkOrder::with(['services.tasks', 'details', 'client', 'user'])
            ->findOrFail($idWorkOrder);

        return response()->json([
            'wo_id' => $workOrder->wo_id,
            'wo_order_code' => $workOrder->wo_order_code,
            'wo_start_date' => $workOrder->wo_start_date,
            'wo_final_date' => $workOrder->wo_final_date,
            'wo_status' => $workOrder->wo_status,
            'wo_description' => $workOrder->wo_description,
            'wo_total' => $workOrder->wo_total,
            'client' => [
                'cli_id' => $workOrder->client->us_id,
                'cli_name' => $workOrder->client->us_name,
                // Añade más campos del cliente según sea necesario
            ],
            'user' => [
                'us_id' => $workOrder->user->us_id,
                'us_name' => $workOrder->user->us_name,
                // Añade más campos del usuario según sea necesario
            ],
            'services' => $workOrder->services->map(function ($service) {
                return [
                    'wo_service_id' => $service->wo_service_id,
                    'service_id' => $service->service_id,
                    'price_service' => $service->price_service,
                    'tasks' => $service->tasks->map(function ($task) {
                        return [
                            'wo_task_id' => $task->wo_task_id,
                            'task_id' => $task->task_id,
                            'task_status' => $task->task_status,
                            // Añade más campos de la tarea según sea necesario
                        ];
                    }),
                    // Añade más campos del servicio según sea necesario
                ];
            }),
            'products' => $workOrder->details->map(function ($detail) {
                return [
                    'dwo_id' => $detail->dwo_id,
                    'pro_id' => $detail->pro_id,
                    'dwo_amount' => $detail->dwo_amount,
                    // Añade más campos del producto según sea necesario
                ];
            }),
        ]);
    }






    public function getInfoUserById($id)
    {
        $user = Profile::with('roles')->find($id);

        if (!$user) {
            return response()->json([
                'error' => 'No se encontraron los datos del usuario',
            ], 404);
        }

        return response()->json([
            'us_image' => $user->us_image,
            'is_email_verified' => !is_null($user->email_verified_at),
            'created_at' => $user->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $user->updated_at->format('d/m/Y H:i:s'),
            'us_name' => $user->us_name,
            'us_lastName' => $user->us_lastName,
            'us_email' => $user->us_email,
            'us_address' => $user->us_address,
            'us_dni' => $user->us_dni,
            'us_first_phone' => $user->us_first_phone,
            'us_second_phone' => $user->us_second_phone,
            'roles' => $user->roles()->pluck('rol_name')->toArray(),
        ]);
    }
    public function obtenerDatos($cedula)
    {
        $url = "https://datos.elixirsa.net/cedula1/$cedula";
        $client = new Client();

        try {
            $response = $client->request('GET', $url, ['verify' => false]);

            // Obtener el código de respuesta HTTP
            $response->getStatusCode();

            // Obtener el cuerpo de la respuesta
            $body = $response->getBody()->getContents();

            // Verificar el contenido en los logs
            Log::info("Respuesta de la API: " . $body);

            // Decodificar el JSON
            $data = json_decode($body, true);

            if (isset($data['nombres']) && isset($data['apellidos'])) {
                return response()->json([
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                ]);
            } else {
                return response()->json([
                    'error' => 'No se encontraron los datos requeridos',
                ], 404);
            }



        } catch (Exception $e) {
            // Registrar el error exacto para depuración
            Log::error("Error al consumir la API: " . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener los datos de la API',
            ], 500);
        }
    }



}
