<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\WorkOrder;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CedulaController extends Controller
{
    public function getEvents()
    {
        $events = WorkOrder::with('user')->get()->map(function ($workOrder) {
            return [
                'id' => $workOrder->wo_id,
                'title' => 'SUP: ' . $workOrder->user->us_name,
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
        $workOrder = WorkOrder::with(['services.tasks', 'details.product', 'client', 'employee'])
            ->findOrFail($idWorkOrder);

        return response()->json([
            'wo_id' => $workOrder->wo_id,
            'wo_order_code' => $workOrder->wo_order_code,
            'wo_start_date' => $workOrder->wo_start_date,
            'wo_final_date' => $workOrder->wo_final_date,
            'wo_status' => $workOrder->wo_status,
            'wo_description' => $workOrder->wo_description,
            'pdf_report' => $workOrder->pdf_report,
            'wo_total' => $workOrder->wo_total,
            'cliente' => [
                'cli_id' => $workOrder->cli_id,
                'us_name' => $workOrder->client->us_name,
                'us_lastName' => $workOrder->client->us_lastName,
                'us_email' => $workOrder->client->us_email,
                'us_address' => $workOrder->client->us_address,
                'us_dni' => $workOrder->client->us_dni,
                'us_first_phone' => $workOrder->client->us_first_phone,
                'us_second_phone' => $workOrder->client->us_second_phone,
                'us_image' => $workOrder->client->us_image,
                'us_status' => $workOrder->client->us_status,
            ],
            'empleado' => [
                'us_id' => $workOrder->employee->us_id,
                'us_name' => $workOrder->employee->us_name,
                'us_lastName' => $workOrder->employee->us_lastName,
                'us_email' => $workOrder->employee->us_email,
                'us_address' => $workOrder->employee->us_address,
                'us_dni' => $workOrder->employee->us_dni,
                'us_first_phone' => $workOrder->employee->us_first_phone,
                'us_second_phone' => $workOrder->employee->us_second_phone,
                'us_image' => $workOrder->employee->us_image,
                'us_status' => $workOrder->employee->us_status,
                'created_at' => $workOrder->employee->created_at,
                'email_verified_at' => $workOrder->employee->email_verified_at,
            ],
            'details' => $workOrder->details->map(function ($detail) {
                return [
                    'dwo_id' => $detail->dwo_id,
                    'pro_id' => $detail->pro_id,
                    'product_name' => $detail->product->pro_name,
                    'dwo_amount' => $detail->dwo_amount,
                    'product_price' => $detail->product->pro_price,
                ];
            }),
            'services' => $workOrder->services->map(function ($service) {
                return [
                    'wo_service_id' => $service->wo_service_id,
                    'service_id' => $service->service_id,
                    'service_name' => $service->service->name_serv,
                    'price_service' => $service->price_service,
                    'tasks' => $service->tasks->map(function ($task) {
                        return [
                            'wo_task_id' => $task->wo_task_id,
                            'task_id' => $task->task_id,
                            'task_name' => $task->task->name_task,
                            'task_status' => $task->task_status,
                        ];
                    }),
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
            $statusCode = $response->getStatusCode();

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
