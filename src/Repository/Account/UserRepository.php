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
    private $connection;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    public function getLastAndFirstId(){
        $sql = "
            SELECT max(User.id) AS 'maxValue', min(User.id) AS 'minValue'
            FROM User
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetch();

    }

    /**
     * @param $user
     * @param $id
     * @return bool
     */
    public function isFollow($user, $id)
    {
        $currentUser = $this->find($id);

        if (!is_null($user) && !is_null($currentUser)) {
            return array_key_exists($user->getId(), $currentUser->getSubscribeTo());
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

        $sql = '
            SELECT JSON_LENGTH(user.followed_by) AS "nb followers"
            FROM user
            WHERE user.id = :id 
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute(['id' => $id]);

        return $stmt->fetchColumn();
    }

    /**
     * @param $id
     * @return mixed[]
     * @throws DBALException
     */
    public function howManySubscribe($id)
    {

        $sql = '
            SELECT JSON_LENGTH(user.subscribe_to) AS "nb abonnements"
            FROM user
            WHERE user.id = :id 
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute(['id' => $id]);

        return $stmt->fetchColumn();
    }

    public function followIdList(int $id)
    {
        $usersIdArray = $this->createQueryBuilder('u')
            ->select('u.followedBy')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult();

        return array_keys($usersIdArray[0]['followedBy']);
    }

    public function followList(int $id)
    {
        // QUERY DQL

        $usersId = $this->onlyFollowersId($id);

        return $this->createQueryBuilder('u')
                    ->select('u.username, u.avatar')
                    ->where('u.id IN (:usersId)')
                    ->setParameter('usersId', $usersId)
                    ->getQuery()
                    ->getResult();

    }

    public function onlyFollowersId($id)
    {
        $usersIdArray = $this->createQueryBuilder('u')
            ->select('u.followedBy')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult();

        return array_keys($usersIdArray[0]['followedBy']);
    }

}
