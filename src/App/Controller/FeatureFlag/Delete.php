<?php

declare(strict_types=1);

namespace App\Controller\FeatureFlag;

use App\Core\Validation\ResponseCodeValidator;
use App\Service\FeatureFlag\DeleteService;
use FeatureFlag\Access\Application\DTO\ExceptionResponseDTO;
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
final class Delete extends AbstractController
{
    public function __construct(
        private readonly DeleteService $service,
        private readonly LoggerInterface $logger,
    ) {}

    #[Route(path: '/feature-flag/{featureFlagId}', methods: 'DELETE', priority: 125)]
    public function __invoke(Request $request): Response
    {
        try {
            $responseStatus = Response::HTTP_NO_CONTENT;
            $featureFlagId = new FeatureFlagId($request->get('featureFlagId'));
            $this->service->delete($featureFlagId);
        } catch (Throwable $e) {
            $responseStatus = ResponseCodeValidator::check($e->getCode());
            $responseContent = new ExceptionResponseDTO($e->getMessage());
            $this->logger->error($e->getMessage(), [
                'request' => $request,
                'exception' => $e,
            ]);
        }

        return new Response(json_encode($responseContent ?? ''), $responseStatus);
    }
}
