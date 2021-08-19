<?php

namespace App\Controller;

use App\Entity\Rechercher;
use App\Entity\User;
use App\Form\SortieRechercheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function list(Request $request, EntityManagerInterface $entityManager)
    {

        /** @var User $user */
        $user = $this->getUser();

        //Instanciation de l'objet Rechercher Request $request, EventRepository $eventRepository, Security $security, SessionInterface $session
        $search = new Rechercher();

        //ajout par défaut du Campus de l'utilisateur
        if (!empty($user)){
            $search->setCampus($user->getCampus());

        $search->setOrganisateur(true);
        $search->setPasInscrit(true);
        $search->setInscrit(true);

        //Création du formulaire
        $searchForm = $this->createForm(SortieRechercheType::class, $search);
        $searchForm->handleRequest($request);

        //Récupération et initialisation des attributs de Recherche
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $search->setCampus($searchForm->get('campus')->getData());
            $search->setMotclef($searchForm->get('motclef')->getData());
            $search->setDateDebut($searchForm->get('dateDebut')->getData());
            $search->setDateFin($searchForm->get('dateFin')->getData());
            $search->setOrganisateur($searchForm->get('organisateur')->getData());
            $search->setInscrit($searchForm->get('inscrit')->getData());
            $search->setPasInscrit($searchForm->get('pasInscrit')->getData());
            $search->setPassees($searchForm->get('passees')->getData());
        }
        //pagination
        $page = $request->get('page', 1);

        //Envoi de la requete
        $sorties = $entityManager->getRepository('App:Sortie')->search($page,10, $search, $user);

        //Création formulaire avec des données
        return $this->render('main/home.html.twig', ['searchForm' => $searchForm->createView(), 'sorties' => $sorties]);
        }

        return  $this->redirectToRoute('app_login');
    }
}
