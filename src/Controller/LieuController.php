<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/lieu", name="lieu_")
 */
class LieuController extends AbstractController
{
//    Cette méthode a été déplacée dans Sortie Controller.
    /**
     * @Route("/create", name="create")
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
            $this->addFlash('success', 'Nouveau lieu ajouté !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('lieu/create.html.twig', [
            'formLieu' => $formLieu->createView(),
        ]);
    }

    /**
     * @Route("/afficherInfos/{id}", name="afficher_infos", methods={"GET"})
     * Cette méthode est appelée en Ajax lors de la sélection du lieu dans la page "Créer une sortie", pour afficher les infos du lieu.
     */
    public function afficherInfos(Request $request, EntityManagerInterface $entityManager)
    {
        $serializer = $this->container->get('serializer');

        //On récupère le lieu par son id. (récupéré du select)
        $lieu = $entityManager->getRepository(Lieu::class)->find((int)$request->get('id'));

        return $this->json([
            'nom' => $lieu->getNomLieu(),
            'rue' => $lieu->getRue(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude(),
        ], 200); ;
    }

    /**
     * @Route("/afficher/{id}", name="afficher_ville", methods={"GET"})
     * méthode test pour AJAX - en cours : afficher la liste des lieux liés à une ville
     */
    public function afficher(Request $request, EntityManagerInterface $entityManager)
    {
        $serializer = $this->container->get('serializer');

        $ville = $entityManager->getRepository(Ville::class)->find((int)$request->get('id'));
        $lieux = $entityManager->getRepository(Lieu::class)->findLieuxByIdVille($ville);
        $lieuxJson = $serializer->serialize($lieux, 'json', ['groups' => ['lieux_list']]);

        return new JsonResponse($lieuxJson,200, [], true) ;
    }

}
