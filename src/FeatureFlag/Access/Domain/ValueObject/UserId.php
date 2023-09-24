<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidUserIdException;
use Shared\ValueObject;

final class UserId implements ValueObject
{
    private const MIN = 1;

    public function __construct(
        public readonly int $value,
    ) {
        if ($value < self::MIN) {
            throw new InvalidUserIdException($value);
        }
    }
}
