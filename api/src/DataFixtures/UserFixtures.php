<?php

namespace App\DataFixtures;

use App\Entity\Gardener;
use App\Entity\Job;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $manager);

        $populator->addEntity(User::class, 20, [], [
            'roles' => function ($user) {
                for ($i = 1; $i < 20; $i++) {
                    $user->setRoles(['ROLE_USER']);
                }
            },
            'password' => function ($user) {
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'password'
                ));
            },
            'type' => function ($user) {
                for ($i = 1; $i < 20; $i++) {
                    $user->setType('user');
                }
            }
        ]);

        $populator->addEntity(Job::class, 20, [], [
            'title' => function ($job) use ($generator) {
                $job->setTitle($generator->bs);
            }
        ]);
        $populator->execute();

        $manager->flush();
    }
}
