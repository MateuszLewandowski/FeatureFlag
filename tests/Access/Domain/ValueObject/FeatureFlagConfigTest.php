<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeZone;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\ValueObject\DateThreshold;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\ValueObject\ModuloUserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Domain\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Domain\Collection\UserEmailDomainNameCollection
 * @covers \FeatureFlag\Access\Domain\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Domain\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Domain\ValueObject\DateThreshold
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 */
final class FeatureFlagConfigTest extends TestCase
{
    public static function scenarios(): array
    {
        return [
            [
                [
                    'forceGrantAccess' => false,
                    'dateThreshold' => [
                        'date' => 'midnight',
                        'timeZone' => 'Europe/Warsaw',
                    ],
                    'userRoles' => [1, 2],
                    'moduloUserId' => 2,
                    'userEmailDomainNames' => ['gmail.com'],
                    'userIds' => [123, 456, 789],
                ],
            ],
            [
                [
                    'forceGrantAccess' => true,
                    'dateThreshold' => null,
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
            ->setDateThreshold($payload['dateThreshold'] ?? null)
            ->setUserEmailDomainNames($payload['userEmailDomainNames'] ?? null)
            ->setUserIds($payload['userIds'] ?? null)
            ->setUserRoles($payload['userRoles'] ?? null)
            ->setModuloUserId($payload['moduloUserId'] ?? null)
            ->build();

        $this->assertSame($payload['forceGrantAccess'], $config->forceGrantAccess);
        $this->assertSame(
            $payload['dateThreshold']
                ? (new DateThreshold(
                new DateTimeImmutable($payload['dateThreshold']['date'], new DateTimeZone($payload['dateThreshold']['timeZone']))
            ))->value->format('Y-m-d')
                : null,
            $config->dateThreshold?->value->format('Y-m-d')
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

        $featureFlagConfig = FeatureFlagConfig::createWithRequest(
            new Request([], ['moduloUserId' => $moduloUserId])
        );

        $this->assertSame($moduloUserId, $featureFlagConfig->moduloUserId->value);
    }
}
