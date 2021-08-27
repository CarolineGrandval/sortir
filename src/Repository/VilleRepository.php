<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    public function findAllWithPagination(int $page = 1, int $nbElementsByPage = 10): array{

        $req = $this->createQueryBuilder('ville')
            ->orderBy('ville.codePostal', 'ASC');
//        $req->setFirstResult((($page < 1 ? 1 : $page) -1)  * $nbElementsByPage);

        return $req->getQuery()->getResult();
    }

    public function findByNom($nomVille){
        return $this->createQueryBuilder('ville')
            ->andWhere('ville.nom = :nom')->setParameter('nom', $nomVille)
            ->getQuery()->getResult();
    }
    // /**
    //  * @return Ville[] Returns an array of Ville objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ville
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
