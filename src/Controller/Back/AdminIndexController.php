<?php

namespace App\Controller\Back;

use App\Service\MercureCookieGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class AdminIndexController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_homepage")
     * @return Response
     */
    public function index()
    {
        return $this->render('Back/index.html.twig');
    }
}
