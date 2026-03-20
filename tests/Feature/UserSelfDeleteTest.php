<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Refresca la base de datos entre pruebas
uses (RefreshDatabase::class);

test('Un usuario no puede eliminarse a sí mismo', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

    // 1) Crear un usuario en la BD de pruebas
    $user = User::factory()->create(
        [
        'email_verified_at' => now()
        ]
    );

    // 2) Simular que el usuario ha iniciado sesión
    $this->actingAs($user, 'web');

    // 3) Simular que intenta borrar un usuario (él mismo)
    $response = $this->delete(route('admin.users.destroy', $user));

    // 4) Esperar a que el servidor bloquee esta acción
    $response->assertStatus(403);

    // 5) Verificar que el usuario sigue existiendo en la base de datos
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});
