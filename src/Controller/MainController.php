<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="default" )
     * @Route("/main", name="app_main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'pageName' => 'Accueil',
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
