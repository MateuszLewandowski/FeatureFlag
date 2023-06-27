<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain;

use Symfony\Component\HttpFoundation\Request;

interface RequestCreatable
{
    public static function createWithRequest(Request $request): self;
}
