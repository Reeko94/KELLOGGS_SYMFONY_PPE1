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

    /**
     * @param $id
     * @return array
     */
    public function getArrayResult($id)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id = :id')
            ->setParameter('id',$id)->getQuery()->getArrayResult();
    }

    public function updateFabricant($id,$libelle)
    {
        return $this->createQueryBuilder('f')
            ->update(Fabricants::class,'f')
            ->set('f.libelle',':libelle')
            ->where('f.id = :id')
            ->setParameter('id' , $id)
            ->setParameter('libelle',$libelle)
            ->getQuery()->getResult();
    }

    /**
     * @param $id
     */
    public function deletefromid($id)
    {
        return $this->createQueryBuilder('f')
            ->delete(Fabricants::class,'f')
            ->where('f.id = :id')
            ->setParameter('id',$id)
            ->getQuery()->getResult();
    }
}
