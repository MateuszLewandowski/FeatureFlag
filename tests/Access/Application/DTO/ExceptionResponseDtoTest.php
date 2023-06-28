<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\DTO;

use FeatureFlag\Access\Application\DTO\ExceptionResponseDTO;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\DTO\ExceptionResponseDTO
 */
final class ExceptionResponseDtoTest extends TestCase
{
    public function testCreate(): void
    {
        $message = 'error message';
        $dto = new ExceptionResponseDTO($message);

        $this->assertSame($message, $dto->error);
    }
}
