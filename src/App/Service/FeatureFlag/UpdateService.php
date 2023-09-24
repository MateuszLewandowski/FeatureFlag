<?php

declare(strict_types=1);

namespace App\Service\FeatureFlag;

use FeatureFlag\Access\Application\SettableRepository;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

final class UpdateService
{
    public function __construct(
        private readonly SettableRepository $repository
    ) {
    }
    
    public function update(FeatureFlagId $featureFlagId, FeatureFlagConfig $featureFlagConfig): void
    {
        $this->repository->update($featureFlagId, $featureFlagConfig);
    }
}
