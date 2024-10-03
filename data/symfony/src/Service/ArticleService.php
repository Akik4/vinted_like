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
                    'id'        => $article->getId(),
                    'name'      => $article->getName(),
                    'price'     => $article->getPrice(),
                    'fav'       => $article->getFav(),
                    'content'   => $article->getContent(),
                    'sender'    => $article->getSeller()->getId(),
                    'id'        => $article->getId(),
                    'img_url'   => $article->getImgUrl()
                ];
            }
        }

        return $universes;
    }

    function getFormArticle($id) : ?Article{
        if($id <= 0) {
            return $article = new Article();
        } 
        
        if($article = $this->entityManager->getRepository(Article::class)->find($id)){
            return $article;
        }
        return NULL;
    }

    function deleteArticle($task){
        $this->entityManager->remove($task);
        $this->entityManager->flush();    
    }

    function updateFavorisState($task, $user) {
        $favoris = $this->favorisRepo->findOneByArticle($task->getId());
            if(!$favoris){
                $favoris = new Favoris();
                $favoris->setArticle($task);
                $favoris->setUser($user);
                $this->entityManager->persist($favoris);
                $task->setFav($task->getFav() + 1);
                $this->entityManager->persist($task);
            } else {
                $this->entityManager->remove($favoris);
                $task->setFav($task->getFav() - 1);
                $this->entityManager->persist($task);   
            }
            $this->entityManager->flush();
    }

    function buyArticle($task, $user){
        $task->setBuy(true);
        $task->setBuyer($user);
        $this->entityManager->persist($task);            
        $this->entityManager->flush();
    }

    function updateArticle($task, $user){
        $task->setSeller($user);
        $this->entityManager->persist($task);            
        $this->entityManager->flush();
    }

    function handleRequest($form, $user_id) : Bool {
        $task = $form->getData();

        if($form->has('delete') && $form->get('delete')->isClicked()){
            $this->deleteArticle($task);
            return true;
        } 
    
        if ($form->has('favoris') && $form->get('favoris')->isClicked()){
            $this->updateFavorisState($task, $user_id);
            return true;
        }
        
        if ($form->has('buy') && $form->get('buy')->isClicked()){
            $this->buyArticle($task, $user_id);
            return true;
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateArticle($task, $user_id);
            return true;
        }

        return false;
    }
}