<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Validation\ResponseCodeValidator;
use FeatureFlag\Access\Application\DTO\ExceptionResponseDTO;
use FeatureFlag\Access\Application\DTO\VerifierResultDTO;
use FeatureFlag\Access\Application\Factory\UserFactory;
use FeatureFlag\Access\Application\VerifyAccessRules;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[AsController]
#[Route('/api/v1')]
final class VerifyAccessConditions extends AbstractController
{
    public function __construct(
        private readonly VerifyAccessRules $verifier,
        private readonly LoggerInterface $logger
    ) {}

    #[Route(path: '/access/verify/{featureFlagId}', methods: 'GET', priority: 200)]
    public function __invoke(Request $request): Response
    {
        try {
            $featureFlagName = new FeatureFlagId($request->get('featureFlagId'));
            $user = UserFactory::createWithRequest($request);

            $isAvailable = $this->verifier->verify($featureFlagName, $user);

            $responseStatus = $isAvailable
                ? Response::HTTP_OK
                : Response::HTTP_FORBIDDEN;
            $responseContent = new VerifierResultDTO($isAvailable);
        } catch (Throwable $e) {
            $responseStatus = ResponseCodeValidator::check($e->getCode());
            $responseContent = new ExceptionResponseDTO($e->getMessage());
            $this->logger->error($e->getMessage(), [
                'request' => $request,
                'exception' => $e,
            ]);
        }

        return new Response(json_encode($responseContent), $responseStatus);
    }
}
