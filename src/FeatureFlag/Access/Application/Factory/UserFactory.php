<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Factory;

use FeatureFlag\Access\Application\Builder\UserBuilder;
use FeatureFlag\Access\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Request;

final class UserFactory
{
    public static function createWithRequest(Request $request): User
    {
        return UserBuilder::create()
            ->setId((int)$request->get('userId'))
            ->setRole((int)$request->get('userRole'))
            ->setEmail($request->get('userEmail'))
            ->build();
    }
}
