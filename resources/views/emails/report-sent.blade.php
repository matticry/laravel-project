<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Trabajo</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
<header style="background-color: #f4f4f4; padding: 20px; text-align: center;">
    <img src="https://i0.wp.com/limpiezainteligente.com.ec/wp-content/uploads/2023/12/image-1.png?fit=175%2C77&ssl=1" alt="Logo de la empresa" style="max-width: 200px;">
</header>

<main style="padding: 20px;">
    <h1 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Reporte de Trabajo</h1>

    <p style="font-size: 16px;">Estimado cliente {{ $report->workOrder->client->us_name ?? 'No especificado' }},</p>

    <p style="font-size: 16px;">Adjunto encontrará el reporte de trabajo para la orden #{{ $report->workOrder->wo_id }}.</p>

    <div style="background-color: #ecf0f1; border-left: 4px solid #3498db; padding: 15px; margin: 20px 0;">
        <h2 style="color: #2980b9; margin-top: 0;">Detalles del reporte:</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 10px;">
                <strong>ID del reporte:</strong> {{ $report->id_report }}
            </li>
            <li style="margin-bottom: 10px;">
                <strong>Fecha:</strong> {{ $report->created_at->format('d/m/Y') }}
            </li>
            <li style="margin-bottom: 10px;">
                <strong>Datos del Técnico:</strong><br>
                Nombre: {{ $report->workOrder->user->us_name ?? 'No especificado' }} {{ $report->workOrder->user->us_lastName ?? '' }}<br>
                Correo: {{ $report->workOrder->user->us_email ?? 'No especificado' }}
            </li>
        </ul>
    </div>

    <p style="font-size: 16px;">Se adjunta a este correo el PDF con los detalles completos de su orden de trabajo.</p>

    <p style="font-size: 16px;">Gracias por su confianza.</p>
</main>

<footer style="background-color: #34495e; color: #fff; text-align: center; padding: 10px; margin-top: 20px;">
    <p style="margin: 0;">&copy; {{ date('Y') }} Tu Empresa. Todos los derechos reservados.</p>
</footer>
</body>
</html>
