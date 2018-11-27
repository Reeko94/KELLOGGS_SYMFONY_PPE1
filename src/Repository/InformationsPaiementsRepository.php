<?php

namespace App\Repository;

use App\Entity\InformationsPaiements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InformationsPaiements|null find($id, $lockMode = null, $lockVersion = null)
 * @method InformationsPaiements|null findOneBy(array $criteria, array $orderBy = null)
 * @method InformationsPaiements[]    findAll()
 * @method InformationsPaiements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformationsPaiementsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InformationsPaiements::class);
    }

    // /**
    //  * @return InformationsPaiements[] Returns an array of InformationsPaiements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InformationsPaiements
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
