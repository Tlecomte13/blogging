<?php


namespace App\Controller\Back\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     * @return Response
     */
    public function users()
    {
        return $this->render('Back/User/users.html.twig');
    }
}