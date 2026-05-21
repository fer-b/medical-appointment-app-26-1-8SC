<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pedido</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #d97706; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #b45309; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        .details-table th { background-color: #fef3c7; font-weight: bold; width: 30%; color: #92400e; }
        .footer { text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Comprobante de Pedido Cervecero</h1>
            <p>Home Brewing - Cerveza Artesanal</p>
        </div>
        
        <table class="details-table">
            <tr>
                <th>Cliente</th>
                <td>{{ $order->client->user->name }} ({{ $order->client->clientCategory->name ?? 'General' }})</td>
            </tr>
            <tr>
                <th>Encargado de Preparación</th>
                <td>{{ $order->employee->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Fecha de Entrega</th>
                <td>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Cargamento</th>
                <td>
                    @php $items = []; @endphp
                    @if($order->six_quantity > 0)
                        @php $items[] = "Six Pack: x" . $order->six_quantity . " unidades"; @endphp
                    @endif
                    @if($order->caguama_quantity > 0)
                        @php $items[] = "Caguama (940ml): x" . $order->caguama_quantity . " unidades"; @endphp
                    @endif
                    {{ count($items) > 0 ? implode(', ', $items) : 'Ninguno especificado' }}
                </td>
            </tr>
            <tr>
                <th>Notas del Pedido</th>
                <td>{{ $order->reason ?? 'Ninguna nota especial' }}</td>
            </tr>
        </table>
        
        <div class="footer">
            <p>Por favor, recuerde que su pedido estará listo en la fecha indicada.</p>
            <p>¡Gracias por elegir a Home Brewing!</p>
        </div>
    </div>
</body>
</html>
