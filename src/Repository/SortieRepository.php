<?php

namespace App\Repository;

use App\Entity\Sortie;
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
        $req = $this->createQueryBuilder('sortie') //-> addSelect('su')
//            ->innerJoin('sortie.sortie_id', 'su')
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
     * @param User $user
     * @param array|null $searchData
     */
    public function search(int $page = 1, int $nbElementsByPage = 10, User $user, ?array $searchData){

    }
}
