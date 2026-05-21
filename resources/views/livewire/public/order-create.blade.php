<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <a href="/">
                <img src="{{ asset('images/cerveza_logo.png') }}" alt="Home Brewing Logo" class="h-20 w-auto mx-auto object-contain mb-4">
            </a>
            <h2 class="text-3xl font-extrabold text-gray-900">Realizar Pedido</h2>
            <p class="mt-2 text-sm text-gray-500">Selecciona las cervezas de tu preferencia y dinos dónde entregar.</p>
        </div>

        @if($orderCompleted)
            <!-- Success State -->
            <div class="bg-white rounded-2xl shadow-xl border border-amber-100 p-8 text-center animate-fade-in-up">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-amber-100 mb-6">
                    <svg class="h-10 w-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">¡Pedido Confirmado!</h3>
                <p class="text-gray-600 mb-6 text-lg">Tu pedido ha sido recibido y nuestro equipo lo preparará pronto. Recibirás un comprobante en tu correo electrónico y confirmación vía WhatsApp.</p>
                <div class="bg-gray-50 rounded-xl p-4 inline-block mb-8 border border-gray-100">
                    <p class="text-sm text-gray-500">Se enviaron las notificaciones a:</p>
                    <p class="font-bold text-gray-900">{{ $email }}</p>
                    <p class="font-bold text-gray-900">{{ $phone }}</p>
                </div>
                <br>
                <a href="/" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-bold rounded-full shadow-sm text-amber-900 bg-amber-100 hover:bg-amber-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                    Volver al Inicio
                </a>
            </div>
        @else
            <!-- Order Form -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form wire:submit.prevent="confirmOrder">
                        
                        <!-- Client Info Section -->
                        <div class="mb-10">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6 border-b pb-2">
                                <i class="fa-solid fa-user text-amber-500"></i> Tus Datos
                            </h3>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700">Nombre Completo</label>
                                    <div class="mt-1">
                                        <input type="text" wire:model.blur="name" id="name" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3 bg-gray-50">
                                    </div>
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700">Correo Electrónico</label>
                                    <div class="mt-1">
                                        <input type="email" wire:model.blur="email" id="email" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3 bg-gray-50" placeholder="tu@correo.com">
                                    </div>
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700">Teléfono (WhatsApp)</label>
                                    <div class="mt-1">
                                        <input type="tel" wire:model.blur="phone" id="phone" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3 bg-gray-50" placeholder="Ej: +521234567890">
                                    </div>
                                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Beer Selection Section -->
                        <div class="mb-10">
                            <div class="flex justify-between items-center mb-6 border-b pb-2">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fa-solid fa-beer-mug-empty text-amber-500"></i> Selección de Cervezas
                                </h3>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-800 rounded-full">Stock Real</span>
                            </div>

                            @error('beer_selection')
                                <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
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
                        </div>

                        <!-- Notes Section -->
                        <div class="mb-8">
                            <label for="reason" class="block text-sm font-semibold text-gray-700">Dirección de Entrega o Notas del Pedido</label>
                            <div class="mt-1">
                                <textarea id="reason" wire:model="reason" rows="3" class="shadow-sm focus:ring-amber-500 focus:border-amber-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3 bg-gray-50" placeholder="Escribe aquí tu dirección exacta, referencias o algún requerimiento especial..."></textarea>
                            </div>
                            @error('reason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                Pago contra entrega 🚚
                            </span>
                            <button type="submit" wire:loading.attr="disabled" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-lg shadow-amber-500/30 text-base font-bold rounded-full text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all hover:scale-105">
                                <span wire:loading.remove>Confirmar Pedido</span>
                                <span wire:loading>Procesando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
