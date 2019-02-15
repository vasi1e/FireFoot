<?php

namespace SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Entity\User;
use SiteBundle\Service\CartOrderServiceInterface;
use SiteBundle\Service\SaveServiceInterface;
use SiteBundle\Service\ServiceForThingsIDontKnowWhereToPut;
use SiteBundle\Service\ShoeServiceInterface;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{
    private $userService;
    private $shoeService;
    private $saveService;
    private $someService;
    private $orderService;

    /**
     * ShoeController constructor.
     * @param UserServiceInterface $userService
     * @param ShoeServiceInterface $shoeService
     * @param SaveServiceInterface $saveService
     * @param CartOrderServiceInterface $orderService
     * @param ServiceForThingsIDontKnowWhereToPut $someService
     */
    public function __construct(UserServiceInterface $userService, ShoeServiceInterface $shoeService,
                                SaveServiceInterface $saveService, CartOrderServiceInterface $orderService,
                                ServiceForThingsIDontKnowWhereToPut $someService)
    {
        $this->userService = $userService;
        $this->shoeService = $shoeService;
        $this->saveService = $saveService;
        $this->orderService = $orderService;
        $this->someService = $someService;
    }

    /**
     * @Route("/addToCart", name="add_to_cart")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return string
     */
    public function addToCartOrGetPriceAction(Request $request)
    {
        $shoeId = $request->query->get("shoeId");
        /** @var Shoe $shoe */
        $shoe = $this->shoeService->findShoeById($shoeId);

        if ($shoe->getCondition() == "used")
        {
            $shoeUser = $this->shoeService->findShoeUserByShoeId($shoeId)[0];
        } else {
            $sizeNum = $request->query->get("sizeNum");
            $size = $this->shoeService->findSizeByNumber($sizeNum);
            if ($shoe === null || $size === null)
            {
                return new Response(null);
            }

            /** @var ShoeUser $shoeUser */
            $shoeUser = $this->shoeService->findShoeUserByShoeAndSize($shoe, $size)[0];
        }

        if ($request->query->get("price") == "true") return new Response($shoeUser->getPrice());
        else
        {
            $order = new CartOrder();
            /** @var User $user */
            $user = $this->getUser();
            $order->setBuyer($user)->setShoeUser($shoeUser)->setSend(false)->setPaid(false);
            $this->saveService->saveOrder($order);

            $user->addOrder($order);
            $shoeUser->addOrder($order);

            return new Response();
        }
    }

    /**
     * @Route("order/delete/{id}", name="delete_order")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromCartAction($id)
    {
        $order = $this->orderService->findOrderById($id);
        $this->orderService->deleteOrder($order);

        return $this->redirectToRoute('my_orders');
    }

    /**
     * @Route("order/address", name="address")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addressAction(Request $request)
    {
        if (isset($_POST['submit']))
        {
            $country = $request->request->get('country');
            $city = $request->request->get('city');
            $address = $request->request->get('street');
            $number = $request->request->get('number');

            $fullAddress = $address . ' ' . $number . ', ' . $city . ', ' . $country;

            /** @var User $currUser */
            $currUser = $this->getUser();

            /** @var CartOrder $order */
            foreach ($currUser->getOrders() as $orderId)
            {
                $order = $this->orderService->findOrderById($orderId);
                $order->setAddress($fullAddress);
                $this->orderService->updateOrder($order);
            }
            return $this->redirectToRoute('payment');
        }
        return $this->render('order/address.html.twig');
    }

    /**
     * @Route("/order/payment", name="payment")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function paymentAction()
    {
        return $this->render('order/payment.html.twig');
    }

    /**
     * @Route("/order/error/{id}", name="order_error")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return Response
     */
    public function errorAction($id)
    {
        /** @var CartOrder $order */
        $order = $this->orderService->findOrderById($id);
        return $this->render('order/noShoe.html.twig', [
            'order' => $order,
            'shoeUser' => $order->getShoeUser(),
        ]);
    }

    /**
     * @Route("/order/my", name="my_orders")
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
