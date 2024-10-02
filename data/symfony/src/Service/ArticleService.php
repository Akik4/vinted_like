<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Article;
use App\Repository\FavorisRepository;
use App\Entity\Favoris;

class ArticleService{
    function __construct(EntityManagerInterface $entityManager, FavorisRepository $favorisRepo) {
        $this->entityManager = $entityManager;
        $this->favorisRepo = $favorisRepo;
    }

    function getAllArticlesFromDB(){
        
        $articles = $this->entityManager->getRepository(Article::class)->findAll();
        $universes = [];
        foreach($articles as $article)
        {
            if(!$article->isBuy()){
                $universes[] = [
                    'id' => $article->getId(),
                    'name' => $article->getName(),
                    'price' => $article->getPrice(),
                    'fav' => $article->getFav(),
                    'content' => $article->getContent(),
                ];
            }
        }

        return $universes;
    }

    function getFormArticle($id) : Article{
        if($id <= 0) {
            return $article = new Article();
        } 

        return $article = $this->entityManager->getRepository(Article::class)->find($id);
    }

    function deleteArticle($task){
        $this->entityManager->remove($task);
        $this->entityManager->flush();    
    }

    function updateFavorisState($task) {
        $favoris = $this->favorisRepo->findOneByArticle($task->getId());
            if(!$favoris){
                $favoris = new Favoris();
                $favoris->setArticle($task);
                $this->entityManager->persist($favoris);
            } else {
                $this->entityManager->remove($favoris);
            }
            $this->entityManager->flush();
    }

    function buyArticle($task){
        $task->setBuy(true);
        $this->entityManager->persist($task);            
        $this->entityManager->flush();
    }

    function updateArticle($task){
        $this->entityManager->persist($task);            
        $this->entityManager->flush();
    }

    function handleRequest($form) : Bool {
        $task = $form->getData();

        if( $form->get('delete')->isClicked()){
            $this->deleteArticle($task);
            return true;
        } 
    
        if ($form->get('favoris')->isClicked()){
            $this->updateFavorisState($task);
            return true;
        }
        
        if ($form->get('buy')->isClicked()){
            $this->buyArticle($task);
            return true;
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateArticle($task);
            return true;
        }

        return false;
    }
}