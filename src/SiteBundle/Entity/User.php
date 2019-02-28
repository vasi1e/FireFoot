<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="SiteBundle\Entity\Role")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;

    /**
     * @var ShoeUser[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\ShoeUser", mappedBy="seller")
     */
    private $sellerShoes;

    /**
     * @var CartOrder[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\CartOrder", mappedBy="buyer")
     */
    private $orders;

    /**
     * @var Shoe[]
     *
     * @ManyToMany(targetEntity="Shoe", inversedBy="usersThatLiked")
     * @JoinTable(name="users_shoes_likes")
     */
    private $likedShoes;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->likedShoes = array();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param Role $role
     * @return User
     */
    public function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $stringRoles = [];

        foreach ($this->roles as $role)
        {
            /** @var Role $role */
            $stringRoles[] = $role->getRole();
        }

        return $stringRoles;
    }

    /**
     * @return array
     */
    public function getSellerShoes()
    {
        $shoeIdArray = [];

        foreach ($this->sellerShoes as $sellerShoe)
        {
            /** @var ShoeUser $sellerShoe */
            $shoeIdArray[] = $sellerShoe->getShoe()->getId();
        }

        return $shoeIdArray;
    }

    /**
     *
     * @param ShoeUser $sellerShoe
     * @return User
     */
    public function addSellerShoe($sellerShoe)
    {
        $this->sellerShoes[] = $sellerShoe;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrders()
    {
        $ordersIdArray = [];

        foreach ($this->orders as $order)
        {
            /** @var CartOrder $order */
            $ordersIdArray[] = $order->getId();
        }

        return $ordersIdArray;
    }

    /**
     * @param CartOrder $order
     */
    public function addOrder(CartOrder $order)
    {
        $this->orders[] = $order;
    }

    /**
     * @return array
     */
    public function getLikedShoes()
    {
        $shoeIdArray = [];

        foreach ($this->likedShoes as $shoe)
        {
            /** @var ShoeSize $shoe */
            $shoeIdArray[] = $shoe->getId();
        }

        return $shoeIdArray;
    }

    /**
     * @param Shoe $likedShoe
     */
    public function addLikedShoe($likedShoe)
    {
        $this->likedShoes[] = $likedShoe;
    }

    /**
     * @param Shoe $likedShoe
     * @return $this
     */
    public function removeLikedShoe($likedShoe)
    {
        if(($key = array_search($likedShoe, $this->likedShoes->getSnapshot())) !== false) {
            unset($this->likedShoes[$key]);
        }

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}

