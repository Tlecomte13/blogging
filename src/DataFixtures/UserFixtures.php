<?php

namespace App\DataFixtures;

use App\Entity\Account\User;
use App\Repository\Account\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder, $userRepository;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepository $userRepository
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
//        $admin = new User();
//
//        $admin  ->setUsername('admin')
//                ->setEmail('admin@admin.fr')
//                ->setPassword($this->encoder->encodePassword($admin, 'password'))
//                ->setRoles(['ROLE_ADMIN']);
//
//        $manager->persist($admin);
//
//
        for ($i = 7000; $i < 9000; $i++) {

            $user = new User();

            $user   ->setUsername('user'. $i)
                    ->setEmail('user'. $i .'@user.fr')
                    ->setPassword($this->encoder->encodePassword($user, 'password'))
                    ->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
