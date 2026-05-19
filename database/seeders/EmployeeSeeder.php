<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::factory(10)->create();

        foreach ($users as $user) {
            $user->assignRole('employee');
            
            \App\Models\Employee::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
