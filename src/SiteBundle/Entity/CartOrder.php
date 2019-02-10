<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartOrder
 *
 * @ORM\Table(name="cart_orders")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\CartOrderRepository")
 */
class CartOrder
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
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\User", inversedBy="orders")
     */
    private $buyer;

    /**
     * @var ShoeUser
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\ShoeUser", inversedBy="orders")
     */
    private $shoeUser;


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
     * Set address
     *
     * @param string $address
     *
     * @return CartOrder
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * @param User $buyer
     * @return CartOrder
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;
        return $this;
    }

    /**
     * @return ShoeUser
     */
    public function getShoeUser()
    {
        return $this->shoeUser;
    }

    /**
     * @param ShoeUser $shoeUser
     * @return CartOrder
     */
    public function setShoeUser($shoeUser)
    {
        $this->shoeUser = $shoeUser;
        return $this;
    }
}

