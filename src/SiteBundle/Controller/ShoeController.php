<?php

namespace SiteBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
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
use SiteBundle\Service\CartOrderServiceInterface;
use SiteBundle\Service\ShoeServiceInterface;
use SiteBundle\Service\SUDServiceInterface;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoeController extends Controller
{
    private $userService;
    private $shoeService;
    private $brandmodelService;
    private $SUDService;
    private $orderService;

    /**
     * ShoeController constructor.
     * @param UserServiceInterface $userService
     * @param ShoeServiceInterface $shoeService
     * @param BrandModelServiceInterface $brandmodelService
     * @param SUDServiceInterface $SUDService
     * @param CartOrderServiceInterface $orderService
     */
    public function __construct(UserServiceInterface $userService, ShoeServiceInterface $shoeService,
                                BrandModelServiceInterface $brandmodelService, SUDServiceInterface $SUDService,
                                CartOrderServiceInterface $orderService)
    {
        $this->userService = $userService;
        $this->shoeService = $shoeService;
        $this->brandmodelService = $brandmodelService;
        $this->SUDService = $SUDService;
        $this->orderService = $orderService;
    }

    /**
     * @Route("/shoe/create/admin", name="admin_shoe_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function adminShoeCreateAction(Request $request)
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
            $error = false;
            if ($_POST['shoe']['brand'] == "" && $_POST["brandToAdd"] == "")
            {
                $this->addFlash("error", "You must choose one brand or write a new one");
                $error = true;
            }
            else if ($_POST['shoe']['brand'] == "")
            {
                $brand = new Brand();
                $brand->setName($_POST['brandToAdd']);

                if ($this->brandmodelService->isBrandExisting($brand) == false)
                {
                    $this->SUDService->saveProperty("brand", $brand);
                    $shoe->setBrand($brand);
                }
                else
                {
                    $this->addFlash("error", "We already have this brand");
                    $error = true;
                }
            }

            if ($error)
            {
                return $this->render('shoe/create.html.twig', [
                    'form' => $form->createView(),
                    'isAdmin' => true
                ]);
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

                $this->SUDService->saveProperty("model", $model);
                $this->SUDService->updateProperty("brand", $currBrand);
            }
            else
            {
                $this->addFlash("error", "We already have this model");
                $error = true;
            }

            if ($error)
            {
                return $this->render('shoe/create.html.twig', [
                    'form' => $form->createView(),
                    'isAdmin' => true
                ]);
            }

            $shoe->setCondition("new");
            $shoe->setConditionOutOf10('10');
            $this->SUDService->saveProperty("shoe", $shoe);

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
     * @Route("/shoe/sell/{id}", name="shoe_sell_id")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putShoeForSellingAction(Request $request, $id = null)
    {
        if ($id === null)
        {
            $shoe = new Shoe();
            $form = $this->createForm(ShoeType::class, $shoe);
            $form->handleRequest($request);

        } else {

            /** @var Shoe $shoe */
            $shoe = $this->shoeService->findShoeById($id);
            if ($shoe === null)
            {
                return $this->redirect("/");
            }

            $shoe->setCondition(null)->setConditionOutOf10(null);
            $form = $this->createForm(ShoeType::class, $shoe);
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($shoe->getCondition() == 'new')
            {
                $shoes = $this->shoeService->findShoesByBrandAndModel($shoe);

                /** @var Shoe $s */
                foreach ($shoes as $s)
                {
                    if ($s->getCondition() == 'new')
                    {
                        $shoe = $s;
                        break;
                    }
                }
            }
            else {
                $this->SUDService->saveProperty("shoe", $shoe);
                $imageFiles = $shoe->getUploadImages();
                $this->shoeService->addingImagesForShoe($imageFiles, $this->getParameter('shoe_directory'), $shoe);
                $shoe->setUploadImages(null);
            }

            $size = new Size();
            $size->setNumber($_POST['size']);

            if ($this->shoeService->isThereSize($size)) $size = $this->shoeService->findSizeByNumber($size->getNumber());
            else $this->SUDService->saveProperty("size", $size);

            $shoeSize = new ShoeSize();
            $shoeSize->setSize($size)->setShoe($shoe);

            if ($shoe->getCondition() == "new")
            {
                if ($this->shoeService->isThereThisSizeForThisShoe($shoeSize)) $shoeSize = $this->shoeService->findShoeSizeByShoeAndSize($shoeSize->getShoe(), $shoeSize->getSize());
                $shoeSize->setQuantity($shoeSize->getQuantity() + 1);
            }
            else $shoeSize->setQuantity(1);

            $this->SUDService->saveProperty("shoeSize", $shoeSize);

            $shoe->addSize($shoeSize);
            $size->addShoe($shoeSize);

            /** @var User $seller */
            $seller = $this->getUser();

            $shoeUser = new ShoeUser();
            $shoeUser->setShoe($shoe)->setSeller($seller)->setPrice($_POST['price'])->setSize($size)->setSold(false);
            $this->SUDService->saveProperty("shoeUser", $shoeUser);

            $shoe->addSeller($shoeUser);
            $seller->addSellerShoe($shoeUser);

            return $this->redirect("/");
        }

        return $this->render('shoe/create.html.twig', [
            'form' => $form->createView(),
            'isAdmin' => false
        ]);
    }

    /**
     * @Route("/shoe/buy", name="shoe_buy")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeShoeAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var CartOrder $order */
        foreach ($user->getOrders() as $orderId)
        {
            /** @var CartOrder $order */
            $order = $this->orderService->findOrderById($orderId);

            if (!$order->isPaid()) {
                if ($this->orderService->isTheOrderAlreadyMadeBySomeoneElse($order)) return $this->redirectToRoute('order_error', ['id' => $order->getId()]);
                else {
                    $shoe = $order->getShoeUser()->getShoe();

                    $sizeNum = $order->getShoeUser()->getSize();
                    $size = $this->shoeService->findSizeByNumber($sizeNum);

                    /** @var ShoeSize $shoeSize */
                    $shoeSize = $this->shoeService->findShoeSizeByShoeAndSize($shoe, $size);

                    if ($shoeSize->getQuantity() == 1) {
                        $this->SUDService->deleteProperty("shoeSize", $shoeSize);
                    } else {
                        $shoeSize->setQuantity($shoeSize->getQuantity() - 1);
                        $this->SUDService->updateProperty("shoeSize", $shoeSize);
                    }

                    $order->getShoeUser()->setSold(true)->setOrdersToEmpty();
                    $this->SUDService->updateProperty("shoeUser", $order->getShoeUser());

                    $order->setPaid(true);
                    $this->SUDService->updateProperty("order", $order);
                }
            }
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/shoe/view/{id}", name="shoe_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction($id)
    {
        /** @var Shoe $shoe */
        $shoe = $this->shoeService->findShoeById($id);
        if ($shoe === null)
        {
            return $this->redirectToRoute('homepage');
        }

        /** @var User $currUser */
        $currUser = $this->getUser();
        if ($currUser == null)
        {
            $likeFlag = 0;
            $const = 0;
        } else {
            if ($this->shoeService->doesThisUserLikeTheShoe($shoe, $currUser)) {
                $likeFlag = 1;
                $const = 1;
            } else {
                $likeFlag = 0;
                $const = 0;
            }
        }

        return $this->render('shoe/view.html.twig', [
            'shoe' => $shoe,
            'likeFlag' => $likeFlag,
            'const' => $const,
        ]);
    }

    /**
     * @Route("/shoe/{id}/likes", name="shoe_likes")
     * @param $id
     * @return Response
     */
    public function likeAction($id)
    {
        /** @var User $currUser */
        $currUser = $this->getUser();
        if ($currUser == null) return new Response("null");

        /** @var Shoe $shoe */
        $shoe = $this->shoeService->findShoeById($id);

        if ($this->shoeService->doesThisUserLikeTheShoe($shoe, $currUser))
        {
            $currUser->removeLikedShoe($shoe);
            $shoe->removeLike();
            $likeFlag = 0;
        }
        else{
            $currUser->addLikedShoe($shoe);
            $shoe->addLike();
            $likeFlag = 1;
        }

        $this->SUDService->updateProperty("user", $currUser);
        $this->SUDService->updateProperty("shoe", $shoe);

        return new Response($likeFlag);
    }
}
