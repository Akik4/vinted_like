<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Service\ArticleService;

class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog')]
    public function index(ArticleService $articleDB, UserInterface $user): Response
    {
        return $this->render('catalog/index.html.twig', [
            'articles' => $articleDB->getAllArticlesFromDB(),
            'userId' => $user->getId()
        ]);
    }
    
    #[Route('/catalog/{filtre}', name: 'app_filter')]
    public function filter(ArticleService $articleDB, UserInterface $user, $filtre): Response
    {
        return $this->render('catalog/index.html.twig', [
            'articles' => $articleDB->getAllArticlesFromDB(),
            'userId' => $user->getId(),
            'filter' => $filtre
        ]);
    }
}
