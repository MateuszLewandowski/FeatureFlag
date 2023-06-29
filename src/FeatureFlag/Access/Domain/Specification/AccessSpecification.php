<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification;

use FeatureFlag\Access\Domain\Specification\Predicates\Expressible;

final class AccessSpecification
{
    private array $expressions = [];

    public function push(Expressible $expression): void
    {
        $this->expressions[] = $expression;
    }

    public function pull(): array
    {
        $expressions = $this->expressions;
        $this->expressions = [];

        return $expressions;
    }
}
