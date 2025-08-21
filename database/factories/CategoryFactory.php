<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'slug' => Str::slug($name),
            'parent_id' => Category::inRandomOrder()->first()?->id,
            'appear_home' => $this->faker->boolean(),
            'active' => $this->faker->boolean(),
            'image' => 'categories/' . $this->faker->image(storage_path('app/public/public/categories'), 640, 480, null, false),
        ];
    }
}
