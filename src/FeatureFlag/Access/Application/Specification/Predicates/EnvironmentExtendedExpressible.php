<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Specification\Predicates;

use FeatureFlag\Access\Domain\FeatureFlag;

interface EnvironmentExtendedExpressible extends Expressible
{
    public function execute(FeatureFlag $featureFlag): bool;
}
