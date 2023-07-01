<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class UserEmailNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User email address has not been provided', Response::HTTP_NOT_FOUND);
    }
}
