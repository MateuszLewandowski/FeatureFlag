<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\Factory;

use DateTimeImmutable;
use DateTimeZone;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers FeatureFlagConfigBuilder
 * @covers FeatureFlagConfig
 */
final class FeatureFlagConfigBuilderTest extends TestCase
{
    public static function scenarios(): array
    {
        return [
            [false, null, null, null, null, null],
            [true, ['wp.pl'], [1, 2], [3, 4], 5, ['date' => 'yesterday', 'timeZone' => 'Europe/Vatican']],
            [false, null, [1, 2], null, null, ['date' => 'next monday', 'timeZone' => 'America/New_York']],
            [true, ['yahoo.com', 'gmail.com'], null, [3, 4], null, null],
        ];
    }

    /**
     * @dataProvider scenarios
     */
    public function testBuildFeatureFlag(
        ?bool $forceGrantAccess,
        ?array $userEmailDomainNames,
        ?array $userIds,
        ?array $userRoles,
        ?int $moduloUserId,
        ?array $dateThreshold,
    ): void {
        $config = FeatureFlagConfigBuilder::create()
            ->setForceGrantAccess($forceGrantAccess)
            ->setUserEmailDomainNames($userEmailDomainNames)
            ->setUserIds($userIds)
            ->setUserRoles($userRoles)
            ->setModuloUserId($moduloUserId)
            ->setDateThreshold($dateThreshold)
            ->build();

        $this->assertSame($forceGrantAccess, $config->forceGrantAccess);
        $this->assertSame(
            $userEmailDomainNames,
            $config->userEmailDomainNames
                ? array_map(static fn(UserEmailDomainName $name) => $name->value, $config->userEmailDomainNames->getCollection())
                : null
        );
        $this->assertSame(
            $userIds,
            $config->userIds
                ? array_map(static fn(UserId $id) => $id->value, $config->userIds->getCollection())
                : null
        );
        $this->assertSame(
            $userRoles,
            $config->userRoles
                ? array_map(static fn(UserRole $role) => $role->value, $config->userRoles->getCollection())
                : null
        );
        $this->assertSame($moduloUserId, $config->moduloUserId?->value);

        $dateThreshold
            ? $this->assertSame(
            (new DateTimeImmutable($dateThreshold['date'], new DateTimeZone($dateThreshold['timeZone'])))->format('Y-m-d'),
            $config->dateThreshold->value->format('Y-m-d')
        )
            : $this->assertNull($config->dateThreshold);
    }

    public function testBuildEmptyFeatureFlag(): void
    {
        $config = FeatureFlagConfigBuilder::create()->build();

        $this->assertFalse($config->forceGrantAccess);
        $this->assertNull($config->userEmailDomainNames);
        $this->assertNull($config->userIds);
        $this->assertNull($config->userRoles);
        $this->assertNull($config->moduloUserId);
        $this->assertNull($config->dateThreshold);
    }

    public function testSkipNullableForceGrantAccess(): void
    {
        $config = FeatureFlagConfigBuilder::create()->setForceGrantAccess(null)->build();

        $this->assertFalse($config->forceGrantAccess);
    }

    public function testSkipDateThresholdIfOneIngredientIsMissing(): void
    {
        $config = FeatureFlagConfigBuilder::create()->setDateThreshold(['timeZone' => 'Europe/Vatican'])->build();

        $this->assertNull($config->dateThreshold->value);
    }
}
