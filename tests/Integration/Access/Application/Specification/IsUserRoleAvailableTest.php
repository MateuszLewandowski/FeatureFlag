<?php

declare(strict_types=1);

namespace App\Tests\Integration\Access\Application\Specification;

use FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Application\Exception\UserRoleNotFoundException;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable
 * @covers \FeatureFlag\Access\Application\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Application\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\Entity\FeatureFlag
 * @covers \FeatureFlag\Access\Domain\Entity\User
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Application\Exception\UserRoleNotFoundException
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
        $this->expectException(UserRoleNotFoundException::class);

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
