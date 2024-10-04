<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MessageController;

use App\Entity\User;
use App\Entity\Notify;


class NotifyService {

    function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    function SendNotificationTo($content, $user)  {
        $notify = new Notify();
        $notify->setContent($content);
        $notify->setReceiver($user);

        $this->entityManager->persist($notify);
        $this->entityManager->flush();
    }
}