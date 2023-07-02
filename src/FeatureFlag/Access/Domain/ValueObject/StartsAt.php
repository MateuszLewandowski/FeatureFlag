<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use DateTimeImmutable;
use JsonSerializable;
use Shared\Domain\DateTimeSerializable;
use Shared\ValueObject;

final class StartsAt implements JsonSerializable, ValueObject, DateTimeSerializable
{
    public function __construct(
        public readonly DateTimeImmutable $value,
    ) {
    }

    public function jsonSerialize(): string
    {
        return $this->value->format(self::FORMAT);
    }
}
