<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use FeatureFlag\Access\Application\Specification\Predicates\IsUserRoleAvailable;
use FeatureFlag\Access\Domain\Exception\InvalidUserIdException;
use FeatureFlag\Access\Domain\Exception\InvalidUserRoleException;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers IsUserRoleAvailable
 * @covers InvalidUserIdException
 * @covers \FeatureFlag\Access\Domain\Exception\invalidUserRoleException
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
