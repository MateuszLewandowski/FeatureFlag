<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/api/v1')]
final class HealthCheck extends AbstractController
{
    public function __construct(
    ) {}

    #[Route(path: '/ping', methods: 'GET', priority: 100)]
    public function __invoke(Request $request): Response
    {
        return new Response('pong', Response::HTTP_OK);
    }
}
