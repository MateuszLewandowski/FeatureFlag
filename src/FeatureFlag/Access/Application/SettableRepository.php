<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application;

use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

interface SettableRepository
{
    public function set(FeatureFlag $featureFlag): self;

    public function delete(FeatureFlagId $id): self;

    public function update(FeatureFlagId $id, FeatureFlagConfig $config): self;
}
