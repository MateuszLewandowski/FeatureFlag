<?php

declare(strict_types=1);

namespace App\Controller\FeatureFlag;

use App\Core\Validation\ResponseCodeValidator;
use FeatureFlag\Access\Application\DTO\ExceptionResponseDTO;
use FeatureFlag\Access\Application\FeatureFlagRepository;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
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
final class Update extends AbstractController
{
    public function __construct(
        private readonly FeatureFlagRepository $repository,
        private readonly LoggerInterface $logger,
    ) {}

    #[Route(path: '/feature-flag', methods: 'PUT', priority: 100)]
    public function __invoke(Request $request): Response
    {
        try {
            $responseStatus = Response::HTTP_NO_CONTENT;
            $this->repository->update(
                new FeatureFlagId($request->request->getString('featureFlagId')),
                FeatureFlagConfig::createWithRequest($request)
            );
        } catch (Throwable $e) {
            $responseStatus = ResponseCodeValidator::check($e->getCode());
            $responseContent = new ExceptionResponseDTO($e->getMessage());
            $this->logger->error($e->getMessage(), [
                'request' => $request,
                'exception' => $e,
            ]);
        } finally {
            return new Response(json_encode($responseContent ?? ''), $responseStatus);
        }
    }
}
