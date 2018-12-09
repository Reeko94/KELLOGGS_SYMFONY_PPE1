<?php

namespace App\Repository;

use App\Entity\Nouveautes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Nouveautes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nouveautes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nouveautes[]    findAll()
 * @method Nouveautes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NouveautesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Nouveautes::class);
    }

    // /**
    //  * @return Nouveautes[] Returns an array of Nouveautes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nouveautes
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
