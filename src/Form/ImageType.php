<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('brochure', FileType::class, [
                'label' => 'Photo',
                // mapped' => false n'est pas lier à aucune proprité d'entité
                'mapped' => false,
                // Comme ça on est pas obliger de retélécharger l'image à chaque edition.
                'required' => false,
                // unmapped champs on ne peut pas définir leurs validations on utilisant les annotations
                // dans l'entity associe, pour ça qu'on utilise PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/git',
                        ],
                        'mimeTypesMessage' => 'Inserez une extension valide. Seulement(.png, .jpg, .jpeg, ou .git), maximum 1024Ko',
                    ])
                ]
            ])
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'ProduitLibelle'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
