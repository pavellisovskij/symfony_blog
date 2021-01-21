<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCategoryByChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search_field', TextType::class, [
                'attr' => [
                    'maxlength' => 255,
                    'required' => true,
                    'placeholder' => 'Enter search query here'
                ]
            ])
            ->add('by', ChoiceType::class, [
                'attr' => [
                    'required' => true
                ],
                'choices' => [
                    'by post title' => 1,
                    'by category name' => 2

                ],
                'choice_attr' => [
                    'by category name' => ['selected' => true]
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
