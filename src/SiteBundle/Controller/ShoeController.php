<?php

namespace SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\Shoe;
use SiteBundle\Form\ShoeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShoeController extends Controller
{
    /**
     * @Route("/shoe/create", name="shoe_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $shoe = new Shoe();
        $form = $this->createForm(ShoeType::class, $shoe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

        }

        return $this->render('shoe/create.html.twig');
    }
}
