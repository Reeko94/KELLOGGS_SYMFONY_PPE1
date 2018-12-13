<?php

namespace App\Repository;

use App\Entity\Panier;
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
     * @param $idUser
     * @param $article
     * @return bool
     */
    public function addArticlePanier($idUser,$article)
    {
        //TODO : dynamisme sur la quantité ?
        $panier = $this->checkPanier($idUser);
        $articles = json_decode($panier[0]->getArticles(),true);
        foreach($articles as $a) {
            if ($article == $a['idarticle'])
                return $this->updatePanier($idUser, $article);
        }

        $articles[] =[
            'idarticle'=> $article,
            'qte'=> 1
        ];

        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',json_encode($articles))
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();

        return true;
    }

    /**
     * @param $idUser
     * @param $article
     * @return bool
     */
    public function updatePanier($idUser,$article,$increment = true,$qte = 0,$reset = false)
    {
        $panier = $this->checkPanier($idUser);
        $panierJ = json_decode($panier[0]->getArticles(),true);
        $key = array_search($article, array_column($panierJ, 'idarticle'));

        ($increment) ? $panierJ[$key]['qte'] = $panierJ[$key]['qte'] + $qte : $panierJ[$key]['qte'] = $panierJ[$key]['qte'] - $qte;

        if($reset) $panierJ[$key]['qte'] = $qte;

        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',json_encode($panierJ))
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();

        return true;
    }

    public function setPanier($idUser,$panier)
    {
        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',json_encode($panier))
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();

        return true;
    }

    public function destroyPanier($idUser)
    {
        return $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':article')
            ->where('p.utilisateur = :user')
            ->setParameter('article','[ ]')
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();
    }
}
