<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain\Exception;

use Dumplie\CustomerService\Domain\OrderId;

class OrderNotFoundException extends Exception
{
    /**
     * @param OrderId $orderId
     *
     * @return OrderNotFoundException
     */
    public static function byId(OrderId $orderId): OrderNotFoundException
    {
        return new self(sprintf('Order with id "%s" does not exists.', (string) $orderId));
    }
}
