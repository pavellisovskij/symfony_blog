<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                    'maxlength' => 100,
                    'required' => true,
                    'placeholder' => 'Enter category name...'
                ]
            ])
            ->add('by', ChoiceType::class, [
                'attr' => [
                    'required' => true
                ],
                'choices' => [
                    'by category name' => 1,
                    'by post name' => 2
                ],
                'choice_attr' => [
                    'by category' => ['selected' => true]
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
