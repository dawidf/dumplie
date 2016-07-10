<?php

declare (strict_types = 1);

namespace Dumplie\Test\Integration\Application\Generic\Customer;

use Dumplie\Application\Command\Customer\AddToCart;
use Dumplie\Application\Command\Customer\RemoveFromCart;
use Dumplie\Application\Transaction\Factory;
use Dumplie\Test\Context\CustomerContext;

abstract class CartTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    protected $transactionFactory;

    /**
     * @var CustomerContext
     */
    protected $customerContext;

    /**
     * @return mixed
     */
    abstract public function clear();

    function test_adding_new_products_to_cart()
    {
        $cartId = $this->customerContext->createEmptyCart('EUR');
        $command = new AddToCart('SKU_1', 2, (string) $cartId);

        $this->customerContext->commandBus()->handle($command);

        $this->clear();
        $this->assertCount(1, $this->customerContext->carts()->getById($cartId)->items());
    }

    function test_removing_products_from_cart()
    {
        $cartId = $this->customerContext->createEmptyCart('EUR');
        $addCommand = new AddToCart('SKU_1', 2, (string) $cartId);
        $this->customerContext->commandBus()->handle($addCommand);

        $removeCommand = new RemoveFromCart((string) $cartId, 'SKU_1');
        $this->customerContext->commandBus()->handle($removeCommand);

        $this->clear();
        $cart = $this->customerContext->carts()->getById($cartId);
        $this->assertTrue($cart->isEmpty());
    }
}