<?php

namespace App\Repository\Account;

use App\Entity\Account\Follow;
use App\Entity\Account\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Follow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Follow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Follow[]    findAll()
 * @method Follow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follow::class);
    }

    public function userIsFollow($userOne, $userTwo)
    {
        return $this->createQueryBuilder('f')
                    ->Where('f.userOne = :user_one AND f.userTwo = :user_two')
                    ->orWhere('f.userOne = :user_two AND f.userTwo = :user_one')
                    ->setParameters([
                        'user_one' => $userOne,
                        'user_two' => $userTwo
                    ])
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function howManyFollow($user)
    {
        return $this->createQueryBuilder('f')
                ->select('count(f.id)')
                ->Where('f.userTwo = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult()
        ;
    }
}
