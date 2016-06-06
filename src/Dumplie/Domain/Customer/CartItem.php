<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\Exception\InvalidArgumentException;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class CartItem
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @var SKU
     */
    private $sku;

    /**
     * @param SKU $sku
     * @param int $quantity
     *
     * @throws InvalidArgumentException
     */
    public function __construct(SKU $sku, int $quantity)
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException();
        }

        $this->quantity = $quantity;
        $this->sku = $sku;
    }

    /**
     * @return SKU
     */
    public function sku() : SKU
    {
        return $this->sku();
    }

    /**
     * @return int
     */
    public function quantity()
    {
        return $this->quantity;
    }
}
