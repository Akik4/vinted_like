<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


use App\Service\ArticleService;

class AccountGestionController extends AbstractController
{
    #[Route('/account/gestion', name: 'app_account_gestion')]
    public function index(UserInterface $user): Response
    {
        return $this->render('account_gestion/index.html.twig', [
            'controller_name' => 'AccountGestionController',
            'articles' => $user->getArticles(),               
        ]);
    }
}
