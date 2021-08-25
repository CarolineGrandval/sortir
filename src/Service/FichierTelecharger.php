<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FichierTelecharger
{
    private $dossierCible;
    private $frappeur;
//    private $validator;
//    private $regles = [
//        'csv_extension' => 'in:csv',
//        'mail_column' => 'required',
//        'pseudo_column' => 'required',
//        'nom_column' => 'required',
//        'prenom_column' => 'required',
//        'telephone_column' => 'required',
//        'password_column' => 'required',
//        'administrateur_column' => 'required',
//        'actif_column' => 'required',
//        'nom' => 'required',
//        'prenom' => 'required',
//        'telephone' => 'required',
//        'mail' => 'email|required',
//        'password' => 'required',
//        'administrateur' => 'required',
//        'actif' => 'required',
//        'pseudo' => 'required',
//    ];

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
            //Vérification de la cohérence des données
            $verif = $this->validationDonnees($ouvertureFichier, $csvExtension);
            if(!$verif){
                //fermer le fichier
                // Close file and free up memory
                fclose($ouvertureFichier);
                unlink($test);
                //dd("C'est moche");
                throw new \Exception('Le fichier n\'est pas cohérent !');
            }
            else{

                //récupere la longueur du fichier
                $i=1 ;//Compteur de ligne
                while(!feof($ouvertureFichier)){
                    $donneesLigne = fgetcsv($ouvertureFichier, 1024, ',');
                    //Création d'une entité Utilisateur
                    $user = new User();
                    dd($donneesLigne);
                    //Intégration dans BDD
                    $i++;
                }
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

    private function getColumnNameByValue($array, $value)
    {
        return in_array($value, $array)? $value : '';
    }

    private function validationDonnees($ouvertureFichier, $csvExtension){
        $validate = false;

        //vérification de la présence des données
        $entete = fgetcsv($ouvertureFichier,0,';');


        //Test présence des données
        $donneesLigne = fgetcsv($ouvertureFichier, 0, ';');

        $entete =array_map("utf8_encode", $entete);

        //liaison entre les en-têtes et la deuxième ligne
        $premiereLigne = array_combine($entete, $donneesLigne);

        //Boucle
        foreach($premiereLigne as $key => $value){
            dump($key);
            dump($value);

        }
        //trouver chaque colonne
        $mail_column = $this->getColumnNameByValue($premiereLigne, 'mail');
        $nom_column = $this->getColumnNameByValue($entete, 'nom');
        $prenom_column = $this->getColumnNameByValue($entete, 'prenom');
        $telephone_column = $this->getColumnNameByValue($entete, 'telephone');
        $password_column = $this->getColumnNameByValue($entete, 'password');
        $administrateur_column = $this->getColumnNameByValue($entete, 'administrateur');
        $actif_column = $this->getColumnNameByValue($entete, 'actif');
        $pseudo_column = $this->getColumnNameByValue($entete, 'pseudo');

        dump($donneesLigne);
        dump(in_array("mail", $entete));
        dump(gettype($premiereLigne));
        dump(in_array("mail", $premiereLigne));
        dump(array_key_exists('mail', $premiereLigne));
        dump(array_keys($premiereLigne, "mail"));

      var_dump($premiereLigne);
        dd($premiereLigne);
        // Chercher chaque donnée avec la colonne correspondante
        $premierLigneMail = array_key_exists('mail', $premiereLigne)? $premiereLigne['mail'] : '';
        $premierLigneNom = array_key_exists('nom', $premiereLigne)? $premiereLigne['nom'] : '';
        $premierLignePrenom = array_key_exists('prenom', $premiereLigne)? $premiereLigne['prenom'] : '';
        $premierLigneTelephone = array_key_exists('telephone', $premiereLigne)? $premiereLigne['telephone'] : '';
        $premierLignePassword = array_key_exists('password', $premiereLigne)? $premiereLigne['password'] : '';
        $premierLigneAdministrateur = array_key_exists('administrateur', $premiereLigne)? $premiereLigne['administrateur'] : '';
        $premierLigneActif = array_key_exists('actif', $premiereLigne)? $premiereLigne['actif'] : '';
        $premierLignePseudo = array_key_exists('pseudo', $premiereLigne)? $premiereLigne['pseudo'] : '';

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
            'actif_column' => $actif_column,
            'nom' => $premierLigneNom,
            'prenom' => $premierLignePrenom,
            'telephone' => $premierLigneTelephone,
            'mail' => $premierLigneMail,
            'password' => $premierLignePassword,
            'administrateur' => $premierLigneAdministrateur,
            'actif' => $premierLigneActif,
            'pseudo' => $premierLignePseudo,
        ];
        dump(array_filter($tableauValidation));
        dump(sizeof(array_filter($tableauValidation)));
        dump(sizeof($tableauValidation));
        die();
        //taille du tableau renseigné
        if(sizeof(array_filter($tableauValidation)) == sizeof($tableauValidation)){
            $validate = true;
        }
        return $validate;
    }

}