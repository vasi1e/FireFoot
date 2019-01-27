<?php

namespace SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\Brand;
use SiteBundle\Entity\Model;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\User;
use SiteBundle\Form\ShoeType;
use SiteBundle\Service\BrandModelService;
use SiteBundle\Service\BrandModelServiceInterface;
use SiteBundle\Service\ShoeServiceInterface;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShoeController extends Controller
{
    private $userService;
    private $shoeService;
    private $brandmodelService;

    /**
     * ShoeController constructor.
     * @param UserServiceInterface $userService
     * @param ShoeServiceInterface $shoeService
     * @param BrandModelServiceInterface $brandmodelService
     */
    public function __construct(UserServiceInterface $userService, ShoeServiceInterface $shoeService,
                                BrandModelServiceInterface $brandmodelService)
    {
        $this->userService = $userService;
        $this->shoeService = $shoeService;
        $this->brandmodelService = $brandmodelService;
    }

    /**
     * @Route("/shoe/create", name="shoe_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $shoe = new Shoe();
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            var_dump($shoe);
            exit();
        }

        return $this->render('shoe/create.html.twig', [
            'form' => $form->createView(),
            'isAdmin' => false
        ]);
    }

    /**
     * @Route("/shoe/create/admin", name="admin_shoe_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function adminCreateAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->userService->isAdmin($user) == false)
        {
            return $this->redirect("/");
        }

        $shoe = new Shoe();
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            if ($this->brandmodelService->isGoingToAddBrand())
            {
                $brand = new Brand();
                $brand->setName($_POST['brandToAdd']);

                if ($this->brandmodelService->isBrandExisting( $brand) == false)
                {
                    $this->brandmodelService->saveProperty("brand", $brand);
                    $shoe->setBrand($brand);
                }
                else throw new \Exception("We already have this brand");
            }

            $model = new Model();
            $model->setName($_POST['modelToAdd']);
            /** @var Brand $currBrand */
            $currBrand = $this->brandmodelService->findPropertyByName("brand", $shoe->getBrand()->getName());

            if ($this->brandmodelService->isModelExisting($model, $currBrand->getId()) == false)
            {
                $currBrand->addModel($model);
                $model->setBrand($currBrand);

                $this->brandmodelService->saveProperty("model", $model);
                $this->brandmodelService->updateProperty("brand", $currBrand);
            }
            else throw new \Exception("We already have this model");

            return $this->redirect("/");
        }

        return $this->render('shoe/create.html.twig', [
            'form' => $form->createView(),
            'isAdmin' => true
        ]);
    }

    /**
     * @Route("/get-models-from-brands", name="models_from_brands")
     * @param Request $request
     * @return JsonResponse
     */
    public function listNeighborhoodsOfCityAction(Request $request)
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
