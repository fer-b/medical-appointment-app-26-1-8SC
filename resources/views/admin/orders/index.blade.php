<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pedidos / Reservas',
    ]
]">
    <div class="relative overflow-x-auto shadow-sm sm:rounded-xl border border-gray-100">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 bg-white">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50/75 border-b border-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">ID</th>
                    <th scope="col" class="px-6 py-4 font-bold">CLIENTE</th>
                    <th scope="col" class="px-6 py-4 font-bold">ENCARGADO</th>
                    <th scope="col" class="px-6 py-4 font-bold">FECHA</th>
                    <th scope="col" class="px-6 py-4 font-bold">DETALLE PEDIDO</th>
                    <th scope="col" class="px-6 py-4 font-bold">ESTADO</th>
                    <th scope="col" class="px-6 py-4 font-bold">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900">#{{ $order->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $order->client->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-amber-700 font-medium">{{ $order->client->clientCategory->name ?? 'General' }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $order->employee->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 font-medium text-gray-600">{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1.5">
                                @if($order->six_quantity > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 w-fit border border-amber-200">
                                        <i class="fa-solid fa-boxes-stacked text-amber-600 mr-0.5"></i> Six Pack: x{{ $order->six_quantity }}
                                    </span>
                                @endif
                                @if($order->caguama_quantity > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 w-fit border border-amber-300">
                                        <i class="fa-solid fa-bottle-beer text-amber-700 mr-0.5"></i> Caguama: x{{ $order->caguama_quantity }}
                                    </span>
                                @endif
                                @if($order->six_quantity == 0 && $order->caguama_quantity == 0)
                                    <span class="text-xs text-gray-400 italic">No especificado</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->status == 1)
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Programado</span>
                            @elseif($order->status == 2)
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">Completado</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">Cancelado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @include('admin.orders.actions', ['order' => $order])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</x-admin-layout>
