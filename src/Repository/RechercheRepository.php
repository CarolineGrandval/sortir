<?php

namespace App\Repository;


use App\Entity\Rechercher;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rechercher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rechercher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rechercher[]    findAll()
 * @method Rechercher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RechercheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rechercher::class);
    }

    /**
     * fonction de recherche avec les différents filtres
     * @param int $page
     * @param int $nbElementsByPage
     * @param Rechercher $search
     * @param User $user
     * @return array
     */
    public function search(int $page = 1, int $nbElementsByPage = 10, Rechercher $search, User $user): array{
        //requete avec seulement le campus de sélectionner
        $req= $this->createQueryBuilder('e')
            ->join('e.state', 's')
            ->addSelect('s')
            ->leftJoin('e.participants', 'p')
            ->addSelect('p')
            ->andWhere('e.campus = :campus')
            ->andWhere('e.isArchived = false')
            ->setParameter('campus', $search->getCampus())
        ;

        // Pagination de la première page et le nombre d'éléments par page
        $req->setFirstResult((($page < 1 ? 1 : $page) -1)  * $nbElementsByPage);
        $req->setMaxResults($nbElementsByPage);

        //Requete sur les sorties pas créés par l'utilisateur
        $eventsCreatedToExclude = $this->createQueryBuilder('e')
            ->join('e.state', 's')
            ->andWhere('e.organiser != :organiser')
            ->setParameter('organiser', $user)
            ->andWhere('s.name = :created')
            ->setParameter('created', State::CREATED)
            ->getQuery()
            ->getResult();

        //Ajout de la requête d'exclusion
        $req->andWhere('e NOT IN (:eventscreatedToExclude)')
            ->setParameter('eventscreatedToExclude', $eventsCreatedToExclude);

        //par mots-clefs
        if($search->getMotclef()!=''){
            $req->andWhere('e.name LIKE :motclef')
                ->setParameter('motclef', '%' . $search->getMotclef() . '%');
        }

        //par date de début
        if (!is_null($search->getDateDebut())) {
            $req->andWhere('e.startDateTime > :startDate or e.startDateTime = :startDate')
                ->setParameter('startDate', $search->getDateDebut());
        }

        //par date de fin
        if (!is_null($search->getDateFin())) {
            $req->andWhere('e.startDateTime < :endDate or e.startDateTime = :endDate')
                ->setParameter('endDate', $search->getDateFin());
        }

        //si organisateur de la sortie
        if ($search->isOrganisateur()) {
            $req->andWhere('e.organiser = :organisateur')
                ->setParameter('organisateur', $user);
        }

        //sur les sorties passées
        if ($search->isPassees()) {
            $req->andWhere('s.name = :passees')
                ->setParameter('passeess', State::PASSED);
        }

        //Utilisateur inscrit
        if ($search->isInscrit()) {
            $req->andWhere('p = :inscrit')
                ->setParameter('inscrit', $user);
        }

        //Utilisateur pas inscrit
        if ($search->isPasInscrit()) {
            $eventsToExclude = $this->createQueryBuilder('e')
                ->join('e.participants', 'p')
                ->where('p = :signedUpUser')
                ->setParameter('signedUpUser', $user)
                ->getQuery()
                ->getResult();
            $req->andWhere('e NOT IN (:eventsToExclude)')
                ->setParameter('eventsToExclude', $eventsToExclude);
        }

        return $req->getQuery()->getResult();

    }

}