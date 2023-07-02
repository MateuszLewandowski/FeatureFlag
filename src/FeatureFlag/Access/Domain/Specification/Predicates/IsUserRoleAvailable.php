<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Exception\UserRoleNotFoundException;

final class IsUserRoleAvailable implements UserExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool
    {
        if (null === $user->role?->value) {
            throw new UserRoleNotFoundException();
        }

        return $featureFlag->config->userRoles->exists($user->role->value);
    }
}
