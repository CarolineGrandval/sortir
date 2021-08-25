<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FichierTelecharger
{
    private $dossierCible;
    private $frappeur;

    /*
     * Constructeur
     */
    public function __construct($dossierCible, SluggerInterface $frappeur) //, ValidationFactory $validator
    {
        $this->dossierCible = $dossierCible;
        $this->frappeur = $frappeur;
        //$this->validator = $validator;
    }

    public function telecharger(UploadedFile $fichier)
    {
        ini_set("auto_detect_line_endings", true);
        //nom du fichier d'origine
        $nomFichierOrigine = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
        //nom du fichier de sauvegarde
        $nomFichierSauvegarde = $this->frappeur->slug($nomFichierOrigine);
        //Récupère l'extension
        $csvExtension = $fichier->getClientOriginalExtension();
        //nom du fichier et génération d'un ID Unique pour faire une copie
        $nomFichier = $nomFichierSauvegarde . '-' .uniqid(). '.' .$csvExtension;

        try{
            //Copie le fichier dans le projet public>uploads
            $test = $fichier->move($this->getTargetDirectory(), $nomFichier);
            //Récupération du pointeur lors de l'ouverture du fichier
            $ouvertureFichier = fopen($test, 'r');
            //test l'ouverture de fichier en mode lecture
            if($ouvertureFichier  === false){
                throw new \Exception('Le fichier ne peut pas être ouvert pour lecture.');
            }
            dd("passe");
            //Vérification de la cohérence des données
            $verif = $this->validationDonnees($ouvertureFichier, $csvExtension);
            dd($verif);
            if(!$verif){
                dd("passeVerif");
                //fermer le fichier et libère la mémoire
                fclose($ouvertureFichier);
                unlink($test);

                throw new \Exception('Le fichier n\'est pas cohérent !');
            }

            return $fichier;

        } catch (FileException $fe){
            throw new \Exception('Le fichier n\'a pas été trouvé !');
        }
        return $fichier;
    }

    public function getTargetDirectory(){
        return $this->dossierCible;
    }

    private function validationDonnees($ouvertureFichier, $csvExtension){
        $validate = false;

        //vérification de la présence des données
        $entete = fgetcsv($ouvertureFichier,0,';');

        //Trouver les noms de colonne
        foreach($entete as $value){
            switch($value){
                case "mail":
                    $mail_column = "mail";
                    break;
                case "nom":
                    $nom_column = "nom";
                    break;
                case "prenom":
                    $prenom_column = "prenom";
                    break;
                case "telephone":
                    $telephone_column = "telephone";
                    break;
                case "password":
                    $password_column = "password";
                    break;
                case "administrateur":
                    $administrateur_column = "administrateur";
                    break;
                case "actif":
                    $actif_column = "actif";
                    break;
                case "pseudo":
                    $pseudo_column = "pseudo";
                    break;
            }
        }


        //Création du tableau de validation
        $tableauValidation = [
            'csv_extension' => $csvExtension,
            'mail_column' => $mail_column,
            'pseudo_column' => $pseudo_column,
            'nom_column' => $nom_column,
            'prenom_column' => $prenom_column,
            'telephone_column' => $telephone_column,
            'password_column' => $password_column,
            'administrateur_column' => $administrateur_column,
            'actif_column' => $actif_column
        ];

        //taille du tableau renseigné
        if(sizeof(array_filter($tableauValidation)) == sizeof($tableauValidation)){
            $validate = true;
        }
        $user = null;
        if($validate){
            while(!feof($ouvertureFichier)){
                $data = fgetcsv($ouvertureFichier, 0, ";");
                while ($data !== FALSE){
                    $user = new User();
                    $user->setMail($data[0]);
                    $user->setPseudo($data[1]);
                    $user->setNom($data[2]);
                    $user->setPrenom($data[3]);
                    $user->setTelephone($data[4]);
                    $user->setPassword($data[5]);
                    $user->setAdministrateur(false);
                    $user->setActif(true);

//                    $entityManager->persist($user);
//                    $entityManager->flush();

                }
            }
        }
        return $user;
    }

}