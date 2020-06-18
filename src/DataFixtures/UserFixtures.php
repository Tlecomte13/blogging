<?php

namespace App\DataFixtures;

use App\Entity\Account\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin->setEmail('admin@admin.fr')
              ->setPassword($this->encoder->encodePassword($admin, 'password'))
              ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {

            $user = new User();

            $user   ->setEmail('user'. $i .'@user.fr')
                    ->setPassword($this->encoder->encodePassword($user, 'password'))
                    ->setRoles(['ROLE_USER']);

            $manager->persist($user);

        }

        $manager->flush();
    }
}
