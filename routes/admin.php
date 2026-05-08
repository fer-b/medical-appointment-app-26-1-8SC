<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;

Route::get("/", function () {
    return view('admin.dashboard');
})->name('dashboard');

//Gestión de roles
Route::resource('roles', RoleController::class);

//Gestión de usuarios
Route::resource('users', UserController::class);


//Gestión de pacientes
Route::resource('patients', PatientController::class);

//Gestión de doctores
Route::resource('doctors', \App\Http\Controllers\Admin\DoctorController::class);
Route::get('doctors/{doctor}/schedules', \App\Livewire\Admin\ScheduleManager::class)->name('doctors.schedules');

//Gestión de citas
Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class);
Route::get('appointments/{appointment}/consultation', \App\Livewire\Admin\ConsultationManager::class)->name('appointments.consultation');