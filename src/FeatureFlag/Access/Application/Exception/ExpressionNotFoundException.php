<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Exception;

use FeatureFlag\Access\Domain\Specification\Predicates\Expressible;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class ExpressionNotFoundException extends RuntimeException
{
    public function __construct(Expressible $expressible)
    {
        parent::__construct(sprintf('Wrong expression object has been passed "%s"', $expressible::class), Response::HTTP_NOT_FOUND);
    }
}
