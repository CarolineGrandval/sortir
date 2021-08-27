<?php

namespace App\Repository;

use App\Entity\Rechercher;
use App\Entity\Sortie;
use App\Entity\User;
use App\Service\EtatEnum;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $container; // constante pour les 30 jours afin de filtrer la requête
    public function __construct(ManagerRegistry $registry, ContainerInterface $container)
    {
        parent::__construct($registry, Sortie::class);
        $this->container = $container;
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

        //requete avec seulement le campus de sélectionné
        $req= $this->createQueryBuilder('s')
            ->innerJoin('s.etat', 'e')
            ->addSelect('e')
            ->leftJoin('s.participants', 'p')
            ->addSelect('p')
            ->andWhere('s.campus = :campus')->setParameter('campus', $search->getCampus())
            ->andWhere('e.id != :annulees')->setParameter('annulees', EtatEnum::ETAT_ANNULE)
            ->andWhere('s.dateHeureDebut >= :date')->setParameter('date', new DateTime('-'.$this->container->getParameter('app.nbjours').' days'))
        ;

        // Pagination de la première page et le nombre d'éléments par page
        $req->setFirstResult((($page < 1 ? 1 : $page) -1)  * $nbElementsByPage);
        $req->setMaxResults($nbElementsByPage);

        //Requete sur les sorties pas créées par l'utilisateur
        $eventsCreatedToExclude = $this->createQueryBuilder('s')
            ->innerJoin('s.etat', 'e')
            ->andWhere('s.organisateur != :organisateur ')->setParameter('organisateur', $user)
            ->andWhere('e.id = :creation')->setParameter('creation', EtatEnum::ETAT_CREATION)
            ->getQuery()->getResult();

        //Ajout de la requête d'exclusion des sorties en création mais pas par l'utilisateur
        if(!empty($eventsCreatedToExclude)){
            $req->andWhere('s NOT IN (:eventscreatedToExclude)')->setParameter('eventscreatedToExclude', $eventsCreatedToExclude);
        }

        
        //par mots-clefs
        if(!empty($search->getMotclef())){
            $req->andWhere('s.nom LIKE :motclef')
                ->setParameter('motclef', '%' . $search->getMotclef() . '%');
        }

        //par date de début
        if (!is_null($search->getDateDebut())) {
            $req->andWhere('DATE(s.dateHeureDebut) > DATE(:dateDebut) or DATE(s.dateHeureDebut) = DATE(:dateDebut)')->setParameter('dateDebut', $search->getDateDebut());
        }


        //par date de fin
        if ($search->getDateFin() !== null) {  //!is_null($search->getDateDebut())
            $req->andWhere('DATE(s.dateHeureDebut) < DATE(:dateFin) or DATE(s.dateHeureDebut) = DATE(:dateFin)')->setParameter('dateFin', $search->getDateFin());
        }

        //si organisateur de la sortie
        if ($search->isOrganisateur()) {
            $req->andWhere('s.organisateur = :organisateur')->setParameter('organisateur', $user);
        }

        //sur les sorties passées
        if ($search->isPassees()) {
            $req->andWhere('e.id = :passees')->setParameter('passees', EtatEnum::ETAT_TERMINE);
        }

        //Utilisateur inscrit
        if ($search->isInscrit()) {
            $req->andWhere('p = :inscrit')->setParameter('inscrit', $user);
        }

        //Utilisateur pas inscrit
        if ($search->isPasInscrit()) {
            $sortiesPasInscrit = $this->createQueryBuilder('s')
                ->innerJoin('s.participants', 'p')
                ->where('p = :signedUpUser')->setParameter('signedUpUser', $user)
                ->getQuery()->getResult();
            //Vérification de la présence de données
            if(!empty($sortiesPasInscrit)){
                $req->andWhere('s NOT IN (:sortiesPasInscrit)')->setParameter('sortiesPasInscrit', $sortiesPasInscrit);
            }
        }

        //Utilisateurs Actifs
//        $usersActifs = $this->createQueryBuilder('s')
//                        ->innerJoin('s.participants', 'p')
//                        ->addSelect('p')
//                        ->where('p.actif = 1')
//                        ->getQuery()->getResult();
//        if(!empty($usersActifs)){
//            $req->andWhere('p IN (:usersActifs)')->setParameter('usersActifs', $usersActifs);
//        }

        //Ordonner par date
        $req->orderBy('s.dateHeureDebut', 'ASC');

        //Retourne la requête selon les filtres ajoutés
        return $req->getQuery()->getResult();
    }

    /**
     * Liste des sorties sur lequelles l'utilisateur est inscrit
     * @param Rechercher $search
     * @param User $user
     * @return array
     */
    public function inscritSortie(Rechercher $search, User $user): array{

        //Création de la requête pour savoir si l'utilisateur est inscrit à l'évènement
        $req = $this->createQueryBuilder('s')
                    ->select('s.id')
                    ->innerjoin('s.participants', 'user')
                    ->andWhere('user.id = :id')->setParameter('id', $user->getId());

        //retourne le tableau du résultat de la requête
        return $req->getQuery()->getResult();
    }

}
