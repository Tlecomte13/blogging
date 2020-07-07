<?php

namespace App\Repository\Account;

use App\Entity\Account\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
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
     * @return void
     * @throws DBALException
     */
    public function howManyFollow($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT JSON_LENGTH(user.followed_by) AS "nb followers"
            FROM user
            WHERE user.id = :id 
        ';

        $stmt = $conn->prepare($sql);

        $stmt->execute(['id' => $id]);

        return $stmt->fetchColumn();
    }

    /**
     * @param $id
     * @return mixed[]
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

    public function followList(int $id)
    {

        $usersIdArray = $this->createQueryBuilder('u')
                             ->select('u.followedBy')
                             ->where('u.id = :id')
                             ->setParameter('id', $id)
                             ->setMaxResults(1)
                             ->getQuery()
                             ->getArrayResult();

        $usersId = array_keys($usersIdArray[0]['followedBy']);

        return $this->createQueryBuilder('u')
                    ->select('u.username, u.avatar')
                    ->where('u.id IN (:usersId)')
                    ->setParameter('usersId', $usersId)
                    ->getQuery()
                    ->getResult();
}

}
