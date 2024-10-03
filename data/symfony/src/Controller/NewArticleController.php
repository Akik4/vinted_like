<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Service\ArticleService;
use App\Entity\Favoris;
use App\Repository\FavorisRepository;



class NewArticleController extends AbstractController
{
    #[Route('/create-article')]
    public function emptyRedirect() {
        return $this->redirectToRoute('app_article_creation', ["id"=> 0]);
    }

    #[Route('/create-article/{id}', name: 'app_article_creation')]
    public function index(String $id,Request $request, UserInterface $user, ArticleService $articleService): Response
    {
        
        $article = $articleService->getFormArticle($id);
        
        $form = $this->createForm(ArticleFormType::class, $article);
        
        $form->handleRequest($request);

        if($articleService->handleRequest($form, $user)){
            return $this->redirectToRoute('app_catalog');
        }

        return $this->render('test/new.html.twig', [
            'lastusername' => $article->getSeller()->getId(),
            'form' => $form,
        ]);
    }
}
