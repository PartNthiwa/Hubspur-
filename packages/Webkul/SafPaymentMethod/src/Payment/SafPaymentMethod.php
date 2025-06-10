<?php

namespace Webkul\SafPaymentMethod\Payment;

use Webkul\Payment\Payment\Payment;

class SafPaymentMethod extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'safpaymentmethod';

    public function getRedirectUrl()
    {
        
    }
}