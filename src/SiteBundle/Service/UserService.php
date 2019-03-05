<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 1/23/2019
 * Time: 3:47 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Role;
use SiteBundle\Entity\User;
use SiteBundle\Repository\RoleRepository;
use SiteBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService implements UserServiceInterface
{
    private $userRepository;
    private $roleRepository;
    private $passwordEncoder;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function findUserById($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUser(User $user)
    {
        $this->userRepository->updateUser($user);
    }

    public function isTheUserRegistered(User $user)
    {
        $anotherUserWithSameEmail = $this
            ->userRepository
            ->findOneBy(['email' => $user->getEmail()]);

        if ($anotherUserWithSameEmail != null) return true;
        else return false;
    }

    public function encodePassword(UserInterface $user)
    {
        $passwordHash = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($passwordHash);
    }

    /**
     * @param User $user
     * @param $role
     * @throws \Exception
     */
    public function setRole(User $user, $role)
    {
        $roleName = "ROLE_".strtoupper($role);

        /** @var Role $userRole */
        $userRole = $this->roleRepository->findOneBy(['name' => $roleName]);
        if ($userRole === null)
        {
            throw new \Exception("The role is not valid!");
        }

        $user->addRole($userRole);
    }

    public function isAdmin(User $user)
    {
        return in_array("ROLE_ADMIN", $user->getRoles());
    }
}