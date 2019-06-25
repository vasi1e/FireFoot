<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 2/12/2019
 * Time: 11:57 AM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\CartOrder;

interface CartOrderServiceInterface
{
    public function findOrderById($id);
    public function isTheOrderAlreadyMadeBySomeoneElse(CartOrder $order);
    public function getListOfUnpaidOrders($orders);
}