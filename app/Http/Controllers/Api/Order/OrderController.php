<?php

namespace App\Http\Controllers\Api\Order;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); 
    }
   

    public function update(Request $request, Order $order)
    {
    
        if ($order->user_id !== auth()->id()) {
            return response()->json(
                ['message' => 'Unauthorized'],
                Response::HTTP_FORBIDDEN
            );
        }

       
        if ($order->status !== 'pending') {
            return response()->json(
                ['message' => 'Only pending orders can be updated'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($order->payments()->exists()) {
            return response()->json(
                ['message' => 'Order with payments cannot be updated'],
                Response::HTTP_BAD_REQUEST
            );
        }

    
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

      
        $total = collect($data['items'])
            ->sum(fn ($item) => $item['quantity'] * $item['price']);

      
        $order->update(['total' => $total]);

       
        $order->items()->delete();

        foreach ($data['items'] as $item) {
            $order->items()->create($item);
        }

        return response()->json(
            $order->load('items'),
            Response::HTTP_OK
        );
    }

    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id());

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You must be logged in to place an order.'
            ], 401);
        }
            $data = $request->validate([
                'items' => 'required|array',
                'items.*.product_name' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            $total = collect($data['items'])
                ->sum(fn($item) => $item['quantity'] * $item['price']);

            $order = Order::create([
                'user_id' => auth('api')->id(),
                'total' => $total,
                'status' => 'pending'
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            return response()->json($order->load('items'), 201);
        }

        public function destroy(Order $order)
        {
            $this->authorize('delete', $order);

            $order->delete();

            return response()->json(['message' => 'Order deleted']);
        }


}
