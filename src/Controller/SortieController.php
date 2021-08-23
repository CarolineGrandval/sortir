<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     * @Route(path="/modifier/{id}", name="modifier", requirements={"id": "\d+"}, methods={"GET", "POST"})
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
     * @Route(path="/publier/{id}", name="publier", requirements={"id": "\d+"}, methods={"GET", "POST"})
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
        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route(path="/motifannulation/{id}", name="motifannulation", requirements={"id": "\d+"}, methods={"GET", "POST"})
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
     * @Route(path="/annuler/{id}", name="annuler", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function annuler(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }

        $etat = $entityManager->find(Etat::class, 6); // état changé à "annulée"
        $sortie->setEtat($etat);
        // Enregistrement de l'entité dans la BDD
        $entityManager->persist($sortie);
        $entityManager->flush();

        // Ajout d'un message de confirmation
        $this->addFlash('success', 'La sortie a bien été annulée');
        // Redirection sur le controlleur
        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route(path="/desister/{id}", name="desister", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function desister(Request $request, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $sortie = $entityManager->getRepository('App:Sortie')->find((int)$request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('La sortie n\'a pas été trouvé !');
        }
        // Enlever le participant de la sortie
        $sortie->removeParticipant($user);
        // Enregistrement de l'entité dans la BDD
        $entityManager->persist($sortie);
        $entityManager->flush();

        // Ajout d'un message de confirmation de désistement
        $this->addFlash('success', 'Vous êtes désinscrit de la sortie !');

        // Redirection sur le controlleur
        return $this->redirectToRoute('main_home');
    }
}
