<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model
 *
 * @ORM\Table(name="models")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\ModelRepository")
 */
class Model
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var Shoe[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\Shoe", mappedBy="model")
     */
    private $shoes;

    /**
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Brand", inversedBy="models")
     */
    private $brand;


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
     * Set name
     *
     * @param string $name
     *
     * @return Model
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return Model
     */
    public function addShoe($shoe)
    {
        $this->shoes[] = $shoe;
        return $this;
    }

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function __toString()
    {
        return $this->getName();
    }
}

