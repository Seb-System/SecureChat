<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GroupeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null, [
                'attr' => ['type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nom du groupe']
            ])->add('users', EntityType::class, [
                'class' => User::class,
                'query_builder' =>  function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id != :id')
                        ->setParameter('id', $options['idUser']);
                },
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'username',
                'attr' => ['class' => 'ks-cboxtags']
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
            ])*/;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
            'idUser' => 0
        ]);
    }
}
