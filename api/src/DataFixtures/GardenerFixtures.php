<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GardenerFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $manager);

        $populator->addEntity(User::class, 20, [], [
            'roles' => function ($user) {
                for ($i = 1; $i < 20; $i++) {
                    $user->setRoles(['ROLE_GARDENER']);
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
                    $user->setType('gardener');
                }
            }
        ]);

        $populator->execute();

        $manager->flush();
    }


}