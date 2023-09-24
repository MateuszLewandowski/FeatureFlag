<?php

declare(strict_types=1);

namespace App\Controller\FeatureFlag;

use App\Core\Validation\ResponseCodeValidator;
use App\Service\FeatureFlag\CreateService;
use FeatureFlag\Access\Application\DTO\ExceptionResponseDTO;
use FeatureFlag\Access\Application\Factory\FeatureFlagConfigFactory;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
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
final class Create extends AbstractController
{
    public function __construct(
        private readonly CreateService $service,
        private readonly LoggerInterface $logger,
    ) {}

    #[Route(path: '/feature-flag', methods: 'POST', priority: 175)]
    public function __invoke(Request $request): Response
    {
        try {
            $responseStatus = Response::HTTP_CREATED;
            $featureFlagId = new FeatureFlagId($request->get('featureFlagId'));
            $featureFlagConfig = FeatureFlagConfigFactory::createWithRequest($request);
            $featureFlag = new FeatureFlag($featureFlagId, $featureFlagConfig);
            $this->service->create($featureFlag);
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
