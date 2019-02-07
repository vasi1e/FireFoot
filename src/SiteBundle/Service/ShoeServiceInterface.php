<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 1/27/2019
 * Time: 3:04 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\ShoeSize;
use SiteBundle\Entity\Size;

interface ShoeServiceInterface
{
    public function findTheShoe(Shoe $shoe);
    public function findShoeById(int $id);
    public function isThereThisSizeForThisShoe(ShoeSize $shoeSize);
    public function isThereSize(Size $size);
    public function addingImagesForShoe($imageFiles, $directory, Shoe $shoe);
}