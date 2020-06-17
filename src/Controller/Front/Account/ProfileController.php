<?php

namespace App\Controller\Front\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function account()
    {
        return $this->render('Front/Account/Profile/account.html.twig');
    }
}
