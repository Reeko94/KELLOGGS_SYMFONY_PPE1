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
        $type = new TypeNutrition();
        $type->setLibelle("Sucré");
        $manager->persist($type);

        $type2 = new TypeNutrition();
        $type2->setLibelle("Salé");
        $manager->persist($type2);

        $manager->flush();
    }
}
