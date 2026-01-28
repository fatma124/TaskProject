<?php

namespace App\Payments;

use Exception;

class PaymentGatewayFactory
{
    public static function make(string $method): PaymentGatewayInterface
    {
        return match ($method) {
            'credit_card' => config('payments.credit_card')
                ? new CreditCardGateway()
                : throw new Exception('Credit Card disabled'),

            'paypal' => config('payments.paypal')
                ? new PaypalGateway()
                : throw new Exception('Paypal disabled'),

            default => throw new Exception('Invalid payment method'),
        };
    }
}
