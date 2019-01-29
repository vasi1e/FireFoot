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

interface ShoeServiceInterface
{
    public function addShoeSize(Shoe $shoe, Size $size);
    public function getSizeQuantityForShoe($shoeId, Size $size);
    public function saveShoe(Shoe $shoe);
    public function saveSize(Size $size);
    public function findTheShoe(Shoe $shoe);
    public function addShoeUser(Shoe $shoe, User $user, $price);
}