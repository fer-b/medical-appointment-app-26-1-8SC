<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
    ],
]">
    <x-slot name="action">
        <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 focus:outline-none" href="{{ route('admin.appointments.create') }}">
            + Nuevo
        </a>
    </x-slot>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">PACIENTE</th>
                    <th scope="col" class="px-6 py-3">DOCTOR</th>
                    <th scope="col" class="px-6 py-3">FECHA</th>
                    <th scope="col" class="px-6 py-3">HORA</th>
                    <th scope="col" class="px-6 py-3">HORA FIN</th>
                    <th scope="col" class="px-6 py-3">ESTADO</th>
                    <th scope="col" class="px-6 py-3">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $appointment->id }}</td>
                        <td class="px-6 py-4">{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">
                            @if($appointment->status == 1)
                                Programado
                            @elseif($appointment->status == 2)
                                Completado
                            @else
                                Cancelado
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @include('admin.appointments.actions', ['appointment' => $appointment])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>

</x-admin-layout>
