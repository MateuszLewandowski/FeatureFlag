<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\DTO;

use FeatureFlag\Access\Application\DTO\VerifierResultDTO;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\DTO\VerifierResultDTO
 */
final class VerifierResultDtoTest extends TestCase
{
    public function testCreate(): void
    {
        $dto = new VerifierResultDTO(false);

        $this->assertFalse($dto->isAvailable);
    }
}
