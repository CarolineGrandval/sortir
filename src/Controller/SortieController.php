<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $etat = $entityManager->find(Etat::class, 1); // par défaut, l'état est mis à "créée"

        // Création de l'entité sortie et lieu
        $sortie = new Sortie();
        $sortie->setOrganisateur($user);
        $sortie->setEtat($etat);
//        $lieu = new Lieu();

        // Création du formulaire
        $formSortie = $this->createForm('App\Form\SortieType', $sortie);
//        $formLieu = $this->createForm('App\Form\LieuType', $lieu);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formSortie->handleRequest($request);
//        $formLieu->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($sortie);
//            $entityManager->persist($lieu);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Sortie successfully added !');
//            $this->addFlash('success', 'Lieu successfully added !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('sortie/index.html.twig', [
            'formSortie' => $formSortie->createView(),
//            'formLieu' => $formLieu->createView(),
        ]);
    }
}
