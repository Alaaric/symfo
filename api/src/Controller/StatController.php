<?php

namespace App\Controller;

use App\Entity\Stat;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stats')]
final class StatController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StatRepository $statRepository
    ) {
    }

    #[Route(name: 'get_all_stats', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $stats = $this->statRepository->findAll();

        return $this->json($stats, Response::HTTP_OK);
    }
}
