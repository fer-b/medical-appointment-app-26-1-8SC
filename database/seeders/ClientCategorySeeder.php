<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClientCategory;

class ClientCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Mayorista',
            'Minorista / Particular',
            'Distribuidor',
            'Cliente VIP',
        ];

        foreach ($categories as $category) {
            ClientCategory::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}