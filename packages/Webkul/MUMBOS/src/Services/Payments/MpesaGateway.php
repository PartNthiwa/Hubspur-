<?php

namespace Webkul\MUMBOS\Services\Payments;

use Webkul\MUMBOS\Models\Contribution;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\MUMBOS\Models\Shareholder;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\StreamedResponse;
use Illuminate\Support\Facades\URL;
use Webkul\MUMBOS\Services\Payments\Contracts\PaymentGateway;

class MpesaGateway implements PaymentGateway
{


protected Client $http;

public function __construct()
{
    $this->http = new Client();
}


public function checkTransactionStatus(string $checkoutRequestId): array
{
    $accessToken = $this->getAccessToken();

    $payload = [
        "BusinessShortCode" => env('MPESA_SHORTCODE'),
        "Password" => base64_encode(env('MPESA_SHORTCODE') . env('MPESA_PASSKEY') . now()->format('YmdHis')),
        "Timestamp" => now()->format('YmdHis'),
        "CheckoutRequestID" => $checkoutRequestId,
    ];

    try {
        $response = $this->http->post('https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ],
            'json' => $payload
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        \Log::info('M-Pesa Transaction Status Response: ', $data);

        // ✅ Map response to simplified status
        if (isset($data['ResultCode'])) {
            return match ($data['ResultCode']) {
                '0'     => ['status' => 'Success', 'message' => $data['ResultDesc'] ?? 'Payment successful.'],
                '1032'  => ['status' => 'Cancelled', 'message' => $data['ResultDesc'] ?? 'User cancelled.'],
                '1', '1037' => ['status' => 'Failed', 'message' => $data['ResultDesc'] ?? 'Payment failed.'],
                default => ['status' => 'Failed', 'message' => $data['ResultDesc'] ?? 'Unknown error.'],
            };
        }

        return ['status' => 'Pending', 'message' => $data['ResponseDescription'] ?? 'Still processing.'];

    } catch (\Exception $e) {
        \Log::error('STK Status API Error: ' . $e->getMessage());
        return ['status' => 'Failed', 'message' => 'Request error.'];
    }
}



protected function getAccessToken(): string
{
    $consumerKey    = config('services.mpesa.consumer_key');
    $consumerSecret = config('services.mpesa.consumer_secret');

    $credentials = base64_encode("{$consumerKey}:{$consumerSecret}");

    $response = $this->http->request('GET', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', [
        'headers' => [
            'Authorization' => "Basic {$credentials}",
        ],
    ]);

    $body = json_decode($response->getBody()->getContents(), true);


    return $body['access_token'] ?? throw new \Exception('Unable to fetch M-Pesa access token');
}

public function initiate(array $payload): array
{

    \Log::info('Initiating STK with payload:', $payload);

    $timestamp   = now()->format('YmdHis');
    $shortcode   = config('services.mpesa.shortcode');
    $passkey     = config('services.mpesa.passkey');
    $callbackUrl = route('clk', [], true);
    $password    = base64_encode($shortcode . $passkey . $timestamp);


    $body = [
        'BusinessShortCode' => $shortcode,
        'Password'          => $password,
        'Timestamp'         => $timestamp,
        'TransactionType'   => 'CustomerPayBillOnline',
        'Amount'            => $payload['amount'],
        'PartyA'            => $payload['phone'],
        'PartyB'            => $shortcode,
        'PhoneNumber'       => $payload['phone'],
        'CallBackURL'       => $callbackUrl,
        'AccountReference'  => $payload['payment_reference'],
        'TransactionDesc'   => 'Contribution Payment',
    ];

    try {
        $accessToken = $this->getAccessToken();

        $response = $this->http->post(config('services.mpesa.endpoint'), [
            'json' => $body,
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
        ]);

        $responseBody = $response->getBody()->getContents();
        \Log::info('M-Pesa STK Response Raw:', ['response' => $responseBody]);

        $data = json_decode($responseBody, true);

        if (!isset($data['CheckoutRequestID'])) {
            \Log::warning('Unexpected M-Pesa STK Response', $data);
            throw new \Exception('Unexpected M-Pesa response: ' . $responseBody);
        }

        return [
            'status'              => 'initiated',
            'reference'           => $data['CheckoutRequestID'],
            'merchant_request_id' => $data['MerchantRequestID'],
            'response'            => $data,
        ];


        if (!empty($payload['shareholder_id'])) {
        \Webkul\MUMBOS\Models\Shareholder::where('id', $payload['shareholder_id'])->update([
            'phone'              => $payload['phone'],
            'payment_reference'  => $data['CheckoutRequestID'], 
            'payment_channel'    => 'mpesa',
        ]);
    }
    } catch (\Exception $e) {
        \Log::error('M-Pesa STK Push Error', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ]);

        return [
            'status'  => 'error',
            'message' => 'Failed to initiate M-Pesa STK Push. Please try again later.',
        ];
    }
}



public function handleCallback(array $callbackData): void
{
    \Log::info('Handling M-Pesa Callback in Gateway:', $callbackData);

    $stk = $callbackData['Body']['stkCallback'] ?? [];

    $reference = $stk['CheckoutRequestID'] ?? null;
    $resultCode = $stk['ResultCode'] ?? 1;
    $resultDesc = $stk['ResultDesc'] ?? 'Unknown';

    $items = collect($stk['CallbackMetadata']['Item'] ?? []);

    $amount  = $items->firstWhere('Name', 'Amount')['Value'] ?? null;
    $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
    $phone   = $items->firstWhere('Name', 'PhoneNumber')['Value'] ?? null;

    \Log::info('Extracted M-Pesa Callback Data:', [
        'checkoutRequestID' => $reference,
        'resultCode'        => $resultCode,
        'resultDesc'        => $resultDesc,
        'amount'            => $amount,
        'receipt'           => $receipt,
        'phone'             => $phone,
    ]);

    if ($reference && $resultCode === 0) {
        Contribution::where('payment_reference', $reference)
            ->update([
                'payment_status'        => 'completed',
                'mpesa_receipt_number'  => $receipt,
                'phone'                 => $phone,
                'amount'                => $amount,
                'status'                => 'approved',
            ]);

        \Log::info("M-Pesa payment successful and recorded for reference {$reference}");
    } else {
     
    Contribution::where('payment_reference', $reference)
        ->update([
            'payment_status' => 'failed',
            'note'           => $resultDesc,
        ]);

        \Log::warning("M-Pesa Callback received for reference {$reference} but not successful. ResultCode: {$resultCode}");
    }
}

}
