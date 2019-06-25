<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 3/4/2019
 * Time: 9:05 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Message;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\User;

interface MessageServiceInterface
{
    public function findChatByShoe(Shoe $shoe, User $user, User $recipient);
    public function getListOfChats($userId);
    public function isTheChatRead($chatId, $userId);
    public function readMessages($allMessages, User $currUser);
    public function makeJSONFromMessages($messages, User $user);
}