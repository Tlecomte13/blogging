<?php


namespace App\Tests\Entity\Account;


use PHPUnit\Framework\TestCase;
use App\Entity\Account\User;

class UserTest extends TestCase
{
    CONST usernameValidRegex = "user";
    CONST usernameInvalidRegex = "user~-è784^";
    CONST regexUsername = "/^[a-zA-Z0-9]+$/i";

    public function testWithValidUsernameRegex() {
        $user = new User();

        $user   ->setUsername(self::usernameValidRegex)
                ->setEmail('user@user.fr')
                ->setPassword('password')
                ->setRoles(['ROLE_USER']);

        $this->assertRegExp(self::regexUsername, $user->getUsername());
    }

    public function testWithInvalidUsernameRegex() {
        $user = new User();

        $user   ->setUsername(self::usernameInvalidRegex)
                ->setEmail('user@user.fr')
                ->setPassword('password')
                ->setRoles(['ROLE_USER']);

        $this->assertNotRegExp(self::regexUsername, $user->getUsername());
    }

    public function testAddFollowedBy() {
        $date = new \DateTime();
        $newDate = $date->format('Y-m-d H:i:s');

        $user = new User();

        $user->addFollowedBy(10);

        $arr = [10 => ['followedAt' => $newDate]];

        $this->assertSame($arr, $user->getFollowedBy());

    }

    public function testAddSubscribeTo() {
        $date = new \DateTime();
        $newDate = $date->format('Y-m-d H:i:s');

        $user = new User();

        $user->addSubscribeTo(10);

        $arr = [10 => ['subscribeAt' => $newDate]];

        $this->assertSame($arr, $user->getSubscribeTo());

    }

    public function testRemoveSubscribeTo() {
        $date = new \DateTime();
        $newDate = $date->format('Y-m-d H:i:s');

        $arr = [10 => ['subscribeAt' => $newDate], 11 => ['subscribeAt' => $newDate]];
        $result = [10 => ['subscribeAt' => $newDate]];

        $user = new User();
        $user->setSubscribeTo($arr);

        $user->removeSubscribeTo(11);

        $this->assertSame($result, $user->getSubscribeTo());

    }

    public function testRemoveFollowedAt() {
        $date = new \DateTime();
        $newDate = $date->format('Y-m-d H:i:s');

        $arr = [10 => ['followedAt' => $newDate], 11 => ['followedAt' => $newDate]];
        $result = [10 => ['followedAt' => $newDate]];

        $user = new User();
        $user->setFollowedBy($arr);

        $user->removeFollowedBy(11);

        $this->assertSame($result, $user->getFollowedBy());

    }

}