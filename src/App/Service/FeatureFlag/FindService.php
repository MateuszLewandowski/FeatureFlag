<?php

declare(strict_types=1);

namespace App\Service\FeatureFlag;

use FeatureFlag\Access\Application\DTO\FeatureFlagDTO;
use FeatureFlag\Access\Application\ReadableRepository;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use Shared\Application\Factory\FeatureFlagDTOFactory;

final class FindService
{
    public function __construct(
        private readonly ReadableRepository $repository
    ) {
    }
    
    public function find(FeatureFlagId $featureFlagId): FeatureFlagDTO
    {
        return FeatureFlagDTOFactory::create($this->repository->get($featureFlagId));
    }
}
