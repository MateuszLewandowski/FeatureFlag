<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Infrastructure\Exception;

use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class FeatureFlagNotFoundException extends RuntimeException
{
    public function __construct(FeatureFlagId $id)
    {
        parent::__construct(sprintf('Feature flag %s not found', $id->value), Response::HTTP_NOT_FOUND);
    }
}
