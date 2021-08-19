<?php

namespace App\Controller;


use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\User;
use App\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Sortie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DisplaySortieController extends AbstractController
{
    /**
     * @Route("/affichersortie", name="display_sortie")
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
