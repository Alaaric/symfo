<?php

namespace App\Controller;

use App\Factory\UploadedImageInfosDtoFactory;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ImageDownloadService;

#[Route('/images')]
final class ImageController extends AbstractController
{
    private ImageRepository $imageRepository;

    public function __construct(
        ImageRepository $imageRepository,
        private UploadedImageInfosDtoFactory $dtoFactory

    ) {
        $this->imageRepository = $imageRepository;
    }

    #[Route(name: 'images', methods: ['GET'])]
    public function index(): Response
    {
        $data = $this->imageRepository->getAllImages();

        return $this->render('images/images.html.twig', [
            'images' => $data,
        ]);
    }

    #[Route('/{id}', name: 'image', methods: ['GET'])]
    public function getImage(int $id): Response
    {
        $data = $this->imageRepository->getImageById($id);

        return new Response($data, Response::HTTP_OK, [
            'Content-Type' => 'image/jpeg',
        ]);
    }

    #[Route('/download/{id}', name: 'download_image', methods: ['GET'])]
    public function downloadImage(int $id, ImageDownloadService $imageDownloadService): Response
    {
        $downloadData = $imageDownloadService->getDownloadData($id);

        return new Response($downloadData['content'], Response::HTTP_OK, [
            'Content-Type' => $downloadData['contentType'],
            'Content-Disposition' => 'attachment; filename="' . $downloadData['filename'] . '"',
        ]);
    }

    #[Route('/upload', name: 'upload_image', methods: ['POST'])]
    public function upload(Request $request): Response
    {
        $file = $request->files->get('image');

        if (!$file) {
            return $this->json(['error' => 'No file provided'], Response::HTTP_BAD_REQUEST);
        }

        $dto = $this->dtoFactory->createFromUploadedFile($file);
        $result = $this->imageRepository->uploadImage($dto);

        return $this->json($result);
    }
}
