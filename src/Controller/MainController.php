<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
    * Ajoute un like à un article 
    * @Route("/post/{index}/like", name="post_add_like", requirements={"index"="\d+"})
     */
    public function editLike($index, PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $postToUpdate = $postRepository->find($index);
        $nblikeActuel = $postToUpdate->getNbLikes();
        $postToUpdate->setNbLikes($nblikeActuel +1);
        dump($nblikeActuel, $postToUpdate);
        $entityManager->flush();        

        return $this->redirectToRoute("app_post", ["index" => $index]);
    }

    /**
     * @Route("/post/{index}/edit", name="post_edit", methods={"GET", "POST"}, requirements={"index":"\d+"})
     */
    public function edit($index, Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager)   
    {
        // 1. Il me faut un objet à associer à mon formulaire
        // dans le cas d'une modification, je vais chercher mon objet en BDD
        $updatePost = $postRepository->find($index);
        
        // 2. je créer mon formulaire depuis le bon FormType
        // je lui donne mon objet, pour qu'il fasse l'association en auto
        $form = $this->createForm(PostType::class, $updatePost);

        // 3. je demande au formulaire de gérer les informations venant de la requete
        // 3.1. $formTitle = $request->request->get('title');
        // 3.2. $updatePost->setTitle($formTitle);
        $form->handleRequest($request);
        
        // DEBUG mon entité est remplit
        //dd($newPost);
            
        // 4. je vérifie que le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid())
        {

            // ? https://symfony.com/doc/current/doctrine.html#persisting-objects-to-the-database

            // TODO : flush, j'ai besoin de l'EntityManagerInterface
            // injection de dépendance
            $entityManager->flush();

            // TODO après un ajout, faire une redirection
            // j'ai l'id de mon objet à jour car j'ai fait un flush avant
            return $this->redirectToRoute("app_post", ["index" => $updatePost->getId()]);
        }

       
        // 5. afficher un formulaire
        //? https://symfony.com/doc/5.4/forms.html#rendering-forms
        return $this->renderForm("main/addPost.html.twig", [
            "formulaire" => $form
        ]);
    }
}
