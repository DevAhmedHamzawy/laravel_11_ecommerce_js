<?php

namespace Database\Factories;

use App\Models\ProductTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductTranslation>
 */
class ProductTranslationFactory extends Factory
{
    protected $model = ProductTranslation::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word() . '-' . rand(1000, 9999),
            'mini_description' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
        ];
    }
}
