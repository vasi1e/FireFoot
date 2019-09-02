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
use SiteBundle\Entity\User;
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
    private $SUDService;

    /**
     * ShoeService constructor.
     * @param SizeRepository $sizeRepository
     * @param ShoeRepository $shoeRepository
     * @param ShoeSizeRepository $shoeSizeRepository
     * @param ShoeUserRepository $shoeUserRepository
     * @param ImageRepository $imageRepository
     * @param SUDServiceInterface $SUDService
     */
    public function __construct(SizeRepository $sizeRepository, ShoeRepository $shoeRepository,
                                ShoeSizeRepository $shoeSizeRepository, ShoeUserRepository $shoeUserRepository,
                                ImageRepository $imageRepository, SUDServiceInterface $SUDService)
    {
        $this->sizeRepository = $sizeRepository;
        $this->shoeRepository = $shoeRepository;
        $this->shoeSizeRepository = $shoeSizeRepository;
        $this->shoeUserRepository = $shoeUserRepository;
        $this->imageRepository = $imageRepository;
        $this->SUDService = $SUDService;
    }


    public function findShoesByBrandAndModel(Shoe $shoe, $condition)
    {
        if ($condition == "used") return $this->shoeRepository->findBy(['brand' => $shoe->getBrand(), 'model' => $shoe->getModel()]);
        else if ($condition == "new") return $this->shoeRepository->findOneBy(['brand' => $shoe->getBrand(), 'model' => $shoe->getModel(), 'condition' => 'new']);
    }

    public function findShoeById(int $id)
    {
        return $this->shoeRepository->find($id);
    }

    public function findShoeSizeByShoeAndSize(Shoe $shoe, Size $size)
    {
        return $this->shoeSizeRepository->findOneBy(['shoe' => $shoe,
            'size' => $size]);
    }

    public function findShoeUserByShoeAndSize(Shoe $shoe, Size $size)
    {
        return $this->shoeUserRepository->findUserWithLowestPrice($shoe->getId(), $size->getNumber());
    }

    public function findShoeUserByShoeId($id)
    {
        return $this->shoeUserRepository->findByShoeId($id);
    }

    public function isThereThisSizeForThisShoe(ShoeSize $shoeSize)
    {
        $anotherShoeSizeWithSameName = $this->findShoeSizeByShoeAndSize($shoeSize->getShoe(), $shoeSize->getSize());

        if ($anotherShoeSizeWithSameName != null) return true;
        else return false;
    }

    public function findSizeByNumber($number)
    {
        return $this->sizeRepository->findOneBy(['number' => $number]);
    }

    public function isThereSize(Size $size)
    {
        $anotherSizeWithSameNum = $this->findSizeByNumber($size->getNumber());

        if ($anotherSizeWithSameNum != null) return true;
        else return false;
    }

    /**
     * @param $imageFiles
     * @param $directory
     * @param Shoe $shoe
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
            $this->SUDService->saveProperty("image", $image);
            $shoe->addImage($image);
        }
    }

    public function doesThisUserLikeTheShoe(Shoe $shoe, User $user)
    {
        return in_array($shoe->getId(), $user->getLikedShoes());
    }

    public function findTop5MostLiked()
    {
        return $this->shoeRepository->findTop5MostLiked();
    }

    public function findTop5LatestRelease()
    {
        return $this->shoeRepository->findTop5LatestRelease();
    }

    public function listOfAllShoes($filters, $sortMethod, $order = "DESC")
    {
        if (empty($filters["brands"])) $brands = null;
        else $brands = implode(" OR s.brand = ", $filters["brands"]);

        $shoes = $this->shoeRepository->getAllShoes($brands, $sortMethod, $order);
        $rightShoes = [];

        if (empty($filters["sizes"])) $rightShoes = $shoes;
        else {
            /** @var Shoe $shoe */
            foreach ($shoes as $shoe) {
                if (!empty(array_intersect($filters["sizes"], $shoe->getSizes()))) $rightShoes[] = $shoe;
            }
        }

        return $rightShoes;
    }
}