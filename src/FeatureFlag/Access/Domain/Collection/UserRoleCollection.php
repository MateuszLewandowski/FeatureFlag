<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Collection;

use FeatureFlag\Access\Domain\ValueObject\UserRole;

final class UserRoleCollection extends ValueObjectCollection
{
    public function __construct(?array $userRoles = [])
    {
        parent::__construct(UserRole::class, $userRoles);
    }
}
