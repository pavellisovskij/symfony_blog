<?php

namespace App\Form;

use App\Entity\MenuItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page', ChoiceType::class, [
                'choices' => $this->transformData($options['pages']),
                'attr' => [
                    'required'    => true,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'pages' => array()
        ]);
        $resolver->setAllowedTypes('pages', 'array');
    }

    private function transformData(array $data, array $additionalData = null): array
    {
        $arr = [];

        if ($additionalData != null) {
            foreach ($additionalData as $key => $value) {
                $arr[$key] = $value;
            }
        }

        foreach ($data as $array) {
            $arr[$array['title']] = $array['id'];
        }

        return $arr;
    }
}
