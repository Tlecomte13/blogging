<?php

namespace App\Controller\Front\User;

use App\Repository\Account\UserRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/user/{id}", name="user_id")
     * @param $id
     * @param UserRepository $userRepository
     * @return Response
     * @throws DBALException
     */
    public function user($id, UserRepository $userRepository)
    {
        $user = null;

        if (!empty($this->user)) {
            $user = $this->user->getId();
        }

        return $this->render('front/user/id.html.twig', [
            'user'              => $userRepository->find($id),
            'follow'            => $userRepository->isFollow($user, $id),
            'howManyFollow'     => $userRepository->howManyFollow($id),
            'howManySubscribe'  => $userRepository->howManySubscribe($id)
        ]);
    }
}
