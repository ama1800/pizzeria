<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un email valid'
                    ])
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('civilite', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'selectpicker',],
                'choices'  => [
                    'MONSIEUR' =>  '1',
                    'MADAME' =>  '0',
                ],
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
            ->add('cp', TextType::class)
            ->add('ville', TextType::class,)
            ->add('adresse', TextType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
