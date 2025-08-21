<?php

namespace Database\Factories;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Tax;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $subcategory = Category::whereNotNull('parent_id')->inRandomOrder()->first();

        return [
            'category_id' => $subcategory ? $subcategory->id : Category::factory(), // fallback في حالة مفيش
            'tax_id' => Tax::inRandomOrder()->first()?->id ?? Tax::factory(),
            'unit_id' => Unit::inRandomOrder()->first()?->id ?? Unit::factory(),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
            'slug' => Str::slug($this->faker->words(3, true) . '-' . Str::random(5)),
            'image' => 'products/default.png',
            'sku' => strtoupper(Str::random(8)),
            'barcode' => $this->faker->ean13,
            'selling_price' => $this->faker->randomFloat(2, 10, 500),
            'buying_price' => $this->faker->randomFloat(2, 10, 500),
            'active' => $this->faker->boolean(90),
        ];
    }
}
