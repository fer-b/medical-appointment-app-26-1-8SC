<div class="mt-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Buscar disponibilidad</h2>
        <p class="text-sm text-gray-500">Encuentra el horario perfecto para tu pedido.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Search & Available Doctors -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <form wire:submit.prevent="searchAvailability" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Fecha</label>
                        <input wire:model="searchDate" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Hora</label>
                        <select wire:model="searchTime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Cualquier hora</option>
                            <option value="08:00">08:00 - 12:00</option>
                            <option value="12:00">12:00 - 16:00</option>
                            <option value="16:00">16:00 - 20:00</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Rol (opcional)</label>
                        <input wire:model="searchSpecialty" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej: Maestro Cervecero">
                    </div>
                    <div>
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Buscar disponibilidad
                        </button>
                    </div>
                </form>
            </div>

            <!-- Employee Cards -->
            <div class="space-y-4">
                @forelse($availableEmployees as $employee)
                    <div class="bg-white rounded-lg shadow p-6 border {{ $selectedEmployee && $selectedEmployee->id === $employee->id ? 'border-blue-500 ring-1 ring-blue-500' : 'border-gray-200' }}">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                {{ strtoupper(substr($employee->user->name ?? 'E', 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $employee->user->name ?? 'Empleado sin nombre' }}</h3>
                                <p class="text-sm text-blue-600">{{ $employee->specialty ?? 'General' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Horarios disponibles:</p>
                            <div class="flex flex-wrap gap-2">
                                <!-- Mocked time slots for the mockup since there is no schedule module -->
                                @foreach(['08:00:00', '09:00:00', '10:30:00', '14:00:00'] as $time)
                                    <button wire:click="selectTimeSlot({{ $employee->id }}, '{{ $time }}')" type="button" 
                                            class="{{ $selectedEmployee && $selectedEmployee->id === $employee->id && $selectedTime === $time ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }} focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center transition-colors">
                                        {{ \Carbon\Carbon::parse($time)->format('H:i') }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 bg-white rounded-lg shadow">
                        No se encontraron empleados disponibles con esos criterios.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- Right Column: Resumen de la cita -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Resumen del pedido</h3>
                
                <form wire:submit.prevent="confirmAppointment">
                    <!-- Read-only Summary Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Empleado:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $selectedEmployee ? $selectedEmployee->user->name : 'No seleccionado' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Fecha:</span>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900 block">{{ $searchDate ? \Carbon\Carbon::parse($searchDate)->format('Y-m-d') : '-' }}</span>
                                @error('searchDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Horario:</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if($selectedTime)
                                    {{ \Carbon\Carbon::parse($selectedTime)->format('H:i') }} - {{ \Carbon\Carbon::parse($selectedTime)->addMinutes(15)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Duración:</span>
                            <span class="text-sm font-medium text-gray-900">15 minutos</span>
                        </div>
                    </div>

                    <!-- Input Fields -->
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Cliente</label>
                        <select wire:model="client_id" class="block w-full p-2.5 text-sm rounded-lg @error('client_id') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @enderror">
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->user->name ?? 'Cliente #'.$client->id }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Motivo del pedido</label>
                        <textarea wire:model="reason" rows="3" class="block p-2.5 w-full text-sm rounded-lg @error('reason') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="Ej: Compra de insumos"></textarea>
                        @error('reason') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Validation Errors for Selection -->
                    @error('selectedEmployee') <span class="text-xs text-red-600 mb-2 block text-center">{{ $message }}</span> @enderror
                    @error('selectedTime') <span class="text-xs text-red-600 mb-2 block text-center">{{ $message }}</span> @enderror

                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Confirmar pedido
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
