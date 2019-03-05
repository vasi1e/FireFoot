<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 3/4/2019
 * Time: 9:06 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Message;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\User;
use SiteBundle\Repository\MessageRepository;

class MessageService implements MessageServiceInterface
{
    private $messageRepository;

    /**
     * MessageService constructor.
     * @param $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function findChatByShoe(Shoe $shoe, User $sender, User $recipient)
    {
        return  $this->messageRepository->getChat($shoe->getId(), $sender->getId(), $recipient->getId());
    }

    public function makeJSONFromMessages($messages, User $user)
    {
        $responseArray = array();
        /** @var Message $message */
        foreach($messages as $message){
            $sender = "";
            if($message->getSender()->getId() == $user->getId()) $sender = "Me";
            else $sender = $message->getSender()->getFullName();

            $responseArray[] = array(
                "id" => $message->getId(),
                "text" => $message->getText(),
                "shoe" => $message->getShoe(),
                "sender" => $sender,
                //"time" => $message->getSendTime(),
            );
        }

        return $responseArray;
    }
}