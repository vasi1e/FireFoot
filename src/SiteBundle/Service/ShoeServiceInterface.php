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
use SiteBundle\Entity\User;

interface ShoeServiceInterface
{
    public function findShoesByBrandAndModel(Shoe $shoe, $condition);
    public function findShoeById(int $id);
    public function findShoeSizeByShoeAndSize(Shoe $shoe, Size $size);
    public function findShoeUserByShoeAndSize(Shoe $shoe, Size $size);
    public function findShoeUserByShoeId($id);
    public function findSizeByNumber($number);
    public function findTop5MostLiked();
    public function findTop5LatestRelease();
    public function listOfAllShoes($filters, $sortMethod, $order = "DESC");
    public function isThereThisSizeForThisShoe(ShoeSize $shoeSize);
    public function isThereSize(Size $size);
    public function addingImagesForShoe($imageFiles, $directory, Shoe $shoe);
    public function doesThisUserLikeTheShoe(Shoe $shoe, User $user);
}