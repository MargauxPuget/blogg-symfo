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
        dump($allPostfromBDD);
        return $this->render('main/index.html.twig', [
            'pageName' => 'Accueil',
            'allPosts' => $allPostfromBDD
        ]);
    }

    /**
     * @Route("/post/{index}", name="app_post", requirements={"index"="\d+"})
     */
    public function post($index, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($index);
        dump($post);
        return $this->render('main/post.html.twig', [
            "postForView" => $post,
            'pageName' => 'PostID',
        ]);
    }

    /**
     * Route de suppression d'article
     * 
     * @Route("/post/{id}/delete", name="app_post_delete", requirements={"id"="\d+"})
     *
     * @return void
     */
    public function deletePost($id, PostRepository $postRepository){

        $postToDelete = $postRepository->find($id);
        // remove + flush
        $postRepository->remove($postToDelete, true);

        return $this->redirectToRoute("default");
    }
}
