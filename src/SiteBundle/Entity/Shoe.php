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
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Brand", inversedBy="shoes", cascade={"persist"})
     */
    private $brand;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Model", inversedBy="shoes", cascade={"persist"})
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
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\Message", mappedBy="shoe")
     */
    private $messages;

    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer", nullable=true)
     */
    private $likes;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups", cascade={"persist"})
     */
    private $usersThatLiked;

    /**
     * @var Image[]
     *
     * @ORM\OneToMany(targetEntity="SiteBundle\Entity\Image", mappedBy="shoe")
     */
    private $images;

    private $uploadImages;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upload_date_and_time", type="datetime")
     */
    private $uploadDateAndTime;

    /**
     * Shoe constructor.
     */
    public function __construct()
    {
        $this->uploadDateAndTime = new \DateTime("now");
        $this->likes = 0;
    }


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
     * @return Shoe
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
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
     * @return Shoe
     */
    public function setConditionOutOf10($conditionOutOf10)
    {
        $this->conditionOutOf10 = $conditionOutOf10;
        return $this;
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
     * @return ShoeUser|ShoeUser[]
     */
    public function getSellers()
    {
        if (count($this->sellers) == 1) return $this->sellers[0];
        else return $this->sellers;
    }

    /**
     * @param ShoeUser $seller
     */
    public function addSeller($seller)
    {
        $this->sellers[] = $seller;
    }

    /**
     * @return Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return $this
     */
    public function addLike()
    {
        $this->likes += 1;
        return $this;
    }

    /**
     * @return $this
     */
    public function removeLike()
    {
        $this->likes -= 1;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getUsersThatLiked()
    {
        $userIdArray = [];

        foreach ($this->usersThatLiked as $user)
        {
            /** @var User $user */
            $shoeIdArray[] = $user->getId();
        }

        return $userIdArray;
    }

    /**
     * @param User $userThatLiked
     */
    public function addUserThatLiked(User $userThatLiked)
    {
        $this->usersThatLiked[] = $userThatLiked;
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
     * @return Shoe
     */
    public function setUploadImages($uploadImages)
    {
        $this->uploadImages = $uploadImages;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUploadDateAndTime()
    {
        return $this->uploadDateAndTime;
    }

    /**
     * @param \DateTime $uploadDateAndTime
     */
    public function setUploadDateAndTime($uploadDateAndTime)
    {
        $this->uploadDateAndTime = $uploadDateAndTime;
    }
}

