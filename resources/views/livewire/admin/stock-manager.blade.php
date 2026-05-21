<div class="mt-6">
    <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Inventario y Stock</h2>
            <p class="text-sm text-gray-500">Administra los niveles de stock para cada formato de cerveza en tiempo real.</p>
        </div>
    </div>

    <!-- Alert / Toast Banner from session if any -->
    @if (session()->has('swal'))
        <script>
            Swal.fire({
                icon: '{{ session('swal')['icon'] }}',
                title: '{{ session('swal')['title'] }}',
                text: '{{ session('swal')['text'] }}',
                showConfirmButton: false,
                timer: 2500,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- CARD 1: Six Packs -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
            <div class="p-6 sm:p-8 space-y-6">
                
                <!-- Format Header -->
                <div class="flex justify-between items-start pb-4 border-b border-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-amber-500/10 text-amber-700 rounded-2xl">
                            <i class="fa-solid fa-boxes-stacked text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Format: Six Pack</h3>
                            <p class="text-xs text-gray-500 font-medium">Lata estándar 355 ml &bull; 6 packs</p>
                        </div>
                    </div>

                    <!-- Live Indicator Status Badge -->
                    @if($sixStock <= 0)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 animate-pulse">
                            <i class="fa-solid fa-triangle-exclamation mr-0.5"></i> Agotado
                        </span>
                    @elseif($sixStock < 10)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                            <i class="fa-solid fa-circle-exclamation mr-0.5"></i> Crítico
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <i class="fa-solid fa-check mr-0.5"></i> Óptimo
                        </span>
                    @endif
                </div>

                <!-- Stock Visualization Widget -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                    <div class="text-center md:text-left flex flex-col justify-center">
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider block mb-1">Stock Disponible</span>
                        <span class="text-5xl font-black text-gray-900 tracking-tight">
                            {{ $sixStock }}
                            <span class="text-sm font-bold text-gray-500 uppercase">packs</span>
                        </span>
                    </div>

                    <div class="space-y-2 flex flex-col justify-center">
                        @if($sixStock <= 0)
                            <div class="text-xs font-bold text-red-600 bg-red-50 border border-red-100 rounded-xl p-3 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                                <div>
                                    <strong class="block">¡Ya no hay stock!</strong>
                                    Los clientes no podrán ordenar este producto.
                                </div>
                            </div>
                        @elseif($sixStock < 10)
                            <div class="text-xs font-bold text-amber-600 bg-amber-50 border border-amber-100 rounded-xl p-3 flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                                <div>
                                    <strong class="block">Quedan pocas piezas</strong>
                                    Se recomienda reabastecer a la brevedad.
                                </div>
                            </div>
                        @else
                            <div class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-lg"></i>
                                <div>
                                    <strong class="block">Nivel saludable</strong>
                                    Suficiente inventario para pedidos entrantes.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action: Quick Adjustment Buttons -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">
                        <i class="fa-solid fa-bolt text-amber-500 mr-0.5"></i> Ajuste Rápido de Inventario
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <!-- Subtract Buttons -->
                        <button type="button" wire:click="adjustSix(-10)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 active:scale-95 transition-all">
                            -10
                        </button>
                        <button type="button" wire:click="adjustSix(-5)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 active:scale-95 transition-all">
                            -5
                        </button>
                        <button type="button" wire:click="adjustSix(-1)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 active:scale-95 transition-all">
                            -1
                        </button>
                        
                        <div class="w-px h-8 bg-gray-200 mx-1"></div>

                        <!-- Add Buttons -->
                        <button type="button" wire:click="adjustSix(1)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +1
                        </button>
                        <button type="button" wire:click="adjustSix(5)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +5
                        </button>
                        <button type="button" wire:click="adjustSix(10)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +10
                        </button>
                        <button type="button" wire:click="adjustSix(50)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +50
                        </button>
                    </div>
                </div>

                <!-- Input: Direct Manual Override -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Establecer Cantidad Exacta
                    </label>
                    <div class="relative rounded-xl shadow-sm max-w-xs">
                        <input type="number" wire:model.live="sixStock" min="0" class="block w-full rounded-xl border-gray-300 pl-4 pr-16 py-3 font-bold text-gray-900 focus:border-amber-500 focus:ring-amber-500 sm:text-sm bg-gray-50" placeholder="0">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                            <span class="text-xs font-bold text-gray-400 uppercase">Packs</span>
                        </div>
                    </div>
                    @error('sixStock') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

        <!-- CARD 2: Caguamas -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
            <div class="p-6 sm:p-8 space-y-6">
                
                <!-- Format Header -->
                <div class="flex justify-between items-start pb-4 border-b border-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-amber-500/10 text-amber-700 rounded-2xl">
                            <i class="fa-solid fa-bottle-beer text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Format: Caguama</h3>
                            <p class="text-xs text-gray-500 font-medium">Envase retornable 940 ml</p>
                        </div>
                    </div>

                    <!-- Live Indicator Status Badge -->
                    @if($caguamaStock <= 0)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 animate-pulse">
                            <i class="fa-solid fa-triangle-exclamation mr-0.5"></i> Agotado
                        </span>
                    @elseif($caguamaStock < 10)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                            <i class="fa-solid fa-circle-exclamation mr-0.5"></i> Crítico
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <i class="fa-solid fa-check mr-0.5"></i> Óptimo
                        </span>
                    @endif
                </div>

                <!-- Stock Visualization Widget -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                    <div class="text-center md:text-left flex flex-col justify-center">
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider block mb-1">Stock Disponible</span>
                        <span class="text-5xl font-black text-gray-900 tracking-tight">
                            {{ $caguamaStock }}
                            <span class="text-sm font-bold text-gray-500 uppercase">pzs</span>
                        </span>
                    </div>

                    <div class="space-y-2 flex flex-col justify-center">
                        @if($caguamaStock <= 0)
                            <div class="text-xs font-bold text-red-600 bg-red-50 border border-red-100 rounded-xl p-3 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                                <div>
                                    <strong class="block">¡Ya no hay stock!</strong>
                                    Los clientes no podrán ordenar este producto.
                                </div>
                            </div>
                        @elseif($caguamaStock < 10)
                            <div class="text-xs font-bold text-amber-600 bg-amber-50 border border-amber-100 rounded-xl p-3 flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                                <div>
                                    <strong class="block">Quedan pocas piezas</strong>
                                    Se recomienda reabastecer a la brevedad.
                                </div>
                            </div>
                        @else
                            <div class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-lg"></i>
                                <div>
                                    <strong class="block">Nivel saludable</strong>
                                    Suficiente inventario para pedidos entrantes.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action: Quick Adjustment Buttons -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">
                        <i class="fa-solid fa-bolt text-amber-500 mr-0.5"></i> Ajuste Rápido de Inventario
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <!-- Subtract Buttons -->
                        <button type="button" wire:click="adjustCaguama(-10)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 active:scale-95 transition-all">
                            -10
                        </button>
                        <button type="button" wire:click="adjustCaguama(-5)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 active:scale-95 transition-all">
                            -5
                        </button>
                        <button type="button" wire:click="adjustCaguama(-1)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 active:scale-95 transition-all">
                            -1
                        </button>
                        
                        <div class="w-px h-8 bg-gray-200 mx-1"></div>

                        <!-- Add Buttons -->
                        <button type="button" wire:click="adjustCaguama(1)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +1
                        </button>
                        <button type="button" wire:click="adjustCaguama(5)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +5
                        </button>
                        <button type="button" wire:click="adjustCaguama(10)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +10
                        </button>
                        <button type="button" wire:click="adjustCaguama(50)" class="inline-flex items-center px-3.5 py-2 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 active:scale-95 transition-all">
                            +50
                        </button>
                    </div>
                </div>

                <!-- Input: Direct Manual Override -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Establecer Cantidad Exacta
                    </label>
                    <div class="relative rounded-xl shadow-sm max-w-xs">
                        <input type="number" wire:model.live="caguamaStock" min="0" class="block w-full rounded-xl border-gray-300 pl-4 pr-16 py-3 font-bold text-gray-900 focus:border-amber-500 focus:ring-amber-500 sm:text-sm bg-gray-50" placeholder="0">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                            <span class="text-xs font-bold text-gray-400 uppercase">Pzs</span>
                        </div>
                    </div>
                    @error('caguamaStock') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>

    </div>

    <!-- Explicit Global Save Button (Bottom Bar) -->
    <div class="mt-8 border-t border-gray-200 pt-6 flex justify-end">
        <button type="button" wire:click="saveStock" wire:loading.attr="disabled" class="inline-flex justify-center py-3.5 px-8 border border-transparent shadow-lg shadow-amber-600/30 text-base font-bold rounded-full text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all hover:scale-105">
            <span wire:loading.remove><i class="fa-solid fa-floppy-disk mr-1.5"></i> Guardar Inventario</span>
            <span wire:loading><i class="fa-solid fa-spinner animate-spin mr-1.5"></i> Guardando...</span>
        </button>
    </div>
</div>
