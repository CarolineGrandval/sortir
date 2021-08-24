<?php

namespace App\Controller;

use App\Entity\Rechercher;
use App\Entity\User;
use App\Form\SortieRechercheType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(path="/", name="main_")
 */
class MainController extends AbstractController
{
    /**
     * @Route(path="{page}", requirements={"page": "\d+"}, defaults={"page": 1}, name="home", methods={"GET","POST"})
     *
     */
    public function list(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, CampusRepository $campusRepository)
    {

        /** @var User $user */
        $user = $this->getUser();

        //Instanciation de l'objet Rechercher Request $request, EventRepository $eventRepository, Security $security, SessionInterface $session
        $search = new Rechercher();

        //si utilisateur connecté
        if (!empty($user)){
            //ajout par défaut du Campus de l'utilisateur
            $search->setCampus($user->getCampus());
            $search->setOrganisateur(true);
            $search->setPasInscrit(true);
            $search->setInscrit(true);
            //Création du formulaire
            $searchForm = $this->createForm('App\Form\SortieRechercheType', $search);
            $searchForm->handleRequest($request);

            //Récupération et initialisation des attributs de Recherche
            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                //Créer le stockage des variables en Session
                $session->set('Rechercher', $search);
            }
            //si changement de page
            else {
                //Savoir si l'objet Recherche existe
                if($session->has('Rechercher')){
                    $search = $session->get('Rechercher');
                    //réassocier l'entité Campus à l'entité Rechercher
                    $campus = $campusRepository->find($search->getCampus()->getId());
                    $search->setCampus($campus);
                    //Renvoit le nouveau formulaire
                    $searchForm = $this->createForm('App\Form\SortieRechercheType', $search);
                }
            }

            //pagination
            $page = $request->get('page', 1);

            //Envoi de la requete
            $sorties = $entityManager->getRepository('App:Sortie')->search($page,10, $search, $user);

            //Requete d'inscription aux sorties en fonction de l'utilisateur connecté
            $inscrits = $entityManager->getRepository('App:Sortie')->inscritSortie($search, $user);

            //Création formulaire avec des données
            return $this->render('main/home.html.twig', ['searchForm' => $searchForm->createView(), 'sorties' => $sorties, 'inscrits' => $inscrits]);
        }

        return  $this->redirectToRoute('app_login');
    }
}
