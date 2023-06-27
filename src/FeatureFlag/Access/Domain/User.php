<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain;

use FeatureFlag\Access\Domain\Factory\UserBuilder;
use FeatureFlag\Access\Domain\ValueObject\UserEmail;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use Symfony\Component\HttpFoundation\Request;

final class User implements RequestCreatable
{
    public function __construct(
        public readonly ?UserId $id,
        public readonly ?UserRole $role,
        public readonly ?UserEmail $email,
    ) {}

    public static function createWithRequest(Request $request): self
    {
        return UserBuilder::create()
            ->setId((int)$request->get('userId'))
            ->setRole((int)$request->get('userRole'))
            ->setEmail($request->get('userEmail'))
            ->build();
    }
}
