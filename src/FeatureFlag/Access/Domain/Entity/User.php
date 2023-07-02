<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Entity;

use FeatureFlag\Access\Domain\ValueObject\UserEmail;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;

final class User
{
    public function __construct(
        public readonly ?UserId $id,
        public readonly ?UserRole $role,
        public readonly ?UserEmail $email,
    ) {}
}
