<?php

namespace SiteBundle\Controller;

use SiteBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $user = $this->getUser();
        var_dump($user->getLikedShoes());
        exit();
        return $this->render('homepage/index.html.twig', [
            'user' => $user
        ]);
    }
}
