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
        foreach ($this->getUserData() as [$fullname, $username, $password, $email]) {
            $user = new Utilisateur();
            $user->setNom($fullname);
            $user->setPrenom($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $client = new Client();
        $client->setNom('The Great');
        $client->setPrenom('Brixton');
        $client->setPassword($this->passwordEncoder->encodePassword($client, 'fedora'));
        $client->setEmail('brixton@brixton.com');
        $client->setDateInscription(new \DateTime('now'));
        $client->setDateNaissance(new \DateTime('2000-11-14'));

        $manager->persist($client);

        $commercial = new Commercial();
        $commercial->setNom('The Good');
        $commercial->setPrenom('Brix');
        $commercial->setPassword($this->passwordEncoder->encodePassword($commercial, 'fedora'));
        $commercial->setEmail('brixton@brix.com');
        $commercial->setDateEntree(new \DateTime('now'));
        $commercial->setPoste('PgM');

        $manager->persist($commercial);

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com'],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com'],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com'],
        ];
    }
}
