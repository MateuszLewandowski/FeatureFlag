<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Infrastructure\Exception;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class JsonFileNotFoundException extends FileNotFoundException
{
    public function __construct()
    {
        parent::__construct('Json file not found', Response::HTTP_NOT_FOUND);
    }
}
