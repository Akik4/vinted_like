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



class TestController extends AbstractController
{
    #[Route('/test/{id}', name: 'app_test')]
    public function index(String $id,Request $request, UserInterface $user, ArticleService $articleService): Response
    {
        
        $article = $articleService->getFormArticle($id);
        
        $form = $this->createForm(ArticleFormType::class, $article);
        
        $form->handleRequest($request);

        if($articleService->handleRequest($form, $this)){
            return $this->redirectToRoute('app_catalog');
        }

        return $this->render('test/new.html.twig', [
            'lastusername' => $user->getId(),
            'form' => $form,
        ]);
    }
}
