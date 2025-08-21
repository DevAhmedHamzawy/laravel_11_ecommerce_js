<?php

namespace Database\Factories;

use App\Models\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tax>
 */
class TaxFactory extends Factory
{
    protected $model = Tax::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word . ' Tax', // مثل: "Sales Tax"
            'code' => strtoupper($this->faker->bothify('TAX-###')),
            'rate' => $this->faker->randomFloat(2, 1, 25), // 1% إلى 25%
            'active' => $this->faker->boolean(),
        ];
    }
}
