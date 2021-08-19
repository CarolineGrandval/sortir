<?php

namespace App\Repository;

use App\Entity\Rechercher;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * Liste toutes les sorties en arrivant sur la page d'accueil
     * @param int $page
     * @param int $nbElementsByPage
     * @return array
     */
    public function getSorties(int $page = 1, int $nbElementsByPage = 10): array{

        $req = $this->createQueryBuilder('sortie')->addSelect('user')
            ->leftJoin('sortie.participants', 'user')
            ->addSelect('campus')
            ->innerJoin('user.campus', 'campus')
            ->addSelect('etat')
            ->innerJoin('sortie.etat', 'etat')
            ->orderBy('sortie.dateHeureDebut', 'DESC');


        // Pagination de la première page et le nombre d'éléments par page
        $req->setFirstResult((($page < 1 ? 1 : $page) -1)  * $nbElementsByPage);
        $req->setMaxResults($nbElementsByPage);

        return $req->getQuery()->getResult();
    }

    /**
     * Liste les sorties en fonction des différentes infos saisies par l'utilisateur
     * @param int $page
     * @param int $nbElementsByPage
     * @param Rechercher $search
     * @param User $user
     * @return array
     */
    public function search(int $page = 1, int $nbElementsByPage = 10, Rechercher $search, User $user): array{

        //requete avec seulement le campus de sélectionner
        $req= $this->createQueryBuilder('s')
            ->leftjoin('s.etat', 'e')
            ->addSelect('e')
            ->leftJoin('s.participants', 'p')
            ->addSelect('p')
            ->andWhere('s.campus = :campus')->setParameter('campus', $search->getCampus());

        // Pagination de la première page et le nombre d'éléments par page
        $req->setFirstResult((($page < 1 ? 1 : $page) -1)  * $nbElementsByPage);
        $req->setMaxResults($nbElementsByPage);

//        //Requete sur les sorties pas créés par l'utilisateur
//        $eventsCreatedToExclude = $this->createQueryBuilder('s')
//            ->join('s.etat', 'e')
//            ->andWhere('s.organisateur != :organisateur ')->setParameter('organisateur', $user)
//            ->andWhere('e.libelle= :created')->setParameter('created', 'Créée')
//            ->getQuery()
//            ->getResult();
//
//        //Ajout de la requête d'exclusion
//        $req->andWhere('e NOT IN (:eventscreatedToExclude)')
//            ->setParameter('eventscreatedToExclude', $eventsCreatedToExclude);
//
//        //par mots-clefs
//        if($search->getMotclef()!=''){
//            $req->andWhere('s.nom LIKE :motclef')
//                ->setParameter('motclef', '%' . $search->getMotclef() . '%');
//        }
//
//        //par date de début
//        if (!is_null($search->getDateDebut())) {
//            $req->andWhere('s.dateHeureDebut > :dateDebut or s.dateHeureDebut = :dateDebut')
//                ->setParameter('startDate', $search->getDateDebut());
//        }
//
//        //par date de fin
//        if (!is_null($search->getDateFin())) {
//            $req->andWhere('s.dateHeureDebut < :dateFin or s.dateHeureDebut = :dateFin')
//                ->setParameter('endDate', $search->getDateFin());
//        }
//
//        //si organisateur de la sortie
//        if ($search->isOrganisateur()) {
//            $req->andWhere('s.organisateur = :organisateur')
//                ->setParameter('organisateur', $user);
//        }
//
//        //sur les sorties passées
//        if ($search->isPassees()) {
//            $req->andWhere('e.libelle = :passees')
//                ->setParameter('passees', State::PASSED);
//        }
//
//        //Utilisateur inscrit
//        if ($search->isInscrit()) {
//            $req->andWhere('p = :inscrit')
//                ->setParameter('inscrit', $user);
//        }
//
//        //Utilisateur pas inscrit
//        if ($search->isPasInscrit()) {
//            $eventsToExclude = $this->createQueryBuilder('e')
//                ->join('e.participants', 'p')
//                ->where('p = :signedUpUser')
//                ->setParameter('signedUpUser', $user)
//                ->getQuery()
//                ->getResult();
//            $req->andWhere('e NOT IN (:eventsToExclude)')
//                ->setParameter('eventsToExclude', $eventsToExclude);
//        }

        return $req->getQuery()->getResult();

    }
}
