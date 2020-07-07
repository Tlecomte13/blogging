<?php

namespace App\DataFixtures;

use App\Entity\Account\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
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

        $admin->setUsername('admin')
            ->setEmail('admin@admin.fr')
              ->setPassword($this->encoder->encodePassword($admin, 'password'))
              ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);


        for ($i = 0; $i < 1000; $i++) {

            $user = new User();

            $user   ->setUsername('user'. $i)
                    ->setEmail('user'. $i .'@user.fr')
                    ->setPassword($this->encoder->encodePassword($user, 'password'))
                    ->setRoles(['ROLE_USER']);
                    if ($i === 0) {
                        for ($j = 4104; $j < 5000; $j++) {
                            $arr = $user->getFollowedBy();
                            $arr[$j] = ['followedAt' => new \Datetime()];
                            $user->setFollowedBy($arr);
                        }

                    }




            $manager->persist($user);

        }





        $manager->flush();
    }
}
