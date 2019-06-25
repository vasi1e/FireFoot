<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 2/12/2019
 * Time: 12:00 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Repository\CartOrderRepository;

class CartOrderService implements CartOrderServiceInterface
{
    private $orderRepository;

    /**
     * CartOrderService constructor.
     * @param $orderRepository
     */
    public function __construct(CartOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function findOrderById($id)
    {
        return $this->orderRepository->find($id);
    }

    public function getListOfUnpaidOrders($orders)
    {
        $unpaidOrders = [];

        /** @var CartOrder $order */
        foreach ($orders as $orderId)
        {
            $order = $this->orderRepository->find($orderId);
            if (!$order->isPaid()) $unpaidOrders[] = $order;
        }

        return $unpaidOrders;
    }

    public function isTheOrderAlreadyMadeBySomeoneElse(CartOrder $order)
    {
        /** @var ShoeUser $shoeUser */
        $shoeUser = $order->getShoeUser();

        if ($this->orderRepository->findSameOrderButPaidByShoeUserId($shoeUser->getId()) != null) return true;
        else return false;
    }
}