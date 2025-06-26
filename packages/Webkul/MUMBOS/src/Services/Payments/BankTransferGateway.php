<?php

namespace Webkul\MUMBOS\Services\Payments;

use Webkul\MUMBOS\Services\Payments\Contracts\PaymentGateway;

class BankTransferGateway implements PaymentGateway
{
    public function initiate(array $payload): array
    {
        // e.g. you might email instructions or generate a PDF invoice
        return [
            'instructions' => 'Pay into Acct: 123456 at XYZ Bank.',
            'reference'    => $payload['payment_reference'],
        ];
    }

    public function handleCallback(array $data): void
    {
        // not usually used for manual bank transfers
    }
}
