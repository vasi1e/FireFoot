<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Brand
 *
 * @ORM\Table(name="brands")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\BrandRepository")
 */
class Brand
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
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\Shoe", mappedBy="brand")
     */
    private $shoes;

    /**
     * @var Model[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\Model", mappedBy="brand")
     */
    private $models;


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
     * @return Brand
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
     * @return Brand
     */
    public function addShoe($shoe)
    {
        $this->shoes[] = $shoe;
        return $this;
    }

    /**
     * @return ArrayCollection|Model[]
     */
    public function getModels()
    {
        $modelNameArray = [];

        foreach ($this->models as $model)
        {
            /** @var Model $model */
            $modelNameArray[] = $model->getName();
        }

        return $this->models;
    }

    /**
     * @param Model $model
     * @return Brand
     */
    public function addModel($model)
    {
        $this->models[] = $model;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}

