<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

Route::get("/", function () {
    return view('admin.dashboard');
})->name('dashboard');

//Gestión de roles
Route::resource('roles', RoleController::class);

//Gestión de usuarios
Route::resource('users', UserController::class);
