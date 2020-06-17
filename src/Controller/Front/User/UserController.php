<?php

namespace App\Controller\Front\User;

use App\Repository\Account\FollowRepository;
use App\Repository\Account\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
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
     * @Route("/user/{id}", name="user_id")
     * @param $id
     * @param UserRepository $userRepository
     * @param FollowRepository $followRepository
     * @return Response
     */
    public function user($id, UserRepository $userRepository, FollowRepository $followRepository)
    {
        $user = $userRepository->findBy(['email' => $this->getUser()->getUsername()]);

        return $this->render('front/user/id.html.twig', [
            'user'          => $userRepository->find($id),
            'follow'        => $followRepository->userIsFollow($user, $id),
            'howManyFollow' => $followRepository->howManyFollow($id)
        ]);
    }
}
