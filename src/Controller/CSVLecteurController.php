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
        //Tester que le fichier n'est pas initialisé
        //dd($formFichier['upload_file']->getData());

            //Si le formulaire a été envoyé
            if($formFichier->isSubmitted() && $formFichier->isValid()){

                //savoir si un fichier est présent
                $fichier = $formFichier['upload_file']->getData();

                if($fichier){
                    $nomFichier = $fichierTelecharger->telecharger($fichier);
                    if($nomFichier !== null){
                        $dossier = $fichierTelecharger->getTargetDirectory();
                        $cheminEntier = $dossier.'/'.$fichier;

                        //dd("lut22");
                    }
                    else{
                        //pb
                    }
                }

                //vérifier que le fichier est au format CSV
                //vérifier que toutes les colonnes sont présentes

                //vérifier la cohérence de données

                //insérer les données en BDD

                //Retour à l'accueil avec un message AddFlash

                return $this->render('registration/fichier.html.twig', ['formFichier' => $formFichier->createView()]);

            }
        return $this->render('registration/fichier.html.twig', ['formFichier' => $formFichier->createView()]);
    }

}