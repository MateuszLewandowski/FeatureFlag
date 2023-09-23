<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidUserEmailException;
use Shared\ValueObject;

final class UserEmail implements ValueObject
{
    public function __construct(
        public readonly string $value,
    ) {
        if (!preg_match('/[a-z0-9.-]{2,255}@[a-z0-9]{2,255}[.][a-z]{2,4}$/', $value)) {
            throw new InvalidUserEmailException($value);
        }
    }
}
