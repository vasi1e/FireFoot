<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 1/27/2019
 * Time: 3:04 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Image;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\ShoeSize;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Entity\Size;
use SiteBundle\Repository\ImageRepository;
use SiteBundle\Repository\ShoeRepository;
use SiteBundle\Repository\ShoeSizeRepository;
use SiteBundle\Repository\ShoeUserRepository;
use SiteBundle\Repository\SizeRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ShoeService implements ShoeServiceInterface
{
    private $sizeRepository;
    private $shoeRepository;
    private $imageRepository;
    private $shoeSizeRepository;
    private $shoeUserRepository;

    /**
     * ShoeService constructor.
     * @param SizeRepository $sizeRepository
     * @param ShoeRepository $shoeRepository
     * @param ShoeSizeRepository $shoeSizeRepository
     * @param ShoeUserRepository $shoeUserRepository
     * @param ImageRepository $imageRepository
     */
    public function __construct(SizeRepository $sizeRepository, ShoeRepository $shoeRepository,
                                ShoeSizeRepository $shoeSizeRepository,
                                ShoeUserRepository $shoeUserRepository, ImageRepository $imageRepository)
    {
        $this->sizeRepository = $sizeRepository;
        $this->shoeRepository = $shoeRepository;
        $this->shoeSizeRepository = $shoeSizeRepository;
        $this->shoeUserRepository = $shoeUserRepository;
        $this->imageRepository = $imageRepository;
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

    public function findTheShoe(Shoe $shoe)
    {
        return $this->shoeRepository->findOneBy(['brand' => $shoe->getBrand(), 'model' => $shoe->getModel()]);
    }

    public function isThereThisSizeForThisShoe(ShoeSize $shoeSize)
    {
        $anotherShoeSizeWithSameName = $this->shoeSizeRepository->findOneBy(['shoe' => $shoeSize->getShoe(),
            'size' => $shoeSize->getSize()]);

        if ($anotherShoeSizeWithSameName != null) return $anotherShoeSizeWithSameName;
        else return false;
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

    public function saveImage(Image $image)
    {
        $this->imageRepository->saveImage($image);
    }

    public function isThereSize(Size $size)
    {
        $anotherSizeWithSameNum = $this->sizeRepository->findOneBy(['number' => $size->getNumber()]);

        if ($anotherSizeWithSameNum != null) return $anotherSizeWithSameNum;
        else return false;
    }

    public function addingImagesForShoe($imageFiles, $directory, Shoe $shoe)
    {
        /** @var UploadedFile $file */
        foreach ($imageFiles as $file)
        {
            $imageName = md5(uniqid()) . '.' . $file->guessExtension();
            $image = new Image();
            $file->move($directory, $imageName);
            $image->setName($imageName);
            $image->setShoe($shoe);
            $this->saveImage($image);
            $shoe->addImage($image);
        }
    }
}