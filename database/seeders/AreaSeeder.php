<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('areas')->insert([
            ['nombre' => 'Barra', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cocina', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Carnes', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cachapas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}