<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\RessourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private RessourceRepository $ressourceRepository;

    public function __construct(CategorieRepository $categorieRepository, SerializerInterface $Serializer,RessourceRepository $ressourceRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->Serializer = $Serializer;
        $this->ressourceRepository = $ressourceRepository;
    }
    #[Route('/api/categories', name: 'app_api_categories', methods: 'GET')]
    public function getAllCategories(): Response
    {
        $categories = $this->categorieRepository->findAll();

        $themeJson = $this->Serializer->serialize($categories,"json", ["groups" => "cat"]);
        $response = New Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set("content-type","application/json");
        $response->setContent($themeJson);
        return $response;
    }

    #[Route('/api/{categorie}/ressource', name: 'app_api_categorie_ressource', methods: 'GET')]
    public function getRessourcesByCategorie($categorie): Response
    {
        $ressources = $this->ressourceRepository->findOneBy($this->categorieRepository->findBy(['intitule' => $categorie]));
        $themeJson = $this->Serializer->serialize($ressources,"json", ["groups" => "res"]);
        $response = New Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set("content-type","application/json");
        $response->setContent($themeJson);
        return $response;
    }
}
