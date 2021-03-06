<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain\Exception;

use Dumplie\Customer\Domain\CartId;

class CheckoutNotFoundException extends NotFoundException
{
    /**
     * @param CartId $cartId
     *
     * @return CheckoutNotFoundException
     */
    public static function byCartId(CartId $cartId): CheckoutNotFoundException
    {
        return new self(sprintf('Checkout for cart id "%s" does not exists.', (string) $cartId));
    }
}