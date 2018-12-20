<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Entity\Fabricants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    public function getlastfive()
    {
        return $this->findBy([],["id" => "DESC"],5);
    }

    public function countByFabricant(Fabricants $fabricant)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.fabricant = :f')
            ->setParameter('f',$fabricant)
            ->getQuery()->getOneOrNullResult();
    }
}
