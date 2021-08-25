<?php

namespace App\Controller;

use App\Service\FichierTelecharger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CSVLecteurController extends AbstractController
{
    /**
     * @Route("/csvimport", name="csv_import")
     */
    public function inserer(Request $request, FichierTelecharger $fichierTelecharger){
        //Création du Formulaire
        $formFichier = $this->createForm('App\Form\AjoutFichierType');
        $formFichier->handleRequest($request);



            //Si le formulaire a été envoyé
            if($formFichier->isSubmitted() && $formFichier->isValid()){

                //savoir si un fichier est présent
                $fichier = $formFichier['upload_file']->getData();

                if($fichier){
                    $nomFichier = $fichierTelecharger->telecharger($fichier);
                    if($nomFichier !== null){

                        $this->addFlash('success', 'Les utilisateurs ont été ajoutés !');

                        // Redirection sur le controlleur
                        return $this->redirectToRoute('/');
                    }
                    else{
                        throw new \Exception('Le fichier n\'a pu être récupéré.');
                    }
                }
                return $this->render('registration/fichier.html.twig', ['formFichier' => $formFichier->createView()]);

            }
        return $this->render('registration/fichier.html.twig', ['formFichier' => $formFichier->createView()]);
    }

}