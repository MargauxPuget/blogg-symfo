<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // on stock là les autheurs à mettre en BDD
        $allAuthorObject = [];

        // premier Autheur : Arthur Genial
        $newAuthor = new Author();
        $newAuthor->setFirstname("Arthur");
        $newAuthor->setLastname("Genial");
        $manager->persist($newAuthor);
        // je conserver les objet autheur pour les utiliser pendant la création de post
        $allAuthorObject[] = $newAuthor;
        
        // second Autheur : Emy Paris
        $newAuthor = new Author();
        $newAuthor->setFirstname("Emy");
        $newAuthor->setLastname("Paris");
        $manager->persist($newAuthor);
        // je conserver les objet autheur pour les utiliser pendant la création de post
        $allAuthorObject[] = $newAuthor;
        

        // TODO : créer 20 posts
        for ($i=1; $i < 20 ; $i++) { 
            
            $newPost = new Post();
            $newPost->setTitle("La croche du siècle #". $i);
            $newPost->setSummary("Le Lorem Ipsum est simplement du faux texte employé dans la composition ...");
            $newPost->setBody("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.");
            $newPost->setPoster("https://picsum.photos/seed/blogg".$i."/200/100");
            // ajout d'un autheur au hasard
            $author = array_rand($allAuthorObject, 1);
            $newPost->setAuthor($allAuthorObject[$author]);
            // création d'un nombre de like entre 0 et 100
            $randLike = mt_rand(0, 100);
            $newPost->setNbLikes($randLike);
            // création d'un nombre de commentaires entre 0 et 15
            $randNbComment = mt_rand(0, 15);
            $postComment = [];
            for ($j=0; $j < $randNbComment ; $j++) { 
                $newComment = new Comment();
                $newComment->setUserName("personnage #". $j);
                $newComment->setBoby("tratratratratagabada");
                $newComment->setPost($newPost);
                $manager->persist($newComment);
                $postComment[] = $newComment;
            }

            $manager->persist($newPost);
        }

        $manager->flush();
    }
}
