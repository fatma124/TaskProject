<?php

namespace App\Http\Controllers\Api\Order;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        
            $data = $request->validate([
                'items' => 'required|array',
                'items.*.product_name' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            $total = collect($data['items'])
                ->sum(fn($item) => $item['quantity'] * $item['price']);

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending'
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            return response()->json($order->load('items'), 201);
        }

}
