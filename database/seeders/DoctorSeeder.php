<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::factory(10)->create();

        foreach ($users as $user) {
            $user->assignRole('Doctor');
            
            \App\Models\Doctor::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
