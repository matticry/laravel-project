<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CedulaController extends Controller
{
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
