<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\User;

interface UserExtendedExpressible extends Expressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool;
}
