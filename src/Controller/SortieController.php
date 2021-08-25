<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Rechercher;
use App\Entity\Sortie;
use App\Entity\User;
use App\Repository\CampusRepository;
use App\Service\EtatEnum;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("sortie/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $etat = $entityManager->find(Etat::class, 1); // par défaut, l'état est mis à "créée"

        // Création de l'entité sortie et lieu
        $sortie = new Sortie();
        $sortie->setOrganisateur($user);
        $sortie->setEtat($etat);
        $sortie->setCampus($this->getUser()->getCampus());
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
            $this->addFlash('success', 'La sortie a bien été créée !');
//            $this->addFlash('success', 'Lieu successfully added !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('sortie/create.html.twig', [
            'formSortie' => $formSortie->createView(),
//            'formLieu' => $formLieu->createView(),
        ]);
    }
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
            return $this->render('sortie/home.html.twig', ['searchForm' => $searchForm->createView(), 'sorties' => $sorties, 'inscrits' => $inscrits]);
        }

        return  $this->redirectToRoute('app_login');
    }

    /**
     * @Route("sortie/affichersortie", name="afficher")
     */
    function afficher_Sortie(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }

        return $this->render('sortie/afficher.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route(path="sortie/modifier/{id}", name="modifier", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function modifier(Request $request, EntityManagerInterface $entityManager)
    {

        // Récupération de l'entité à modifier
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));

            $dateDebut = (\DateTimeImmutable::createFromMutable($sortie->getDateHeureDebut()));
            $dateInscr = (\DateTimeImmutable::createFromMutable($sortie->getDateLimiteInscription()));

            $sortie->setDateHeureDebut($dateDebut);
            $sortie->setDateLimiteInscription($dateInscr);

        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('Sortie Not Found !');
        }

        // Création du formulaire
        $formSortie = $this->createForm('App\Form\SortieType', $sortie);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formSortie->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($sortie);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'La sortie a bien été modifiée !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('sortie_modifier', ['id' => $sortie->getId()]);
        }

        return $this->render('sortie/create.html.twig', [
            'formSortie' => $formSortie->createView(),
            'id' => $request->get('id')
        ]);
    }

    /**
     * @Route(path="sortie/publier/{id}", name="publier", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function publier(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }

        $etat = $entityManager->find(Etat::class, 2); // état changé à "ouverte"
        $sortie->setEtat($etat);
        // Enregistrement de l'entité dans la BDD
        $entityManager->persist($sortie);
        $entityManager->flush();

        // Ajout d'un message de confirmation
        $this->addFlash('success', 'La sortie a bien été publiée !');
        // Redirection sur le controlleur
        return $this->redirectToRoute('sortie_home');
    }

    /**
     * @Route(path="sortie/motifannulation/{id}", name="motifannulation", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function motifannulation(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
            //on caste les dates pour éviter des erreurs dans l'affichage du formulaire.
            $dateDebut = (\DateTimeImmutable::createFromMutable($sortie->getDateHeureDebut()));
            $dateInscr = (\DateTimeImmutable::createFromMutable($sortie->getDateLimiteInscription()));
            $sortie->setDateHeureDebut($dateDebut);
            $sortie->setDateLimiteInscription($dateInscr);
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }

        // Création du formulaire
        $formSortie = $this->createForm('App\Form\SortieType', $sortie);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formSortie->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($sortie);
            $entityManager->flush();

            // Redirection sur le controlleur
            return $this->redirectToRoute('sortie_annuler', ['id' => $sortie->getId()]);
        }

        return $this->render('sortie/annuler.html.twig', [
            'formSortie' => $formSortie->createView(),
            'id' => $request->get('id'),
            'sortie' => $sortie,
        ]);

    }

    /**
     * @Route(path="sortie/annuler/{id}", name="annuler", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function annuler(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }

        //On vérifie le rôle de l'utilisateur : doit être organisateur ou admin pour supprimer la sortie.
        //Seules les sorties en création ou ouvertes peuvent être annulées.
        /** @var User $user */
        $user = $this->getUser();
        if ($user == $sortie->getOrganisateur() || $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN') &&
            $sortie->getEtat()->getId() == 1 or $sortie->getEtat()->getId() == 2 or $sortie->getEtat()->getId() == 3) {
            $etat = $entityManager->find(Etat::class, 6); // état changé à "annulée"
            $sortie->setEtat($etat);
            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($sortie);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'La sortie a bien été annulée');
        }

        // Redirection sur le controlleur
        return $this->redirectToRoute('sortie_home');
    }

//    /**
//     * @Route(path="/desister/{id}", name="desister", requirements={"id": "\d+"}, methods={"GET"})
//      Cette méthode fonctionne sans Ajax
//     */
//    public function desister(Request $request, EntityManagerInterface $entityManager)
//    {
//        /** @var User $user */
//        $user = $this->getUser();
//
//        try {
//            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
//        } catch (NonUniqueResultException | NoResultException $e) {
//            throw $this->createNotFoundException('La sortie n\'a pas été trouvé !');
//        }
//        // Enlever le participant de la sortie
//        $sortie->removeParticipant($user);
//        // Enregistrement de l'entité dans la BDD
//        $entityManager->persist($sortie);
//        $entityManager->flush();
//
//        // Ajout d'un message de confirmation de désistement
//        $this->addFlash('success', 'Vous êtes désinscrit de la sortie !');
//
//        // Redirection sur le controlleur
//        return $this->redirectToRoute('sortie_home');
//    }

    /**
     * @Route(path="sortie/ajouter/{id}", name="add_sortie", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    function ajouter_Utilisateur(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));

        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        }

        if ($sortie->getParticipants()->count() < $sortie->getNbParticipantsMax()) { // on vérifie qu'il reste des places disponibles.
            $sortie->addParticipant($user);
        }

        // Enregistrement de l'entité dans la BDD
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie_afficher', ['id' => $sortie->getId()]);
    }

    /**
     * @Route(path="sortie/afficherLesUtilisateurs", name="afficher_utilisateurs", methods={"GET", "POST"})
     */
    function tousLesUtilisateurs(Request $request, EntityManagerInterface $entityManager)
    {
        $users = $entityManager->getRepository("App:User");
        $users->getUsers();

        return $this->redirectToRoute('sortie_afficher');
    }
//    /**
//     * @Route(path="/sinscrire/{id}", name="sinscrire", requirements={"id": "\d+"}, methods={"GET"})
//      Cette méthode fonctionne sans Ajax
//     */
//    public function sinscrire(Request $request, EntityManagerInterface $entityManager)
//    {
//        /** @var User $user */
//        $user = $this->getUser();
//
//        try {
//            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
//        } catch (NonUniqueResultException | NoResultException $e) {
//            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
//        }
//
//        //Si le participant n'est pas inscrit, on l'inscrit. S'il est déjà inscrit, on le désinscrit.
//        $sortie->addParticipant($user);
//        $sortie->removeParticipant($user);
//        $entityManager->persist($sortie);
//        $entityManager->flush();
//        $this->addFlash('success', 'Vous êtes bien inscrit à la sortie !');
//
//        // Redirection sur le controlleur
//        return $this->redirectToRoute('sortie_home');
//    }

//Cette méthode gère l'inscription et la désinscription en Ajax.
    /**
     * @Route(path="sortie/inscriptions/{id}", name="inscriptions", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function inscriptions(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvée !');
        }

        //Si le participant n'est pas inscrit, on l'inscrit. S'il est déjà inscrit, on le désinscrit.
        if ($sortie->getEtat()->getId() == EtatEnum::ETAT_OUVERT or $sortie->getEtat()->getId() == EtatEnum::ETAT_FERME) {
            if ($sortie->isInscrit($user)) {
                $sortie->removeParticipant($user);
                $entityManager->persist($sortie);
            } else {
                // on vérifie qu'il reste des places disponibles et que la date limite d'inscription n'est pas passée.
                $date = getdate();
                if ($sortie->getParticipants()->count() < $sortie->getNbParticipantsMax() && $sortie->getDateLimiteInscription() > $date) {
                    $sortie->addParticipant($user);
                    $entityManager->persist($sortie);
                }
            }

        }
        $entityManager->flush();

        $choix = null;
        if ($sortie->isInscrit($user)) {
            $choix = 'Se désinscrire';
            $inscrit = 'X';
        } else {
            $choix = 'S\'inscrire';
            $inscrit = ' ';
        }

        return $this->json([
            'inscrit' => $inscrit,
            'choix' => $choix,
            'nbInscrits' => $sortie->getParticipants()->count(),
            'nbPlaces' => $sortie->getNbParticipantsMax()
        ], 200);

    }
}
