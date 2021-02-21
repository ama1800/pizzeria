<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminEditUserType extends AbstractType
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
                'attr' => ['class' => 'form-control'],
            ])
            ->add('roles', ChoiceType::class, [
                'required' => false,
                'choices'  => [
                    'USER' =>  'ROLE_USER',
                    'EMPLOYE' =>  'ROLE_EMPLOYE',
                    'GERANT' =>  'ROLE_GERANT',
                    'ADMIN' =>  'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ])
            ->add('nom', TextType::class, [

                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom', TextType::class, [

                'attr' => ['class' => 'form-control'],
            ])
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
            ->add('cp', TextType::class, [

                'attr' => ['class' => 'form-control'],
            ])
            ->add('ville', TextType::class, [

                'attr' => ['class' => 'form-control'],
            ])
            ->add('adresse', TextType::class, [

                'attr' => ['class' => 'form-control'],
            ])
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
