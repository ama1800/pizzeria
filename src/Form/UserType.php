<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un email valid'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passes doivent etre identiques!',
                'options' => ['attr' => ['class' => 'password-field']],
                'mapped' => true,
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmation du mot de passe'),
                'constraints' => new Length([
                    'min' => 6,
                    'minMessage' => "Veuillez mettre plus de {{ limit }} characters",
                    'max' => 12,
                    'maxMessage' => "Veuillez ne pas mettre plus de {{ limit }} characters",

                ]),
                'required' => true
            ))
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('civilite', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'selectpicker',],
                'choices'  => [
                    'MONSIEUR' =>  '1',
                    'MADAME' =>  '0',
                ],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'N° téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('cp', TextType::class)
            ->add('ville', TextType::class)
            ->add('adresse', TextType::class)
            // ->add('brochure', FileType::class, [
            //     'label' => 'Photo',
            //     // unmapped means that this field is not associated to any entity property
            //     'mapped' => false,
            //     // make it optional so you don't have to re-upload the file
            //     // every time you edit the Product details
            //     'required' => false,
            //     // unmapped fields can't define their validation using annotations
            //     // in the associated entity, so you can use the PHP constraint classes
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '1024k',
            //             'mimeTypes' => [
            //                 'image/png',
            //                 'image/jpg',
            //                 'image/jpeg',
            //                 'image/git',
            //             ],
            //             'mimeTypesMessage' => 'Inserez une extension valide. Seulement(.png, .jpg, .jpeg, ou .git), maximum 1024Ko',
            //         ])
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
