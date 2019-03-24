<?php

namespace SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SiteBundle\Entity\CartOrder;
use SiteBundle\Entity\Message;
use SiteBundle\Entity\User;
use SiteBundle\Form\UserType;
use SiteBundle\Service\MessageServiceInterface;
use SiteBundle\Service\SaveServiceInterface;
use SiteBundle\Service\ServiceForThingsIDontKnowWhereToPut;
use SiteBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    private $userService;
    private $saveService;
    private $someService;
    private $messageService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userService
     * @param SaveServiceInterface $saveService
     * @param ServiceForThingsIDontKnowWhereToPut $someService
     * @param MessageServiceInterface $messageService
     */
    public function __construct(UserServiceInterface $userService, SaveServiceInterface $saveService,
                                ServiceForThingsIDontKnowWhereToPut $someService, MessageServiceInterface $messageService)
    {
        $this->userService = $userService;
        $this->saveService = $saveService;
        $this->someService = $someService;
        $this->messageService = $messageService;
    }

    /**
     * @Route("/user/login", name="security_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/user/logout", name="security_logout")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutAction()
    {
        return $this->redirect("/");
    }

    /**
     * @Route("/user/register", name="security_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($this->userService->isTheUserRegistered($user))
            {
                throw new \Exception("This email is already registered!");
            }

            $this->userService->encodePassword($user);
            $this->userService->setRole($user, "user");
            $this->saveService->saveUser($user);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/profile/{id}", name="profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction($id)
    {
        /** @var User $user */
        $user = $this->userService->findUserById($id);
        $shoes = $user->getSellerShoes();

        $counterForUnreadMessages = 0;
        $chatIds = [];
        /** @var Message $message */
        foreach ($user->getReceivedMessages() as $message)
        {
            $chatId = $message->getChatId();

            if(!in_array($chatId, $chatIds))
            {
                $chatIds[] = $chatId;

                if (!($this->messageService->isTheChatRead($chatId, $user->getId())[0]['read'])) $counterForUnreadMessages++;
            }
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'shoes' => $shoes,
            'count' => $counterForUnreadMessages,
        ]);
    }
}
