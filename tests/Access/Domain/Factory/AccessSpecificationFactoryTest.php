<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\Factory;

use DateTimeImmutable;
use FeatureFlag\Access\Domain\Factory\AccessSpecificationFactory;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\Specification\AccessSpecification;
use FeatureFlag\Access\Domain\Specification\Predicates\DoesUserEmailAddressIncludesDomain;
use FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo;
use FeatureFlag\Access\Domain\Specification\Predicates\IsDateThresholdExceeded;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\Factory\AccessSpecificationFactory
 * @covers \FeatureFlag\Access\Domain\Specification\AccessSpecification
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserEmailAddressIncludesDomain
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsDateThresholdExceeded
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable
 * @covers \FeatureFlag\Access\Domain\Collection\UserEmailDomainNameCollection
 * @covers \FeatureFlag\Access\Domain\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Domain\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Domain\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\FeatureFlag
 * @covers \FeatureFlag\Access\Domain\ValueObject\DateThreshold
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Domain\ValueObject\ModuloUserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole

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
            IsUserRoleAvailable::class,
            DoesUserIdSatisfyModulo::class,
            IsDateThresholdExceeded::class,
            IsUserIdAvailable::class,
            DoesUserEmailAddressIncludesDomain::class,
        ];

        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key'),
            FeatureFlagConfigBuilder::create()
                ->setUserRoles([1, 4, 7])
                ->setUserIds([1, 5, 7])
                ->setUserEmailDomainNames(['gmail.com'])
                ->setDateThreshold([
                    'date' => (new DateTimeImmutable('tomorrow'))->format('Y-m-d'),
                    'timeZone' => 'Europe/Warsaw',
                ])
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
