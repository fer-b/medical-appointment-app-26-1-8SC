<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
        'route' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">

    @livewire('admin.appointment-create')

</x-admin-layout>
