<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class UserIdNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User id has not been provided', Response::HTTP_NOT_FOUND);
    }
}
