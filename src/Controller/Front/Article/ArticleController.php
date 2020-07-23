<?php


namespace App\Controller\Front\Article;


use App\Entity\Account\User;
use App\Entity\Article\Article;
use App\Entity\Article\Comment;
use App\Form\Article\CommentType;
use App\Repository\Article\ArticleRepository;
use App\Repository\Article\CommentRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function articles(ArticleRepository $articleRepository)
    {
        return $this->render('Front/Article/articles.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/article/{username}/{slug}", name="article_id")
     * @param $username
     * @param Article $slug
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param CommentRepository $commentRepository
     * @return Response
     * @throws DBALException
     */
    public function articleId($username, $slug, ArticleRepository $articleRepository, Request $request,
                              EntityManagerInterface $manager, CommentRepository $commentRepository)
    {

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        return $this->render('Front/Article/id.html.twig', [
            'article' => $articleRepository->findArticleWithUserAndSlug($username, $slug),
            'nbArticle' => $articleRepository->howManyArticles($username),
            'comment' => $commentRepository->findAll(),
            'form' => $form->createView()
        ]);
    }
}