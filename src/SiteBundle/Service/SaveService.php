<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 2/7/2019
 * Time: 2:33 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Image;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\ShoeSize;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Entity\Size;
use SiteBundle\Entity\User;
use SiteBundle\Repository\BrandRepository;
use SiteBundle\Repository\ImageRepository;
use SiteBundle\Repository\ModelRepository;
use SiteBundle\Repository\ShoeRepository;
use SiteBundle\Repository\ShoeSizeRepository;
use SiteBundle\Repository\ShoeUserRepository;
use SiteBundle\Repository\SizeRepository;
use SiteBundle\Repository\UserRepository;

class SaveService implements SaveServiceInterface
{
    private $userRepository;
    private $sizeRepository;
    private $shoeRepository;
    private $imageRepository;
    private $shoeSizeRepository;
    private $shoeUserRepository;
    private $modelRepository;
    private $brandRepository;

    /**
     * BrandModelService constructor.
     * @param UserRepository $userRepository
     * @param SizeRepository $sizeRepository
     * @param ShoeRepository $shoeRepository
     * @param ShoeSizeRepository $shoeSizeRepository
     * @param ShoeUserRepository $shoeUserRepository
     * @param ImageRepository $imageRepository
     * @param ModelRepository $modelRepository
     * @param BrandRepository $brandRepository
     */
    public function __construct(UserRepository $userRepository, SizeRepository $sizeRepository, ShoeRepository $shoeRepository,
                                ShoeSizeRepository $shoeSizeRepository, ShoeUserRepository $shoeUserRepository,
                                ImageRepository $imageRepository, ModelRepository $modelRepository, BrandRepository $brandRepository)
    {
        $this->userRepository = $userRepository;
        $this->modelRepository = $modelRepository;
        $this->brandRepository = $brandRepository;
        $this->sizeRepository = $sizeRepository;
        $this->shoeRepository = $shoeRepository;
        $this->shoeSizeRepository = $shoeSizeRepository;
        $this->shoeUserRepository = $shoeUserRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUser(User $user)
    {
        $this->userRepository->saveUser($user);
    }

    /**
     * @param Shoe $shoe
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveShoe(Shoe $shoe)
    {
        $this->shoeRepository->saveShoe($shoe);
    }

    /**
     * @param Size $size
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveSize(Size $size)
    {
        $this->sizeRepository->saveSize($size);
    }

    /**
     * @param ShoeSize $shoeSize
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveShoeSize(ShoeSize $shoeSize)
    {
        $this->shoeSizeRepository->saveShoeSize($shoeSize);
    }

    /**
     * @param ShoeUser $shoeUser
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveShoeUser(ShoeUser $shoeUser)
    {
        $this->shoeUserRepository->saveShoeUser($shoeUser);
    }

    /**
     * @param Image $image
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveImage(Image $image)
    {
        $this->imageRepository->saveImage($image);
    }

    public function saveProperty($property, $object)
    {
        $repositoryName = $property . "Repository";
        $this->$repositoryName->save($object);
    }
}