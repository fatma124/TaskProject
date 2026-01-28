<?php

namespace App\Payments;

use App\Models\Order;

class PaypalGateway implements PaymentGatewayInterface
{
    public function pay(Order $order): bool
    {
        
        return rand(0, 1) === 1;
    }
}
