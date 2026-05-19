<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;

Route::get("/", function () {
    return view('admin.dashboard');
})->name('dashboard');

//Gestión de roles
Route::resource('roles', RoleController::class);

//Gestión de usuarios
Route::resource('users', UserController::class);


//Gestión de clientes
Route::resource('clients', ClientController::class);

//Gestión de empleados
Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
Route::get('employees/{employee}/schedules', \App\Livewire\Admin\ScheduleManager::class)->name('employees.schedules');

//Gestión de pedidos
Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
Route::get('orders/{order}/consultation', \App\Livewire\Admin\ConsultationManager::class)->name('orders.consultation');