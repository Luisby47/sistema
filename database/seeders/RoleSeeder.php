<?php

namespace Database\Seeders;

use App\Models\CrnubeSpreadsheetRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // El uso de seeder es para interactuar con la base de datos y poblarla con datos iniciales, en este caso, roles
    public function run(): void
    {
        CrnubeSpreadsheetRole::create([
            'id' => CrnubeSpreadsheetRole::DEFAULT_ROLE_ID, // Contador que va incrementando
            'name' => 'Super Admin', // Cambia 'Super Admin' por el nombre de tu rol
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
