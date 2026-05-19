<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pedidos / Reservas',
        'route' => route('admin.orders.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">

    @livewire('admin.order-create')

</x-admin-layout>
