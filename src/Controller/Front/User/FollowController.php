<?php

namespace App\Controller\Front\User;

use App\Entity\Account\Follow;
use App\Repository\Account\FollowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowController extends AbstractController
{
    /**
     * @Route("/follow/add", name="follow_add")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param FollowRepository $followRepository
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $manager, FollowRepository $followRepository)
    {
        $userOne = $request->get('userOne');
        $userTwo = $request->get('userTwo');

        if (empty($followRepository->findBy(['userOne' => $userOne, 'userTwo' => $userTwo]))) {
            $follow = new Follow();

            $follow ->setUserOne($userOne)
                ->setUserTwo($userTwo);

            $manager->persist($follow);
            $manager->flush();

            return new Response('ok');
        } else {
            return new Response('Déjà follow');
        }
    }

    /**
     * @Route("/follow/remove", name="follow_remove")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param FollowRepository $followRepository
     * @return Response
     */
    public function remove(Request $request, EntityManagerInterface $manager, FollowRepository $followRepository)
    {
        $userOne = $request->get('userOne');
        $userTwo = $request->get('userTwo');
        $follow  = $followRepository->findOneBy(['userOne' => $userOne, 'userTwo' => $userTwo]);

        $manager->remove($follow);
        $manager->flush();

        return new Response('ok');
    }

}
