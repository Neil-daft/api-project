<?php

namespace App\DataFixtures;

use App\Entity\Job;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Fixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $manager);
        $populator->addEntity(User::class, 40, [], [
            'roles' => function ($user) use ($generator) {
                $user->setRoles(['ROLE_USER']);
            },
            'password' => function ($user) {
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'password'
                ));
            }
        ]);
        $populator->addEntity(Job::class, 20);
        $populator->execute();

        $manager->flush();
    }
}
