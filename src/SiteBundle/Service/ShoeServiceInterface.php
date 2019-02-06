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

interface ShoeServiceInterface
{
    public function saveShoe(Shoe $shoe);
    public function saveSize(Size $size);
    public function saveImage(Image $image);
    public function saveShoeSize(ShoeSize $shoeSize);
    public function saveShoeUser(ShoeUser $shoeUser);
    public function findTheShoe(Shoe $shoe);
    public function isThereThisSizeForThisShoe(ShoeSize $shoeSize);
    public function isThereSize(Size $size);
    public function addingImagesForShoe($imageFiles, $directory, Shoe $shoe);
}