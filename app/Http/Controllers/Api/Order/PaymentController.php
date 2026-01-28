<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Payments\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class PaymentController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        return response()->json(
            $query->paginate(10),
            Response::HTTP_OK
        );
    }

   
    public function store(Request $request)
    {
      
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method'   => 'required|string|in:credit_card,paypal',
        ]);

      
        $order = Order::findOrFail($data['order_id']);

       
        if ($order->status !== 'confirmed') {
            return response()->json(
                ['message' => 'Payment can only be processed for confirmed orders'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
         
            $gateway = PaymentGatewayFactory::make($data['method']);

           
            $isSuccessful = $gateway->pay($order);

            
            $payment = Payment::create([
                'order_id' => $order->id,
                'method'   => $data['method'],
                'status'   => $isSuccessful ? 'successful' : 'failed',
            ]);

            return response()->json(
                $payment,
                Response::HTTP_CREATED
            );

        } catch (Exception $e) {
          
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
