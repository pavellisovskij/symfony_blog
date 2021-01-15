<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\File;
use App\Entity\Files;
use App\Entity\Post;
use CKSource\Bundle\CKFinderBundle\Form\Type\CKFinderFileChooserType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', CKEditorType::class, [
                'config' =>[
                    'toolbar' => 'standard',
                    'required' => true
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'placeholder' => 'Select the categories...'
            ])
            ->add('preview', FileType::class, [
                'label' => 'Post preview',
//                'attr' => [
//                    'class' => 'btn btn-primary'
//                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
