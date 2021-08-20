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
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(VilleRepository $repository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // Création de l'entité à créer et du formulaire
        $ville = new Ville();
        $formVille = $this->createForm('App\Form\VilleType', $ville);
        $formVille->handleRequest($request);

        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash('success', 'Ville ajoutée !');
            return $this->redirectToRoute('ville_create');
        }

        //Récupération de toutes les villes enregistrées en base pour affichage - avec pagination
        $pagination = $paginator->paginate(
            $queryBuilder = $entityManager->getRepository('App:Ville')->findAllWithPagination(),
            $request->query->getInt('page', 1), 6
        );


//        $villes = $entityManager->getRepository('App:Ville')->findAllWithPagination();

        //Envoi à la vue
        return $this->render('ville/create.html.twig', [
            'formVille' => $formVille->createView(), 'pagination' => $pagination,
        ]);
    }

}
