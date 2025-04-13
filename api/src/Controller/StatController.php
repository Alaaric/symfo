<?php

namespace App\Controller;

use App\Service\EmailService;
use App\Mapper\StatOutputDtoMapper;
use App\Repository\StatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

        $data = $this->statMapper->mapToStatOutputDto($stats);

        return $this->json($data);
    }

    #[Route('/top', name: 'get_top_stats', methods: ['GET'])]
    public function test(): JsonResponse
    {
        $stats = $this->statRepository->findTop20ByWeek((new \DateTime())->format('o-W'));

        $data = $this->statMapper->mapToStatOutputDto($stats);

        return $this->json($data);
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
