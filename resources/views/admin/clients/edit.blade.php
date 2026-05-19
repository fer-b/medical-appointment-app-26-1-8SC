@php
    // Campos por pestaña para detectar errores de validación
    $errorGroups = [
        'antecedentes' => ['allergies', 'chronic_conditions', 'surgical_history', 'family_history'],
        'informacion-general' => ['blood_type_id', 'observations'],
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

<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => 'Editar']
]">

    {{-- Formulario para actualizar la información del paciente --}}
    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf 
        @method('PUT') 
        
        <x-wire-card class="mb-8">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center">
                    <img src="{{ $patient->user->profile_photo_url }}" alt="{{ $patient->user->name }}" class="h-20 w-20 rounded-full object-cover object-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 ml-4">{{ $patient->user->name }}</p>
                    </div>
                </div>
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">Volver</x-wire-button>
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
                        <i class="fa-solid fa-file-lines me-2"></i> Antecedentes
                    </x-tabs-link>

                    <x-tabs-link tab="informacion-general" :error="$errors->hasAny($errorGroups['informacion-general'])">
                        <i class="fa-solid fa-info me-2"></i> Información general
                    </x-tabs-link>

                    <x-tabs-link tab="contacto-emergencia" :error="$errors->hasAny($errorGroups['contacto-emergencia'])">
                        <i class="fa-solid fa-heart me-2"></i> Contacto de emergencia
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
                                <x-wire-button primary sm href="{{ route('admin.users.edit', $patient->user) }}" target="_blank">
                                    Editar usuario <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                                </x-wire-button>
                            </div>
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div><span class="text-gray-500 font-semibold">Teléfono: </span><span class="text-gray-900 text-sm ml-1">{{ $patient->user->phone }}</span></div>
                        <div><span class="text-gray-500 font-semibold">Correo: </span><span class="text-gray-900 text-sm ml-1">{{ $patient->user->email }}</span></div>
                        <div><span class="text-gray-500 font-semibold">Dirección: </span><span class="text-gray-900 text-sm ml-1">{{ $patient->user->address }}</span></div>
                    </div>
                </x-tabs-content>

                <x-tabs-content tab="antecedentes">
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div>
                            <x-wire-textarea label="Alergias conocidas" name="allergies">
                                {{ old('allergies', $patient->allergies) }}
                            </x-wire-textarea>
                        </div>
                        <div>
                            <x-wire-textarea label="Enfermedades crónicas" name="chronic_conditions">
                                {{ old('chronic_conditions', $patient->chronic_conditions) }}
                            </x-wire-textarea>
                        </div>
                        <div>
                            <x-wire-textarea label="Antecedentes quirúrgicos" name="surgical_history">
                                {{ old('surgical_history', $patient->surgical_history) }}
                            </x-wire-textarea>
                        </div>
                        <div>
                            <x-wire-textarea label="Antecedentes familiares" name="family_history">
                                {{ old('family_history', $patient->family_history) }}
                            </x-wire-textarea>
                        </div>
                    </div>
                </x-tabs-content>

                <x-tabs-content tab="informacion-general">
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div>
                            <x-wire-native-select label="Tipo de Sangre" class="mb-4" name="blood_type_id">
                                <option value="">Selecciona un tipo de sangre</option>
                                @foreach ($bloodTypes as $bloodType)
                                    <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                                        {{ $bloodType->name }}
                                    </option>
                                @endforeach
                            </x-wire-native-select>
                            
                            <x-wire-textarea label="Observaciones" name="observations">
                                {{ old('observations', $patient->observations) }}
                            </x-wire-textarea>
                        </div>
                    </div>
                </x-tabs-content>

                <x-tabs-content tab="contacto-emergencia">
                    <div class="space-y-4">
                        <x-wire-input label="Nombre de contacto" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"/>
                        <x-wire-phone label="Teléfono de contacto" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" mask="(###) ###-####" placeholder="(123) 456-7890"/>
                        <x-wire-input label="Relación con el contacto" name="emergency_contact_relationship" placeholder="Familiar, Amigo, etc." value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}"/>
                    </div>
                </x-tabs-content>

            </x-tabs>
        </x-wire-card>
    </form>

</x-admin-layout>