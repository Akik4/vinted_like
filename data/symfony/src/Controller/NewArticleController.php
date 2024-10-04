<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Pontedilana\PhpWeasyPrint\Pdf;
use Pontedilana\WeasyprintBundle\WeasyPrint\Response\PdfResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Service\ArticleService;
use App\Service\NotifyService;
use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use Twig\Environment;



class NewArticleController extends AbstractController
{
    #[Route('/create-article', 'app_articlec')]
    public function emptyRedirect()
    {
        return $this->redirectToRoute('app_article_creation', ["id" => 0]);
    }

    #[Route('/addtofavorite/{articleid}', name: "app_add_to_favorite")]
    public function addToFavorite(int $articleid, ArticleService $articleService, UserInterface $user)
    {
        $article = $articleService->getFormArticle($articleid);
        $articleService->updateFavorisState($article, $user);
        return $this->redirectToRoute('app_catalog');
    }

    #[Route('/buy/{article}', name: "app_buy")]
    public function buyArticle(int $article, ArticleService $articleService, UserInterface $user, NotifyService $notify)
    {
        $article = $articleService->getFormArticle($article);
        $articleService->buyArticle($article, $user);
        $notify->SendNotificationTo(sprintf('%s à acheté votre article : %s', $user->GetUsername(), $article->GetName()), $article->GetSeller());

        return $this->redirectToRoute('app_catalog');
    }

    #[Route('/export', name: "app_export")]
    public function ExportToPDF(ArticleService $articleService, UserInterface $user, Pdf $weasyPrint,): Response
    {
        $html = $this->render('account_gestion/index.html.twig', [
            'articles' => $user->getArticles(),               
            'buyed_articles' => $user->getArticlesBuyed(),               
        ]);
        $pdfContent = $weasyPrint->getOutputFromHtml($html);

        return new PdfResponse(
            content: $pdfContent,
            fileName: 'file.pdf',
            contentType: 'application/pdf',
            contentDisposition: ResponseHeaderBag::DISPOSITION_INLINE,
            // or download the file instead of displaying it in the browser with
            // contentDisposition: ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            status: 200,
            headers: []
        );   
    }


    #[Route('/create-article/{id}', name: 'app_article_creation')]
    public function index(String $id, Request $request, UserInterface $user, ArticleService $articleService): Response
    {

        if (!$article = $articleService->getFormArticle($id)) {
            return $this->redirectToRoute('app_article_creation', ['id' => 0]);
        }

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);

        if ($articleService->handleRequest($form, $user)) {
            return $this->redirectToRoute('app_catalog');
        }

        return $this->render('test/new.html.twig', [
            'form' => $form,
        ]);
    }
}
