<?php

namespace SiteBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Shoe", inversedBy="sellers")
     */
    private $shoe;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\User", inversedBy="sellerShoes")
     */
    private $seller;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;


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
}

