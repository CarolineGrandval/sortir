<?php

namespace App\Controller;

use App\Entity\Rechercher;
use App\Entity\User;
use App\Form\SortieRechercheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/", name="main_")
 */
class MainController extends AbstractController
{
    /**
     * @Route(path="{page}", requirements={"page": "\d+"}, defaults={"page": 1}, name="home", methods={"GET","POST"})
     *
     */
    public function list(Request $request, EntityManagerInterface $entityManager){

        /** @var User $user */
        $user = $this->getUser();

        //Instanciation de l'objet Rechercher Request $request, EventRepository $eventRepository, Security $security, SessionInterface $session
        $search = new Rechercher();
        //ajout par défaut du Campus de l'utilisateur
        $search->setCampus($this->getUser()->getCampus());

        //Création du formulaire
        $searchForm = $this->createForm(SortieRechercheType::class, $search);
        $searchForm->handleRequest($request);

        $events = [];
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $events=$searchForm->get('campus','motclef', 'dateDebut')->getData();//récupération
        }
        $page = $request->get('page', 1);

        $sorties = $entityManager->getRepository('App:Rechercher')->search($page, 10, $events, $user);
        //$events = $eventRepository->search($search, $security->getUser());

        return $this->render('main/home.html.twig', [
            'searchForm' => $searchForm->createView(),
            'events' => $sorties
        ]);

//        //création tableau de données
//        $donneesRecherche = [
//            'organisateur'=>true,
//            'inscrit'=>true,
//            'pasInscrit'=>true,
//            'passees'=>false,
//            'dateDebut'=>new DateTime("-1 month"),
//            'dateFin'=>new DateTime("+ 25 days"),
//            ];
//
//        //création formulaire
//        $formSearch = $this->createForm('App\Form\SortieRechercheType',$donneesRecherche);
//        $formSearch->handleRequest($request);
//
//        //Récupération de la recherche
//        $query= null;
//        if($formSearch->isSubmitted() && $formSearch->isValid()){
//
//        }
//
//        //Récupération de la page
//        $page = $request->get('page', 1);
//        //Requête ramenant toutes les sorties
//        $sorties = $entityManager->getRepository('App:Sortie')->getSorties($page, 10);
//        //Requête ramenant tous les campus
//        $campus = $entityManager->getRepository('App:Campus')->findAll();
//
//        return $this->render('main/home.html.twig', ['sorties' => $sorties, 'campus' => $campus]);

    }
}
