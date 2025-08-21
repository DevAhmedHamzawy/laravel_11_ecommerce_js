<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'image' => $this->faker->image(
                storage_path('app/public/public/brands'), 640, 480, null, true
            ),
            'description' => $this->faker->paragraph,
            'active' => $this->faker->boolean(),
        ];
    }
}
