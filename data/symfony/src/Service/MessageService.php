<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MessageController;
use App\Entity\Message;
use App\Entity\User;
use App\Service\NotifyService;


class MessageService {
    
    function __construct(EntityManagerInterface $entityManager, NotifyService $notify){
        $this->entityManager = $entityManager;
        $this->notify = $notify;
    }

    function getMessages($id, $user) : Array {
        $convID = $user->getId() > $id ? $id . $user->getId() : $user->getId() . $id;
        return $this->entityManager->getRepository(Message::class)->findMessages($convID)->getResult();
    }

    function handleMessage($form, $id, $user) : Bool {
        $convID = $user->getId() > $id ? $id . $user->getId() : $user->getId() . $id;

        if ($form->get('send')->isClicked()) {
            $message = new Message();

            $message->setContent($form->getData()['message']);
            $message->setUserID($user);
            $message->setUser2($this->entityManager->getRepository(User::class)->find($id));
            $message->setConversationId($convID);
            $this->notify(sprintf('%s vous à envoyé un message', $this->entityManager->getRepository(User::class)->find($id)));

            $this->entityManager->persist($message);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }
}