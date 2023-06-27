<?php

declare(strict_types=1);

namespace App\Core\Validation;

use Symfony\Component\HttpFoundation\Response;

final class ResponseCodeValidator
{
    public static function check(string|int $code): int
    {
        $code = (int)$code;
        if (
            $code >= Response::HTTP_CONTINUE and $code <= Response::HTTP_EARLY_HINTS
            || $code >= Response::HTTP_OK and $code <= Response::HTTP_IM_USED
            || $code >= Response::HTTP_MULTIPLE_CHOICES and $code <= Response::HTTP_PERMANENTLY_REDIRECT
            || $code >= Response::HTTP_BAD_REQUEST and $code <= Response::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS
            || $code >= Response::HTTP_INTERNAL_SERVER_ERROR and $code <= Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED
        ) {
            return $code;
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
