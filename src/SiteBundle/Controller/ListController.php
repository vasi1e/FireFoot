<?php

namespace SiteBundle\Controller;

use SiteBundle\Entity\Shoe;
use SiteBundle\Entity\User;
use SiteBundle\Service\ShoeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends Controller
{
    private $shoeService;

    /**
     * ListController constructor.
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

    /**
     * @Route("/shoe/used/{id}", name="list_used_shoes")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsedShoes($id)
    {
        $shoe = $this->shoeService->findShoeById($id);
        $shoes = $this->shoeService->findShoesByBrandAndModel($shoe);

        $usedShoes = [];
        /** @var Shoe $s */
        foreach ($shoes as $s)
        {
            if ($s->getCondition() == 'used') $usedShoes[] = $s;
        }

        return $this->render('shoe/listUsedShoes.html.twig', [
            'shoes' => $usedShoes
        ]);
    }
}
