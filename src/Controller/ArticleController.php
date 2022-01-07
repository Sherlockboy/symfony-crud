<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $repository;
    private $entityManager;
    
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->repository = $doctrine->getRepository(Article::class);
    }
    
    /**
     * @Route("/articles")
     * @Method({"GET"})
     */
    public function index()
    {
        $articles = $this->repository->findAll();

        return $this->render('articles/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/articles/{id}")
     */
    public function show(int $id)
    {
        $article = $this->repository->find($id);

        return $this->render('articles/show.html.twig', compact('article'));
    }

    /**
     * @Route("/articles/save")
     * @Method({"GET"})
     */
    public function save() : Response
    {   
        $article = new Article();
        $article->setTitle('Article 4');
        $article->setBody('Body for Article 4');

        $this->entityManager->persist($article);

        $this->entityManager->flush();

        return new Response("Saved article with id=" . $article->getId());
    }
}
