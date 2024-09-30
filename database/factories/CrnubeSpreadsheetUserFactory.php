<?php

namespace Database\Factories;
use App\Models\CrnubeSpreadsheetUser;
use App\Models\CrnubeSpreadsheetRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Pest\Support\Str;

class CrnubeSpreadsheetUserFactory extends Factory
{
    protected $model = CrnubeSpreadsheetUser::class;

    public function definition(): array
    {
        return [
            // Aquí generamos un id único para simular la relación con la tabla 'res_users'
            'id' => $this->faker->unique()->randomNumber(), // Esto asume que tienes un ID en 'res_users'

            'email' => $this->faker->unique(),
            'password' => Hash::make('password'), // Hasheamos la contraseña
            'name' => $this->faker->name,
            'avatar' => $this->faker->imageUrl(640, 480, 'people', true, 'Faker'), // Avatar opcional
            'remember_token' => Str::random(10), // Generamos un token de recuerdo
            'role_id' => CrnubeSpreadsheetRole::factory(), // Relación con roles, utilizando su propia fábrica
        ];
    }
}
