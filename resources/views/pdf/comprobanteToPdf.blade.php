<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 800px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .header {
            background-color: #09304c;
            color: white;
            padding: 15px;
            text-align: left;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .info {
            padding: 15px;
            background-color: #f2f2f2;
            margin-bottom: 15px;
        }
        .info p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: right;
        }
        th {
            background-color: #e9ecef;
            text-align: left;
        }
        .total {
            font-weight: bold;
        }
        .salary-box {
            background-color: #0b2942;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ public_path('images/login-logo.png') }}" alt="Logo" width="600">
        <br> <br> <br>
        <h1>{{ $empresa }}</h1>
        <p>{{ $dpto }}</p>
        <p>Comprobante de Pago de Salario</p>
    </div>

    <div class="info">
        <p><strong>Nombre:</strong> {{ $nombre }}</p>
        <p><strong>Cédula:</strong> {{ $cedula }}</p>
        <p><strong>Puesto:</strong> {{ $puesto }}</p>
    </div>

<<<<<<< Updated upstream
    <h3>Ingresos</h3>
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
                <td>{{ number_format($ingreso['monto'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p class="total"><strong>Total Ingresos:</strong> {{ number_format($totalIngresos, 2, ',', '.') }}</p>

=======
<<<<<<< HEAD
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="padding-right: 10px;">
                    <h3>Ingresos</h3>
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
                                <td>{{ number_format($ingreso['monto'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <p class="total"><strong>Total Ingresos:</strong> {{ number_format($totalIngresos, 2, ',', '.') }}</p>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <div style="padding-left: 10px;">
                    <h3>Deducciones</h3>
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
                                <td>{{ number_format($deduccion['monto'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <p class="total"><strong>Total Deducciones:</strong> {{ number_format($totalDeducciones, 2, ',', '.') }}</p>
                </div>
            </td>
        </tr>
    </table>

=======
    <h3>Ingresos</h3>
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
                <td>{{ number_format($ingreso['monto'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p class="total"><strong>Total Ingresos:</strong> {{ number_format($totalIngresos, 2, ',', '.') }}</p>

>>>>>>> Stashed changes
    <h3>Deducciones</h3>
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
                <td>{{ number_format($deduccion['monto'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p class="total"><strong>Total Deducciones:</strong> {{ number_format($totalDeducciones, 2, ',', '.') }}</p>

    <div class="salary-box">
        <p>Salario Depositado: _</p>
    </div>
>>>>>>> bdb3e4fdcbae611d55cc049b477f33afa8492516
</div>
</body>
</html>
