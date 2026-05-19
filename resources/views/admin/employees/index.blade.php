<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
    ],
]">

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
        <div class="flex items-center justify-between p-4 bg-white">
            <div>
                <label for="table-search" class="sr-only">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="text" id="table-search" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar">
                </div>
            </div>
            <div>
                 <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                    Columnas <i class="fa-solid fa-chevron-down ml-2"></i>
                 </button>
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">NOMBRE</th>
                    <th scope="col" class="px-6 py-3">EMAIL</th>
                    <th scope="col" class="px-6 py-3">DNI</th>
                    <th scope="col" class="px-6 py-3">TELÉFONO</th>
                    <th scope="col" class="px-6 py-3">ESPECIALIDAD</th>
                    <th scope="col" class="px-6 py-3">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($doctors as $doctor)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $doctor->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $doctor->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $doctor->user->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $doctor->user->id_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $doctor->user->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $doctor->specialty ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <!-- Green Clock button (Horarios placeholder) -->
                                <a href="{{ route('admin.doctors.schedules', $doctor) }}" class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 text-center" title="Horarios del doctor">
                                    <i class="fa-solid fa-clock"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center">No hay doctores registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-4 p-4">
            {{ $doctors->links() }}
        </div>
    </div>

</x-admin-layout>
