<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

<header style="background-color: #f4f4f4; padding: 20px; text-align: center;">
    <h1 style="color: #1a5276; margin: 0;">Sistema de Citas Médicas</h1>
</header>

<main style="padding: 20px;">
    <h2 style="color: #2c3e50; border-bottom: 2px solid #2980b9; padding-bottom: 10px;">Historial Clínico</h2>

    <p style="font-size: 16px;">
        Estimado/a <strong>{{ $consulta->paciente->us_name }} {{ $consulta->paciente->us_lastName }}</strong>,
    </p>

    <p style="font-size: 16px;">
        Se adjunta a este correo el documento PDF con su historial clínico y resultados de exámenes correspondientes a la consulta #{{ $consulta->id_co }}.
    </p>

    <div style="background-color: #ecf0f1; border-left: 4px solid #2980b9; padding: 15px; margin: 20px 0;">
        <h3 style="color: #2980b9; margin-top: 0;">Detalles de la consulta:</h3>
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 8px;">
                <strong>Fecha:</strong> {{ $consulta->fecha_registro ? $consulta->fecha_registro->format('d/m/Y H:i') : 'Sin fecha' }}
            </li>
            <li style="margin-bottom: 8px;">
                <strong>Motivo:</strong> {{ $consulta->motivo }}
            </li>
            <li style="margin-bottom: 8px;">
                <strong>Paciente:</strong> {{ $consulta->paciente->us_name }} {{ $consulta->paciente->us_lastName }}
            </li>
            <li>
                <strong>Cédula:</strong> {{ $consulta->paciente->us_dni }}
            </li>
        </ul>
    </div>

    <p style="font-size: 16px;">
        El documento PDF adjunto contiene toda la información de su historial clínico, incluyendo los resultados de los exámenes realizados.
    </p>

    <p style="font-size: 16px;">
        Si tiene alguna consulta, no dude en contactarnos.
    </p>

    <p style="font-size: 16px;">Gracias por su confianza.</p>
</main>

<footer style="background-color: #34495e; color: #fff; text-align: center; padding: 10px; margin-top: 20px;">
    <p style="margin: 0;">&copy; {{ date('Y') }} Sistema de Citas Médicas. Todos los derechos reservados.</p>
</footer>

</body>
</html>
