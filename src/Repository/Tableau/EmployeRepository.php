<?php

namespace App\Repository\Tableau;

use App\Entity\Tableau\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Employe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employe[]    findAll()
 * @method Employe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employe::class);
    }

    // /**
    //  * @return Employe[] Returns an array of Employe objects
    //  */
    
    public function findTopEmploye()
    {
        $qb = $this->createQueryBuilder('h');

        $qb 
            ->select('h.prenom,e.hours')
            ->Join('App\Entity\Tableau\Hours','e','WITH','e.employe = h.id')
            ;

            return $qb->getQuery()->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Employe
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
