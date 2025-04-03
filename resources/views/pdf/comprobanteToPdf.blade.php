<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante Salarial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2, p {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
<h1>Comprobante Salarial</h1>

<p><strong>Nombre:</strong> {{ $nombre }}</p>
<p><strong>Cédula:</strong> {{ $cedula }}</p>
<p><strong>Puesto:</strong> {{ $puesto }}</p>

<h2>Ingresos</h2>
<table>
    <thead>
    <tr>
        <th>Concepto</th>
        <th>Monto</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($ingresos as $ingreso)
        <tr>
            <td>{{ $ingreso['nombre'] }}</td>
            <td>{{ number_format($ingreso['monto'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p><strong>Total Ingresos:</strong> {{ number_format($totalIngresos, 2) }}</p>

<h2>Deducciones</h2>
<table>
    <thead>
    <tr>
        <th>Concepto</th>
        <th>Monto</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($deducciones as $deduccion)
        <tr>
            <td>{{ $deduccion['nombre'] }}</td>
            <td>{{ number_format($deduccion['monto'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p><strong>Total Deducciones:</strong> {{ number_format($totalDeducciones, 2) }}</p>
</body>
</html>
