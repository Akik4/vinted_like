<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Message;
use App\Entity\User;
use App\Service\MessageService;

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
    public function index(String $id, Request $request,UserInterface $user, MessageService $messageService): Response
    { //String addition
        
        $form = $this->createFormBuilder(null)
            ->add('message', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre message...'
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('send', SubmitType::class)
            ->getForm();


        $messages = $messageService->getMessages($id, $user);

        $form->handleRequest($request);
        
        if($messageService->handleMessage($form, $id, $user)){
            return $this->redirectToRoute('app_message', ['id' => $id]);
        }

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'form' => $form,
            'messages' => $messages,
        ]);
    }
}
