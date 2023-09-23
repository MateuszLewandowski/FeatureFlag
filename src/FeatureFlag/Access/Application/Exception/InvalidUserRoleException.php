<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Exception;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidUserRoleException extends InvalidArgumentException
{
    public function __construct(int $role)
    {
        parent::__construct(sprintf('Invalid user role has been provided "%s"', $role), Response::HTTP_BAD_REQUEST);
    }
}
