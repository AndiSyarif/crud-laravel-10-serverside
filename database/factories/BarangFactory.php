<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = array('Elektronik', 'Makanan', 'Minuman', 'Alat', 'Software', 'Hardware');

        return [
            'name' => fake()->unique()->name(),
            'category' => $category[rand(0, 5)],
            'supplier' => fake()->unique()->company(),
            'stock' => rand(1, 500),
            'price' => rand(100000, 5000000),
            'note' => fake()->unique()->bs()
        ];
    }
}
