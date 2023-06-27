<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Exception;

use InvalidArgumentException;

final class InvalidDateThresholdTimeZoneException extends InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
