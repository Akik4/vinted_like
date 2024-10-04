<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Service\ArticleService;

class ProductPageController extends AbstractController
{
    #[Route('/productPage/{id}', name: 'app_product_page')]
    public function index(string $id, ArticleService $articleService, UserInterface $user): Response
    {
        
        return $this->render('product_page/index.html.twig', [
            'controller_name' => 'ProductPageController',
            'article' => $articleService->getFormArticle($id),
        ]);
    }
}
