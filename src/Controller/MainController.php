<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="default" )
     * @Route("/main", name="app_main")
     */
    public function index(PostRepository $postRepository): Response
    {
        // TODO :  aller cherche tous les posts de la BDD
        $allPostfromBDD = $postRepository->findAll();
        return $this->render('main/index.html.twig', [
            'pageName' => 'Accueil',
            'allPost' => $allPostfromBDD
        ]);
    }

    /**
     * @Route("/post/{index}",name="app_main_post", requirements={"index"="\d+"})
     */
    public function post(): Response
    {
        return $this->render('main/post.html.twig', [
            'pageName' => 'PostID',
        ]);
    }
}
