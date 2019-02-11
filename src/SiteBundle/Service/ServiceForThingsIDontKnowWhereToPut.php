<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 2/10/2019
 * Time: 8:47 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\ShoeSize;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Repository\CartOrderRepository;
use SiteBundle\Repository\ShoeSizeRepository;
use SiteBundle\Repository\ShoeUserRepository;

class ServiceForThingsIDontKnowWhereToPut
{
    private $orderRepository;
    private $shoeUserRepository;
    private $shoeSizeRepository;

    /**
     * ServiceForThingsIDontKnowWhereToPut constructor.
     * @param CartOrderRepository $orderRepository
     * @param ShoeUserRepository $shoeUserRepository
     * @param ShoeSizeRepository $shoeSizeRepository
     */
    public function __construct(CartOrderRepository $orderRepository, ShoeUserRepository $shoeUserRepository,
                                ShoeSizeRepository $shoeSizeRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->shoeUserRepository = $shoeUserRepository;
        $this->shoeSizeRepository = $shoeSizeRepository;
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
     * @param ShoeUser $shoeUser
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateShoeUser(ShoeUser $shoeUser)
    {
        $this->shoeUserRepository->updateShoeUser($shoeUser);
    }

    /**
     * @param ShoeUser $shoeUser
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteShoeUser(ShoeUser $shoeUser)
    {
        $this->shoeUserRepository->deleteShoeUser($shoeUser);
    }

    /**
     * @param ShoeSize $shoeSize
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteShoeSize(ShoeSize $shoeSize)
    {
        $this->shoeSizeRepository->deleteShoeSize($shoeSize);
    }

    /**
     * @param ShoeSize $shoeSize
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateShoeSize(ShoeSize $shoeSize)
    {
        $this->shoeSizeRepository->updateShoeSize($shoeSize);
    }
}