<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\Factory;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Specification\AccessSpecification;
use FeatureFlag\Access\Application\Specification\Predicates\DoesUserEmailAddressIncludesDomain;
use FeatureFlag\Access\Application\Specification\Predicates\DoesUserIdSatisfyModulo;
use FeatureFlag\Access\Application\Specification\Predicates\IsDateThresholdExceeded;
use FeatureFlag\Access\Application\Specification\Predicates\IsUserIdAvailable;
use FeatureFlag\Access\Application\Specification\Predicates\IsUserRoleAvailable;
use FeatureFlag\Access\Domain\Factory\AccessSpecificationFactory;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use PHPUnit\Framework\TestCase;

/**
 * @covers AccessSpecificationFactory
 * @covers \FeatureFlag\Access\Application\Specification\AccessSpecification
 * @covers \FeatureFlag\Access\Application\Specification\Predicates\DoesUserEmailAddressIncludesDomain
 * @covers \FeatureFlag\Access\Application\Specification\Predicates\DoesUserIdSatisfyModulo
 * @covers \FeatureFlag\Access\Application\Specification\Predicates\IsDateThresholdExceeded
 * @covers \FeatureFlag\Access\Application\Specification\Predicates\IsUserRoleAvailable
 * @covers \FeatureFlag\Access\Application\Specification\Predicates\IsUserIdAvailable
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
