<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 2/10/2019
 * Time: 8:47 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\ShoeSize;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Repository\ShoeSizeRepository;
use SiteBundle\Repository\ShoeUserRepository;

class ServiceForThingsIDontKnowWhereToPut
{
    private $shoeUserRepository;
    private $shoeSizeRepository;

    /**
     * ServiceForThingsIDontKnowWhereToPut constructor.
     * @param ShoeUserRepository $shoeUserRepository
     * @param ShoeSizeRepository $shoeSizeRepository
     */
    public function __construct(ShoeUserRepository $shoeUserRepository, ShoeSizeRepository $shoeSizeRepository)
    {
        $this->shoeUserRepository = $shoeUserRepository;
        $this->shoeSizeRepository = $shoeSizeRepository;
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