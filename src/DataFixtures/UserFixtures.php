<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Commercial;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setNom('The Great');
        $client->setPrenom('Brixton');
        $client->setPassword($this->passwordEncoder->encodePassword($client, 'fedora'));
        $client->setEmail('brixton@brixton.com');
        $client->setDateInscription(new \DateTime('now'));
        $client->setDateNaissance(new \DateTime('2000-11-14'));
        $client->setType(1);
        $client->setActif(1);
        $client->setDiscr('client');
        $manager->persist($client);

        $commercial = new Commercial();
        $commercial->setNom('The Good');
        $commercial->setPrenom('Brix');
        $commercial->setPassword($this->passwordEncoder->encodePassword($commercial, 'fedora'));
        $commercial->setEmail('brixton@brix.com');
        $commercial->setDateEntree(new \DateTime('now'));
        $commercial->setType(2);
        $commercial->setActif(1);
        $manager->persist($commercial);

        $manager->flush();
    }

    private function getUserData(): array
    {

    }
}
