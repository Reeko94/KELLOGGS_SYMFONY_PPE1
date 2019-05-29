<?php

namespace App\Repository;

use App\Entity\Panier;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    // /**
    //  * @return Panier[] Returns an array of Panier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Panier
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $user
     * @return mixed
     */
    public function checkPanier($user)
    {
        return $this->createQueryBuilder('p')->select()
            ->where('p.utilisateur = :user')
            ->setParameter('user',$user)
            ->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getAllPanier()
    {
        return $this->createQueryBuilder('p')->select()
            ->getQuery()->getArrayResult();
    }

    /**
     * @param $idUser
     * @param $jsonPanier
     * @return bool
     */
    public function addArticlePanier($idUser,$jsonPanier)
    {
        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',$jsonPanier)
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();
        return true;
    }

    /**
     * @param integer $idUser
     * @param string $jsonPanier
     * @return bool
     */
    public function updatePanier(Utilisateur $idUser,string $jsonPanier)
    {
        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',$jsonPanier)
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();

        return true;
    }

    /**
     * @param $idUser
     * @param $panier
     * @return bool
     */
    public function setPanier($idUser,$panier)
    {
        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',$panier)
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();

        return true;
    }

    /**
     * @param $idUser
     * @return mixed
     */
    public function destroyPanier($idUser)
    {
        $articles = [new \stdClass()];
        return $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':article')
            ->where('p.utilisateur = :user')
            ->setParameter('article',':article')
            ->setParameter('user',$idUser)
            ->setParameter('article',json_encode($articles))
            ->getQuery()->getResult();
    }

    /**
     * @param $articles
     * @param int $idPanier
     * @return mixed
     */
    public function updatePanierWithId($articles, int $idPanier)
    {
        return $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.id = :id')
            ->setParameter('articles',$articles)
            ->setParameter('id',$idPanier)
            ->getQuery()->getResult();
    }
}
