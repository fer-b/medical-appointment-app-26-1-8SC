<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hacer-pedido', \App\Livewire\Public\OrderCreate::class)->name('public.order.create');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin') || $user->hasRole('employee')) {
            return redirect()->route('admin.dashboard');
        }
        // Clientes no tienen panel, van al inicio
        return redirect('/');
    })->name('dashboard');
});
