<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use DateTimeImmutable;
use JsonSerializable;
use Shared\ValueObject;

final class DateThreshold implements JsonSerializable, ValueObject
{
    public function __construct(
        public readonly DateTimeImmutable $value,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->value->format('Y-m-d'),
            'timeZone' => $this->value->getTimezone()->getName(),
        ];
    }
}
