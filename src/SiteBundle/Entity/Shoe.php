<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Brand", inversedBy="shoes")
     */
    private $brand;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Model", inversedBy="shoes")
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="shoe_condition", type="string", length=255)
     */
    private $condition;

    /**
     * @var double
     *
     * @ORM\Column(name="condition_out_of_10", type="decimal")
     * @Assert\Range(
     *      min = 0,
     *      max = 10,
     *      maxMessage = "Rate the condition up to 10"
     * )
     */
    private $conditionOutOf10;

    /**
     * @var ArrayCollection|ShoeSize[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\ShoeSize", mappedBy="shoe")
     */
    private $sizes;

    /**
     * @var ShoeUser[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\ShoeUser", mappedBy="shoe")
     */
    private $sellers;

    /**
     * @var Image[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\Image", mappedBy="shoe")
     */
    private $images;

    private $uploadImages;


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
     * @param Brand $brand
     *
     * @return Shoe
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * Get brand
     *
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param Model $model
     *
     * @return Shoe
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get model
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
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
     * @return int
     */
    public function getConditionOutOf10()
    {
        return $this->conditionOutOf10;
    }

    /**
     * @param int $conditionOutOf10
     */
    public function setConditionOutOf10($conditionOutOf10)
    {
        $this->conditionOutOf10 = $conditionOutOf10;
    }

    /**
     * @return array
     */
    public function getSizes()
    {
        $stringSizes = [];

        foreach ($this->sizes as $size)
        {
            $stringSizes[] = $size->getSize()->getNumber();
        }

        return $stringSizes;
    }

    /**
     * @param ShoeSize $size
     * @return Shoe
     */
    public function addSize($size)
    {
        $this->sizes[] = $size;
        return $this;
    }

    /**
     * @return array
     */
    public function getSellers()
    {
        $userIdArray = [];

        foreach ($this->sellers as $seller)
        {
            /** @var ShoeUser $seller*/
            $userIdArray[] = $seller->getSeller()->getId();
        }

        return $userIdArray;
    }

    /**
     * @param ShoeUser $seller
     */
    public function addSeller($seller)
    {
        $this->sellers[] = $seller;
    }

    /**
     * @return array
     */
    public function getImagesId()
    {
        $imageIdArray = [];

        foreach ($this->images as $image)
        {
            /** @var Image $image*/
            $imageIdArray[] = $image->getId();
        }

        return $imageIdArray;
    }

    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Image $image
     */
    public function addImage($image)
    {
        $this->images[] = $image;
    }

    /**
     * @return mixed
     */
    public function getUploadImages()
    {
        return $this->uploadImages;
    }

    /**
     * @param mixed $uploadImages
     */
    public function setUploadImages($uploadImages)
    {
        $this->uploadImages = $uploadImages;
    }
}

