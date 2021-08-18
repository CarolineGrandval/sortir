<?php

namespace App\Controller;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;

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

        //création tableau de données
        $donneesRecherche = [
            'est_organisateur'=>true,
            'inscrit_a'=>true,
            'pas_inscrit'=>true,
            'passees'=>false,
            'date_debut'=>new DateTime("-1 month"),
            'date_fin'=>new DateTime("+ 25 days"),
            ];

        //création formulaire
        $formSearch = $this->createForm('App\Form\SortieRechercheType',$donneesRecherche);
        $formSearch->handleRequest($request);

        //Récupération de la recherche
        $query= null;
        if($formSearch->isSubmitted() && $formSearch->isValid()){

        }

        //Récupération de la page
        $page = $request->get('page', 1);
        //Requête ramenant toutes les sorties
        $sorties = $entityManager->getRepository('App:Sortie')->getSorties($page, 10);
        //Requête ramenant tous les campus
        $campus = $entityManager->getRepository('App:Campus')->findAll();

        return $this->render('main/home.html.twig', ['sorties' => $sorties, 'campus' => $campus]);

    }
}
