<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 30 orders
        Order::factory(30)->create()
        ->each(fn (Order $order) =>
            // create orderItem for each Order
            // each order will have a random item from 1 to 5
            OrderItem::factory(random_int(1, 5))
            ->create(['order_id' => $order->id])
        );
    }
}
