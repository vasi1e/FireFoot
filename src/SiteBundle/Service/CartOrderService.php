<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 2/12/2019
 * Time: 12:00 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\CartOrder;
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

    /**
     * @param CartOrder $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateOrder(CartOrder $order)
    {
        $this->orderRepository->updateOrder($order);
    }

    /**
     * @param CartOrder $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteOrder(CartOrder $order)
    {
        $this->orderRepository->deleteOrder($order);
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
}