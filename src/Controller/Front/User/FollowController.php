<?php

namespace App\Controller\Front\User;

use App\Repository\Account\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class FollowController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    /**
     * @Route("/follow/add", name="follow_add")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $user = $userRepository->find($this->user->getId());

        $user->addFollow($request->get('userTwo'));
        $manager->persist($user);
        $manager->flush();

        return new Response('ok');
    }

    /**
     * @Route("/follow/remove", name="follow_remove")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function remove(Request $request, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $user = $userRepository->find($this->user->getId());

        $user->removeFollow($request->get('user'));
        $manager->persist($user);
        $manager->flush();

        return new Response('ok');
    }

}
