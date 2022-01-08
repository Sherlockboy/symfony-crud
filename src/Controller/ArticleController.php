<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/articles", name="index_articles")
     * @Method({"GET"})
     */
    public function index()
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $this->repository->findAll()
        ]);
    }

    /**
     * @Route("/articles/create", name="create_article")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $form = $this->generateArticleForm(new Article());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('index_articles');
        }

        return $this->render('articles/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create article'
        ]);
    }

    /**
     * @Route("/articles/{id}")
     * @Method({"GET"})
     */
    public function show(int $id)
    {
        return $this->render('articles/show.html.twig', [
            'article' => $this->repository->find($id)
        ]);
    }

    /**
     * @Route("/articles/update/{id}", name="update_article")
     * @Method({"GET", "POST"})
     */
    public function update(Request $request, int $id)
    {
        $form = $this->generateArticleForm($this->repository->find($id));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('index_articles');
        }

        return $this->render('articles/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Update article'
        ]);
    }

    /**
     * @Route("/articles/delete/{id}")
     * @Method({"DELETE"})
     */
    public function destroy(int $id)
    {
        $article = $this->repository->find($id);
        $this->entityManager->remove($article);
        $this->entityManager->flush();

        (new Response())->send();
    }

    private function generateArticleForm(Article $article)
    {
        return $this->createFormBuilder($article)
            ->add('title', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('body', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Update',
                'attr' => ['class' => 'btn btn-success mt-2']
            ])
            ->getForm();
    }
}
