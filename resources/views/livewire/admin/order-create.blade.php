<div class="mt-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Registrar Nuevo Pedido</h2>
        <p class="text-sm text-gray-500">Selecciona el cliente y los formatos de cerveza a ordenar con stock disponible en tiempo real.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Client, Date & Beer Selection -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Section 1: Client and Date Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-amber-500"></i> Información General
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Cliente</label>
                        <select wire:model.live="client_id" class="block w-full p-3 text-sm rounded-lg border {{ $errors->has('client_id') ? 'bg-red-50 border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-amber-500 focus:border-amber-500' }}">
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->user->name ?? 'Cliente #'.$client->id }} ({{ $client->clientCategory->name ?? 'General' }})</option>
                            @endforeach
                        </select>
                        @error('client_id') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Fecha del Pedido</label>
                        <input wire:model.live="searchDate" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-3" required>
                        @error('searchDate') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Beer Stock Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-beer-mug-empty text-amber-500"></i> Selección de Cervezas
                    </h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-800 rounded-full">
                        Stock Real
                    </span>
                </div>

                @error('beer_selection')
                    <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                        <span class="font-bold">Error:</span> {{ $message }}
                    </div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Format 1: Six Pack -->
                    <div class="relative flex flex-col justify-between p-6 bg-white rounded-2xl border-2 {{ $orderSix ? 'border-amber-500 bg-amber-50/20' : 'border-gray-100 hover:border-gray-200' }} transition-all duration-300 shadow-sm">
                        <div>
                            <!-- Header Info -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-amber-100/50 rounded-xl text-amber-800">
                                    <i class="fa-solid fa-boxes-stacked text-2xl"></i>
                                </div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="orderSix" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </div>

                            <h4 class="text-xl font-bold text-gray-900">Six Pack</h4>
                            <p class="text-xs text-gray-500 mb-4">6 piezas de 355 ml (Lata)</p>
                            
                            <div class="flex justify-between items-center py-2 border-t border-b border-gray-100/50 my-4">
                                <span class="text-sm font-semibold text-gray-600">Stock disponible:</span>
                                <span class="text-sm font-bold text-amber-800">{{ $stockSix }} packs</span>
                            </div>
                        </div>

                        <!-- Quantity Selector (Only active if selected) -->
                        <div class="mt-4">
                            @if($orderSix)
                                <label class="block mb-2 text-xs font-bold text-amber-900 uppercase tracking-wider">Cantidad a Pedir</label>
                                <div class="flex items-center justify-between bg-white border border-amber-200 rounded-xl p-1 shadow-sm">
                                    <button type="button" wire:click="decrementSix" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold transition-all">-</button>
                                    <input type="number" wire:model.live="sixQty" class="w-16 text-center border-0 p-0 font-bold text-gray-900 focus:ring-0" min="1" max="{{ $stockSix }}">
                                    <button type="button" wire:click="incrementSix" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold transition-all">+</button>
                                </div>
                                @error('sixQty') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                            @else
                                <div class="text-center py-3 text-sm text-gray-400 font-medium bg-gray-50 rounded-xl">
                                    Activa el interruptor para agregar
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Format 2: Caguama -->
                    <div class="relative flex flex-col justify-between p-6 bg-white rounded-2xl border-2 {{ $orderCaguama ? 'border-amber-500 bg-amber-50/20' : 'border-gray-100 hover:border-gray-200' }} transition-all duration-300 shadow-sm">
                        <div>
                            <!-- Header Info -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-amber-100/50 rounded-xl text-amber-800">
                                    <i class="fa-solid fa-bottle-beer text-2xl"></i>
                                </div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="orderCaguama" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </div>

                            <h4 class="text-xl font-bold text-gray-900">Caguama</h4>
                            <p class="text-xs text-gray-500 mb-4">Envase retornable de 940 ml</p>
                            
                            <div class="flex justify-between items-center py-2 border-t border-b border-gray-100/50 my-4">
                                <span class="text-sm font-semibold text-gray-600">Stock disponible:</span>
                                <span class="text-sm font-bold text-amber-800">{{ $stockCaguama }} pzs</span>
                            </div>
                        </div>

                        <!-- Quantity Selector (Only active if selected) -->
                        <div class="mt-4">
                            @if($orderCaguama)
                                <label class="block mb-2 text-xs font-bold text-amber-900 uppercase tracking-wider">Cantidad a Pedir</label>
                                <div class="flex items-center justify-between bg-white border border-amber-200 rounded-xl p-1 shadow-sm">
                                    <button type="button" wire:click="decrementCaguama" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold transition-all">-</button>
                                    <input type="number" wire:model.live="caguamaQty" class="w-16 text-center border-0 p-0 font-bold text-gray-900 focus:ring-0" min="1" max="{{ $stockCaguama }}">
                                    <button type="button" wire:click="incrementCaguama" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold transition-all">+</button>
                                </div>
                                @error('caguamaQty') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                            @else
                                <div class="text-center py-3 text-sm text-gray-400 font-medium bg-gray-50 rounded-xl">
                                    Activa el interruptor para agregar
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Right Column: Live Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-amber-500"></i> Resumen del Pedido
                </h3>
                
                <form wire:submit.prevent="confirmOrder">
                    <!-- Read-only Summary Details -->
                    <div class="space-y-4 mb-6 bg-amber-50/10 p-4 rounded-xl border border-amber-100/50">
                        <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                            <span class="text-sm text-gray-500">Cliente:</span>
                            <span class="text-sm font-bold text-gray-900 text-right">
                                @if($client_id && $clients->find($client_id))
                                    {{ $clients->find($client_id)->user->name }}
                                @else
                                    <span class="text-gray-400 italic">No seleccionado</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-sm text-gray-500">Fecha de Entrega:</span>
                            <span class="text-sm font-bold text-gray-900">
                                {{ $searchDate ? \Carbon\Carbon::parse($searchDate)->format('d/m/Y') : '-' }}
                            </span>
                        </div>

                        <!-- Ordered Items Breakdown -->
                        <div class="border-b border-gray-100 pb-3">
                            <span class="text-sm text-gray-500 block mb-2">Detalles del Cargamento:</span>
                            <div class="space-y-2">
                                @if($orderSix)
                                    <div class="flex justify-between items-center bg-white px-3 py-1.5 rounded-lg border border-gray-100">
                                        <span class="text-xs font-semibold text-gray-700">Six Packs:</span>
                                        <span class="text-xs font-bold text-amber-800">x{{ $sixQty }} unidades</span>
                                    </div>
                                @endif

                                @if($orderCaguama)
                                    <div class="flex justify-between items-center bg-white px-3 py-1.5 rounded-lg border border-gray-100">
                                        <span class="text-xs font-semibold text-gray-700">Caguamas:</span>
                                        <span class="text-xs font-bold text-amber-800">x{{ $caguamaQty }} unidades</span>
                                    </div>
                                @endif

                                @if(!$orderSix && !$orderCaguama)
                                    <div class="text-center py-2 text-xs text-gray-400 italic font-medium">
                                        Ningún producto seleccionado
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Instructions / Custom Notes -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Detalles de Entrega y Notas</label>
                        <textarea wire:model="reason" rows="4" class="block p-3 w-full text-sm rounded-lg border {{ $errors->has('reason') ? 'bg-red-50 border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border-gray-300 text-gray-900 focus:ring-amber-500 focus:border-amber-500' }}" placeholder="Ej: Entregar por la puerta trasera o indicaciones especiales sobre la temperatura del lote..."></textarea>
                        @error('reason') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-300 font-bold rounded-xl text-sm px-5 py-3.5 text-center shadow-lg shadow-amber-600/20 hover:shadow-amber-600/30 transition-all duration-300">
                        Confirmar y Crear Pedido
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
