<?php

namespace App\Controller;

use App\Repository\ActionRepository;
use App\Repository\CategorieRepository;
use App\Repository\RessourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private RessourceRepository $ressourceRepository;
    private ActionRepository $actionRepository;

    public function __construct(CategorieRepository $categorieRepository, SerializerInterface $Serializer,RessourceRepository $ressourceRepository,ActionRepository $actionRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->Serializer = $Serializer;
        $this->ressourceRepository = $ressourceRepository;
        $this->actionRepository = $actionRepository;
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
        $categorieEntity = $this->categorieRepository->findOneBy(['intitule' => $categorie]);
        $ressources = $this->ressourceRepository->findBy(['categorie' => $categorieEntity]);
        $themeJson = $this->Serializer->serialize($ressources,"json", ["groups" => "res"]);
        $response = New Response();
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set("content-type","application/json");
        $response->setContent($themeJson);
        return $response;
    }

    #[Route('/api/action', name: 'app_api_post_action', methods: ['POST'])]
    public function postAction(Request $request): Response
    {
        $returnActions = [];
        $data = $request->toArray();
        foreach ($data as $key => $actionId) {

            $returnActions[] = [
                "key" => $key,
                "id" => $actionId,
                "nombreRessources" => $this->actionRepository->find($actionId)->getNombreRessources(),
                "ressource" => $this->actionRepository->find($actionId)->getRessource()->getId(),
                "user" => $this->actionRepository->find($actionId)->getUser()->getId()
            ];
        }

        return new Response(json_encode($returnActions) , 200, []);
    }



}
