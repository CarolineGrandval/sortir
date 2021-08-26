<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FichierTelecharger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class CSVLecteurController extends AbstractController
{
    /**
     * @Route("/csvimport", name="csv_import")
     */
    public function inserer(Request $request, FichierTelecharger $fichierTelecharger, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder){
        //Création du Formulaire
        $formFichier = $this->createForm('App\Form\AjoutFichierType');
        $formFichier->handleRequest($request);


            //Si le formulaire a été envoyé
            if($formFichier->isSubmitted() && $formFichier->isValid()){

                //savoir si un fichier est présent
                $fichier = $formFichier['upload_file']->getData();
                //si le fichier est présent
                if($fichier){
                    $donneesFichier = $fichierTelecharger->telecharger($fichier);
                    //si la validation retourne un tableau
                    if(!empty($donneesFichier)){
                        //Boucle sur le fichier
                        for($i=0; $i <= sizeof($donneesFichier)-1; $i++)
                        {
                            $donnees = explode(";", $donneesFichier[$i][0]);
                            //création de l'entité Utilisateur
                            $user = new User();
                            $user->setMail($donnees[0]);
                            $user->setPseudo($donnees[1]);
                            $user->setNom($donnees[2]);
                            $user->setPrenom($donnees[3]);
                            $user->setTelephone($donnees[4]);
                            $user->setPassword($passwordEncoder->hashPassword($user, $donnees[5]));
                            $user->setAdministrateur(false);
                            $user->setActif(true);
                            //Entité Campus
                            $campus = $entityManager->getRepository('App:Campus')->find($donnees[8]);
                            $user->setCampus($campus);
                            //Insertion dans file d'attente, ensuite dans BDD
                            $entityManager->persist($user);
                            $entityManager->flush();
                        }
                        //Message
                        $this->addFlash('success', 'Les utilisateurs ont été ajoutés !');

                        // Redirection sur le controlleur
                        return $this->redirectToRoute('sortie_home');
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