<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Factory;

use App\Entity\FeatureFlag as Entity;
use FeatureFlag\Access\Domain\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use Shared\Domain\DateTimeSerializable;
use Symfony\Component\HttpFoundation\Request;

final class FeatureFlagConfigFactory
{
    public static function createWithRequest(Request $request): FeatureFlagConfig
    {
        return FeatureFlagConfigBuilder::create()
            ->setForceGrantAccess($request->get('forceGrantAccess', false))
            ->setStartsAt($request->get('startsAt'))
            ->setEndsAt($request->get('endsAt'))
            ->setUserEmailDomainNames(json_decode($request->request->getString('userEmailDomainNames', '[]'), true))
            ->setUserIds(json_decode($request->request->getString('userIds', '[]'), true))
            ->setUserRoles(json_decode($request->request->getString('userRoles', '[]'), true))
            ->setModuloUserId($request->request->getInt('moduloUserId') ?? null)
            ->build();
    }

    public static function createWithEntity(Entity $entity): FeatureFlagConfig
    {
        return FeatureFlagConfigBuilder::create()
            ->setForceGrantAccess($entity->isForceGrantAccess())
            ->setStartsAt($entity->getStartsAt()?->format(DateTimeSerializable::FORMAT))
            ->setEndsAt($entity->getEndsAt()?->format(DateTimeSerializable::FORMAT))
            ->setUserEmailDomainNames($entity->getUserEmailDomainNames())
            ->setUserIds($entity->getUserIds())
            ->setUserRoles($entity->getUserRoles())
            ->setModuloUserId($entity->getModuloUserId())
            ->build();
    }
}
