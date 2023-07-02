<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\DTO;

use FeatureFlag\Access\Application\DTO\FeatureFlagDTO;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\DTO\FeatureFlagDTO
 */
final class FeatureFlagDtoTest extends TestCase
{
    public function testCreate(): void
    {
        $featureFlagId = '5341112';
        $startsAt = '2024-01-01 12:00:55';
        $endsAt = '2022-01-05 14:15:55';
        $userEmailDomainNames = ['gmail.com'];
        $userIds = [1, 6, 32];
        $userRoles = [1, 5];
        $moduloUserId = 4;

        $dto = new FeatureFlagDTO(
            $featureFlagId,
            false,
            $startsAt,
            $endsAt,
            $userEmailDomainNames,
            $userIds,
            $userRoles,
            $moduloUserId
        );

        $this->assertSame($featureFlagId, $dto->featureFlagId);
        $this->assertSame(false, $dto->forceGrantAccess);
        $this->assertSame($startsAt, $dto->startsAt);
        $this->assertSame($endsAt, $dto->endsAt);
        $this->assertSame($userEmailDomainNames, $dto->userEmailDomainNames);
        $this->assertSame($userIds, $dto->userIds);
        $this->assertSame($userRoles, $dto->userRoles);
        $this->assertSame($moduloUserId, $dto->moduloUserId);
    }
}
