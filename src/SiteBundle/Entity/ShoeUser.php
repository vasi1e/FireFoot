<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShoeUser
 *
 * @ORM\Table(name="shoes_users")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\ShoeUserRepository")
 */
class ShoeUser
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
     * @var Shoe
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Shoe", inversedBy="sellers", cascade={"persist"})
     */
    private $shoe;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\User", inversedBy="sellerShoes", cascade={"persist"})
     */
    private $seller;

    /**
     * @var Size
     *
     * @ORM\Column(name="size_number", type="string")
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var CartOrder[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\CartOrder", mappedBy="shoeUser")
     */
    private $orders;

    /**
     * @var bool
     *
     * @ORM\Column(name="sold", type="boolean")
     */
    private $sold;


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
     * Set shoe
     *
     * @param string $shoe
     *
     * @return ShoeUser
     */
    public function setShoe($shoe)
    {
        $this->shoe = $shoe;

        return $this;
    }

    /**
     * Get shoe
     *
     * @return Shoe
     */
    public function getShoe()
    {
        return $this->shoe;
    }

    /**
     * Set seller
     *
     * @param string $seller
     *
     * @return ShoeUser
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return User
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @return Size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param Size $size
     * @return ShoeUser
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return ShoeUser
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
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
    public function addOrder($order)
    {
        $this->orders[] = $order;
    }

    public function setOrdersToEmpty()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isSold()
    {
        return $this->sold;
    }

    /**
     * @param bool $sold
     * @return ShoeUser
     */
    public function setSold($sold)
    {
        $this->sold = $sold;
        return $this;
    }
}

