<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Exception;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidUserIdException extends InvalidArgumentException
{
    public function __construct(int $id)
    {
        parent::__construct(sprintf('Invalid user id has been provided "%s"', $id), Response::HTTP_BAD_REQUEST);
    }
}
