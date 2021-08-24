<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     */
    public function delete(Request $request, EntityManagerInterface $entityManager){
        try{
            $user = $entityManager->getRepository('App:User')->find((int)$request->get('id'));
            $em=$this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }catch (NonUniqueResultException | NoResultException $e) {
            throw $this->createNotFoundException('User Not Found !');
        }
        // Redirection sur le controlleur
        return $this->redirectToRoute('UserController');
    }
    /**
     * @Route("/index", name="index")
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
