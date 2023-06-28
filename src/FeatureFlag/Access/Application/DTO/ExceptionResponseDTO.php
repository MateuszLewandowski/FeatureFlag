<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\DTO;

final class ExceptionResponseDTO
{
    public function __construct(
        public readonly string $error,
    ) {}
}
