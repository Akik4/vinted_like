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
    #[Route('/test/{id}', name: 'app_test')]
    public function index(String $id,Request $request, EntityManagerInterface $entityManager): Response
    {
        if($id <= 0) {
            $article = new Article();
        } else {
            $article = $entityManager->getRepository(Article::class)->find($id);
        }
        
        $form = $this->createForm(ArticleFormType::class, $article);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            if( $form->get('delete')->isClicked()){
                $entityManager->remove($task);
            } else {
                $entityManager->persist($task);
            }
            $entityManager->flush();    
            return $this->redirectToRoute('app_catalog');
        }
        return $this->render('test/new.html.twig', [
            'form' => $form,
        ]);
    }
}
