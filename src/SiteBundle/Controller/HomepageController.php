<?php

namespace SiteBundle\Controller;

use SiteBundle\Entity\User;
use SiteBundle\Service\ShoeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
    private $shoeService;

    /**
     * HomepageController constructor.
     * @param $shoeService
     */
    public function __construct(ShoeServiceInterface $shoeService)
    {
        $this->shoeService = $shoeService;
    }

    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $topLikedShoes = $this->shoeService->findTop5MostLiked();
        $topLatestShoes = $this->shoeService->findTop5LatestRelease();

        return $this->render('homepage/index.html.twig', [
            'likedShoes' => $topLikedShoes,
            'latestShoes' => $topLatestShoes
        ]);
    }
}
