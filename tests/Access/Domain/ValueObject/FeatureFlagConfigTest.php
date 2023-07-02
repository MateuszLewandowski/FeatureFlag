<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\ValueObject;

use DateTimeImmutable;
use FeatureFlag\Access\Domain\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigFactory;
use FeatureFlag\Access\Domain\ValueObject\EndsAt;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \FeatureFlag\Access\Domain\Builder\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\ValueObject\ModuloUserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Domain\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Domain\Collection\UserEmailDomainNameCollection
 * @covers \FeatureFlag\Access\Domain\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Domain\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Domain\ValueObject\StartsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\EndsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\Factory\FeatureFlagConfigFactory
 */
final class FeatureFlagConfigTest extends TestCase
{
    public static function scenarios(): array
    {
        return [
            [
                [
                    'forceGrantAccess' => false,
                    'startsAt' => 'midnight',
                    'userRoles' => [1, 2],
                    'moduloUserId' => 2,
                    'userEmailDomainNames' => ['gmail.com'],
                    'userIds' => [123, 456, 789],
                ],
            ],
            [
                [
                    'forceGrantAccess' => true,
                    'startsAt' => null,
                    'userRoles' => null,
                    'moduloUserId' => null,
                    'userEmailDomainNames' => null,
                    'userIds' => null,
                ],
            ],
            [
                [
                    'forceGrantAccess' => false,
                ],
            ],
            [
                [
                    'forceGrantAccess' => false,
                    'someDifferentKey' => 1,
                ],
            ],
        ];
    }

    /** @dataProvider scenarios */
    public function testCreateConfig(array $payload): void
    {
        $config = FeatureFlagConfigBuilder::create()
            ->setForceGrantAccess($payload['forceGrantAccess'] ?? false)
            ->setStartsAt($payload['startsAt'] ?? null)
            ->setUserEmailDomainNames($payload['userEmailDomainNames'] ?? null)
            ->setUserIds($payload['userIds'] ?? null)
            ->setUserRoles($payload['userRoles'] ?? null)
            ->setModuloUserId($payload['moduloUserId'] ?? null)
            ->build();

        $this->assertSame($payload['forceGrantAccess'], $config->forceGrantAccess);
        $this->assertSame(
            $payload['startsAt']
                ? (new EndsAt(
                new DateTimeImmutable($payload['startsAt'])
            ))->value->format('Y-m-d H:i:s')
                : null,
            $config->startsAt?->value->format('Y-m-d H:i:s')
        );
        $this->assertSame($payload['moduloUserId'], $config->moduloUserId?->value);
        $this->assertSame(
            $payload['userRoles'],
            $config->userRoles
                ? array_map(static fn(UserRole $userRole) => $userRole->value, $config->userRoles->getCollection())
                : null
        );
        $this->assertSame(
            $payload['userEmailDomainNames'],
            $config->userEmailDomainNames
                ? array_map(static fn(UserEmailDomainName $userEmailDomainName) => $userEmailDomainName->value,
                $config->userEmailDomainNames->getCollection())
                : null
        );
        $this->assertSame(
            $payload['userIds'],
            $config->userIds
                ? array_map(static fn(UserId $userId) => $userId->value, $config->userIds->getCollection())
                : null
        );
    }

    public function testCreateWithRequest(): void
    {
        $moduloUserId = 10;

        $featureFlagConfig = FeatureFlagConfigFactory::createWithRequest(
            new Request([], ['moduloUserId' => $moduloUserId])
        );

        $this->assertSame($moduloUserId, $featureFlagConfig->moduloUserId->value);
    }
}
