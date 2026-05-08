<div class="p-6 bg-white rounded-lg shadow-md max-w-4xl mx-auto mt-6">
    <div class="mb-6 pb-4 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Horarios del Dr(a). {{ $doctor->user->name }}</h2>
        <p class="text-gray-600">Especialidad: {{ $doctor->specialty }}</p>
    </div>

    <!-- Agregar Horario Formulario -->
    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Agregar Nuevo Horario</h3>
        <form wire:submit.prevent="addSchedule" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label class="block mb-2 text-sm font-medium text-gray-900">Día</label>
                <select wire:model="day" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Seleccione un día</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
                @error('day') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div class="w-32">
                <label class="block mb-2 text-sm font-medium text-gray-900">Hora Inicio</label>
                <input type="time" wire:model="start_time" class="block w-full p-2.5 text-sm rounded-lg @error('start_time') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-white border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @enderror">
                @error('start_time') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div class="w-32">
                <label class="block mb-2 text-sm font-medium text-gray-900">Hora Fin</label>
                <input type="time" wire:model="end_time" class="block w-full p-2.5 text-sm rounded-lg @error('end_time') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-white border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @enderror">
                @error('end_time') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <i class="fa-solid fa-plus mr-2"></i> Agregar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Horarios -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Día</th>
                    <th scope="col" class="px-6 py-3">Hora Inicio</th>
                    <th scope="col" class="px-6 py-3">Hora Fin</th>
                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctor->schedules as $schedule)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $schedule->day }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="deleteSchedule({{ $schedule->id }})" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2 text-center inline-flex items-center">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No hay horarios registrados para este doctor.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
