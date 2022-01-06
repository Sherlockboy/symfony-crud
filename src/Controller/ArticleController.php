<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles")
     * @Method({"GET"})
     */
    public function index()
    {
        $articles = ['Article 1', 'Article 2', 'Article 3'];

        return $this->render('articles/index.html.twig', compact('articles'));
    }
}
