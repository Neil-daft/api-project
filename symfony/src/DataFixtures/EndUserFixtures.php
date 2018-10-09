<?php

namespace App\DataFixtures;

use App\Entity\EndUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class EndUserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $manager);
        $populator->addEntity(EndUser::class, 40);
        $populator->execute();
    }
}
