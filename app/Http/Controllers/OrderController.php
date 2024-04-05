<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index()
    {
        $orders = Order::with('orderItems')->paginate();

        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        $order = Order::with('orderItems')->find($id);

        return new OrderResource($order);
    }

    // export csv file
    public function export()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=orders.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function (){
            $orders = Order::all();
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Name', 'Email', 'Product Title', 'Price', 'Quantity']);
            // loop orders
            foreach($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->name,
                    $order->email,
                    '',
                    '',
                    '',
                ]);
                // loop order items
                foreach($order->orderItems as $orderItem){
                    fputcsv($file, [
                        '',
                        '',
                        '',
                        $orderItem->product_title,
                        $orderItem->price,
                        $orderItem->quantity,

                    ]);
                }
            }

            // close the open file
            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }

    public function chart()
    {
        // SELECT DATE_FORMAT(orders.created_at, '%Y-%m-%d') AS dateinfo, SUM(order_items.price * order_items.quantity) AS sum
        // FROM orders
        // JOIN order_items ON orders.id=order_items.order_id
        // GROUP BY dateinfo;

        // Using Raw SQL query with laravel
      return Order::query()
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m-%d') AS dateinfo, SUM(order_items.price * order_items.quantity) AS sum")
                ->groupBy('dateinfo')
                ->get();
    }
}
