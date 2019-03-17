<?php

namespace SiteBundle\Controller;
use SiteBundle\Entity\Message;
use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\User;
use SiteBundle\Service\MessageServiceInterface;
use SiteBundle\Service\SaveServiceInterface;
use SiteBundle\Service\ShoeServiceInterface;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{
    private $shoeService;
    private $messageService;
    private $saveService;
    private $userService;

    /**
     * MessageController constructor.
     * @param ShoeServiceInterface $shoeService
     * @param MessageServiceInterface $messageService
     * @param SaveServiceInterface $saveService
     * @param UserServiceInterface $userService
     */
    public function __construct(ShoeServiceInterface $shoeService, MessageServiceInterface $messageService,
                                SaveServiceInterface $saveService, UserServiceInterface $userService)
    {
        $this->shoeService = $shoeService;
        $this->messageService = $messageService;
        $this->saveService = $saveService;
        $this->userService = $userService;
    }

    /**
     * @Route("/message/chat/{shoeId}/{userId}", name="message_chat")
     * @param Request $request
     * @param $shoeId
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendMessageAction(Request $request, $shoeId, $userId)
    {
        /** @var Shoe $shoe */
        $shoe = $this->shoeService->findShoeById($shoeId);

        if($shoe->getCondition() == "new") return $this->redirectToRoute("homepage");

        /** @var User $currUser */
        $currUser = $this->getUser();
        /** @var User $recipient */
        $recipient = $this->userService->findUserById($userId);

        $allMessages = $this->messageService->findChatByShoe($shoe, $currUser, $recipient);
        $this->messageService->readMessages($allMessages, $currUser);
        $chat = $this->messageService->makeJSONFromMessages($allMessages, $currUser);

        if (isset($_POST['submit']))
        {
            $message = new Message();
            $text = $request->request->get('messageText');

            if ($allMessages == null) $chatId = md5(uniqid(rand(), true));
            else $chatId = $allMessages[0]->getChatId();

            $message->setText($text)
                     ->setShoe($shoe)
                     ->setSender($currUser)
                     ->setRecipient($recipient)
                     ->setChatId($chatId)
                     ->setRead(false);
            $this->saveService->saveMessage($message);

            $currUser->addSendMessage($message);
            $recipient->addReceivedMessage($message);

            return $this->redirect("/message/chat/" . $shoe->getId() . "/" . $recipient->getId());
        }

        return $this->render('message/chat.html.twig', [
            'chat' => $chat,
            'shoe' => $shoe,
            'recipient' => $recipient,
        ]);
    }

    /**
     * @Route("/messages-refresh", name="message_refresh")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refreshChatAction(Request $request)
    {
        /** @var Shoe $shoe */
        $shoe = $this->shoeService->findShoeById($request->query->get("shoeId"));

        if($shoe->getCondition() == "new") return $this->redirectToRoute("homepage");

        /** @var User $currUser */
        $currUser = $this->getUser();
        /** @var User $recipient */
        $recipient = $this->userService->findUserById($request->query->get("recipientId"));

        $allMessages = $this->messageService->findChatByShoe($shoe, $currUser, $recipient);
        $this->messageService->readMessages($allMessages, $currUser);
        $chat = $this->messageService->makeJSONFromMessages($allMessages, $currUser);

        return new JsonResponse($chat);
    }
}
