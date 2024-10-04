<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(UserInterface $user): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $user,
            'notifications' => $user->getNotifies()
        ]);
    }
}
