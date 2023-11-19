<?php

namespace App\Controller;

use App\Entity\Action;
use App\Repository\ActionRepository;
use App\Repository\CategorieRepository;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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
    private EntityManager $entityManager;

    public function __construct(CategorieRepository $categorieRepository, SerializerInterface $Serializer,RessourceRepository $ressourceRepository,ActionRepository $actionRepository,EntityManager $entityManager)
    {
        $this->categorieRepository = $categorieRepository;
        $this->Serializer = $Serializer;
        $this->ressourceRepository = $ressourceRepository;
        $this->actionRepository = $actionRepository;
        $this->entityManager = $entityManager;
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

    #[Route('api/action/last', name: 'app_action_last', methods: ['GET'])]
    public function getActionNumber(): Response
    {

        // create the SQL statement
        $sql = 'SELECT a.id, r.intitule 
        FROM action a 
        JOIN ressource r ON a.id = r.id 
        ORDER BY a.id DESC 
        LIMIT 4';
        // create the result set mapping
        $rsm = new ResultSetMappingBuilder($this->entityManager);
        $rsm->addRootEntityFromClassMetadata(Action::class, 'a');

        // create the native query
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        // get the results
        $actions = $query->getResult();

        $ActionJson = $this->Serializer->serialize($actions, 'json' ,['groups'=>'getAction']);

        return new Response($ActionJson , Response::HTTP_OK, ['content-type' => 'application/json']);
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
