<div>
    <div class="bg-white rounded-lg shadow-md p-6 max-w-5xl mx-auto mt-6">
        
        <!-- Header: Patient Info and History Button -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $order->client->user->name ?? 'Cliente' }}</h2>
                <p class="text-sm text-gray-500">
                    DNI: {{ $order->client->user->id_number ?? 'No registrado' }} | 
                    Email: {{ $order->client->user->email ?? 'No registrado' }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.clients.edit', $order->client_id) }}" class="text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center">
                    <i class="fa-solid fa-address-card mr-2"></i> Ver Perfil
                </a>
                <button wire:click="openHistoryModal" type="button" class="text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Pedidos Anteriores
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="me-2">
                    <button wire:click="setTab('detalles')" type="button" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'detalles' ? 'text-amber-600 border-amber-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-clipboard-list w-4 h-4 me-2 {{ $activeTab === 'detalles' ? 'text-amber-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Detalles del Pedido
                    </button>
                </li>
                <li class="me-2">
                    <button wire:click="setTab('ticket')" type="button" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'ticket' ? 'text-amber-600 border-amber-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-receipt w-4 h-4 me-2 {{ $activeTab === 'ticket' ? 'text-amber-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Ticket / Productos
                    </button>
                </li>
            </ul>
        </div>

        <!-- Form for Both Tabs -->
        <form wire:submit.prevent="saveConsultation">
            
            <!-- Tab: Consulta -->
            <div class="{{ $activeTab === 'detalles' ? 'block' : 'hidden' }}">
                <div class="mb-4">
                    <label for="details" class="block mb-2 text-sm font-medium text-gray-900">Detalles del Pedido</label>
                    <textarea wire:model="details" id="details" rows="4" class="block p-2.5 w-full text-sm rounded-lg @error('details') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-gray-50 border border-gray-300 text-gray-900 focus:ring-amber-500 focus:border-amber-500 @enderror" placeholder="Ej: Pedido para fiesta de cumpleaños..."></textarea>
                    @error('details') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="extra_notes" class="block mb-2 text-sm font-medium text-gray-900">Notas de Preparación / Envío</label>
                    <textarea wire:model="extra_notes" id="extra_notes" rows="4" class="block p-2.5 w-full text-sm rounded-lg @error('extra_notes') bg-red-50 border border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else bg-gray-50 border border-gray-300 text-gray-900 focus:ring-amber-500 focus:border-amber-500 @enderror" placeholder="Ej: Enviar frío, llevar a la puerta trasera..."></textarea>
                    @error('extra_notes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900">Comentarios Adicionales (Opcional)</label>
                    <textarea wire:model="notes" id="notes" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-amber-500 focus:border-amber-500" placeholder="Agregue comentarios extra..."></textarea>
                </div>
            </div>

            <!-- Tab: Ticket -->
            <div class="{{ $activeTab === 'ticket' ? 'block' : 'hidden' }}">
                
                @foreach($products as $index => $product)
                    <div class="flex items-center gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <div class="flex-1">
                            <label class="block mb-2 text-xs font-medium text-gray-900">Producto (Cerveza / Insumo)</label>
                            <input wire:model="products.{{ $index }}.name" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2" placeholder="Ej: Session IPA">
                        </div>
                        <div class="w-1/4">
                            <label class="block mb-2 text-xs font-medium text-gray-900">Cantidad</label>
                            <input wire:model="products.{{ $index }}.dose" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2" placeholder="Ej: 2 Cajas">
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2 text-xs font-medium text-gray-900">Presentación</label>
                            <input wire:model="products.{{ $index }}.frequency" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2" placeholder="Ej: Botella 355ml">
                        </div>
                        <div class="pt-6">
                            <button wire:click.prevent="removeProduct({{ $index }})" type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <button wire:click.prevent="addProduct" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">
                    + Añadir Producto
                </button>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end mt-6 border-t border-gray-200 pt-6">
                <button type="submit" class="text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center inline-flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Pedido
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
                        Pedidos Anteriores
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
                    @forelse($order->client->orders()->where('status', 2)->where('id', '!=', $order->id)->latest()->get() as $pastOrder)
                    <div class="p-4 border border-amber-200 rounded-lg bg-amber-50 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <p class="font-bold text-amber-800"><i class="fa-regular fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($pastOrder->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($pastOrder->start_time)->format('H:i') }}</p>
                            <a href="#" class="text-xs font-medium text-amber-600 hover:underline border border-amber-600 rounded px-2 py-1">Ver Detalles</a>
                        </div>
                        <p class="text-sm text-gray-700 mb-1"><strong>Atendido por:</strong> {{ $pastOrder->employee->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-700 mb-1"><strong>Motivo:</strong> {{ $pastOrder->reason ?? 'No especificado' }}</p>
                        <p class="text-sm text-gray-600 italic">Nota: El detalle de los productos se guardaría en una tabla de Pedidos y Tickets.</p>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No hay pedidos anteriores registrados para este cliente.</p>
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
