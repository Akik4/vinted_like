<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Article;

class ArticleService{
    function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    function getAllArticlesFromDB(){
        
        return $this->entityManager->getRepository(Article::class)->findAll();
    }
}