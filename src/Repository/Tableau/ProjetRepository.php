<?php

namespace App\Repository\Tableau;

use App\Entity\Tableau\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

     /**
      * @return Projet[] Returns an array of Projet objects
      */
    
    public function findEnCours()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.dateLivraison is NULL')
            ->getQuery()
            ->getResult()
            
        ;
        
    }

     /**
      * @return Projet[] Returns an array of Projet objects
      */
    
      public function findDerniers()
      {
          return $this->createQueryBuilder('p')
              ->orderBy('p.dateCreation','DESC')
              ->setMaxResults(5)
              ->getQuery()
              ->getResult()
          ;
      }

    /**
      * @return Projet[] Returns an array of Projet objects
      */
    
      public function findLivre()
      {
          return $this->createQueryBuilder('p')
              ->andWhere('p.dateLivraison is NOT NULL')
              ->getQuery()
              ->getResult()
          ;
      }

      
    

    /*
    public function findOneBySomeField($value): ?Projet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
