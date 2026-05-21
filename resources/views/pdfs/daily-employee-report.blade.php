<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Diario de Pedidos</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #d97706; padding-bottom: 10px; }
        .employee-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #fef3c7; color: #92400e; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        .status { font-weight: bold; text-transform: uppercase; }
        .badge { background: #f59e0b; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #b45309;">{{ isset($isFullSchedule) && $isFullSchedule ? 'Reporte Completo de Pedidos' : 'Agenda de Pedidos del Día' }}</h1>
        <p>Fecha de Reporte: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="employee-info">
        <p><strong>Maestro Cervecero / Repartidor:</strong> {{ $employee->user->name }}</p>
        <p><strong>Rol:</strong> {{ $employee->specialty ?? 'Personal de Cervecería' }}</p>
    </div>

    <h3>{{ isset($isFullSchedule) && $isFullSchedule ? 'Todos los Pedidos Asignados' : 'Pedidos a Preparar Hoy' }}</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Cliente</th>
                <th>Detalle del Pedido (Cantidades)</th>
                <th>Notas / Dirección</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                    <small>{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }}</small>
                </td>
                <td>{{ optional($order->client->user)->name ?? 'Cliente Externo' }}</td>
                <td>
                    @if($order->six_quantity > 0)
                        <span class="badge">Six Pack: x{{ $order->six_quantity }}</span><br>
                    @endif
                    @if($order->caguama_quantity > 0)
                        <span class="badge" style="background:#b45309; margin-top:2px; display:inline-block;">Caguama: x{{ $order->caguama_quantity }}</span>
                    @endif
                </td>
                <td>{{ $order->reason ?? 'Sin detalles adicionales' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">No hay pedidos asignados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este es un reporte automático generado por el Sistema de Home Brewing.</p>
    </div>
</body>
</html>
