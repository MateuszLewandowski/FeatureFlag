<?php

declare(strict_types=1);

namespace App\Service\FeatureFlag;

use FeatureFlag\Access\Application\SettableRepository;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

final class DeleteService
{
    public function __construct(
        private readonly SettableRepository $repository
    ) {
    }
    
    public function delete(FeatureFlagId $featureFlagId): void
    {
        $this->repository->delete($featureFlagId);
    }
}
