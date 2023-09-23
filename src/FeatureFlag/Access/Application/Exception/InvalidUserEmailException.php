<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Exception;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidUserEmailException extends InvalidArgumentException
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('Invalid user email has been provided "%s"', $email), Response::HTTP_BAD_REQUEST);
    }
}
