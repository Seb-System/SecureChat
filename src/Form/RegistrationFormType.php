<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'attr' => array('class' => 'form-control', 'type' => 'text', 'placeholder' => 'Username'),
                'label' => false,
            ])
            ->add('email', null, [
                'attr' => array('class' => 'form-control', 'type' => 'text', 'placeholder' => 'Email'),
                'label' => false,
            ])->add('plainPassword', RepeatedType::class, [
                 // instead of being set onto the object directly,
                 // this is read and encoded in the controller
                 'type' => PasswordType::class,
                 'mapped' => false,
                 'constraints' => [
                     new NotBlank([
                         'message' => 'Please enter a password',
                     ]),
                     new Length([
                         'min' => 6,
                         'minMessage' => 'Your password should be at least {{ limit }} characters',
                         // max length allowed by Symfony for security reasons
                         'max' => 4096,
                     ]),
                 ],
                 'first_options' => [
                                      'label' => false,
                                      'attr' =>  array('class' => 'form-control', 'placeholder' => 'Password')
                                    ],
                 'second_options' => ['label' => false,
                                      'attr' =>  array('class' => 'form-control', 'placeholder' => 'Confirmation Password')
                                    ],
                 'invalid_message' => 'Les deux mots de passe ne correspondent pas !'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
