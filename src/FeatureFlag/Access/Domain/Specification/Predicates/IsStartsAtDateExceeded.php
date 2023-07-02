<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use DateTimeImmutable;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;

final class IsStartsAtDateExceeded implements EnvironmentExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag): bool
    {
        $startsAt = $featureFlag->config->startsAt;
        $now = new DateTimeImmutable('now', $startsAt->value->getTimezone());

        return $startsAt->value->format($startsAt::FORMAT) <= $now->format($startsAt::FORMAT);
    }
}
