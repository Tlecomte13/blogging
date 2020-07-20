<?php

namespace App\Controller\Ratchet;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebsocketController extends AbstractController
{
    /**
     * @Route("/websocket", name="websocket")
     */
    public function index()
    {
        return $this->render('ratchet/websocket/index.html.twig', [
            'controller_name' => 'WebsocketController',
        ]);
    }
}
