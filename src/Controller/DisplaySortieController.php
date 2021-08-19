<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Sortie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DisplaySortieController extends AbstractController
{
    /**
     * @Route("/display/sortie", name="display_sortie")
     */
    public function index(): Response
    {
        //On appelle une sortie en particulier

        $sortie=$this->getDoctrine()->getRepository(Sortie::class)->find(1);

        return $this->render('display_sortie/index.html.twig', [
            'sortie' => $sortie,
        ]);
    }
}
