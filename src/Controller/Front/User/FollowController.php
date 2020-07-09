<?php

namespace App\Controller\Front\User;

use App\Repository\Account\UserRepository;
use Datetime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
     * @throws Exception
     */
    public function add(Request $request, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $userFollow = $userRepository->find($request->get('userTwo'));

        $followedBy = $userFollow->getFollowedBy();
        $followedBy[$this->user->getId()] = ['followedAt' => new Datetime()];

        $userFollow->setFollowedBy($followedBy);

        $user = $userRepository->find($this->user->getId());
        $user->addSubscribeTo($request->get('userTwo'));

        $manager->persist($userFollow);
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
        $userUnFollow = $userRepository->find($request->get('user'));

        $arr = $userUnFollow->getFollowedBy();

        unset($arr[$this->user->getId()]);

        $userUnFollow->setFollowedBy($arr);

        $user = $userRepository->find($this->user->getId());
        $user->removeSubscribeTo($request->get('user'));

        $manager->persist($userUnFollow);
        $manager->persist($user);
        $manager->flush();

        return new Response('ok');
    }

    /**
     * @Route("/follow/{id}", name="follow_id")
     * @param $id
     * @param UserRepository $userRepository
     * @return Response
     */
    public function id($id, UserRepository $userRepository)
    {
        $follows = $userRepository->followList($id);

        return $this->render('Front/User/Stats/Follow/id.html.twig', [
            'follows'   => $follows,
            'user'      => $userRepository->find($id)
        ]);
    }

    /**
     * @Route("/follow/search", name="follow_search")
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function search(UserRepository $userRepository, Request $request)
    {
        $follows = $userRepository->followList('3103', $request->get('email'));

        return new Response('ok', '200', ['follows' => $follows]);
    }
}
