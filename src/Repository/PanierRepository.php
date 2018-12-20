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
     * @return array
     */
    public function getAllPanier()
    {
        return $this->createQueryBuilder('p')->select()
            ->getQuery()->getArrayResult();
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
        $countPanier = (count((array)json_decode($panier[0]->getArticles())[0]));
        $articles = json_decode($panier[0]->getArticles(),true);
        $articles = array_values($articles);
        // S'il y a au moins un article
        if($countPanier >= 1){
            foreach($articles as $a) {
                if ($article == intval($a['idarticle']))
                    return $this->updatePanier($idUser, $article,true,1);
            }
        } else {
            unset($articles[0]);
        }
        array_push($articles,[
            'idarticle'=> $article,
            'qte'=> 1
        ]);

        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',json_encode(array_values($articles)))
            ->setParameter('user',$idUser)
            ->getQuery()->getResult();

        return true;
    }

    /**
     * @param $idUser
     * @param $article
     * @param bool $increment
     * @param int $qte
     * @param bool $reset
     * @return bool
     */
    public function updatePanier($idUser,$article,$increment = true,$qte = 0,$reset = false)
    {
        $panier = $this->checkPanier($idUser);
        $panierJ = json_decode($panier[0]->getArticles(),true);
        $panierJ = array_values($panierJ);
        $key = array_search($article, array_column($panierJ, 'idarticle'));
        ($increment) ? $panierJ[$key]['qte'] = $panierJ[$key]['qte'] + $qte : $panierJ[$key]['qte'] = $panierJ[$key]['qte'] - $qte;
        if($reset) $panierJ[$key]['qte'] = (int) $qte;


        $this->createQueryBuilder('p')
            ->update(Panier::class,'p')
            ->set('p.articles',':articles')
            ->where('p.utilisateur = :user')
            ->setParameter('articles',json_encode(array_values($panierJ)))
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
            ->setParameter('articles',json_encode(array_values($panier)))
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

    // deletearticleforpanier
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
