<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 2/7/2019
 * Time: 2:28 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\Image;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\ShoeSize;
use SiteBundle\Entity\ShoeUser;
use SiteBundle\Entity\Size;
use SiteBundle\Entity\User;

interface SaveServiceInterface
{
    public function saveUser(User $user);
    public function saveShoe(Shoe $shoe);
    public function saveSize(Size $size);
    public function saveImage(Image $image);
    public function saveOrder(CartOrder $order);
    public function saveShoeSize(ShoeSize $shoeSize);
    public function saveShoeUser(ShoeUser $shoeUser);
    public function saveProperty($property, $object);
}