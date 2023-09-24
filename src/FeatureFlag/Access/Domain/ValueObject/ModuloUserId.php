<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidModuloUserIdException;
use Shared\ValueObject;

final class ModuloUserId implements ValueObject
{
    private const MIN = 1;
    private const MAX = 100;

    public function __construct(
        public readonly int $value
    ) {
        if (self::MIN >= $value || self::MAX < $value) {
            throw new InvalidModuloUserIdException($value);
        }
    }
}
