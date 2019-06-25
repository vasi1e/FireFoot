<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 1/23/2019
 * Time: 3:19 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserServiceInterface
{
    public function isTheUserRegistered(User $user);
    public function encodePassword(UserInterface $user);
    public function setRole(User $user, $role);
    public function isAdmin(User $user);
    public function findUserById($id);
}