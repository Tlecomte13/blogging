<?php

namespace App\Repository\Article;

use App\Entity\Article\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    private $connection;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    /**
     * @param $username
     * @param $slug
     * @return Article[] Returns an array of Article objects
     * @throws DBALException
     */
    public function findArticleWithUserAndSlug($username, $slug)
    {
        $sql = '
                SELECT article.title, article.content, article.tags, article.created_at, article.image, article.slug,
                JSON_LENGTH(user.subscribe_to) AS "nbSubscribe", JSON_LENGTH(user.followed_by) AS "nbFollow",
                user.username, user.avatar
                FROM article
                JOIN user ON article.created_by_id = user.id
                WHERE article.slug = :slug
                AND user.username = :id
    ';


        $stmt = $this->connection->prepare($sql);

        $stmt->execute(['id' => $username, 'slug' => $slug]);

        return $stmt->fetch();
    }

    /**
     * @param $username
     * @return Article[] Returns an array of Article objects
     * @throws DBALException
     */
    public function howManyArticles($username)
    {
        $sql = "
                SELECT COUNT(*) AS 'nbArticle'
                FROM article
                JOIN user ON article.created_by_id = user.id
                WHERE user.username = :username
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute(['username' => $username]);

        return $stmt->fetchColumn();
    }

    /**
     * @param $id
     * @return mixed[]
     * @throws DBALException
     */
    public function getArticles($id)
    {
        $sql = "
                SELECT  
                    article.title ,article.created_at, 
                    article.slug, user.username 
                FROM article
                    JOIN user ON article.created_by_id = user.id              
                WHERE user.id = :id  
        ";
        $stmt = $this->connection->prepare($sql);

        $stmt->execute(['id' => $id]);

        $arr = $stmt->fetchAll();

        return [
            'nbArticle' => count($arr),
            'articles' => $arr
        ];
    }
}
