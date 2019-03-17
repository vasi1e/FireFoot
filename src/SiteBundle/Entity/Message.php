<?php

namespace SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\User", inversedBy="sendMessages")
     */
    private $sender;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\User", inversedBy="receivedMessages")
     */
    private $recipient;

    /**
     * @var string
     *
     * @ORM\Column(name="chat_id", type="string")
     */
    private $chatId;

    /**
     * @var Shoe
     *
     * @ORM\ManyToOne(targetEntity="SiteBundle\Entity\Shoe", inversedBy="messages")
     */
    private $shoe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendTime", type="datetime")
     */
    private $sendTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $read;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->sendTime = new \DateTime("now");;
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
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sendTime
     *
     * @param \DateTime $sendTime
     *
     * @return Message
     */
    public function setSendTime($sendTime)
    {
        $this->sendTime = $sendTime;
        return $this;
    }

    /**
     * Get sendTime
     *
     * @return string
     */
    public function getSendTime()
    {
        return $this->sendTime->format('Y-m-d H:i:s');
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     * @return Message
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     * @return Message
     */
    public function setRecipient(User $recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @param string $chatId
     * @return Message
     */
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
        return $this;
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
     * @return Message
     */
    public function setShoe(Shoe $shoe)
    {
        $this->shoe = $shoe;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * @param bool $read
     * @return Message
     */
    public function setRead($read)
    {
        $this->read = $read;
        return $this;
    }

}

