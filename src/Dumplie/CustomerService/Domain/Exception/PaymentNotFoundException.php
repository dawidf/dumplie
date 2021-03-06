<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain\Exception;

use Dumplie\CustomerService\Domain\PaymentId;

class PaymentNotFoundException extends Exception
{
    /**
     * @param PaymentId $paymentId
     *
     * @return PaymentNotFoundException
     */
    public static function byId(PaymentId $paymentId): PaymentNotFoundException
    {
        return new self(sprintf('Payment with id "%s" does not exists.', (string)$paymentId));
    }
}
