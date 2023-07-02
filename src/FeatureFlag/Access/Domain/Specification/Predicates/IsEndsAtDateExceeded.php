<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use DateTimeImmutable;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;

final class IsEndsAtDateExceeded implements EnvironmentExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag): bool
    {
        $endsAt = $featureFlag->config->endsAt;
        $now = new DateTimeImmutable('now', $endsAt->value->getTimezone());

        return $endsAt->value->format($endsAt::FORMAT) >= $now->format($endsAt::FORMAT);
    }
}
