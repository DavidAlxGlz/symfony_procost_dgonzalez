<?php

namespace App\Repository\Tableau;

use App\Entity\Tableau\Hours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\Expr\Join as ExprJoin;

/**
 * @method Hours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hours[]    findAll()
 * @method Hours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hours::class);
    }

     
    
    public function findSumHours()
    {
        return $this->createQueryBuilder('h')
            ->select('sum(h.hours)')
            ->getQuery()
            ->getResult()
        ;

    }

    public function findTempsProduction()
    {
        $qb = $this->createQueryBuilder('h');

        $qb 
            ->select('h.id,h.hours,e.prenom,e.nom,p.nom as projet')
            ->setMaxResults(6)
            ->Join('App\Entity\Tableau\Projet','p','WITH','p.id = h.projet')
            ->Join('App\Entity\Tableau\Employe','e','WITH','e.id = h.employe')
            ;

            return $qb->getQuery()->getResult();
    }

    public function findHistoricProduction($id)
    {
        $qb = $this->createQueryBuilder('h');

        $qb 
            ->select('h.id,h.hours,h.dateSaisie,e.prenom,e.nom,p.nom as projet,h.hours * e.coutHoraire as cout')
            ->andWhere('e.id = :id')
            ->setParameter('id', $id)
            ->Join('App\Entity\Tableau\Projet','p','WITH','p.id = h.projet')
            ->Join('App\Entity\Tableau\Employe','e','WITH','e.id = h.employe')
            ;

            return $qb->getQuery()->getResult();
    }

    public function findHistoricProductionProjet($id)
    {
        $qb = $this->createQueryBuilder('h');

        $qb 
            ->select('h.id,h.hours,h.dateSaisie,e.prenom,e.nom,p.nom as projet,h.hours * e.coutHoraire as cout')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->Join('App\Entity\Tableau\Projet','p','WITH','p.id = h.projet')
            ->Join('App\Entity\Tableau\Employe','e','WITH','e.id = h.employe')
            ;

            return $qb->getQuery()->getResult();
    }

    public function findHoursLivraison()
    {
        $qb = $this->createQueryBuilder('h');

        $qb 
            ->select('(SUM(h.hours * e.coutHoraire) / sum(p.prixVente)) * 100 as Rentabilite')
            ->Join('App\Entity\Tableau\Projet','p','WITH','p.id = h.projet')
            ->Join('App\Entity\Tableau\Employe','e','WITH','e.id = h.employe')
            ;

            return $qb->getQuery()->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Hours
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
