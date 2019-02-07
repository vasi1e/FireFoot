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
     * @var ShoeSize[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\ShoeSize", mappedBy="size")
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
     * @return array
     */
    public function getShoes()
    {
        $shoeIdArray = [];

        foreach ($this->shoes as $shoe)
        {
            /** @var ShoeSize $shoe */
            $shoeIdArray[] = $shoe->getShoe()->getId();
        }

        return $shoeIdArray;
    }

    /**
     * @param ShoeSize $shoe
     * @return Size
     */
    public function addShoe($shoe)
    {
        $this->shoes[] = $shoe;
        return $this;
    }

    public function __toString()
    {
        return $this->getNumber();
    }
}

