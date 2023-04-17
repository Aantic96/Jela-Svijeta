<?php

namespace App\DataFixtures;

use App\Factory\TagsFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        TagsFactory::createMany(4);
    }
}