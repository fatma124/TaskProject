<?php

namespace App\Payments;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function pay(Order $order): bool;
}
