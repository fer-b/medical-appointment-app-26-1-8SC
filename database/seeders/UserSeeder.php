<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Administrador
        $admin = User::create([
            'name' => 'Administrador Home Brewing',
            'email' => 'admin@homebrewing.com',
            'password' => bcrypt('12341234'),
            'id_number' => '111111111',
            'phone' => '1111111111',
            'address' => 'Sede Central Home Brewing',
        ]);
        $admin->assignRole('admin');

        // 2. Crear Empleado
        $employeeUser = User::create([
            'name' => 'Maestro Cervecero Carlos',
            'email' => 'empleado@homebrewing.com',
            'password' => bcrypt('12341234'),
            'id_number' => '222222222',
            'phone' => '2222222222',
            'address' => 'Área de Fermentación',
        ]);
        $employeeUser->assignRole('employee');
        Employee::create([
            'user_id' => $employeeUser->id,
            'specialty' => 'Maestro Cervecero',
        ]);

        // 3. Crear Cliente de Prueba
        $clientUser = User::create([
            'name' => 'Cliente Mayorista Juan',
            'email' => 'cliente@homebrewing.com',
            'password' => bcrypt('12341234'),
            'id_number' => '333333333',
            'phone' => '3333333333',
            'address' => 'Av. de las Cervezas 123',
        ]);
        $clientUser->assignRole('client');
        Client::create([
            'user_id' => $clientUser->id,
            'client_category_id' => 1, // Mayorista
            'allergies' => 'Ninguna',
            'chronic_conditions' => 'Ninguna',
            'surgical_history' => 'Ninguna',
            'family_history' => 'Ninguna',
            'observations' => 'Cliente preferencial de barriles IPA',
        ]);
    }
}
