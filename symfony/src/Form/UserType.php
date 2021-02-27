<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'attr' => [
                    'required' => false
                ],
                'always_empty' => true
            ])
        ;

        if ($options['action'] === 'edit') {
            $builder->add('blocked', ChoiceType::class, [
                'attr' => [
                    'required' => false
                ],
                'mapped' => false,
                'choices' => [
                    ''         => 0,
                    '1 hour'   => 1,
                    '12 hours' => 2,
                    '1 day'    => 3,
                    '1 week'   => 4,
                    '1 month'  => 5,
                    '6 months' => 6,
                    '1 year'   => 7

                ],
                'choice_attr' => [
                    '' => ['selected' => true]
                ],
                'label' => 'Do you want to block the user?'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
