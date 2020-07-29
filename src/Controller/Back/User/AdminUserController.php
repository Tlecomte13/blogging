<?php


namespace App\Controller\Back\User;

use App\Repository\Account\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function users(UserRepository $userRepository)
    {

        return $this->render('Back/User/users.html.twig', [
            'users' => $this->json($userRepository->getAllJson())
        ]);
    }
}