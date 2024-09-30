<?php

namespace Database\Factories;

use App\Models\CrnubeSpreedsheatConceptos;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
            'tipo_concepto' => Arr::random(['ING', 'DED']),
            'tipo_valor' => Arr::random(['MONT', 'PORC']),
            'motivo' => $this->faker,
            'valor' => $this->faker->numberBetween(100, 5000),
            'observaciones' => $this->faker,
        ];
    }
}
