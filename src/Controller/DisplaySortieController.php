<?php

namespace App\Controller;


use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Sortie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplaySortieController extends AbstractController
{
    /**
     * @Route("/affichersortie", name="display_sortie")
     */
    function afficher_Sortie(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw createNotFoundException('Sortie non trouvée !');
        }

//        return $this->render('display_sortie/index.html.twig', [
//            'sortie' => $sortie,
//        ]);
        return $this->render('sortie/afficher.html.twig', [
            'sortie' => $sortie,
        ]);
    }

}
