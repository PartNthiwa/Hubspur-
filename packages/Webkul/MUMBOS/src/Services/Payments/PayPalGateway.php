<?php
namespace Webkul\MUMBOS\Services\Payments;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Webkul\MUMBOS\Services\Payments\Contracts\PaymentGateway;

class PayPalGateway implements PaymentGateway
{
    protected PayPalHttpClient $client;

    public function __construct()
    {
        $env    = new SandboxEnvironment(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        );
        $this->client = new PayPalHttpClient($env);
    }

    public function initiate(array $payload): array
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent'          => 'CAPTURE',
            'purchase_units'  => [[
                'amount' => [
                    'currency_code' => $payload['currency'],
                    'value'         => $payload['amount'],
                ],
            ]],
            'application_context' => [
                'return_url' => route('shop.payments.paypal.success'),
                'cancel_url' => route('shop.payments.paypal.cancel'),
            ],
        ];

        $resp = $this->client->execute($request);

        return [
            'orderID'      => $resp->result->id,
            'approveLink'  => collect($resp->result->links)
                                ->firstWhere('rel','approve')->href,
        ];
    }

    public function handleCallback(array $data): void
    {
        // capture order, update Contribution…
    }
}
