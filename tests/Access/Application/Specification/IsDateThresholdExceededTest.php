<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Specification\Predicates\IsDateThresholdExceeded;
use FeatureFlag\Access\Domain\Exception\InvalidDateThresholdTimeZoneException;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\Specification\Predicates\IsDateThresholdExceeded
 * @covers InvalidDateThresholdTimeZoneException
 */
final class IsDateThresholdExceededTest extends TestCase
{
    public function testExpectsTrue(): void
    {
        $expression = new IsDateThresholdExceeded();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setDateThreshold([
                        'date' => (new DateTimeImmutable('yesterday'))->format('Y-m-d'),
                        'timeZone' => 'Europe/Warsaw',
                    ])
                    ->build(),
            ),
        );

        $this->assertTrue($result);
    }

    public function testExpectsFalse(): void
    {
        $expression = new IsDateThresholdExceeded();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setDateThreshold([
                        'date' => (new DateTimeImmutable('tomorrow'))->format('Y-m-d'),
                        'timeZone' => 'Europe/Warsaw',
                    ])
                    ->build()
            ),
        );

        $this->assertFalse($result);
    }

    public function testExpectsException(): void
    {
        $this->expectException(InvalidDateThresholdTimeZoneException::class);

        $expression = new IsDateThresholdExceeded();
        $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setDateThreshold([
                        'date' => '2023-01-01',
                        'timeZone' => 'invalid',
                    ])
                    ->build()
            ),
        );
    }
}
