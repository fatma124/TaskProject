<?php

namespace App\Payments;

use App\Models\Order;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function pay(Order $order): bool
    {
       
        return true; 
    }
}
