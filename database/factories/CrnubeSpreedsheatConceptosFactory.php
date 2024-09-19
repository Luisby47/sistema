<?php

namespace Database\Factories;

use App\Models\CrnubeSpreedsheatConceptos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CrnubeSpreedsheatConceptos>
 */
class CrnubeSpreedsheatConceptosFactory extends Factory
{
    protected $model = CrnubeSpreedsheatConceptos::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo_concepto' => $this->faker->randomElement(['ING', 'DED']),
            'tipo_valor' => $this->faker->randomElement(['MONT', 'PORC']),
            'motivo' => $this->faker->sentence(),
            'valor' => $this->faker->numberBetween(100, 5000),
            'observaciones' => $this->faker->paragraph(),
        ];
    }
}
