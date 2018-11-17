<?php

namespace App\Repository;

use App\Entity\Fabricants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Fabricants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fabricants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fabricants[]    findAll()
 * @method Fabricants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FabricantsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Fabricants::class);
    }

    // /**
    //  * @return Fabricants[] Returns an array of Fabricants objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fabricants
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
