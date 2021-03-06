<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine\ORM\Domain;

use Doctrine\ORM\EntityManager;
use Dumplie\Customer\Domain\Cart;
use Dumplie\Customer\Domain\CartId;
use Dumplie\Customer\Domain\Carts;
use Dumplie\Customer\Domain\Exception\CartNotFoundException;

final class ORMCarts implements Carts
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param CartId $cartId
     * @return Cart
     * @throws CartNotFoundException
     */
    public function getById(CartId $cartId) : Cart
    {
        $cart = $this->entityManager->getRepository(Cart::class)->findOneBy(['id.id' => $cartId]);

        if ($cart === null) {
            throw CartNotFoundException::byId($cartId);
        }

        return $cart;
    }

    /**
     * @param Cart $cart
     */
    public function add(Cart $cart)
    {
        $this->entityManager->persist($cart);
    }

    /**
     * @param CartId $cartId
     * @throws CartNotFoundException
     */
    public function remove(CartId $cartId)
    {
        $this->entityManager->remove($this->getById($cartId));
    }

    /**
     * @param CartId $cartId
     * @return bool
     */
    public function exists(CartId $cartId) : bool
    {
        $cart = $this->entityManager->getRepository(Cart::class)->findOneBy(['id.id' => $cartId]);

        return ($cart instanceof Cart);
    }
}