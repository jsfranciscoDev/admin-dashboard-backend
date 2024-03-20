<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{   
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
   
    public function definition()
    {
        $categories = ProductCategory::pluck('id')->toArray();

        return [
            'name' => $this->faker->word,
            'category_id' => $this->faker->randomElement($categories),
            'description' => $this->faker->sentence,
        ];
    }
    

}
