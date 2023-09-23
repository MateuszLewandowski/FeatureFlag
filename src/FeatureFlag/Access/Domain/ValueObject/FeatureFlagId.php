<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidFeatureFlagNameException;
use Shared\ValueObject;

final class FeatureFlagId implements ValueObject
{
    public function __construct(
        public readonly string $value,
    ) {
        if (!preg_match('/[a-zA-Z0-9-_.]{2,64}$/', $value)) {
            throw new InvalidFeatureFlagNameException($value);
        }
    }
}
