<?php

namespace App\Form;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPostToCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', EntityType::class, [
                'attr' => [
                    'required' => true
                ],
                'class' => Post::class,
                'query_builder' => function (PostRepository $pr) use ($options) {
                    return $pr->createQueryBuilder('p')
                        ->where(':category NOT MEMBER OF p.categories')
                        ->setParameter('category', $options['category_id'])
                        ->orderBy('p.title', 'ASC');
                },
                'choice_label' => 'title',
                'placeholder' => 'Select the post...'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'category_id' => null
        ]);
    }
}
