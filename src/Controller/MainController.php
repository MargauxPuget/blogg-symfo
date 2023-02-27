<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="default" )
     * @Route("/main", name="app_main")
     */
    public function index(PostRepository $postRepository, AuthorRepository $authorRepository): Response
    {
        // TODO :  aller cherche tous les posts de la BDD
        $allPostfromBDD = $postRepository->findAll();
        // dump($allPostfromBDD);

        // TODO :  aller cherche tous les autheurs de la BDD
        $allAuthorfromBDD = $authorRepository->findAll();
        

        return $this->render('main/index.html.twig', [
            'pageName' => 'Accueil',
            'allPosts' => $allPostfromBDD,
            'allAuthors' => $allAuthorfromBDD
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

    /**
     * Ajout d'un article
     *
     * @Route("/post/add", name="app_post_add", methods={"GET", "POST"})
     */
    public function addPost(Request $request, PostRepository $postRepository){
        
        $newPost = new Post();

        $form = $this->createForm(PostType::class, $newPost);

        $form->handleRequest($request);
        dump($newPost);

        if ($form->isSubmitted() && $form->isValid())
        {
            $postRepository->add($newPost, true);
            return $this->redirectToRoute("app_post", ["index" => $newPost->getId()]);
        }

        return $this->renderForm("main/addPost.html.twig", [
           "formulaire" => $form
        ]);
    }
}
