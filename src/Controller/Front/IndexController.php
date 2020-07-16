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
     * @param MercureCookieGeneratorService $cookieGenerator
     * @return Response
     */
    public function index(MercureCookieGeneratorService $cookieGenerator)
    {
        $token = $cookieGenerator->generate($this->getUser());

        $response =  $this->render('Front/index.html.twig', [
            'controller_name' => 'IndexController',
            'subscriber_token' =>  $token['twig']
        ]);

        $response->headers->set(
            'TAGROSSEMER',
            $token['all']
        );

        return $response;
    }

    /**
     * @Route("/push/{user}", name="push")
     * @param PublisherInterface $publisher
     * @return JsonResponse
     */
    public function push(PublisherInterface $publisher)
    {

        $update = new Update('/ping',
            json_encode(['message' => 'Hello World!']),
        true
        );

        $publisher($update);

        return $this->json('Done');

    }
}
