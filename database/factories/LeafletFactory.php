<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leaflet>
 */
class LeafletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => fake()->numberBetween(1,15),
            'leaflet_category_id'=> 1,
            'leaflet_number'=>fake()->numberBetween(1,20),
            'meta_title' =>fake()->paragraph,
            'meta_description' =>fake()->paragraph,
            'title' =>fake()->paragraph,
            'description' =>fake()->paragraph,
            'slug'=>fake()->slug(),
            'start_date'=>fake()->date('Y-m-d'),
            'end_date'=>fake()->date('Y-m-d'),
            'start_offer_date'=>fake()->date('Y-m-d'),
            'end_offer_date'=>fake()->date('Y-m-d'),
            'next_offer_date'=>fake()->date('Y-m-d'),
            'pages'=>fake()->numberBetween(10,20),
            'thumbnail'=>'biedronka.png',
            'is_alcohol'=>fake()->boolean(),
            'is_regions'=>fake()->boolean(),
            'is_promo_main'=>fake()->boolean(),
            'is_next_promo'=>fake()->boolean(),
            'liked_users'=>fake()->numberBetween(10,20)
        ];
    }
}
