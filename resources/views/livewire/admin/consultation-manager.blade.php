<div>
    <div class="bg-white rounded-lg shadow-md p-6 max-w-5xl mx-auto mt-6">
        
        <!-- Header: Patient Info and History Button -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $appointment->patient->user->name ?? 'Paciente' }}</h2>
                <p class="text-sm text-gray-500">
                    DNI: {{ $appointment->patient->user->id_number ?? 'No registrado' }} | 
                    Tipo de Sangre: {{ $appointment->patient->bloodType->name ?? 'No registrado' }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.patients.edit', $appointment->patient_id) }}" class="text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center">
                    <i class="fa-solid fa-file-medical mr-2"></i> Ver Historia
                </a>
                <button wire:click="openHistoryModal" type="button" class="text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Consultas Anteriores
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="me-2">
                    <button wire:click="setTab('consulta')" type="button" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'consulta' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-stethoscope w-4 h-4 me-2 {{ $activeTab === 'consulta' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Consulta
                    </button>
                </li>
                <li class="me-2">
                    <button wire:click="setTab('receta')" type="button" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'receta' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-prescription-bottle-medical w-4 h-4 me-2 {{ $activeTab === 'receta' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Receta
                    </button>
                </li>
            </ul>
        </div>

        <!-- Form for Both Tabs -->
        <form wire:submit.prevent="saveConsultation">
            
            <!-- Tab: Consulta -->
            <div class="{{ $activeTab === 'consulta' ? 'block' : 'hidden' }}">
                <div class="mb-4">
                    <label for="diagnosis" class="block mb-2 text-sm font-medium text-gray-900">Diagnóstico</label>
                    <textarea wire:model="diagnosis" id="diagnosis" rows="4" class="block p-2.5 w-full text-sm rounded-lg @error('diagnosis') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                    @error('diagnosis') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="treatment" class="block mb-2 text-sm font-medium text-gray-900">Tratamiento</label>
                    <textarea wire:model="treatment" id="treatment" rows="4" class="block p-2.5 w-full text-sm rounded-lg @error('treatment') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                    @error('treatment') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">Notas Adicionales (Opcional)</label>
                    <textarea wire:model="notes" id="notes" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                </div>
            </div>

            <!-- Tab: Receta -->
            <div class="{{ $activeTab === 'receta' ? 'block' : 'hidden' }}">
                
                @foreach($medicines as $index => $medicine)
                    <div class="flex items-center gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <div class="flex-1">
                            <label class="block mb-2 text-xs font-medium text-gray-900">Medicamento</label>
                            <input wire:model="medicines.{{ $index }}.name" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ej: Amoxicilina 500mg">
                        </div>
                        <div class="w-1/4">
                            <label class="block mb-2 text-xs font-medium text-gray-900">Dosis</label>
                            <input wire:model="medicines.{{ $index }}.dose" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ej: 1 pastilla">
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2 text-xs font-medium text-gray-900">Frecuencia / Duración</label>
                            <input wire:model="medicines.{{ $index }}.frequency" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Ej: Cada 8 horas por 7 días">
                        </div>
                        <div class="pt-6">
                            <button wire:click.prevent="removeMedicine({{ $index }})" type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <button wire:click.prevent="addMedicine" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
                    + Añadir Medicamento
                </button>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end mt-6 border-t border-gray-200 pt-6">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center inline-flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Consulta
                </button>
            </div>
        </form>
    </div>

    <!-- History Modal -->
    @if($isHistoryModalOpen)
    <div class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Consultas Anteriores
                    </h3>
                    <button wire:click="closeHistoryModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 max-h-96 overflow-y-auto">
                    <!-- Placeholder Data based on Mockup. Since there is no Consultation model yet, this iterates over mock or appointment history -->
                    @forelse($appointment->patient->appointments()->where('status', 2)->where('id', '!=', $appointment->id)->latest()->get() as $pastAppointment)
                    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <p class="font-bold text-blue-800"><i class="fa-regular fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($pastAppointment->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($pastAppointment->start_time)->format('H:i') }}</p>
                            <a href="#" class="text-xs font-medium text-blue-600 hover:underline border border-blue-600 rounded px-2 py-1">Consultar Detalle</a>
                        </div>
                        <p class="text-sm text-gray-700 mb-1"><strong>Atendido por:</strong> Dr(a). {{ $pastAppointment->doctor->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-700 mb-1"><strong>Motivo:</strong> {{ $pastAppointment->reason ?? 'No especificado' }}</p>
                        <!-- Here we would ideally show the actual diagnosis/treatment from a Consultations table, but since we don't have it explicitly requested to be created as a table, we show placeholders or reason -->
                        <p class="text-sm text-gray-600 italic">Nota: El detalle médico se guardaría en una tabla de Consultas y Recetas asociada a la cita.</p>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No hay consultas anteriores registradas para este paciente.</p>
                    @endforelse
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 border-t border-gray-200 rounded-b justify-end">
                    <button wire:click="closeHistoryModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
