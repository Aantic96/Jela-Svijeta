<?php

namespace App\DataFixtures;

use App\Factory\IngredientsFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       IngredientsFactory::createMany(15);
    }
}