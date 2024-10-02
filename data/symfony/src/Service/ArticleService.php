<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Article;

class ArticleService{
    function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
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
}