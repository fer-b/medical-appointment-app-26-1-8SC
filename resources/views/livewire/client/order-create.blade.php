<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Order Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
                <div class="mb-6 pb-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900">Realizar Nuevo Pedido</h2>
                        <p class="mt-1 text-sm text-gray-500">Selecciona el formato y cantidad de cerveza artesanal.</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-amber-50 text-amber-800 rounded-full border border-amber-100">Stock Real</span>
                </div>

                <form wire:submit.prevent="confirmOrder" class="space-y-8">
                    @error('beer_selection')
                        <div class="p-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-100 flex items-center gap-2" role="alert">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span class="font-bold">Error:</span> {{ $message }}
                        </div>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Format 1: Six Pack -->
                        <div class="relative flex flex-col justify-between p-6 bg-white rounded-2xl border-2 {{ $stockSix <= 0 ? 'border-gray-200 bg-gray-50/50 opacity-75' : ($orderSix ? 'border-amber-500 bg-amber-50/20' : 'border-gray-100 hover:border-gray-200') }} transition-all duration-300 shadow-sm">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div class="p-3 bg-amber-100/50 rounded-xl text-amber-800">
                                        <i class="fa-solid fa-boxes-stacked text-2xl"></i>
                                    </div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="orderSix" class="sr-only peer" @if($stockSix <= 0) disabled @endif>
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500 peer-disabled:bg-gray-100 peer-disabled:after:bg-gray-200"></div>
                                    </label>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900">Six Pack</h4>
                                <p class="text-xs text-gray-500 mb-4">6 piezas de 355 ml (Lata)</p>
                                <div class="flex justify-between items-center py-2 border-t border-b border-gray-100/50 my-4">
                                    <span class="text-sm font-semibold text-gray-600">Disponibles:</span>
                                    @if($stockSix <= 0)
                                        <span class="text-sm font-bold text-red-600">Agotado</span>
                                    @elseif($stockSix < 10)
                                        <span class="text-sm font-bold text-amber-600">{{ $stockSix }} packs</span>
                                    @else
                                        <span class="text-sm font-bold text-amber-800">{{ $stockSix }} packs</span>
                                    @endif
                                </div>
                                @if($stockSix <= 0)
                                    <div class="mt-2 text-xs font-bold text-red-600 bg-red-50 border border-red-100 rounded-lg p-2.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        <span>¡Ya no hay stock!</span>
                                    </div>
                                @elseif($stockSix < 10)
                                    <div class="mt-2 text-xs font-bold text-amber-600 bg-amber-50 border border-amber-100 rounded-lg p-2.5 flex items-center gap-1.5 animate-pulse">
                                        <i class="fa-solid fa-circle-exclamation text-amber-500"></i>
                                        <span>Quedan pocas piezas</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4">
                                @if($stockSix <= 0)
                                    <div class="text-center py-3 text-sm text-red-500 font-bold bg-red-50 border border-red-100 rounded-xl">No disponible</div>
                                @elseif($orderSix)
                                    <label class="block mb-2 text-xs font-bold text-amber-900 uppercase tracking-wider">Cantidad</label>
                                    <div class="flex items-center justify-between bg-white border border-amber-200 rounded-xl p-1 shadow-sm">
                                        <button type="button" wire:click="decrementSix" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold">-</button>
                                        <input type="number" wire:model.live="sixQty" class="w-16 text-center border-0 p-0 font-bold text-gray-900 focus:ring-0" min="1" max="{{ $stockSix }}" readonly>
                                        <button type="button" wire:click="incrementSix" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold">+</button>
                                    </div>
                                    @error('sixQty') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                                @else
                                    <div class="text-center py-3 text-sm text-gray-400 font-medium bg-gray-50 rounded-xl">Activa para pedir</div>
                                @endif
                            </div>
                        </div>

                        <!-- Format 2: Caguama -->
                        <div class="relative flex flex-col justify-between p-6 bg-white rounded-2xl border-2 {{ $stockCaguama <= 0 ? 'border-gray-200 bg-gray-50/50 opacity-75' : ($orderCaguama ? 'border-amber-500 bg-amber-50/20' : 'border-gray-100 hover:border-gray-200') }} transition-all duration-300 shadow-sm">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div class="p-3 bg-amber-100/50 rounded-xl text-amber-800">
                                        <i class="fa-solid fa-bottle-beer text-2xl"></i>
                                    </div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="orderCaguama" class="sr-only peer" @if($stockCaguama <= 0) disabled @endif>
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500 peer-disabled:bg-gray-100 peer-disabled:after:bg-gray-200"></div>
                                    </label>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900">Caguama</h4>
                                <p class="text-xs text-gray-500 mb-4">Envase retornable de 940 ml</p>
                                <div class="flex justify-between items-center py-2 border-t border-b border-gray-100/50 my-4">
                                    <span class="text-sm font-semibold text-gray-600">Disponibles:</span>
                                    @if($stockCaguama <= 0)
                                        <span class="text-sm font-bold text-red-600">Agotado</span>
                                    @elseif($stockCaguama < 10)
                                        <span class="text-sm font-bold text-amber-600">{{ $stockCaguama }} pzs</span>
                                    @else
                                        <span class="text-sm font-bold text-amber-800">{{ $stockCaguama }} pzs</span>
                                    @endif
                                </div>
                                @if($stockCaguama <= 0)
                                    <div class="mt-2 text-xs font-bold text-red-600 bg-red-50 border border-red-100 rounded-lg p-2.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        <span>¡Ya no hay stock!</span>
                                    </div>
                                @elseif($stockCaguama < 10)
                                    <div class="mt-2 text-xs font-bold text-amber-600 bg-amber-50 border border-amber-100 rounded-lg p-2.5 flex items-center gap-1.5 animate-pulse">
                                        <i class="fa-solid fa-circle-exclamation text-amber-500"></i>
                                        <span>Quedan pocas piezas</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4">
                                @if($stockCaguama <= 0)
                                    <div class="text-center py-3 text-sm text-red-500 font-bold bg-red-50 border border-red-100 rounded-xl">No disponible</div>
                                @elseif($orderCaguama)
                                    <label class="block mb-2 text-xs font-bold text-amber-900 uppercase tracking-wider">Cantidad</label>
                                    <div class="flex items-center justify-between bg-white border border-amber-200 rounded-xl p-1 shadow-sm">
                                        <button type="button" wire:click="decrementCaguama" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold">-</button>
                                        <input type="number" wire:model.live="caguamaQty" class="w-16 text-center border-0 p-0 font-bold text-gray-900 focus:ring-0" min="1" max="{{ $stockCaguama }}" readonly>
                                        <button type="button" wire:click="incrementCaguama" class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold">+</button>
                                    </div>
                                    @error('caguamaQty') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                                @else
                                    <div class="text-center py-3 text-sm text-gray-400 font-medium bg-gray-50 rounded-xl">Activa para pedir</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Solicitada de Entrega</label>
                            <input type="date" wire:model="searchDate" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-xl p-3 bg-gray-50">
                            @error('searchDate') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección de Entrega / Notas Adicionales</label>
                        <textarea wire:model="reason" rows="3" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-xl p-3 bg-gray-50" placeholder="Especifica tu dirección de entrega y referencias..."></textarea>
                        @error('reason') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm text-gray-500">Pago contra entrega en domicilio 🚚</span>
                        <button type="submit" wire:loading.attr="disabled" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-lg shadow-amber-500/30 text-base font-bold rounded-full text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all hover:scale-105">
                            <span wire:loading.remove>Realizar Pedido</span>
                            <span wire:loading>Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Recent Orders -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 space-y-6">
                <h3 class="text-lg font-bold text-gray-900 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-500"></i> Mis Últimos Pedidos
                </h3>

                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-500">Pedido #{{ $order->id }}</span>
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full
                                    {{ $order->status == 1 ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $order->status == 2 ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status == 0 ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ $order->status == 1 ? 'Programado' : ($order->status == 2 ? 'Completado' : 'Cancelado') }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                @if($order->six_quantity > 0)
                                    <div>• {{ $order->six_quantity }} Six Pack(s)</div>
                                @endif
                                @if($order->caguama_quantity > 0)
                                    <div>• {{ $order->caguama_quantity }} Caguama(s)</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-sm text-gray-400 italic">
                            Aún no has realizado ningún pedido. ¡Pide tu primera cerveza artesanal hoy! 🍻
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
    </div>
</div>
