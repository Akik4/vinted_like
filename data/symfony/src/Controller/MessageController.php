<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Message;
use App\Entity\User;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message_home')]
    public function home_message(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    { 
        $conversations = $entityManager->getRepository(Message::class)->findConversations($user->getID())->getResult();

        return $this->render('message/home.html.twig', [
            'userid' => $user->getId(),
            'conversations_id' => $conversations
        ]);
    }

    #[Route('/message/{id}', name: 'app_message')]
    public function index(String $id ,Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    { //String addition
        $form = $this->createFormBuilder(null)
        ->add('message', TextType::class)
        ->add('send', SubmitType::class)
        ->getForm();
        
        $form->handleRequest($request);
        $convID = $user->getId() > $id ? $id . $user->getId() : $user->getId() . $id;
        $messages = $entityManager->getRepository(Message::class)->findMessages($convID)->getResult();

        if($form->get('send')->isClicked()){
            $message = new Message();

            $message->setContent($form->getData()['message']);
            $message->setUserID($user);
            $message->setUser2($entityManager->getRepository(User::class)->find($id));
            $convID = $user->getId() > $id ? $id. $user->getId() : $user->getId(). $id;
            $message->setConversationId($convID);

            $entityManager->persist($message);            
            $entityManager->flush();

            return $this->redirectToRoute('app_message', ['id' => $id]);
        }
        
        
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'form' => $form,
            'messages' => $messages
        ]);
    }
}
