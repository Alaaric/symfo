<?php

namespace App\Controller;

use App\Repository\ApiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    private ApiRepository $apiRepository;

    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $data = $this->apiRepository->fetchTestData();

        return $this->render('test/index.html.twig', [
            'data' => $data,
        ]);
    }
}