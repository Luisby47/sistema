<?php

namespace Database\Seeders;

use App\Models\CrnubeSpreadsheetRole;
use App\Models\CrnubeSpreadsheetUser;
use App\Models\ResUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // El uso de seeder es para interactuar con la base de datos y poblarla con datos iniciales, en este caso, un super admin
    public function run(): void
    {
        // Asumiendo que ya tienes un rol 'Super Admin'
        $superAdminRole = CrnubeSpreadsheetRole::where('name', 'Super Admin')->first();

        if (!$superAdminRole) {
            $superAdminRole = CrnubeSpreadsheetRole::create(['name' => 'Super Admin']);
        }

        // Buscar el usuario admin en res_users
        $resUser = ResUser::where('login', 'admin@crnube.net')->first();

        if ($resUser) {
            CrnubeSpreadsheetUser::create([
                'id' => $resUser->id,
                'role_id' => $superAdminRole->id,
                'email' => $resUser->email ?? 'admin@example.com', // Cambia el email por uno válido
                'password' => Hash::make('root'), // Cambia 'password' por una contraseña segura
                'name' => $resUser->name ?? 'Super Admin', // Cambia 'Super Admin' por el nombre del usuario
            ]);
        } else {
            // Si no existe un usuario admin en res_users, puedes manejarlo aquí
            // Por ejemplo, lanzar una excepción o crear un nuevo usuario en res_users
        }
    }
}
