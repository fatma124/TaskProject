<?php
    namespace App\Services\PaymentGateways;
    
    class PaymentGatewayFactory
    {
        public static function make(string $method): PaymentGatewayInterface
        {
            return match ($method) {
                'credit_card' => new CreditCardGateway(),
                'paypal' => new PaypalGateway(),
                default => throw new Exception('Invalid payment method')
            };
        }
    }
