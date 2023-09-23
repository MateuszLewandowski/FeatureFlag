<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Exception;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidModuloUserIdException extends InvalidArgumentException
{
    public function __construct(int $modulo)
    {
        parent::__construct(sprintf('Invalid modulo value has been provided "%s"', $modulo), Response::HTTP_BAD_REQUEST);
    }
}
