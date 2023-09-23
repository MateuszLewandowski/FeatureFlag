<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidFeatureFlagNameException;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Application\Exception\InvalidFeatureFlagNameException
 */
final class FeatureFlagIdTest extends TestCase
{
    public static function positiveScenarios(): array
    {
        return [
            ['NewFeature1'],
            ['New_feature-1'],
            ['F1'],
            ['VeryLongFeatureFlagIdSomeMoreText'],
        ];
    }

    public static function negativeScenarios(): array
    {
        return [
            ['1'],
            [base64_encode(random_bytes(64))],
            ['Name!'],
            ['Name@'],
            ['Name#'],
            ['Name$'],
            ['Name%'],
            ['Name^'],
            ['Name&'],
            ['Name*'],
            ['Name('],
            ['Name)'],
            ['Name+'],
            ['Name='],
            ['Name{'],
            ['Name}'],
            ['Name['],
            ['Name]'],
            ['Name;'],
            ['Name:'],
            ['Name"'],
            ['Name\\'],
            ['Name|||'],
            ['Name??'],
            ['Name,'],
            ['Name>'],
        ];
    }

    /** @dataProvider positiveScenarios */
    public function testCreateValidFeatureFlagId(string $featureFlagIdKey): void
    {
        $featureFlagId = new FeatureFlagId($featureFlagIdKey);

        $this->assertSame($featureFlagIdKey, $featureFlagId->value);
    }

    /** @dataProvider negativeScenarios */
    public function testHandleExceptionDuringCreatingFeatureFlagId(string $featureFlagIdKey): void
    {
        $this->expectException(InvalidFeatureFlagNameException::class);

        new FeatureFlagId($featureFlagIdKey);
    }

}
