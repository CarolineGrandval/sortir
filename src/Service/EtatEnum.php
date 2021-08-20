<?php

namespace App\Service;

class EtatEnum
{
    const ETAT_CREATION = 1;
    const ETAT_OUVERT = 2;
    const ETAT_FERME = 3;
    const ETAT_ENCOURS = 4;
    const ETAT_TERMINE = 5;
    const ETAT_ANNULE = 6;

    /**
     * Récupère tous les états sous forme de tableau
     * @return int[]
     */
    public static function tableauEtatEnum(){
        return array(
            self::ETAT_CREATION,
            self::ETAT_OUVERT,
            self::ETAT_FERME,
            self::ETAT_ENCOURS,
            self::ETAT_TERMINE,
            self::ETAT_ANNULE
            );
    }
}