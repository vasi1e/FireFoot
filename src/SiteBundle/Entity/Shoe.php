<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SiteBundle\Repository\ShoeRepository;

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
     * @ORM\ManyToMany(targetEntity="SiteBundle\Entity\User")
     * @ORM\JoinTable(name="shoes_users",
     *     joinColumns={@ORM\JoinColumn(name="shoe_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $sellers;


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
     * Set price
     *
     * @param string $price
     *
     * @param $userId
     * @param ShoeRepository $shoeRepository
     * @return Shoe
     */
    public function setPrice($price, $userId, ShoeRepository $shoeRepository)
    {
        $shoeRepository->saveShoeUser($this->getId(), $userId, $price);
        return $this;
    }

    /**
     * Get price
     *
     * @param $userId
     * @param ShoeRepository $shoeRepository
     *
     * @return float
     */
    public function getPrice($userId, ShoeRepository $shoeRepository)
    {
        return doubleval($shoeRepository->findShoeInTableShoesUsers($this->getId(), $userId)['price']);
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
     * @return array
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
     * @return array
     */
    public function getSellers()
    {
        $userIdArray = [];

        foreach ($this->sellers as $seller)
        {
            /** @var User $seller*/
            $userIdArray[] = $seller->getId();
        }

        return $userIdArray;
    }

    /**
     * @param User $seller
     */
    public function addSeller($seller)
    {
        $this->sellers[] = $seller;
    }

}

