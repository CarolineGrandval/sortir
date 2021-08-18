<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            // encode the plain password
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPlainPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/login", name="app_login", methods={"GET", "POST"})
//     */
//    public function login(AuthenticationUtils $authenticationUtils): Response
//    {
//        // Récupération des erreurs de traitement de connexion
//        $error = $authenticationUtils->getLastAuthenticationError();
//
//        // Récupération de l'identifiant de l'utilisateur
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
//    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout() {}

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

//        dump($user);
//        exit();

        // Création du formulaire
        $formEdit = $this->createForm('App\Form\RegistrationFormType', $user);

        // Récupérer les données envoyées par le navigateur et les transmettre au formulaire
        $formEdit->handleRequest($request);

        // Vérifier les données du formulaire
        if ($formEdit->isSubmitted() && $formEdit->isValid()) {

            // Enregistrement de l'entité dans la BDD
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajout d'un message de confirmation
            $this->addFlash('success', 'User successfully updated !');

            // Redirection sur le controlleur
            return $this->redirectToRoute('app_register', ['id' => $user->getId()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $formEdit->createView(),
        ]);
    }
}
