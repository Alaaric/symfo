<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/images')]
final class ImageController extends AbstractController
{
    #[Route(name: 'app_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): JsonResponse
    {
        $images = $imageRepository->findAll();
        return $this->json($images);
    }

    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $uploadedFile = $request->files->get('image');

        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $uniqueName = uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($destination, $uniqueName);

        $image = new Image();
        $image->setFileName($uniqueName);
        $image->setName($uploadedFile->getClientOriginalName() ?? '');
        $entityManager->persist($image);
        $entityManager->flush();

        return $this->json(['message' => 'Image uploaded', 'filename' => $uniqueName]);
    }

    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): JsonResponse
    {
        return $this->json($image);
    }

    #[Route('/{id}/file', name: 'get_image_file', methods: ['GET'])]
    public function getImageFile(Image $image): BinaryFileResponse
    {
        $filename = $image->getFilename();
        $filepath = $this->getParameter('images_directory') . '/' . $filename;

        if (!file_exists($filepath)) {
            throw $this->createNotFoundException('Image file not found');
        }

        return new BinaryFileResponse($filepath, 200, []);
    }

    #[Route('/{id}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Image $image, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($image);
        $entityManager->flush();

        return $this->json(['message' => 'Image deleted'], Response::HTTP_NO_CONTENT);
    }
}
