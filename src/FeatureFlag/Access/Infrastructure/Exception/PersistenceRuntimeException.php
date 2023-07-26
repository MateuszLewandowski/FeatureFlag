<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Infrastructure\Exception;

use RuntimeException;
use Throwable;

final class PersistenceRuntimeException extends RuntimeException
{
    public function __construct(Throwable $previous)
    {
        parent::__construct('Database is not working properly', $previous->getCode(), $previous);
    }
}
