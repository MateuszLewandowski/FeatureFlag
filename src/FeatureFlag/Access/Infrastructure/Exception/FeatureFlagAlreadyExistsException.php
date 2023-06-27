<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Infrastructure\Exception;

use FeatureFlag\Access\Domain\FeatureFlag;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class FeatureFlagAlreadyExistsException extends RuntimeException
{
    public function __construct(FeatureFlag $featureFlag)
    {
        parent::__construct(sprintf('Feature flag %s already exists', $featureFlag->id->value), Response::HTTP_CONFLICT);
    }
}
