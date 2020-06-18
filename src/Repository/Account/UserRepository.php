<?php

namespace App\Repository\Account;

use App\Entity\Account\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $user
     * @param $id
     * @return bool
     */
    public function isFollow($user, $id)
    {
        if (!is_null($user)) {
            $user = $this->find($user);

            if (!empty($user->getSubscribeTo())) {
                return array_key_exists($id, $user->getSubscribeTo());
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return int
     */
    public function howManyFollow($id)
    {
        $query = $this->createQueryBuilder('u')
                      ->select('u.followedBy')
                      ->where('u.id = :id')
                      ->setParameter('id', $id)
                      ->getQuery()
                      ->getResult()
        ;

        return count($query[0]['followedBy']);
    }

    /**
     * @param $id
     * @return int
     */
    public function howManySubscribe($id)
    {
        $query = $this->createQueryBuilder('u')
                        ->select('u.subscribeTo')
                        ->where('u.id = :id')
                        ->setParameter('id', $id)
                        ->getQuery()
                        ->getResult()
        ;

        return count($query[0]['subscribeTo']);
    }

    public function followList($id)
    {
        $query = $this->createQueryBuilder('u')
                        ->select('u.followedBy')
                        ->where('u.id = :id')
                        ->setParameter('id', $id)
                        ->getQuery()
                        ->getResult()
        ;
        $arr = [];
        foreach ($query[0]['followedBy'] as $key => $val) {
            $arr[] = $this->createQueryBuilder('u')
                            ->select('u.email, u.createdAt')
                            ->where('u.id = :key')
                            ->setParameter('key', $key)
                            ->getQuery()
                            ->getArrayResult()
            ;

        }
        return $arr;


    }

}
