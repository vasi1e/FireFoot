<?php

namespace SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SiteBundle\Entity\Brand;
use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\Model;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\ShoeSize;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Entity\Size;
use SiteBundle\Entity\User;
use SiteBundle\Form\ShoeType;
use SiteBundle\Service\BrandModelServiceInterface;
use SiteBundle\Service\SaveService;
use SiteBundle\Service\SaveServiceInterface;
use SiteBundle\Service\ShoeServiceInterface;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoeController extends Controller
{
    private $userService;
    private $shoeService;
    private $brandmodelService;
    private $saveService;

    /**
     * ShoeController constructor.
     * @param UserServiceInterface $userService
     * @param ShoeServiceInterface $shoeService
     * @param BrandModelServiceInterface $brandmodelService
     * @param SaveServiceInterface $saveService
     */
    public function __construct(UserServiceInterface $userService, ShoeServiceInterface $shoeService,
                                BrandModelServiceInterface $brandmodelService, SaveServiceInterface $saveService)
    {
        $this->userService = $userService;
        $this->shoeService = $shoeService;
        $this->brandmodelService = $brandmodelService;
        $this->saveService = $saveService;
    }

    /**
     * @Route("/shoe/sell", name="shoe_sell")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createShoeWithoutId(Request $request)
    {
        $shoe = new Shoe();
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted()) return $this->createAction($shoe);

        return $this->render('shoe/create.html.twig', [
            'form' => $form->createView(),
            'isAdmin' => false
        ]);
    }

    /**
     * @Route("/shoe/sell/{id}", name="shoe_sell_id")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createShoeWithId(Request $request, $id)
    {
        /** @var Shoe $shoe */
        $shoe = $this->shoeService->findShoeById($id);
        if ($shoe === null)
        {
            return $this->redirect("/");
        }

        $shoe->setCondition(null)->setConditionOutOf10(null);
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted()) return $this->createAction($shoe);

        return $this->render('shoe/create.html.twig', [
            'form' => $form->createView(),
            'isAdmin' => false
        ]);
    }

    /**
     * @Route("/shoe/buy/{id}", name="shoe_buy_id")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction(Request $request, $id)
    {
        
    }

    /**
     * @Route("/shoe/create/admin", name="admin_shoe_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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
                    $this->saveService->saveProperty("brand", $brand);
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
                $shoe->setModel($model);

                $this->saveService->saveProperty("model", $model);
                $this->brandmodelService->updateProperty("brand", $currBrand);
            }
            else throw new \Exception("We already have this model");

            $shoe->setCondition("new");
            $shoe->setConditionOutOf10('10');
            $this->saveService->saveShoe($shoe);

            $imageFiles = $form->getData()->getUploadImages();
            $this->shoeService->addingImagesForShoe($imageFiles, $this->getParameter('shoe_directory'), $shoe);

            return $this->redirect("/");
        }

        return $this->render('shoe/create.html.twig', [
            'form' => $form->createView(),
            'isAdmin' => true
        ]);
    }

    /**
     * @Route("/shoe/view/{id}", name="shoe_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction($id)
    {
        $shoe = $this->shoeService->findShoeById($id);
        if ($shoe === null)
        {
            return $this->redirect("/");
        }

        return $this->render('shoe/view.html.twig', [
            'shoe' => $shoe
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

    /**
     * @Route("/addToCart", name="add_to_cart")
     * @param Request $request
     * @return string
     */
    public function addToCartOrGetPriceAction(Request $request)
    {
        $shoeId = $request->query->get("shoeId");
        $sizeNum = $request->query->get("sizeNum");

        $shoe = $this->shoeService->findShoeById($shoeId);
        $size = $this->shoeService->findSizeByNumber($sizeNum);
        if ($shoe == null || $size == null)
        {
            return new Response(null);
        }

        /** @var ShoeUser $shoeUser */
        $shoeUser = $this->shoeService->findShoeUserByShoeAndSize($shoe, $size)[0];

        if ($request->query->get("price") == "true") return new Response($shoeUser->getPrice());
        else
        {
            $order = new CartOrder();
            /** @var User $user */
            $user = $this->getUser();
            $order->setBuyer($user)->setShoeUser($shoeUser);
            $this->saveService->saveOrder($order);

            $user->addOrder($order);
            $shoeUser->addOrder($order);

            return new Response();
        }
    }

    public function createAction(Shoe $shoe)
    {
        if ($shoe->getCondition() == 'new')
        {
            /** @var Shoe $shoe */
            $shoe = $this->shoeService->findTheShoe($shoe);
        }
        else {
            $this->saveService->saveShoe($shoe);
            $imageFiles = $shoe->getUploadImages();
            $this->shoeService->addingImagesForShoe($imageFiles, $this->getParameter('shoe_directory'), $shoe);
            $shoe->setUploadImages(null);
        }

        $size = new Size();
        $size->setNumber($_POST['size']);

        if ($this->shoeService->isThereSize($size)) $size = $this->shoeService->findSizeByNumber($size->getNumber());
        else $this->saveService->saveSize($size);

        $shoeSize = new ShoeSize();
        $shoeSize->setSize($size)->setShoe($shoe);

        if ($shoe->getCondition() == "new")
        {
            if ($this->shoeService->isThereThisSizeForThisShoe($shoeSize)) $shoeSize = $this->shoeService->findShoeSizeByShoeAndSize($shoeSize->getShoe(), $shoeSize->getSize());
            $shoeSize->setQuantity($shoeSize->getQuantity() + 1);
        }
        else $shoeSize->setQuantity(1);

        $this->saveService->saveShoeSize($shoeSize);

        $shoe->addSize($shoeSize);
        $size->addShoe($shoeSize);

        /** @var User $seller */
        $seller = $this->getUser();

        $shoeUser = new ShoeUser();
        $shoeUser->setShoe($shoe)->setSeller($seller)->setPrice($_POST['price'])->setSize($size);
        $this->saveService->saveShoeUser($shoeUser);

        $shoe->addSeller($shoeUser);
        $seller->addSellerShoe($shoeUser);

        return $this->redirect("/");
    }
}
