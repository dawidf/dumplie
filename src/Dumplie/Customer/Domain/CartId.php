<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\SharedKernel\Domain\Identity\UUID;
use Ramsey\Uuid\Uuid as BaseUUID;

final class CartId extends UUID
{
    /**
     * @return CartId
     */
    public static function generate() : CartId
    {
        return new self((string) BaseUUID::uuid4());
    }
}
