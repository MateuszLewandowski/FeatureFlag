<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Exception\InvalidEndsAtException;
use JsonSerializable;
use Shared\Domain\DateTimeSerializable;
use Shared\ValueObject;

final class EndsAt implements JsonSerializable, ValueObject, DateTimeSerializable
{
    public function __construct(
        public readonly DateTimeImmutable $value,
        ?StartsAt $startsAt = null,
    ) {
        if (null === $startsAt) {
            return;
        }

        if ($startsAt->value > $this->value) {
            throw new InvalidEndsAtException();
        }
    }

    public function jsonSerialize(): string
    {
        return $this->value->format(self::FORMAT);
    }
}
