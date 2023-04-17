<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\FoodFactory;
use App\Factory\IngredientFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FoodFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        FoodFactory::createMany(20, function () {
            return [
                'category' => CategoryFactory::random(),
                'ingredients' => IngredientFactory::randomRange(1,3),
                'tags' => TagFactory::randomRange(1,3)
            ];
        });
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            TagFixtures::class,
            IngredientFixtures::class
        ];
    }
}
