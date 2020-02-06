<?php

namespace App\Form;

use App\Entity\Groupe;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
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
            //->add('users_p')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
}
