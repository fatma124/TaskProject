<?php
namespace App\Http\Controllers\Api\Order;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|string'
        ]);

        $order = Order::findOrFail($data['order_id']);

        if ($order->status !== 'confirmed') {
            return response()->json(['error' => 'Order must be confirmed'], 400);
        }

        $gateway = PaymentGatewayFactory::make($data['method']);
        $success = $gateway->pay($order);

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => $data['method'],
            'status' => $success ? 'successful' : 'failed'
        ]);

        return response()->json($payment, 201);
    }
}
