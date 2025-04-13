<?php

namespace App\Controller;

use App\Service\EmailService;
use App\Mapper\StatOutputDtoMapper;
use App\Repository\StatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/stats')]
final class StatController extends AbstractController
{
    public function __construct(
        private StatRepository $statRepository,
        private StatOutputDtoMapper $statMapper,
        private EmailService $emailService
    ) {}

    #[Route(name: 'get_all_stats', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $stats = $this->statRepository->findAll();
        $globalStats = $this->statRepository->getGlobalStats();

        $data = $this->statMapper->mapToStatOutputDto($stats, $globalStats);

        return $this->json($data);
    }

    #[Route('/top', name: 'get_top_stats', methods: ['GET'])]
    public function test(): JsonResponse
    {
        $stats = $this->statRepository->findTop20ByWeek((new \DateTime())->format('o-W'));

        $data = $this->statMapper->mapToStatOutputDto($stats);

        return $this->json($data);
    }

    #[Route('/custom', name: 'custom_stats', methods: ['GET'])]
    public function getCustomStats(Request $request): JsonResponse
    {
        $column = $request->query->get('column', 'views');
        $order = $request->query->get('order', 'DESC');
        $limit = (int) $request->query->get('limit', 30);
        $week = $request->query->get('week', (new \DateTime())->format('o-W'));

        try {
            $stats = $this->statRepository->getStatsByColumnOrderAndLimit($column, $order, $limit, $week);

            $data = $this->statMapper->mapToStatOutputDto($stats);
            return $this->json($data);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/send-weekly-stats', name: 'send_weekly_stats', methods: ['POST'])]
    public function sendWeeklyStatsEmail(): JsonResponse
    {
        try {
            $this->emailService->sendWeeklyStatsEmail();

            return new JsonResponse(['status' => 'Email envoyé avec succès.']);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['status' => 'Aucune statistique disponible pour cette semaine.', 'error' => $e->getMessage()], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $e) {
            return new JsonResponse(['status' => 'Erreur lors de l\'envoi de l\'email.', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
