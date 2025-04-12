<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Repository\StatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    public function __construct(
        private ImageRepository $imageRepository,
        private StatRepository $statRepository
    ) {}

    /*#[Route(name: 'app_admin')]
    public function index(): Response
    {
       return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
       ]);
    }*/

    #[Route(name: 'app_admin')]
    public function index(): Response
    {
        $data = $this->imageRepository->getAllImages();

        return $this->render('admin/index.html.twig', [
            'images' => $data,
        ]);
    }

   /* #[Route('/stats', name: 'admin_stats', methods: ['GET'])]
    public function getAllStats(): JsonResponse
    {
        try {
            $stats = $this->statRepository->getAllStats();
            return $this->json($stats);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/image/delete/{id}', name: 'admin_delete_image', methods: ['DELETE'])]
    public function deleteImage(int $id): JsonResponse
    {
        try {
            $response = $this->imageRepository->deleteImage($id);
            return $this->json(['message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }*/

    #[Route('/stats', name: 'admin_stats', methods: ['GET'])]
    public function getAllStats(): Response
    {
        $stats = $this->statRepository->getAllStats();
        return $this->render('admin/stats.html.twig', [
            'stats' => $stats,
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