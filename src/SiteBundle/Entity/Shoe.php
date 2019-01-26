<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Shoe
 *
 * @ORM\Table(name="shoes")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\ShoeRepository")
 */
class Shoe
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
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Brand", inversedBy="shoes")
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Model", inversedBy="shoes")
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="condition", type="string", length=255)
     */
    private $condition;

    /**
     * @var ArrayCollection|Size[]
     *
     * @ORM\ManyToMany(targetEntity="SiteBundle\Entity\Size")
     * @ORM\JoinTable(name="shoes_sizes",
     *     joinColumns={@ORM\JoinColumn(name="shoe_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="size_id", referencedColumnName="id")})
     */
    private $sizes;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\User", inversedBy="sellerShoes")
     */
    private $seller;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\User", inversedBy="buyerShoes")
     */
    private $buyer;


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
     * Set brand
     *
     * @param string $brand
     *
     * @return Shoe
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Shoe
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Shoe
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
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return ArrayCollection|Size[]
     */
    public function getSizes()
    {
        $stringSizes = [];

        foreach ($this->sizes as $size)
        {
            /** @var Size $size */
            $stringSizes[] = $size->getNumber();
        }

        return $stringSizes;
    }

    /**
     * @param Size $size
     * @return Shoe
     */
    public function addSize($size)
    {
        $this->sizes[] = $size;
        return $this;
    }

    /**
     * @return User
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @param User $seller
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;
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
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;
    }

}

