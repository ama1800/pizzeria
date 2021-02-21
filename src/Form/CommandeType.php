<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('adresseLivraison', TextType::class, [
                'label' => 'Adresse Compléte',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'N° téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('etage', NumberType::class, [
                'label' => 'Etage',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('appartement', NumberType::class, [
                'label' => 'Votre N° d\'appartement.',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('remarque', TextType::class, [
                'label' => 'Remarques',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add(
                'agreeTerms',
                CheckboxType::class,
                ['mapped' => false]
            )
            // ->add('submit', SubmitType::class, [
            //     'label' => 'Valider',
            //     'attr' => [
            //         'class' => 'btn btn-primary'
            //     ],
            // ])
        ;
        // ->add('user') //defint dans le controller;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
