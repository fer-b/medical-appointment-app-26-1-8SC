<x-admin-layout title="Clientes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Clientes',
    ],
]">

    @livewire('admin.datatables.client-table')

</x-admin-layout>