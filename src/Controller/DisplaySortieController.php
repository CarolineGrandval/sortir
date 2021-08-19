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
     * @Route("/display/sortie", name="display_sortie")
     */
    public function index(): Response
    {
        //On appelle une sortie en particulier
        $numerosortie=1;
        $sortie=$this->getDoctrine()->getRepository(Sortie::class)->find(1);
        $campus=$this->getDoctrine()->getRepository(Campus::class)->find(1);
        $lieu=$this->getDoctrine()->getRepository(Lieu::class)->find(1);
        $ville=$this->getDoctrine()->getRepository(Ville::class)->find(1);
        $users=$this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('display_sortie/index.html.twig', [
            'sortie' => $sortie,
            'campus' => $campus,
            'lieu' => $lieu,
            'ville' => $ville,
            'users' => $users,
        ]);
    }
}
