<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route(path="/delete/{id}", name="delete", requirements={"id": "\d+"}, methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager){
        try{
            $user = $entityManager->getRepository('App:User')->find((int)$request->get('id'));
            $entityManager->remove($user);
            $entityManager->flush();
        }catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }

        $this->addFlash('success', 'L\'utilisateur a été supprimé');

        // Redirection sur le controlleur
        return $this->redirectToRoute('user_index');
    }
    /**
     * @Route("/index", name="index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users=$entityManager->getRepository("App:User")->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users'=>$users,
        ]);
    }

}
