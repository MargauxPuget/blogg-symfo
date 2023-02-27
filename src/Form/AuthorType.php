<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'empty_data' => ""
            ])
            ->add('lastname', TextType::class, [
                'empty_data' => ""
            ])
            /*
            ->add('createdAt')
            ->add('updatedAt')
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
            // on désactive la validation HTML 5
            'attr' =>[
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
