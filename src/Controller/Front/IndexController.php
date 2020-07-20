<?php

namespace App\Controller\Front;

use App\Service\MercureCookieGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index()
    {

        return $this->render('Front/index.html.twig', [
            'controller_name' => 'IndexController'
        ]);

    }
}
