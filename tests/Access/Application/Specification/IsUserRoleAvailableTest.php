<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use FeatureFlag\Access\Domain\Exception\InvalidUserRoleException;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable
 * @covers \FeatureFlag\Access\Domain\Exception\invalidUserRoleException
 * @covers \FeatureFlag\Access\Domain\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Domain\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\FeatureFlag
 * @covers \FeatureFlag\Access\Domain\User
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 */
final class IsUserRoleAvailableTest extends TestCase
{
    public function testExpectsTrue(): void
    {
        $expression = new IsUserRoleAvailable();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserRoles([1])
                    ->build()
            ),
            new User(null, new UserRole(1), null)
        );

        $this->assertTrue($result);
    }

    public function testExpectsFalse(): void
    {
        $expression = new IsUserRoleAvailable();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserRoles([1])
                    ->build()
            ),
            new User(null, new UserRole(2), null)
        );

        $this->assertFalse($result);
    }

    public function testExpectsException(): void
    {
        $this->expectException(invalidUserRoleException::class);

        $expression = new IsUserRoleAvailable();
        $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserRoles([1])
                    ->build()
            ),
            new User(null, null, null)
        );
    }
}
