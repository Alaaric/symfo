<?php

namespace App\Controller;

use App\Mapper\StatOutputDtoMapper;
use App\Repository\StatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stats')]
final class StatController extends AbstractController
{
    public function __construct(
        private StatRepository $statRepository,
        private StatOutputDtoMapper $statMapper
    ) {
    }

    #[Route(name: 'get_all_stats', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $stats = $this->statRepository->findAll();

        $data = $this->statMapper->mapToStatOutputDto($stats);

        return $this->json($data);
    }
}
