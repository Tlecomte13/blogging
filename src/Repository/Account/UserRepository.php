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



//        $sql = "
//                SELECT user.followed_by
//                FROM user
//                WHERE user.id = :id
//                LIMIT 1
//        ";
//
//        $stmt = $this->connection->prepare($sql);
//        $stmt->execute(['id' => $id]);
//
//        $json = json_decode($stmt->fetchColumn(), true);
//
//        $usersId = implode(',', array_keys($json));
//        $userParams = implode(',', array_fill(0, count(array_keys($json)), '?'));
//
//        dump($usersId);
//
//        $users = "
//                    SELECT user.username
//                    FROM user
//                    WHERE user.id IN (:usersId)
//        ";
//
//        $req = $this->connection->prepare($users);
//        $req->execute([
//            'usersId'       => $usersId
//        ]);
//
//
//        return $req->fetchAll();



    }

}
