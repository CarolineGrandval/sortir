<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ville", name="ville_")
 */
class VilleController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     * @Route("/afficher", name="afficher", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(VilleRepository $repository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        if($request->attributes->get('_route') == 'ville_create'){

            // Création de l'entité à créer et du formulaire
            $ville = new Ville();
            $formVille = $this->createForm('App\Form\VilleType', $ville);
            $formVille->handleRequest($request);

            if ($formVille->isSubmitted() && $formVille->isValid()) {
                //Recherche de la présence dans la BDD
                $villeBDD =$entityManager->getRepository('App:Ville')->findByNom($ville->getNom());

                //si une ville existe déjà
                if(!empty($villeBDD)){
                    // Ajout d'un message de refus
                    $this->addFlash('danger', 'La ville existe déjà !');
                    // Redirection sur le controlleur
                    return $this->redirectToRoute('ville_create');
                }
                //Place dans la file d'attente et enregistre dans la BDD
                $entityManager->persist($ville);
                $entityManager->flush();
                //Message
                $this->addFlash('success', 'Ville ajoutée !');
                //Retour sur la page
                return $this->redirectToRoute('ville_create');
            }
            //Envoie à la vue
            return $this->render('ville/create.html.twig', [
                'formVille' => $formVille->createView() //, 'pagination' => $pagination,
            ]);
        }

        if($request->attributes->get('_route') == 'ville_afficher'){
            //Récupération de toutes les villes enregistrées en base pour affichage - avec pagination
            $pagination = $paginator->paginate(
                $entityManager->getRepository('App:Ville')->findAllWithPagination(),
                $request->query->getInt('page', 1), 6
            );

            //Envoi à la vue
            return $this->render('ville/afficher.html.twig', [
                'pagination' => $pagination,
            ]);
        }


    }


}
