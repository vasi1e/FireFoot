<?php

namespace SiteBundle\Controller;

use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\Model;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\User;
use SiteBundle\Service\BrandModelServiceInterface;
use SiteBundle\Service\CartOrderServiceInterface;
use SiteBundle\Service\MessageServiceInterface;
use SiteBundle\Service\ShoeServiceInterface;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ListController extends Controller
{
    private $shoeService;
    private $brandmodelService;
    private $messageService;
    private $userService;
    private $orderService;

    /**
     * ListController constructor.
     * @param ShoeServiceInterface $shoeService
     * @param BrandModelServiceInterface $brandmodelService
     * @param MessageServiceInterface $messageService
     * @param UserServiceInterface $userService
     * @param CartOrderServiceInterface $orderService
     */
    public function __construct(ShoeServiceInterface $shoeService, BrandModelServiceInterface $brandmodelService,
                                MessageServiceInterface $messageService, UserServiceInterface $userService,
                                CartOrderServiceInterface $orderService)
    {
        $this->shoeService = $shoeService;
        $this->brandmodelService = $brandmodelService;
        $this->messageService = $messageService;
        $this->userService = $userService;
        $this->orderService = $orderService;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function listShoesAction(Request $request, $id = null)
    {
        $brandsNameFilter = array_filter(explode("And", $request->query->get('brand')));
        $sizeFilter = array_filter(explode("And", $request->query->get('size')));

        $brandsFilter = [];
        foreach ($brandsNameFilter as $brandName)
        {
            $brand = $this->brandmodelService->findPropertyByName("brand", $brandName);
            if (is_null($brand)) throw new \Exception("Invalid brand in URL");
            else $brandsFilter[] = $brand->getId();
        }

        $filters = array("brands" => $brandsFilter, "sizes" => $sizeFilter);

        switch ($id)
        {
            case 1: $sortMethod = "uploadDateAndTime"; break;
            case 2: $sortMethod = "likes"; break;
            default: $sortMethod = null; break;
        }

        $shoes = $this->shoeService->listOfAllShoes($filters, $sortMethod);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $shoes, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $brands = $this->brandmodelService->getAllBrandsName();

        return $this->render('shoe/list.html.twig', [
            'pagination' => $pagination,
            'brands' => $brands
        ]);
    }

    /**
     * @Route("/shoe/used/list/{id}", name="list_used_shoes")
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
     * @Route("/message/list", name="chats_list")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function listMessages()
    {
        /** @var User $user */
        $user = $this->getUser();
        $chats = $this->messageService->getListOfChats($user->getId());
        $rightChats = [];

        foreach ($chats as $chat)
        {
            /** @var Shoe $shoe */
            $shoe = $this->shoeService->findShoeById($chat[1]);
            $shoeName = $shoe->getBrand() . ' ' . $shoe->getModel();
            $rightChat['shoeName'] = $shoeName;
            $rightChat['shoeId'] = $chat[1];

            if($chat[2] == $user->getId())
            {
                $rightChat['personId'] = $chat[3];
                $person = $this->userService->findUserById($chat[3]);
                $rightChat['personName'] = $person->getFullName();
            } else {
                $rightChat['personId'] = $chat[2];
                $person = $this->userService->findUserById($chat[2]);
                $rightChat['personName'] = $person->getFullName();
            }

            $rightChat['read'] = true;
            if (!empty($this->messageService->isTheChatRead($chat['chatId'], $user->getId())))
            {
                if ($this->messageService->isTheChatRead($chat['chatId'], $user->getId())[0]['read']) $rightChat['read'] = true;
                else $rightChat['read'] = false;
            }

            $rightChats[] = $rightChat;
        }

        return $this->render('message/list.html.twig', [
            'chats' => $rightChats
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
     * @Route("/order/list", name="my_orders")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function listOrdersAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $orders = $user->getOrders();
        $unpaidOrders = $this->orderService->getListOfUnpaidOrders($orders);

        $totalSum = 0;
        /** @var CartOrder $order */
        foreach ($unpaidOrders as $order)
        {
            $totalSum += doubleval($order->getShoeUser()->getPrice());
        }

        return $this->render('order/myorders.html.twig', [
            'orders' => $unpaidOrders,
            'totalPrice' => number_format($totalSum, 2)
        ]);
    }
}
