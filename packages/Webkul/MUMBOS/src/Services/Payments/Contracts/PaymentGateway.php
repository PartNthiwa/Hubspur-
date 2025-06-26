<?php
namespace Webkul\MUMBOS\Services\Payments\Contracts;

interface PaymentGateway
{
    /** @param  array  $payload
      * @return array   (eg. redirect URL, transaction IDs, etc)
      */
    public function initiate(array $payload): array;

    /** @param  array  $callbackData */
    public function handleCallback(array $callbackData): void;
}