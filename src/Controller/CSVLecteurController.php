<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CSVLecteurController extends AbstractController
{
    /**
     * @Route("/csvimport", name="csv_import")
     */
    public function inserer(Request $request){
        $formFichier = $this->createForm('App\Form\AjoutFichierType');
        $formFichier->handleRequest($request);
        //Si le formulaire a été envoyé
        if(isset($_POST['submit'])){

            return $this->render('registration/fichier.html.twig');
        }
        return $this->render('registration/fichier.html.twig', ['formFichier' => $formFichier->createView()]);
    }
//return $this->render('ville/create.html.twig', [
//                'formVille' => $formVille->createView() //, 'pagination' => $pagination,
//            ]);
}