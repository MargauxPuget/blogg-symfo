<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Author;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\PostType;
use App\Form\CommentType;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
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
    public function post($index, PostRepository $postRepository, CommentRepository $commentRepository): Response
    {
        $post = $postRepository->find($index);
        dump($post);

        $allComments = $commentRepository->findBy(
        [
            "post" => $post
        ]
        );
        dump($allComments);

        return $this->render('main/post.html.twig', [
            "postForView" => $post,
            "commentForView" => $allComments,
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
     * Ajout d'un comment à un article donnée
     *
     * @Route("/comment/add/{idPost}", name="app_comment_add", methods={"GET", "POST"}, requirements={"idPost"="\d+"})
     */
    public function addComment($idPost, Request $request, CommentRepository $commentRepository, PostRepository $postRepository){
        
        $post = $postRepository->find($idPost);
        $newComment = new Comment();
        $newComment->setPost($post);

        $form = $this->createForm(CommentType::class, $newComment);

        $form->handleRequest($request);
        dump($newComment);

        if ($form->isSubmitted() && $form->isValid())
        {
            $commentRepository->add($newComment, true);
            return $this->redirectToRoute("app_post", ["index" => $post->getId()]);
        }

        return $this->renderForm("main/addComment.html.twig", [
           "formulaire" => $form
        ]);
    }

    /**
     * Ajout d'un auteur
     *
     * @Route("/author/add", name="app_author_add", methods={"GET", "POST"})
     */
    public function addAuthor(Request $request, AuthorRepository $authorRepository){
        
        $newAuthor = new Author();

        $form = $this->createForm(AuthorType::class, $newAuthor);

        $form->handleRequest($request);
        dump($newAuthor);

        if ($form->isSubmitted() && $form->isValid())
        {
            $authorRepository->add($newAuthor, true);
            return $this->redirectToRoute("default");
        }

        return $this->renderForm("main/addAuthor.html.twig", [
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
