<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Repository\StatRepository;
use App\Service\ChartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    public function __construct(
        private ImageRepository $imageRepository,
        private StatRepository $statRepository
    ) {}

    #[Route(name: 'app_admin')]
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
}
