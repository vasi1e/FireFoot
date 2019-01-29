<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 1/27/2019
 * Time: 3:04 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\Size;
use SiteBundle\Entity\User;
use SiteBundle\Repository\ShoeRepository;
use SiteBundle\Repository\SizeRepository;

class ShoeService implements ShoeServiceInterface
{
    private $sizeRepository;
    private $shoeRepository;

    /**
     * ShoeService constructor.
     * @param SizeRepository $sizeRepository
     * @param ShoeRepository $shoeRepository
     */
    public function __construct(SizeRepository $sizeRepository, ShoeRepository $shoeRepository)
    {
        $this->sizeRepository = $sizeRepository;
        $this->shoeRepository = $shoeRepository;
    }

    public function addShoeSize(Shoe $shoe, Size $size)
    {
        $shoeSizeRow = $this->sizeRepository->findShoeInTableShoeSizes($shoe->getId(), $size->getId());

        if ($shoeSizeRow == false)
        {
            $this->sizeRepository->saveShoeSize($shoe->getId(), $size->getId(), 1);
        }
        else
        {
            $this->setSizeQuantityForShoe($shoe->getId(), $size);
        }
    }

    public function addShoeUser(Shoe $shoe, User $user, $price)
    {
        $this->shoeRepository->saveShoeUser($shoe->getId(), $user->getId(), $price);
    }

    public function getSizeQuantityForShoe($shoeId, Size $size)
    {
        return $size->getQuantity($shoeId, $size->getId(), $this->sizeRepository);
    }

    public function setSizeQuantityForShoe($shoeId, Size $size)
    {
        $size->addOneQuantity($shoeId, $this->sizeRepository);
    }

    public function getPriceForShoe($userId, Shoe $shoe)
    {
        return $shoe->getPrice($userId, $this->shoeRepository);
    }

    public function setPriceForShoe($price, $userId, Shoe $shoe)
    {
        $shoe->setPrice($price, $userId, $this->shoeRepository);
    }

    /**
     * @param Shoe $shoe
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveShoe(Shoe $shoe)
    {
        $this->shoeRepository->saveShoe($shoe);
    }

    public function findTheShoe(Shoe $shoe)
    {
        return $this->shoeRepository->findOneBy(['brand' => $shoe->getBrand(), 'model' => $shoe->getModel()]);
    }

    /**
     * @param Size $size
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveSize(Size $size)
    {
        $this->sizeRepository->saveSize($size);
    }
}