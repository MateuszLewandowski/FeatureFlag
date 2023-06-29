<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Factory;

use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\Specification\AccessSpecification;
use FeatureFlag\Access\Domain\Specification\Predicates\DoesUserEmailAddressIncludesDomain;
use FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo;
use FeatureFlag\Access\Domain\Specification\Predicates\IsDateThresholdExceeded;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable;

final class AccessSpecificationFactory
{
    private const MAP = [
        'dateThreshold' => IsDateThresholdExceeded::class,
        'userIds' => IsUserIdAvailable::class,
        'userRoles' => IsUserRoleAvailable::class,
        'userEmailDomainNames' => DoesUserEmailAddressIncludesDomain::class,
        'moduloUserId' => DoesUserIdSatisfyModulo::class,
    ];

    public static function create(FeatureFlag $featureFlag): AccessSpecification
    {
        $accessSpecification = new AccessSpecification();

        foreach (self::MAP as $property => $expressible) {
            if ($featureFlag->config?->{$property} && class_exists($expressible)) {
                $accessSpecification->push(new $expressible());
            }
        }

        return $accessSpecification;
    }
}
