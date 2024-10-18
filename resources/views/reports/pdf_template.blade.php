<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de la Orden de Trabajo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .title {
            color: #0066cc;
            font-size: 24px;
            font-weight: bold;
        }
        .logo {
            text-align: right;
        }
        .logo img {
            max-width: 150px;
        }
        .company-info {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .client-info {
            margin-top: 50px;
            border-collapse: collapse;
            width: 50%;
        }
        .client-info th {
            background-color: #4a86e8;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #fff;
        }
        .client-info td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .client-info td:first-child {
            width: 30%;
        }
        .section-title {
            width: 50%;
        }
        .order-details {
            border-collapse: collapse;
            max-width: 450px;
            margin-top: -50px;
            float: right;
        }
        .order-details th {
            background-color: #4a86e8;
            color: white;
            font-weight: normal;
            padding: 8px;
            text-align: center;
            border: 1px solid #fff;
        }
        .order-details td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .section-title {
            background-color: #0066cc;
            color: white;
            padding: 5px 10px;
        }
        .technician-info {
            position: absolute;
            right: 0;
            top: 30%;
            border-collapse: collapse;
            width: 350px;
            margin-right: 20px;
            margin-top: 20px;
        }
        .technician-info th {
            background-color: #4a86e8;
            color: white;
            font-weight: normal;
            padding: 8px;
            border: 1px solid #fff;
            text-align: center;
        }
        .technician-info td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .service-details {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        .service-details th {
            background-color: #4a86e8;
            color: white;
            font-weight: normal;
            padding: 8px;
            text-align: left;
            border: 1px solid #fff;
        }
        .service-details td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .service-details td:nth-child(2) {
            text-align: right;
        }
        .service-details .total {
            text-align: right;
            font-weight: bold;
        }
        .product-details {
            border-collapse: collapse;
            width: 550px;
            height: 41px;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        .product-details th {
            background-color: #4a86e8;
            color: white;
            font-weight: normal;
            padding: 8px;
            text-align: center;
            border: 1px solid #fff;
        }
        .product-details td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 50px;
            margin-bottom: 30px;
        }
        .signature-line {
            width: 40%;
            text-align: center;
        }
        .signature-line hr {
            border: none;
            border-top: 1px solid #000;
            margin-bottom: 5px;
            width: 80%; /* Hace la línea más corta */
            margin-left: auto;
            margin-right: auto;
        }
        .signature-line p {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }
        .signature-image {
            max-width: 200px;
            height: auto;
            margin-bottom: 5px;
        }
        .thank-you {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }

        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }
            .header {
                flex-direction: column;
                align-items: center;
            }
            .title {
                text-align: center;
                margin-bottom: 10px;
            }
            .logo {
                text-align: center;
            }
            .client-info, .order-details, .technician-info, .product-details {
                width: 100%;
                float: none;
                margin: 20px 0;
            }
            .technician-info {
                position: static;
            }
            .signatures {
                flex-direction: column;
                align-items: center;
            }
            .signature-line {
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <div class="title">REPORTE DE LA ORDEN DE TRABAJO</div>
    <div class="logo">
        <img src="{{ public_path('assets/img/logo.png') }}" alt="limpieza inteligente">
    </div>
</div>

<div class="company-info">
    <div>Dirección: Calle Principal 123, Ciudad Ejemplo</div>
    <div>Correo Electrónico: info@limpiezainteligente.com</div>
    <div>Teléfono: (123) 456-7890</div>
</div>

<table class="order-details">
    <tr>
        <th>Código de la Orden</th>
        <th>Fecha de Creación</th>
    </tr>
    <tr>
        <td>{{ $report->workOrder->wo_order_code ?? 'N/A' }}</td>
        <td>{{ $report->workOrder->wo_start_date ? \Carbon\Carbon::parse($report->workOrder->wo_start_date)->format('d \d\e M \d\e\l Y H:i') : 'N/A' }}</td>
    </tr>
</table>

<table class="client-info">
    <tr>
        <th colspan="2">Datos del Cliente:</th>
    </tr>
    <tr>
        <td>Nombres:</td>
        <td>{{ $report->workOrder->client->us_name ?? 'No especificado' }}</td>
    </tr>
    <tr>
        <td>Apellidos:</td>
        <td>{{ $report->workOrder->client->us_lastName ?? 'No especificado' }}</td>
    </tr>
    <tr>
        <td>Cédula:</td>
        <td>{{ $report->workOrder->client->us_dni ?? 'No especificado' }}</td>
    </tr>
    <tr>
        <td>Dirección del domicilio:</td>
        <td>{{ $report->workOrder->client->us_address ?? 'No especificado' }}</td>
    </tr>
</table>

<table class="technician-info">
    <tr>
        <th>Número del Técnico</th>
        <th>Nombre del Técnico</th>
    </tr>
    <tr>
        <td>{{ $report->workOrder->user->us_id ?? 'N/A' }}</td>
        <td>{{ ($report->workOrder->user->us_name ?? '') . ' ' . ($report->workOrder->user->us_lastName ?? '') ?: 'No especificado' }}</td>
    </tr>
</table>

<table class="service-details">
    <tr>
        <th>Descripción:</th>
        <th>Valor Unitario</th>
    </tr>
    @forelse($report->workOrder->services as $service)
        <tr>
            <td>{{ $service->service->name_serv ?? 'Servicio no especificado' }}</td>
            <td>${{ number_format($service->price_service ?? 0, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2">No hay servicios registrados</td>
        </tr>
    @endforelse
    <tr>
        <td colspan="2" class="total">TOTAL: ${{ number_format($report->workOrder->wo_total ?? 0, 2) }}</td>
    </tr>
</table>

<table class="product-details">
    <tr>
        <th>Código del Producto</th>
        <th>Nombre del Producto</th>
        <th>Cantidad</th>
    </tr>
    @forelse($report->workOrder->usedProducts as $usedProduct)
        <tr>
            <td>{{ $usedProduct->product->pro_code ?? 'N/A' }}</td>
            <td>{{ $usedProduct->product->pro_name ?? 'Producto no especificado' }}</td>
            <td>{{ $usedProduct->up_amount ?? 0 }} unidad(es)</td>
        </tr>
    @empty
        <tr>
            <td colspan="3">No hay productos registrados</td>
        </tr>
    @endforelse
</table>
@php
    $slimBaseUrl = 'https://slim-api-project-e1fc80b7d790.herokuapp.com';
@endphp

<div class="signatures">
    <div class="signature-line">
        @if($report->signature_report)
            <img src="{{ $slimBaseUrl . $report->signature_report }}" alt="Firma del Técnico" class="signature-image">
        @else
            <hr style="width: 80%; border-top: 1px solid #000; margin: 30px auto;">
        @endif
        <p>Firma del Técnico</p>
    </div>
    <div class="signature-line">
        @if($report->signature_client_report)
            <img src="{{ $slimBaseUrl . $report->signature_client_report }}" alt="Firma del Cliente" class="signature-image">
        @else
            <hr style="width: 80%; border-top: 1px solid #000; margin: 30px auto;">
        @endif
        <p>Firma del Cliente</p>
    </div>
</div>

<div class="thank-you">
    <p>Gracias por usar nuestros servicios</p>
</div>
</body>
</html>
