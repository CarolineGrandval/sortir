<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FichierTelecharger
{
    private $dossierCible;
    private $frappeur;
    private $validator;
    private $regles = [
        'csv_extension' => 'in:csv',
        'mail_column' => 'required',
        'pseudo_column' => 'required',
        'nom_column' => 'required',
        'prenom_column' => 'required',
        'telephone_column' => 'required',
        'password_column' => 'required',
        'administrateur_column' => 'required',
        'actif_column' => 'required',
        'nom' => 'required',
        'prenom' => 'required',
        'telephone' => 'required',
        'mail' => 'email|required',
        'password' => 'required',
        'administrateur' => 'required',
        'actif' => 'required',
        'pseudo' => 'required',
    ];

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
                throw new Exception('Le fichier ne peut pas être ouvert pour lecture.');
            }
            //vérification de la présence des données
            $entete = fgetcsv($ouvertureFichier,0,',');
            dd($entete);

//            //Récupère la première ligne avec les noms de colonnes
//            $entete = fgetcsv($ouvertureFichier,0,',');
//
//            //Trouver Chaque colonne
//            $mail_column = $this->getColumnNameByValue($entete, 'mail');
//            $nom_column = $this->getColumnNameByValue($entete, 'nom');
//            $prenom_column = $this->getColumnNameByValue($entete, 'prenom');
//            $telephone_column = $this->getColumnNameByValue($entete, 'telephone');
//            $password_column = $this->getColumnNameByValue($entete, 'password');
//            $administrateur_column = $this->getColumnNameByValue($entete, 'administrateur');
//            $actif_column = $this->getColumnNameByValue($entete, 'actif');
//            $pseudo_column = $this->getColumnNameByValue($entete, 'pseudo');
//
//            //récupère les informations de la deuxième ligne
//            $donneesLigne = fgetcsv($ouvertureFichier, 0, ',');
//
//            //liaison entre les en-têtes et la deuxième ligne
//            $premiereLigne = array_combine($entete, $donneesLigne);
//
//            // Chercher chaque donnée avec la colonne correspondante
//            $premierLigneMail = array_key_exists('mail', $premiereLigne)? $premiereLigne['mail'] : '';
//            $premierLigneNom = array_key_exists('nom', $premiereLigne)? $premiereLigne['nom'] : '';
//            $premierLignePrenom = array_key_exists('prenom', $premiereLigne)? $premiereLigne['prenom'] : '';
//            $premierLigneTelephone = array_key_exists('telephone', $premiereLigne)? $premiereLigne['telephone'] : '';
//            $premierLignePassword = array_key_exists('password', $premiereLigne)? $premiereLigne['password'] : '';
//            $premierLigneAdministrateur = array_key_exists('administrateur', $premiereLigne)? $premiereLigne['administrateur'] : '';
//            $premierLigneActif = array_key_exists('actif', $premiereLigne)? $premiereLigne['actif'] : '';
//            $premierLignePseudo = array_key_exists('pseudo', $premiereLigne)? $premiereLigne['pseudo'] : '';
//
//            //fermer le fichier et libére la mémoire
//            fclose($ouvertureFichier);
//
//            //Création du tableau de validation
//            $tableauValidation = [
//                'csv_extension' => $csvExtension,
//                'mail_column' => $mail_column,
//                'pseudo_column' => $pseudo_column,
//                'nom_column' => $nom_column,
//                'prenom_column' => $prenom_column,
//                'telephone_column' => $telephone_column,
//                'password_column' => $password_column,
//                'administrateur_column' => $administrateur_column,
//                'actif_column' => $actif_column,
//                'nom' => $premierLigneNom,
//                'prenom' => $premierLignePrenom,
//                'telephone' => $premierLigneTelephone,
//                'mail' => $premierLigneMail,
//                'password' => $premierLignePassword,
//                'administrateur' => $premierLigneAdministrateur,
//                'actif' => $premierLigneActif,
//                'pseudo' => $premierLignePseudo,
//            ];
//
//            return $this->validator->make($tableauValidation, $this->regles);
            return $fichier;

        } catch (FileException $fe){
            throw new Exception('Le fichier n\'a pas été trouvé !');
            //throw $this->createNotFoundException('Le fichier n\'a pas été trouvé !');
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

}