<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;
use InvalidArgumentException;

final class InvalidFeatureFlagNameException extends InvalidArgumentException
{
    public function __construct(string $featureFlagName)
    {
        parent::__construct(sprintf('Invalid feature flag name has been provided "%s"', $featureFlagName), Response::HTTP_BAD_REQUEST);
    }
}
