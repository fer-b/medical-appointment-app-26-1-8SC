<div>
    @if(isset($isFullSchedule) && $isFullSchedule)
        <h2>Tu Agenda Completa de Pedidos</h2>
        <p>Hola, {{ $employee->user->name }}.</p>
        <p>A continuación, se presenta la lista completa de sus próximos pedidos programados:</p>
    @else
        <h2>Tus Pedidos Programados para Hoy</h2>
        <p>Hola, {{ $employee->user->name }}.</p>
        <p>A continuación, se presenta la lista de clientes que atenderá el día de hoy:</p>
    @endif
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente / Cliente</th>
                <th>Motivo de Pedido</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->end_time)->format('H:i') }}</td>
                    <td>{{ $order->client->user->name }}</td>
                    <td>{{ $order->reason ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No tiene pedidos programados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
