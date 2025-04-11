<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Repository\StatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/stats', name: 'admin_stats', methods: ['GET'])]
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
    }
}