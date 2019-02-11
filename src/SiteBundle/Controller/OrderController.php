<?php

namespace SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Entity\User;
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

    /**
     * ShoeController constructor.
     * @param UserServiceInterface $userService
     * @param ShoeServiceInterface $shoeService
     * @param SaveServiceInterface $saveService
     * @param ServiceForThingsIDontKnowWhereToPut $someService
     */
    public function __construct(UserServiceInterface $userService, ShoeServiceInterface $shoeService,
                                SaveServiceInterface $saveService,
                                ServiceForThingsIDontKnowWhereToPut $someService)
    {
        $this->userService = $userService;
        $this->shoeService = $shoeService;
        $this->saveService = $saveService;
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
            $order->setBuyer($user)->setShoeUser($shoeUser)->setSend(false)->setPaid(false);
            $this->saveService->saveOrder($order);

            $user->addOrder($order);
            $shoeUser->addOrder($order);

            return new Response();
        }
    }

    /**
     * @Route("order/address", name="address")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
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
                $order = $this->someService->findOrderById($orderId);
                $order->setAddress($fullAddress);
                $this->someService->updateOrder($order);
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
}
