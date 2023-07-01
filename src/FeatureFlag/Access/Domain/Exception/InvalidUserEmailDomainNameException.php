<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Exception;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidUserEmailDomainNameException extends InvalidArgumentException
{
    public function __construct(string $domain)
    {
        parent::__construct(sprintf('Invalid user email domain has been provided "%s"', $domain), Response::HTTP_BAD_REQUEST);
    }
}
