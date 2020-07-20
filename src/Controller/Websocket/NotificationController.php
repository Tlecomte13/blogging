<?php

namespace App\Controller\Websocket;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/websocket", name="websocket")
     */
    public function index()
    {
        return $this->render('ratchet/websocket/index.html.twig', [
            'ws_url' => 'localhost:8080',
        ]);
    }
}
