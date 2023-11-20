<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'E-mail :'
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'nom d\'utilisatteur :'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles utilisateur',
                'attr' => [
                    'class' => 'form-check'
                ],
                'choices' => [
                            'Admin' => "ROLE_ADMIN"
                        ],
                'choice_attr' => [
                    'Admin' => ['class' => 'm-2'],
                ],
                'required' => true,
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
