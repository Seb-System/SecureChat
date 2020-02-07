<?php

namespace App\Form;

use App\Entity\Groupe;
use Doctrine\DBAL\Types\TextType;
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

        for($i = 0; $i < $options['nbUsers']; $i++ ){
            $builder ->add('checkbox_' . $i , CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'for' => $i,
                    'class' => 'custom-control-input'
                )
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
            'nbUsers' => 0
        ]);
    }
}
