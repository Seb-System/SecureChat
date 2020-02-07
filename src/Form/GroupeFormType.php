<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GroupeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'attr' => ['type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nom du groupe']
            ])-> add('users', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'username',
            ])
            //->add('picture')
            //->add('date')
            /*->add('users', CollectionType::class, [
                'attr' =>  [
                    'class' => 'form-control',
                    'type' => 'text',
                    'id' => 'participant',
                    'placeholder' => 'Ajouter un participant...'
                ],
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => ['class' => 'user form-user'],
                ]
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
            'nbUsers' => 0
        ]);
    }
}
