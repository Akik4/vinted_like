<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\ArticleService;

class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog')]
    public function index(ArticleService $articleDB): Response
    {
        return $this->render('catalog/index.html.twig', [
            'articles' => $articleDB->getAllArticlesFromDB(),
        ]);
    }
}
