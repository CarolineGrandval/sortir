<?php

namespace App\Repository;


use App\Entity\Search;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Search|null find($id, $lockMode = null, $lockVersion = null)
 * @method Search|null findOneBy(array $criteria, array $orderBy = null)
 * @method Search[]    findAll()
 * @method Search[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Search::class);
    }

    public function search(int $page = 1, int $nbElementsByPage = 10, Search $search, User $user){
//        $req= $this->createQueryBuilder('e')
//            ->join('e.state', 's')
//            ->addSelect('s')
//            ->leftJoin('e.participants', 'p')
//            ->addSelect('p')
//            ->andWhere('e.campus = :campus')
//            ->andWhere('e.isArchived = false')
//            ->setParameter('campus', $search->getCampus())
//        ;

    }

}