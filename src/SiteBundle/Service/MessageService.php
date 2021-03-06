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
    private $SUDService;

    /**
     * MessageService constructor.
     * @param MessageRepository $messageRepository
     * @param SUDServiceInterface $SUDService
     */
    public function __construct(MessageRepository $messageRepository, SUDServiceInterface $SUDService)
    {
        $this->messageRepository = $messageRepository;
        $this->SUDService = $SUDService;
    }

    public function findChatByShoe(Shoe $shoe, User $sender, User $recipient)
    {
        return  $this->messageRepository->getChat($shoe->getId(), $sender->getId(), $recipient->getId());
    }

    public function getListOfChats($userId)
    {
        return $this->messageRepository->getListOfChats($userId);
    }

    public function isTheChatRead($chatId, $userId)
    {
        return $this->messageRepository->isTheChatRead($chatId, $userId);
    }

    /**
     * @param $allMessages
     * @param User $currUser
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function readMessages($allMessages, User $currUser)
    {
        foreach ($allMessages as $m)
        {
            /** @var Message $m */
            if ($currUser === $m->getRecipient())
            {
                $m->setRead(true);
                $this->SUDService->updateProperty("message", $m);
            }
        }
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
                "text" => $message->getText(),
                "sender" => $sender,
                "time" => $message->getSendTime(),
            );
        }

        return $responseArray;
    }
}