<?php

namespace SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Entity\User;
use SiteBundle\Service\CartOrderServiceInterface;
use SiteBundle\Service\ShoeServiceInterface;
use SiteBundle\Service\SUDServiceInterface;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{
    private $userService;
    private $shoeService;
    private $SUDService;
    private $orderService;

    /**
     * ShoeController constructor.
     * @param UserServiceInterface $userService
     * @param ShoeServiceInterface $shoeService
     * @param SUDServiceInterface $SUDService
     * @param CartOrderServiceInterface $orderService
     */
    public function __construct(UserServiceInterface $userService, ShoeServiceInterface $shoeService,
                                SUDServiceInterface $SUDService, CartOrderServiceInterface $orderService)
    {
        $this->userService = $userService;
        $this->shoeService = $shoeService;
        $this->SUDService = $SUDService;
        $this->orderService = $orderService;
    }

    /**
     * @Route("/addToCart", name="add_to_cart")
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
            if ($sizeNum == null) return new Response("Please choose size.");
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

            if ($user == null) return new Response("not logged");

            $order->setBuyer($user)->setShoeUser($shoeUser)->setSend(false)->setPaid(false);
            $this->SUDService->saveProperty("order", $order);

            $user->addOrder($order);
            $shoeUser->addOrder($order);

            return new Response("okay");
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
        $this->SUDService->deleteProperty("order", $order);

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
                $this->SUDService->updateProperty("order", $order);
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
}
