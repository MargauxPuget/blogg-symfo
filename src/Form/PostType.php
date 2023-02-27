<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'empty_data' => ""
            ])
            ->add('poster', TextType::class, [
                'empty_data' => ""
            ])
            ->add('body', TextareaType::class)
            // listes des auteurs
            // ? https://symfony.com/doc/current/reference/forms/types/entity.html
            // du fait que cela soit un EntityType, l'élément HTML qui sera généré aura toutes les valeurs possibles
            ->add('author', EntityType::class, [
                // ! ne pas oublier de dire de quelle entité en parle
                'class' => Author::class,
                // ! Object of class App\Entity\Author could not be converted to string
                // je dois préciser quelle propriété doit être afficher dans la liste déroulante
                'choice_label' => 'firstname',
            ])
            ->add('nbLikes', IntegerType::class, [
                'empty_data' => ""
            ])
            ->add('summary', TextareaType::class)
            /*
            ->add('publishedAt')
            ->add('createdAt')
            ->add('updatedAt')
            */
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            // on désactive la validation HTML 5
            'attr' =>[
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
