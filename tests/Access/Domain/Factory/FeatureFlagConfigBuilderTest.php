<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\Factory;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Application\Collection\UserEmailDomainNameCollection
 * @covers \FeatureFlag\Access\Application\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Application\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Application\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Domain\ValueObject\StartsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\EndsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\ModuloUserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 */
final class FeatureFlagConfigBuilderTest extends TestCase
{
    public static function scenarios(): array
    {
        return [
            [false, null, null, null, null, null, null],
            [true, ['wp.pl'], [1, 2], [3, 4], 5, 'yesterday', 'next monday'],
            [false, null, [1, 2], null, null, 'next monday', 'next friday +1 week'],
            [true, ['yahoo.com', 'gmail.com'], null, [3, 4], null, null, null],
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
        ?string $startsAt,
        ?string $endsAt,
    ): void {
            $config = FeatureFlagConfigBuilder::create()
                ->setForceGrantAccess($forceGrantAccess)
                ->setUserEmailDomainNames($userEmailDomainNames)
                ->setUserIds($userIds)
                ->setUserRoles($userRoles)
                ->setModuloUserId($moduloUserId)
                ->setStartsAt($startsAt)
                ->setEndsAt($endsAt)
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

        $startsAt
            ? $this->assertSame((new DateTimeImmutable($startsAt))->format('Y-m-d H:i:s'), $config->startsAt->value->format('Y-m-d H:i:s'))
            : $this->assertNull($config->startsAt);

        $endsAt
            ? $this->assertSame((new DateTimeImmutable($endsAt))->format('Y-m-d H:i:s'), $config->endsAt->value->format('Y-m-d H:i:s'))
            : $this->assertNull($config->endsAt);
    }

    public function testBuildEmptyFeatureFlag(): void
    {
        $config = FeatureFlagConfigBuilder::create()->build();

        $this->assertFalse($config->forceGrantAccess);
        $this->assertNull($config->userEmailDomainNames);
        $this->assertNull($config->userIds);
        $this->assertNull($config->userRoles);
        $this->assertNull($config->moduloUserId);
        $this->assertNull($config->startsAt);
    }

    public function testSkipNullableForceGrantAccess(): void
    {
        $config = FeatureFlagConfigBuilder::create()->setForceGrantAccess(null)->build();

        $this->assertFalse($config->forceGrantAccess);
    }
}
