<?php

namespace App\DataFixtures;

use App\Controller\TypeNutritionController;
use App\Entity\TypeNutrition;
use App\Repository\TypeNutritionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class Fabricants extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        // $product = new Product();
        // $manager->persist($product);
        for($i=0;$i<100;$i++) {
            $Fabricant = new \App\Entity\Fabricants();
            $Fabricant->setLibelle($faker->words(2,true));
            $Fabricant->setLogo('img/test.png');
            $manager->persist($Fabricant);
        }
        $manager->flush();
    }
}
