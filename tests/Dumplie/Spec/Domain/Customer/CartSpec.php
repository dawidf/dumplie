<?php

namespace Spec\Dumplie\Domain\Customer;

use Dumplie\Domain\Customer\CartId;
use Dumplie\Domain\Customer\Exception\ProductNotAvailableException;
use Dumplie\Domain\Customer\Exception\ProductNotInCartException;
use Dumplie\Domain\Customer\Product;
use Dumplie\Domain\SharedKernel\Exception\InvalidCurrencyException;
use Dumplie\Domain\SharedKernel\Money\Price;
use Dumplie\Domain\SharedKernel\Product\SKU;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CartSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(CartId::generate(), 'EUR');
    }

    function it_is_empty_by_default()
    {
        $this->isEmpty()->shouldReturn(true);
    }

    function it_accepts_new_products()
    {
        $this->add(new Product(new SKU("DUMPLIE_SKU_1"), Price::fromInt(100, 'EUR'), true), 1);

        $this->isEmpty()->shouldReturn(false);
        $this->items()->shouldHaveCount(1);
    }

    function it_replace_products_with_same_sku()
    {
        $this->add(new Product(new SKU("DUMPLIE_SKU_1"), Price::fromInt(100, 'EUR'), true), 1);

        $this->add(new Product(new SKU("DUMPLIE_SKU_1"), Price::fromInt(100, 'EUR'), true), 4);


        $this->isEmpty()->shouldReturn(false);
        $this->items()->shouldHaveCount(1);
    }

    function it_throws_exception_when_adding_product_with_different_currencies()
    {
        $this->add(new Product(new SKU("DUMPLIE_SKU_1"), Price::EUR(100), true), 1);

        $this->shouldThrow(InvalidCurrencyException::class)
            ->during('add', [new Product(new SKU("DUMPLIE_SKU_2"), Price::PLN(100), true), 1]);
    }

    function it_throws_exception_when_adding_unavailable_product()
    {
        $this->shouldThrow(ProductNotAvailableException::class)
            ->during('add', [new Product(new SKU("DUMPLIE_SKU_1"), Price::PLN(100), false), 1]);
    }

    function it_throws_exception_when_removing_products_that_is_not_in_cart()
    {
        $this->shouldThrow(ProductNotInCartException::class)
            ->during('remove', [new SKU('NOT_EXISTING_SKU')]);
    }

    function it_allows_to_remove_items()
    {
        $sku = new SKU("DUMPLIE_SKU_1");
        $this->add(new Product($sku, Price::EUR(100), true), 1);

        $this->remove($sku);

        $this->isEmpty()->shouldReturn(true);
    }
}
