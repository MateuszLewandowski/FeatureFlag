<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Application;

use FeatureFlag\Access\Application\DTO\VerifierResultDTO;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\DTO\VerifierResultDTO
 */
final class VerifierResultDtoTest extends TestCase
{
    public function testCreateDTO(): void
    {
        $dto = new VerifierResultDTO(true);
        $this->assertTrue($dto->isAvailable);

        $dto = new VerifierResultDTO(false);
        $this->assertFalse($dto->isAvailable);
    }
}
