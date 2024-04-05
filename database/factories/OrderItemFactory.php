<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @var string
     */
    protected $model = OrderItem::class;
    /**
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //create fake oder item data
            // order_id will be set in the seeder
            'product_title' => $this->faker->name,
            'price' => $this->faker->numberBetween(10, 100),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
