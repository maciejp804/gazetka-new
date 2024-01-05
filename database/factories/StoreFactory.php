<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=> fake()->unique()->name(),
            'name_genitive'=> fake()->unique()->name(),
            'name_locative'=> fake()->unique()->name(),
            'subdomain'=> fake()->unique()->word(),
            'meta_title'=> fake()->unique()->word(),
            'meta_description'=> fake()->unique()->word(),
            'title'=> fake()->unique()->word(),
            'excerpt_description'=> fake()->unique()->word(),
            'description'=> fake()->unique()->word(),
            'category_store_id'=>fake()->numberBetween(1,6),
            'status'=>1,
            'rate'=>fake()->numberBetween(1,9),
            'votes'=>fake()->numberBetween(50,100),
            'offers'=>fake()->numberBetween(1,20),
            'logo'=>'biedronka.png',
            'is_online' => fake()->boolean(),
        ];
    }
}
