<?php

declare(strict_types=1);

namespace App\Service\FeatureFlag;

use FeatureFlag\Access\Application\DTO\FeatureFlagDTO;
use FeatureFlag\Access\Application\ReadableRepository;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use Shared\Application\Factory\FeatureFlagDTOFactory;

final class FindAllService
{
    public function __construct(
        private readonly ReadableRepository $repository
    ) {
    }
    
    /** @return FeatureFlagDTO[] */
    public function findAll(): array
    {
        return array_map(
            static fn (FeatureFlag $featureFlag) => FeatureFlagDTOFactory::create($featureFlag),
            $this->repository->getFeatureFlags()
        );        
    }
}
