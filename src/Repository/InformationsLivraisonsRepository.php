<?php

namespace App\Repository;

use App\Entity\InformationsLivraisons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InformationsLivraisons|null find($id, $lockMode = null, $lockVersion = null)
 * @method InformationsLivraisons|null findOneBy(array $criteria, array $orderBy = null)
 * @method InformationsLivraisons[]    findAll()
 * @method InformationsLivraisons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformationsLivraisonsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InformationsLivraisons::class);
    }

    // /**
    //  * @return InformationsLivraisons[] Returns an array of InformationsLivraisons objects
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
    public function findOneBySomeField($value): ?InformationsLivraisons
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getInfosByUser($user)
    {
        return $this->createQueryBuilder('i')
            ->select('i.id,i.numero,i.complement,i.rue,i.codepostal,i.ville,i.pays')
            ->where('i.utilisateur = :user')
            ->setParameter('user',$user)
            ->getQuery()->getResult();
    }
}
