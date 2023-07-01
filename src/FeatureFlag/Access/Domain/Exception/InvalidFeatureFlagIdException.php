<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;
use InvalidArgumentException;

final class InvalidFeatureFlagIdException extends InvalidArgumentException
{
    public function __construct(string $featureFlagId)
    {
        parent::__construct(sprintf('Invalid feature flag id has been provided "%s"', $featureFlagId), Response::HTTP_BAD_REQUEST);
    }
}
