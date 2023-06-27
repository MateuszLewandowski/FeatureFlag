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
        $userEmailDomainNames = ['gmail.com'];
        $userIds = [1, 6, 32];
        $userRoles = [1, 5];
        $moduloUserId = 4;

        $dto = new FeatureFlagDTO(
            $featureFlagId,
            false,
            $userEmailDomainNames,
            $userIds,
            $userRoles,
            $moduloUserId
        );

        $this->assertSame($featureFlagId, $dto->featureFlagId);
        $this->assertSame(false, $dto->forceGrantAccess);
        $this->assertSame($userEmailDomainNames, $dto->userEmailDomainNames);
        $this->assertSame($userIds, $dto->userIds);
        $this->assertSame($userRoles, $dto->userRoles);
        $this->assertSame($moduloUserId, $dto->moduloUserId);
    }
}
