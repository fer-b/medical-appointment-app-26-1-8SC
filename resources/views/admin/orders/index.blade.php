<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pedidos / Reservas',
    ],
]">
    <x-slot name="action">
        <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 focus:outline-none" href="{{ route('admin.orders.create') }}">
            + Nuevo
        </a>
    </x-slot>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">CLIENTE</th>
                    <th scope="col" class="px-6 py-3">EMPLEADO</th>
                    <th scope="col" class="px-6 py-3">FECHA</th>
                    <th scope="col" class="px-6 py-3">HORA</th>
                    <th scope="col" class="px-6 py-3">HORA FIN</th>
                    <th scope="col" class="px-6 py-3">ESTADO</th>
                    <th scope="col" class="px-6 py-3">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $order->id }}</td>
                        <td class="px-6 py-4">{{ $order->client->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $order->employee->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->end_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">
                            @if($order->status == 1)
                                Programado
                            @elseif($order->status == 2)
                                Completado
                            @else
                                Cancelado
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
