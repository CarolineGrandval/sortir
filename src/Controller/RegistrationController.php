<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/adminregister", name="app_register", methods={"GET", "POST"})
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm('App\Form\RegistrationFormType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On récupère l'image transmise
            $images = $form->get('image')->getData();

            foreach($images as $image){
                // On génère un nouveau nom de fichier unique
                $fichier = md5(uniqid()).'.'.$image->guessExtension();


                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($fichier);
                $user->addImage($img);
                $entityManager->persist($user);
            }

            // encode the plain password
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPlainPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user,
        ]);
    }


//TODO : réunir cette méthode en une seule

    /**
     * @Route(path="/editprofile/{id}", name="edit_profile", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager) {

        // Récupération de l'entité à modifier
        try {
            $user = $entityManager->getRepository('App:User')->find((int) $request->get('id'));
        } catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }

        // Création du formulaire
        $formEdit = $this->createForm('App\Form\RegistrationFormType', $user);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formEdit->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formEdit->isSubmitted() && $formEdit->isValid()) {


            // On récupère l'image transmise
            $images = $formEdit->get('image')->getData();

            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($fichier);

                //Si l'utilisateur a déjà une photo de profil, Vider le tableau pour remplacer l'image
                $tabImages = $user->getImages();
                if (!empty($tabImages)){
                    foreach ($tabImages as $im){
                        $user->removeImage($im);
                    }
                }

                $user->addImage($img);
                $entityManager->persist($user);
            }

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'Votre profil a bien été modifié !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('sortie_home', ['id' => $user->getId()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $formEdit->createView(),
            'user' => $user, //ligne ajoutée pour récupérer la photo du user
        ]);
    }

    /**
     * @Route(path="/afficher/{id}", name="afficher_profil", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function afficher(Request $request, EntityManagerInterface $entityManager){

        //Récupération de l'identifiant de l'organisateur de la sortie
        $user = $entityManager->getRepository('App:User')->find((int) $request->get('id'));

        return $this->render('user/affiche.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/delete/image/{id}", name="user_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getName();

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On supprime le fichier
            if (!empty($nom) && file_exists($this->getParameter('images_directory').'/'.$nom)){
                unlink($this->getParameter('images_directory').'/'.$nom);
            }

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

}
