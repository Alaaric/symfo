<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\ImageService;
use App\Service\StatsTracker;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/images')]
final class ImageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
    ) {}

    #[Route(name: 'get_all_image', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): JsonResponse
    {
        $images = $imageRepository->findAll();
        $data = $this->serializer->serialize($images, 'json', ['groups' => ['image:read']]);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'new_image', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageService $imageService): JsonResponse
    {
        $uploadedFile = $request->files->get('image');
        $originalName = $request->request->get('originalName');
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images';

        $image = $imageService->uploadAndSaveImage($uploadedFile,  $originalName, $destination);

        return $this->json(['message' => 'Image uploaded', 'filename' => $image->getFileName()], Response::HTTP_CREATED);
    }

    #[Route('/{id}/file', name: 'view_image', methods: ['GET'])]
    public function getImageFile(Request $request, Image $image, StatsTracker $statsTracker): BinaryFileResponse
    {

        $filepath = $this->getParameter('images_directory') . '/' . $image->getFilename();
        $port = $request->getPort();

        if ($port !== 8001) {

            $statsTracker->trackStat($image, 'view');
        }

        return new BinaryFileResponse($filepath, Response::HTTP_OK, []);
    }

    #[Route('/download/{id}', name: 'get_image_file', methods: ['GET'])]
    public function downloadImageFile(Request $request, Image $image, StatsTracker $statsTracker): BinaryFileResponse
    {
        $filepath = $this->getParameter('images_directory') . '/' . $image->getFilename();
        $port = $request->getPort();

        if ($port !== 8001) {
            $statsTracker->trackStat($image, 'download');
        }

        return new BinaryFileResponse($filepath, Response::HTTP_OK, [
            'Content-Disposition' => 'attachment; filename="' . $image->getFilename() . '"',
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_image', methods: ['POST'])]
    public function delete(Image $image): JsonResponse
    {
        $filePath = $this->getParameter('images_directory') . '/' . $image->getFilename();

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->entityManager->remove($image);
        $this->entityManager->flush();

        return $this->json(['message' => 'Image deleted'], Response::HTTP_NO_CONTENT);
    }
}
