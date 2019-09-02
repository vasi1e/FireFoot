<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShoeSize
 *
 * @ORM\Table(name="shoes_sizes")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\ShoeSizeRepository")
 */
class ShoeSize
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
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Shoe", inversedBy="sizes", cascade={"persist"})
     */
    private $shoe;

    /**
     * @var Size
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Size", inversedBy="shoes", cascade={"persist"})
     */
    private $size;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;


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
     * @return Shoe
     */
    public function getShoe()
    {
        return $this->shoe;
    }

    /**
     * @param Shoe $shoe
     * @return ShoeSize
     */
    public function setShoe($shoe)
    {
        $this->shoe = $shoe;
        return $this;
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
     * @return ShoeSize
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return ShoeSize
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}

