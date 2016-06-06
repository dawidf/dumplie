<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\Customer;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Carts;
use Dumplie\Domain\Customer\Products;
use Dumplie\Domain\SharedKernel\Product\SKU;

final class AddToCartHandler
{
    /**
     * @var Carts
     */
    private $carts;

    /**
     * @var Products
     */
    private $products;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * AddToCartHandler constructor.
     *
     * @param Products $products
     * @param Carts    $carts
     */
    public function __construct(Products $products, Carts $carts, Factory $factory)
    {
        $this->products = $products;
        $this->carts = $carts;
        $this->factory = $factory;
    }

    /**
     * @param AddToCart $command
     *
     * @throws \Exception
     */
    public function handle(AddToCart $command)
    {
        $product = $this->products->getBySku(new SKU($command->sku()));
        $cart = $this->carts->getById(new CartId($command->cartId()));

        $transaction = $this->factory->open();

        try {
            $cart->add($product, $command->quantity());
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
