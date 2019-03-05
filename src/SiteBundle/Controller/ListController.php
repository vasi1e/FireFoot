<?php

namespace SiteBundle\Controller;

use SiteBundle\Entity\Model;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\User;
use SiteBundle\Service\BrandModelServiceInterface;
use SiteBundle\Service\ShoeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends Controller
{
    private $shoeService;
    private $brandmodelService;

    /**
     * ListController constructor.
     * @param ShoeServiceInterface $shoeService
     * @param BrandModelServiceInterface $brandmodelService
     */
    public function __construct(ShoeServiceInterface $shoeService, BrandModelServiceInterface $brandmodelService)
    {
        $this->shoeService = $shoeService;
        $this->brandmodelService = $brandmodelService;
    }

    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $topLikedShoes = $this->shoeService->findTop5MostLiked();
        $topLatestShoes = $this->shoeService->findTop5LatestRelease();

        return $this->render('homepage/index.html.twig', [
            'likedShoes' => $topLikedShoes,
            'latestShoes' => $topLatestShoes
        ]);
    }

    /**
     * @Route("/shoe/list/{id}", name="list_shoes_id")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listShoesAction($id = null)
    {
        $sortMethod = "";
        switch ($id)
        {
            case 1: $sortMethod = "uploadDateAndTime"; break;
            case 2: $sortMethod = "likes"; break;
            default: $sortMethod = ""; break;
        }

        if ($sortMethod == "") $allShoes = $this->shoeService->listOfAllShoes();
        else $allShoes = $this->shoeService->sortShoesBy($sortMethod);

        $shoes = $this->shoeService->makeJSONFromShoes($allShoes);

        return $this->render('shoe/list.html.twig', [
            'shoes' => $shoes
        ]);
    }

    /**
     * @Route("/shoe/sortedList", name="list_shoes_sorted_by")
     * @param Request $request
     * @return JsonResponse
     */
    public function listSortedShoesAction(Request $request)
    {
        $sortMethod = $request->query->get("sortMethod");

        if ($sortMethod != "likes" && $sortMethod != "uploadDateAndTime")  return new JsonResponse(["error" => "some error"]);
        else {
            $shoes = $this->shoeService->sortShoesBy($sortMethod);
            $responseArray = $this->shoeService->makeJSONFromShoes($shoes);

            return new JsonResponse($responseArray);
        }
    }

    /**
     * @Route("/shoe/used/{id}", name="list_used_shoes")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsedShoes($id)
    {
        $shoe = $this->shoeService->findShoeById($id);
        $shoes = $this->shoeService->findShoesByBrandAndModel($shoe);

        $usedShoes = [];
        /** @var Shoe $s */
        foreach ($shoes as $s)
        {
            if ($s->getCondition() == 'used') $usedShoes[] = $s;
        }

        return $this->render('shoe/listUsedShoes.html.twig', [
            'shoes' => $usedShoes
        ]);
    }

    /**
     * @Route("/get-models-from-brands", name="models_from_brands")
     * @param Request $request
     * @return JsonResponse
     */
    public function listModelsOfBrandAction(Request $request)
    {
        $brandId = $request->query->get("brandid");

        $models = $this->brandmodelService->getModelsForBrand($brandId);

        $responseArray = array();
        /** @var Model $model */
        foreach($models as $model){
            $responseArray[] = array(
                "id" => $model->getId(),
                "name" => $model->getName()
            );
        }

        return new JsonResponse($responseArray);
    }
}
