<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Size
 *
 * @ORM\Table(name="sizes")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\SizeRepository")
 */
class Size
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
     * @ORM\Column(name="number", type="string", unique=true)
     */
    private $number;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var ArrayCollection|Shoe[]
     *
     * @ORM\ManyToMany(targetEntity="SiteBundle\Entity\User", mappedBy="roles")
     */
    private $shoes;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Size
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return ArrayCollection|Shoe[]
     */
    public function getShoes()
    {
        $shoeIdArray = [];

        foreach ($this->shoes as $shoe)
        {
            /** @var Shoe $shoe */
            $shoeIdArray[] = $shoe->getId();
        }

        return $shoeIdArray;
    }

    /**
     * @param Shoe $shoe
     * @return Size
     */
    public function addShoe($shoe)
    {
        $this->shoes[] = $shoe;
        return $this;
    }
}

