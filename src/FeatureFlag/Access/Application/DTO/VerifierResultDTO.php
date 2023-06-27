<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\DTO;

final class VerifierResultDTO
{
    public function __construct(
        public readonly bool $isAvailable,
    ) {}
}
