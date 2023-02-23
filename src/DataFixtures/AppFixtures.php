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
            $newPost->setBody("blablablablablablablablablablablablablablablablablablablablablablablablablablablabla");
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
                # code...
            }

            $manager->persist($newPost);
        }

        $manager->flush();
    }
}
