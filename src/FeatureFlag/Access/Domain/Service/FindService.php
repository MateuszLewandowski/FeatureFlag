<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Service;

use FeatureFlag\Access\Application\ReadableRepository;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

final class FindService
{
    public function __construct(
        private readonly ReadableRepository $repository
    ) {
    }
    
    public function find(FeatureFlagId $featureFlagId): FeatureFlag
    {
        return $this->repository->get($featureFlagId);
    }
}
