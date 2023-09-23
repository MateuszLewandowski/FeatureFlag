<?php

declare(strict_types=1);

namespace App\Tests\Integration\Access\Domain\Factory;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Application\Factory\AccessSpecificationFactory;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Specification\AccessSpecification;
use FeatureFlag\Access\Domain\Specification\Predicates\DoesUserEmailAddressIncludesDomain;
use FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo;
use FeatureFlag\Access\Domain\Specification\Predicates\IsEndsAtDateExceeded;
use FeatureFlag\Access\Domain\Specification\Predicates\IsStartsAtDateExceeded;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\Factory\AccessSpecificationFactory
 * @covers \FeatureFlag\Access\Domain\Specification\AccessSpecification
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserEmailAddressIncludesDomain
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsStartsAtDateExceeded
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable
 * @covers \FeatureFlag\Access\Application\Collection\UserEmailDomainNameCollection
 * @covers \FeatureFlag\Access\Application\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Application\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Application\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\Entity\FeatureFlag
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Domain\ValueObject\ModuloUserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Domain\ValueObject\StartsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\EndsAt
 */
final class AccessSpecificationFactoryTest extends TestCase
{
    public function testCreateAccessSpecification(): void
    {
        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key'),
            FeatureFlagConfigBuilder::create()->build()
        );

        $accessSpecification = AccessSpecificationFactory::create($featureFlag);

        $this->assertInstanceOf(AccessSpecification::class, $accessSpecification);
    }

    public function testCreateBasicSpecification(): void
    {
        $expectedExpressions = [
            IsUserRoleAvailable::class,
            DoesUserIdSatisfyModulo::class,
        ];

        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key'),
            FeatureFlagConfigBuilder::create()
                ->setUserRoles([1, 4, 7])
                ->setModuloUserId(4)
                ->build()
        );

        $expressions = AccessSpecificationFactory::create($featureFlag)->pull();

        foreach ($expressions as $expression) {
            $this->assertTrue(in_array($expression::class, $expectedExpressions));
        }
    }

    public function testCreateFullSpecification(): void
    {
        $expectedExpressions = [
            IsStartsAtDateExceeded::class,
            IsEndsAtDateExceeded::class,
            IsUserRoleAvailable::class,
            DoesUserIdSatisfyModulo::class,
            IsStartsAtDateExceeded::class,
            IsUserIdAvailable::class,
            DoesUserEmailAddressIncludesDomain::class,
        ];

        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key'),
            FeatureFlagConfigBuilder::create()
                ->setUserRoles([1, 4, 7])
                ->setUserIds([1, 5, 7])
                ->setUserEmailDomainNames(['gmail.com'])
                ->setStartsAt((new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'))
                ->setEndsAt((new DateTimeImmutable('next monday'))->format('Y-m-d H:i:s'))
                ->setModuloUserId(4)
                ->setForceGrantAccess(false)
                ->build()
        );

        $expressions = AccessSpecificationFactory::create($featureFlag)->pull();

        foreach ($expressions as $expression) {
            $this->assertTrue(in_array($expression::class, $expectedExpressions));
        }
    }
}
