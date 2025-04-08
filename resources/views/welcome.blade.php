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
            padding: 0px 0;;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* IMPORTANTE: esto lo pega arriba */
            min-height: 100vh;
            box-sizing: border-box;
        }
        .container {
            margin-top: 0;
            padding-top: 0px;
        }
        .header {
            background-color: #09304c;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center; /* ✅ centra verticalmente */
            gap: 20px;
        }

        .info-text {
            flex: 1; /* ✅ permite que el texto tome espacio sin empujar al logo */
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

        .salary-box {
            background-color: #0b2942;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }

        .logo-circular {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: white;
            padding: 10px;
            object-fit: cover;
        }

        .tables-row {
            display: flex; /* Usamos flexbox para alinear las tablas en una línea */
            justify-content: space-between; /* Alinea las tablas a la derecha */
            gap: 20px; /* Espacio entre las tablas */
            width: 100%;
            flex-wrap: nowrap;
        }

        .table-container {
            flex: 1;
            width: 45%;
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #e9ecef;
            text-align: left;
        }
        .total {
            font-weight: bold;
        }
        .salary-box {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td style="color: white; vertical-align: top; text-align: left;">
                    <h1 style="margin: 0;">{{ $empresa }}</h1>
                    <p style="margin: 0;">{{ $dpto }}</p>
                    <p style="margin: 0;">Comprobante de Pago de Salario</p>
                </td>
                <td style="text-align: right;">
                    <img src="{{ public_path('images/logo_anfiteatro.png') }}" alt="Logo" class="logo-circular">
                </td>
            </tr>
        </table>
    </div>

    <div class="info">
        <p><strong>Nombre:</strong> {{ $nombre }}</p>
        <p><strong>Cédula:</strong> {{ $cedula }}</p>
        <p><strong>Puesto:</strong> {{ $puesto }}</p>
    </div>

    <<<<<<< Updated upstream
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
    =======
    <div class="tables-row">
        <div class="table-container">
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

        <div class="table-container">
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


        <div class="salary-box">
            <p class="total"><strong>Salario Depositado:</strong> {{ number_format($salarioNeto, 2, ',', '.') }}</p>
        </div>
        >>>>>>> Stashed changes
    </div>
</div>
</body>
</html>
