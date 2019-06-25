<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 6/25/2019
 * Time: 2:58 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Repository\BrandRepository;
use SiteBundle\Repository\CartOrderRepository;
use SiteBundle\Repository\ImageRepository;
use SiteBundle\Repository\MessageRepository;
use SiteBundle\Repository\ModelRepository;
use SiteBundle\Repository\ShoeRepository;
use SiteBundle\Repository\ShoeSizeRepository;
use SiteBundle\Repository\ShoeUserRepository;
use SiteBundle\Repository\SizeRepository;
use SiteBundle\Repository\UserRepository;

class SUDService implements SUDServiceInterface
{
    private $userRepository;
    private $sizeRepository;
    private $shoeRepository;
    private $imageRepository;
    private $shoeSizeRepository;
    private $shoeUserRepository;
    private $modelRepository;
    private $brandRepository;
    private $orderRepository;
    private $messageRepository;

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
     * @param CartOrderRepository $orderRepository
     * @param MessageRepository $messageRepository
     */
    public function __construct(UserRepository $userRepository, SizeRepository $sizeRepository, ShoeRepository $shoeRepository,
                                ShoeSizeRepository $shoeSizeRepository, ShoeUserRepository $shoeUserRepository,
                                ImageRepository $imageRepository, ModelRepository $modelRepository, BrandRepository $brandRepository,
                                CartOrderRepository $orderRepository, MessageRepository $messageRepository)
    {
        $this->userRepository = $userRepository;
        $this->modelRepository = $modelRepository;
        $this->brandRepository = $brandRepository;
        $this->sizeRepository = $sizeRepository;
        $this->shoeRepository = $shoeRepository;
        $this->shoeSizeRepository = $shoeSizeRepository;
        $this->shoeUserRepository = $shoeUserRepository;
        $this->imageRepository = $imageRepository;
        $this->orderRepository = $orderRepository;
        $this->messageRepository = $messageRepository;
    }

    public function saveProperty($property, $object)
    {
        $repositoryName = $property . "Repository";
        $this->$repositoryName->save($object);
    }

    public function updateProperty($property, $object)
    {
        $repositoryName = $property . "Repository";
        $this->$repositoryName->update($object);
    }

    public function deleteProperty($property, $object)
    {
        $repositoryName = $property . "Repository";
        $this->$repositoryName->delete($object);
    }
}