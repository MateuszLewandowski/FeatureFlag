<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use FeatureFlag\Access\Domain\Exception\InvalidUserIdException;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo
 * @covers \FeatureFlag\Access\Domain\Exception\InvalidUserIdException
 */
final class DoesUserIdSatisfyModuloTest extends TestCase
{
    public function testExpectsTrue(): void
    {
        $expression = new DoesUserIdSatisfyModulo();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setModuloUserId(5)
                    ->build()
            ),
            new User(new UserId(10), null, null)
        );

        $this->assertTrue($result);
    }

    public function testExpectsFalse(): void
    {
        $expression = new DoesUserIdSatisfyModulo();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setModuloUserId(5)
                    ->build()
            ),
            new User(new UserId(7), null, null)
        );

        $this->assertFalse($result);
    }

    public function testExpectsException(): void
    {
        $this->expectException(InvalidUserIdException::class);

        $expression = new DoesUserIdSatisfyModulo();
        $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setModuloUserId(5)
                    ->build()
            ),
            new User(null, null, null)
        );
    }
}
