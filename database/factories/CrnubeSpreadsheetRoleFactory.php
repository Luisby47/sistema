<?php

namespace Database\Factories;
use App\Models\CrnubeSpreadsheetRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class CrnubeSpreadsheetRoleFactory extends Factory
{
    protected $model = CrnubeSpreadsheetRole::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle, // Genera un título de trabajo como rol, por ejemplo "Manager", "Admin", etc.
        ];
    }
}
