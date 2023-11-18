<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository, SerializerInterface $Serializer)
    {
        $this->categorieRepository = $categorieRepository;
        $this->Serializer = $Serializer;
    }
    #[Route('/api/categories', name: 'app_api_categories', methods: 'GET')]
    public function getAllCategories(): Response
    {
        $categories = $this->categorieRepository->findAll();

        $themeJson = $this->Serializer->serialize($categories,"json", ["groups" => "cat"]);
        dd($themeJson);
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set("content-type","application/json");
        $response->setContent($themeJson);
        return $response;
    }
}
