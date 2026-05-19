@php
    // Campos por pestaña para detectar errores de validación
    $errorGroups = [
        'antecedentes' => ['allergies', 'chronic_conditions', 'surgical_history', 'family_history'],
        'informacion-general' => ['client_category_id', 'observations'],
        'contacto-emergencia' => ['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'],
    ];

    // Pestaña que se mostrará por defecto al cargar la vista
    $initialTab = 'datos-personales';

    // Si hay errores cuando se envia el formulario, cambiamos la pestaña inicial a la afectada
    foreach ($errorGroups as $tabName => $fields) {
        if ($errors->hasAny($fields)) {
            $initialTab = $tabName;
            break; 
        }
    }
@endphp

<x-admin-layout title="Clientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Clientes', 'href' => route('admin.clients.index')],
    ['name' => 'Editar']
]">

    {{-- Formulario para actualizar la información del cliente --}}
    <form action="{{ route('admin.clients.update', $client) }}" method="POST">
        @csrf 
        @method('PUT') 
        
        <x-wire-card class="mb-8">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center">
                    <img src="{{ $client->user->profile_photo_url }}" alt="{{ $client->user->name }}" class="h-20 w-20 rounded-full object-cover object-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 ml-4">{{ $client->user->name }}</p>
                    </div>
                </div>
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.clients.index') }}">Volver</x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check"></i> Guardar Cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>    
            
        <x-wire-card>
            <x-tabs :active="$initialTab">
                
                <x-slot name="header">
                    <x-tabs-link tab="datos-personales">
                        <i class="fa-solid fa-user me-2"></i> Datos personales
                    </x-tabs-link>

                    <x-tabs-link tab="antecedentes" :error="$errors->hasAny($errorGroups['antecedentes'])">
                        <i class="fa-solid fa-file-lines me-2"></i> Notas y Preferencias
                    </x-tabs-link>

                    <x-tabs-link tab="informacion-general" :error="$errors->hasAny($errorGroups['informacion-general'])">
                        <i class="fa-solid fa-info me-2"></i> Información general
                    </x-tabs-link>

                    <x-tabs-link tab="contacto-emergencia" :error="$errors->hasAny($errorGroups['contacto-emergencia'])">
                        <i class="fa-solid fa-heart me-2"></i> Contacto adicional
                    </x-tabs-link>
                </x-slot>

                <x-tabs-content tab="datos-personales">
                    <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-blue-800">Información de cuenta de usuario</h3>
                                    <div class="mt-1 text-sm text-blue-600">
                                        <p>La <strong>información del acceso</strong> (nombre, email y contraseña) debe gestionarse desde la cuenta de usuario asociada.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <x-wire-button primary sm href="{{ route('admin.users.edit', $client->user) }}" target="_blank">
                                    Editar usuario <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                                </x-wire-button>
                            </div>
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div><span class="text-gray-500 font-semibold">Teléfono: </span><span class="text-gray-900 text-sm ml-1">{{ $client->user->phone }}</span></div>
                        <div><span class="text-gray-500 font-semibold">Correo: </span><span class="text-gray-900 text-sm ml-1">{{ $client->user->email }}</span></div>
                        <div><span class="text-gray-500 font-semibold">Dirección: </span><span class="text-gray-900 text-sm ml-1">{{ $client->user->address }}</span></div>
                    </div>
                </x-tabs-content>

                <x-tabs-content tab="antecedentes">
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div>
                            <x-wire-textarea label="Preferencias de Estilo" name="allergies">
                                {{ old('allergies', $client->allergies) }}
                            </x-wire-textarea>
                        </div>
                        <div>
                            <x-wire-textarea label="Restricciones o Notas de Envío" name="chronic_conditions">
                                {{ old('chronic_conditions', $client->chronic_conditions) }}
                            </x-wire-textarea>
                        </div>
                        <div>
                            <x-wire-textarea label="Historial de Pedidos Especiales" name="surgical_history">
                                {{ old('surgical_history', $client->surgical_history) }}
                            </x-wire-textarea>
                        </div>
                        <div>
                            <x-wire-textarea label="Notas del Negocio (ej. volumen)" name="family_history">
                                {{ old('family_history', $client->family_history) }}
                            </x-wire-textarea>
                        </div>
                    </div>
                </x-tabs-content>

                <x-tabs-content tab="informacion-general">
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div>
                            <x-wire-native-select label="Categoría de Cliente" class="mb-4" name="client_category_id">
                                <option value="">Selecciona una categoría</option>
                                @foreach ($clientCategories as $category)
                                    <option value="{{ $category->id }}" @selected(old('client_category_id', $client->client_category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-wire-native-select>
                            
                            <x-wire-textarea label="Observaciones" name="observations">
                                {{ old('observations', $client->observations) }}
                            </x-wire-textarea>
                        </div>
                    </div>
                </x-tabs-content>

                <x-tabs-content tab="contacto-emergencia">
                    <div class="space-y-4">
                        <x-wire-input label="Nombre de contacto secundario" name="emergency_contact_name" value="{{ old('emergency_contact_name', $client->emergency_contact_name) }}"/>
                        <x-wire-phone label="Teléfono de contacto secundario" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $client->emergency_contact_phone) }}" mask="(###) ###-####" placeholder="(123) 456-7890"/>
                        <x-wire-input label="Relación con el contacto secundario" name="emergency_contact_relationship" placeholder="Encargado de Compras, Socio, etc." value="{{ old('emergency_contact_relationship', $client->emergency_contact_relationship) }}"/>
                    </div>
                </x-tabs-content>

            </x-tabs>
        </x-wire-card>
    </form>

</x-admin-layout>