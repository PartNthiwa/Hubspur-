<?php

use Webkul\MUMBOS\Services\Payments\Contracts\PaymentGateway;

class PaymentGatewayFactory
{
    public static function make(string $method): PaymentGateway
    {
        return match ($method) {
            'bank_transfer' => resolve(BankTransferGateway::class),
            'mpesa'         => resolve(MpesaGateway::class),
            'paypal'        => resolve(PayPalGateway::class),
            default         => throw new \InvalidArgumentException("Unsupported: $method"),
        };
    }
}
// Usage example:
// $gateway = PaymentGatewayFactory::make('mpesa');
// $response = $gateway->initiate(['amount' => 1000, 'phone' => '254712345678', 'payment_reference' => 'REF123']);
// echo $response['checkoutRequestID']; // Outputs the CheckoutRequestID from M-Pesa
// $gateway->handleCallback(['status' => 'success', 'transaction_id' => 'TX12345']);
// This would handle the callback from M-Pesa, updating your system accordingly
// Note: Ensure you have the necessary classes (BankTransferGateway, MpesaGateway, PayPalGateway) imported  
// and available in your application context.
// The factory method resolves the appropriate gateway class based on the payment method provided.
// It uses Laravel's service container to resolve dependencies, allowing for easy testing and flexibility.  
// The `initiate` method is used to start the payment process, while `handleCallback` processes the response from the payment gateway.
// This design allows you to easily add new payment gateways in the future by simply implementing the `PaymentGateway` interface
// and updating the factory method to include the new gateway class.    
// This approach promotes clean code practices, separation of concerns, and adheres to the Open/Closed Principle,
// allowing your application to be easily extensible without modifying existing code.
// Ensure you have the necessary classes (BankTransferGateway, MpesaGateway, PayPalGateway) imported
// and available in your application context.