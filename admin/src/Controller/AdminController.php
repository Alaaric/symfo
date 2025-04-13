<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Repository\StatRepository;
use App\Service\ChartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    public function __construct(
        private ImageRepository $imageRepository,
        private StatRepository $statRepository
    ) {}

    #[Route(name: 'admin')]
    public function index(): Response
    {
        $data = $this->imageRepository->getAllImages();

        return $this->render('admin/index.html.twig', [
            'images' => $data,
        ]);
    }

    #[Route('/stats', name: 'admin_stats', methods: ['GET'])]
    public function getAllStats(ChartService $chartService): Response
    {

        $stats = $this->statRepository->getAllStats();

        $chart = $chartService->createBarChart($stats);

        return $this->render('admin/stats.html.twig', [
            'chart' => $chart,
        ]);
    }

    #[Route('/stats/custom', name: 'admin_custom_stats', methods: ['GET'])]
    public function getCustomStats(Request $request, ChartService $chartService): Response
    {
        $column = $request->query->get('column', 'views');
        $order = $request->query->get('order', 'DESC');
        $limit = (int) $request->query->get('limit', 30);
        $week = $request->query->get('week');

        try {
            $stats = $this->statRepository->getCustomStats($column, $order, $limit, $week);

            $chart = $chartService->createBarChart($stats);

            return $this->render('admin/stats.html.twig', [
                'customStats' => $stats,
                'chart' => $chart,
            ]);
        } catch (\RuntimeException $e) {
            return $this->render('admin/error.html.twig', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/image/delete/{id}', name: 'admin_delete_image', methods: ['POST'])]
    public function deleteImage(int $id): Response
    {
        try {
            $this->imageRepository->deleteImage($id);
            $this->addFlash('success', 'Image deleted successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'An error occurred: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/send-mail', name: 'send_stats_mail', methods: ['POST'])]
    public function sendMail(): RedirectResponse
    {
        $this->statRepository->triggerWeeklyReport();

        $this->addFlash('success', 'Email envoyé !');

        return $this->redirectToRoute('admin');
    }
}
