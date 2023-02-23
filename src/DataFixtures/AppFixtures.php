<?php

namespace App\DataFixtures;

use App\Entity\Author;
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
        


        $manager->flush();
    }
}
