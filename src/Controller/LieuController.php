<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
//    Cette méthode a été déplacée dans Sortie Controller.
    /**
     * @Route("/lieu", name="lieu")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        // Création de l'entité à créer
        $lieu = new Lieu();

        // Création du formulaire
        $formLieu = $this->createForm('App\Form\LieuType', $lieu);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formLieu->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formLieu->isSubmitted() && $formLieu->isValid()) {

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($lieu);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Lieu successfully added !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('lieu');
        }

        return $this->render('lieu/index.html.twig', [
            'formLieu' => $formLieu->createView(),
        ]);
    }
}
