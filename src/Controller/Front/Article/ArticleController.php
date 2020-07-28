<?php


namespace App\Controller\Front\Article;


use App\Entity\Account\User;
use App\Entity\Article\Article;
use App\Entity\Article\Comment;
use App\Form\Article\ArticleType;
use App\Form\Article\CommentType;
use App\Repository\Article\ArticleRepository;
use App\Repository\Article\CommentRepository;
use App\Service\Image\UploadedBase64FileService;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/articles/add", name="article_add")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addArticle(Request $request, EntityManagerInterface $manager)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $slugger = new Slugify();
                $safeFilename = $slugger->slugify($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();


                try {
                    $image->move($this->getParameter('avatar_directory'), $newFilename);
                } catch (FileException $e) {

                }

                $article->setImage($newFilename);
            }

            $article->setTags(null);
            $article->setCreatedBy($this->getUser());
            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès:</strong> Votre compte à bien été modifié"
            );

        }

        return $this->render('Front/Article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{username}/{slug}", name="article_id")
     * @param $username
     * @param Article $slug
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param EntityManagerInterface $manager
     * @param UploadedBase64FileService $base64FileService
     * @return Response
     * @throws DBALException
     */
    public function articleId($username, $slug, ArticleRepository $articleRepository, Request $request,
                              CommentRepository $commentRepository, EntityManagerInterface $manager,
                              UploadedBase64FileService $base64FileService)
    {

        $article = $articleRepository->findOneBy([
            'slug' => $slug
        ]);

        $comment = new Comment();


        if ($request->get('content')) {

            $content = $request->get('content');


            $comment->setArticle($article)
                    ->setCreatedBy($this->getUser())
                    ->setContent($content);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre commentaire a bien été publié !'
            );

        }

        return $this->render('Front/Article/id.html.twig', [
            'article' => $articleRepository->findArticleWithUserAndSlug($username, $slug),
            'nbArticle' => $articleRepository->howManyArticles($username),
            'comment' => $commentRepository->findBy([
                'article' => $article
            ])
        ]);
    }
}