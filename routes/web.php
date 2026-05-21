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
        // Clientes van a su panel de pedidos
        return redirect()->route('client.order.create');
    })->name('dashboard');

    Route::get('/mi-pedido', \App\Livewire\Client\OrderCreate::class)->name('client.order.create');
});
