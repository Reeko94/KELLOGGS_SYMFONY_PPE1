<?php

namespace App\DataFixtures;

use App\Entity\TypeNutrition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TypeNutritionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr-FR');
        for($i = 0;$i<5;$i++)
        {
            $type = new TypeNutrition();
            $type->setLibelle($faker->words(1,true));
            $manager->persist($type);
        }
        $manager->flush();
    }
}
