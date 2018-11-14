<?php

namespace App\Repository;

use App\Entity\TypeNutrition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeNutrition|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeNutrition|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeNutrition[]    findAll()
 * @method TypeNutrition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeNutritionRepository extends ServiceEntityRepository
{
    /**
     * TypeNutritionRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeNutrition::class);
    }

}
