<?php

namespace App\Controller\Front\User;

use App\Entity\Account\User;
use App\Repository\Account\UserRepository;
use App\Service\NotificationService;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    /**
     * @Route("/users", name="users_list")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function users(UserRepository $userRepository)
    {

        return $this->render('front/user/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{user}", name="user_id")
     * @param User $user
     * @param UserRepository $userRepository
     * @return Response
     * @throws DBALException
     */
    public function user($user, UserRepository $userRepository)
    {

        $user = $userRepository->findOneBy([
            'username' => $user
        ]);



        return $this->render('front/user/id.html.twig', [
            'user'              => $user,
            'follow'            => $userRepository->isFollow($user, $this->user->getId()),
            'howManyFollow'     => $userRepository->howManyFollow($user->getId()),
            'howManySubscribe'  => $userRepository->howManySubscribe($user->getId())
        ]);
    }
}
