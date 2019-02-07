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
    private $saveService;

    /**
     * ShoeService constructor.
     * @param SizeRepository $sizeRepository
     * @param ShoeRepository $shoeRepository
     * @param ShoeSizeRepository $shoeSizeRepository
     * @param SaveServiceInterface $saveService
     * @param ShoeUserRepository $shoeUserRepository
     * @param ImageRepository $imageRepository
     */
    public function __construct(SizeRepository $sizeRepository, ShoeRepository $shoeRepository,
                                ShoeSizeRepository $shoeSizeRepository, SaveServiceInterface $saveService,
                                ShoeUserRepository $shoeUserRepository, ImageRepository $imageRepository)
    {
        $this->sizeRepository = $sizeRepository;
        $this->shoeRepository = $shoeRepository;
        $this->shoeSizeRepository = $shoeSizeRepository;
        $this->shoeUserRepository = $shoeUserRepository;
        $this->imageRepository = $imageRepository;
        $this->saveService = $saveService;
    }

    public function findTheShoe(Shoe $shoe)
    {
        return $this->shoeRepository->findOneBy(['brand' => $shoe->getBrand(), 'model' => $shoe->getModel()]);
    }

    public function findShoeById(int $id)
    {
        return $this->shoeRepository->find($id);
    }

    public function isThereThisSizeForThisShoe(ShoeSize $shoeSize)
    {
        $anotherShoeSizeWithSameName = $this->shoeSizeRepository->findOneBy(['shoe' => $shoeSize->getShoe(),
            'size' => $shoeSize->getSize()]);

        if ($anotherShoeSizeWithSameName != null) return $anotherShoeSizeWithSameName;
        else return false;
    }

    public function isThereSize(Size $size)
    {
        $anotherSizeWithSameNum = $this->sizeRepository->findOneBy(['number' => $size->getNumber()]);

        if ($anotherSizeWithSameNum != null) return $anotherSizeWithSameNum;
        else return false;
    }

    /**
     * @param $imageFiles
     * @param $directory
     * @param Shoe $shoe
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function addingImagesForShoe($imageFiles, $directory, Shoe $shoe)
    {
        /** @var UploadedFile $file */
        foreach ($imageFiles as $file)
        {
            $imageName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($directory, $imageName);
            $image = new Image();
            $image->setName($imageName);
            $image->setShoe($shoe);
            $this->saveService->saveImage($image);
            $shoe->addImage($image);
        }
    }
}