<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function updateUtilisateur(Utilisateur $user,array $datas)
    {
     $this->createQueryBuilder('u')
            ->update(Utilisateur::class,'u')
            ->set('u.nom',':nom')
            ->set('u.prenom',':prenom')
            ->where('u.id = :id')
            ->setParameter('id',$user->getId())
            ->setParameter('nom', $datas['nom'])
            ->setParameter('prenom',$datas['prenom'])
            ->getQuery()->getArrayResult();

     $this->createQueryBuilder('c')
         ->update(Client::class,'c')
         ->set('c.date_naissance',':dn')
         ->where('c.id = :id')
         ->setParameter('dn', $datas['datenaissance'])
         ->setParameter('id', $user->getId())
         ->getQuery()->getResult();

     return true;

    }

    public function orderByType()
    {
        return $this->createQueryBuilder('u')
            ->select()
            ->orderBy('u.type','DESC')
            ->where('u.actif = :actif')
            ->setParameter('actif',1)
            ->getQuery()->getResult();
    }

}
