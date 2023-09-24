<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Application\Exception\UserIdNotFoundException;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;

final class DoesUserIdSatisfyModulo implements UserExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool
    {
        if (null === $user->id?->value) {
            throw new UserIdNotFoundException();
        }

        return 0 === $user->id->value % $featureFlag->config->moduloUserId->value;
    }
}
