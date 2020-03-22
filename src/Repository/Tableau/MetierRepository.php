<?php

namespace App\Repository\Tableau;

use App\Entity\Tableau\Metier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Metier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metier[]    findAll()
 * @method Metier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metier::class);
    }

    // /**
    //  * @return Metier[] Returns an array of Metier objects
    //  */
    
    public function findMetiers()
    {
        return $this->createQueryBuilder('m')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Metier
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
