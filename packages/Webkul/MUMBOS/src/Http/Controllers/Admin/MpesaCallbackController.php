<?php
namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Models\Share;
use Webkul\Customer\Models\Customer;
use Illuminate\Http\Request;
use Webkul\MUMBOS\Models\Contribution;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{

public function handleCallback(Request $request)
{
    \Log::error('hit the callback method');

    $payload = json_decode($request->getContent(), true);

    if (! $payload) {
        \Log::error('Invalid M-Pesa Callback JSON');
        return response()->json(['error' => 'Invalid callback data'], 400);
    }

    // Log full callback
    \Log::info('Handling M-Pesa Callback in Gateway: ', $payload);

    // Delegate to gateway
    app(\Webkul\MUMBOS\Services\Payments\MpesaGateway::class)->handleCallback($payload);

    return response()->json(['message' => 'Callback processed'], 200);
}

}