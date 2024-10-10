<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CedulaController extends Controller
{
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
