<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Article;
use App\Form\ArticleFormType;


class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();    

            return $this->redirectToRoute('app_home');  
        }

        return $this->render('test/new.html.twig', [
            'form' => $form,
        ]);
    }
}
