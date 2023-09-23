<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application;

use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

interface ReadableRepository
{
    /** @return FeatureFlag[] */
    public function getFeatureFlags(): array;

    public function get(FeatureFlagId $id): FeatureFlag;
}
