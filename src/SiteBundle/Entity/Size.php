<?php

namespace SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SiteBundle\Repository\ShoeRepository;
use SiteBundle\Repository\SizeRepository;

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
     * @var ArrayCollection|Shoe[]
     *
     * @ORM\ManyToMany(targetEntity="SiteBundle\Entity\Shoe", mappedBy="sizes")
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
     * @param $shoeId
     * @param SizeRepository $sizeRepository
     * @return Size
     */
    public function addOneQuantity($shoeId, SizeRepository $sizeRepository)
    {
        $sizeRepository->saveShoeSize(
            $shoeId,
            $this->getId(),
            $this->getQuantity($shoeId, $this->getId(), $sizeRepository) + 1);
        return $this;
    }

    /**
     * Get quantity
     *
     * @param $shoeId
     * @param $sizeId
     * @param SizeRepository $sizeRepository
     * @return int
     */
    public function getQuantity($shoeId, $sizeId, SizeRepository $sizeRepository)
    {
        return intval($sizeRepository->findShoeInTableShoeSizes($shoeId, $sizeId)['quantity']);
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

