<div>
    <h2>Reporte Diario de Pedidos</h2>
    <p>Hola, administrador.</p>
    <p>A continuación, se presenta la lista general de pedidos programados para el día de hoy:</p>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th>Hora</th>
                <th>Cliente</th>
                <th>Empleado</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }}</td>
                    <td>{{ $order->client->user->name }}</td>
                    <td>{{ $order->employee->user->name }}</td>
                    <td>{{ $order->reason ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No hay pedidos programados para hoy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
