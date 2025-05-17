<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Cuenta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .ticket {
            width: 300px;
            margin: 0 auto;
            text-align: center;
        }
        .titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .detalle {
            text-align: left;
            margin-bottom: 20px;
        }
        .productos {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="titulo">Detalles del Ticket</div>
        <div class="detalle">
            <p><strong>Estación:</strong> {{ $ticketData['estacion'] }}</p>
            <p><strong>ID de la Cuenta:</strong> {{ $ticketData['cuenta_id'] }}</p>
            <p><strong>Cliente:</strong> {{ $ticketData['cliente'] }}</p>
            <p><strong>Fecha y Hora:</strong> {{ $ticketData['fecha_hora'] }}</p>
        </div>
        <div class="productos">
            <h4>Productos:</h4>
            <ul>
                @foreach ($ticketData['productos'] as $producto)
                    <li>{{ $producto->nombre }} - {{ $producto->cantidad }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>