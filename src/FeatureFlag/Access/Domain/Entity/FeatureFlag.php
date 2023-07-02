<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Entity;

use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

class FeatureFlag
{
    public function __construct(
        public readonly FeatureFlagId $id,
        public readonly FeatureFlagConfig $config
    ) {}
}
