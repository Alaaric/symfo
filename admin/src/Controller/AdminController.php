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

final class AdminController extends AbstractController
{
    public function __construct(
        private ImageRepository $imageRepository,
        private StatRepository $statRepository
    ) {}

    #[Route('/', name: 'admin')]
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
        $availableWeeks = $this->statRepository->getAvailableWeeks();

        $chart = $chartService->createBarChart($stats);

        return $this->render('admin/stats.html.twig', [
            'stats' => $stats,
            'chart' => $chart,
            'availableWeeks' => $availableWeeks,
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
            $availableWeeks = $this->statRepository->getAvailableWeeks();

            $chart = $chartService->createBarChart($stats);

            return $this->render('admin/stats.html.twig', [
                'stats' => $stats,
                'chart' => $chart,
                'availableWeeks' => $availableWeeks,
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

    #[Route('/send-mail', name: 'send_stats_mail', methods: ['POST'])]
    public function sendMail(): RedirectResponse
    {
        $this->statRepository->triggerWeeklyReport();

        $this->addFlash('success', 'Email envoyé !');

        return $this->redirectToRoute('admin');
    }

    #[Route('images/{id}', name: 'image', methods: ['GET'])]
    public function getImage(int $id): Response
    {
        $data = $this->imageRepository->getImageById($id);

        return new Response($data, Response::HTTP_OK, [
            'Content-Type' => 'image/jpeg',
        ]);
    }

    #[Route('/image/file/{imgName}', name: 'display_image', methods: ['GET'])]
    public function listImageFiles(string $imgName): Response
    {
        $file = $this->imageRepository->getImageFileForDisplay($imgName);

        return new Response($file, Response::HTTP_OK, [
            'Content-Type' => 'image/jpeg',
        ]);
    }
}
