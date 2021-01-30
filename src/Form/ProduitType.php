<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->addEventListener(
        //     FormEvents::POST_SUBMIT,
        //     function (FormEvent $event) {

        //         foreach ($event->getData()->getImages() as $image) {
        //             $image->addProduit($event->getData());
        //         }
        //     }
        // );
        $builder
            ->add('produitLibelle', TextType::class)
            ->add('ProduitPrix', NumberType::class, [
                'scale' => 2,
            ])
            ->add('disponible', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'selectpicker',],
                'choices'  => [
                    'Disponible' =>  '1',
                    'Répture' =>  '0',
                ],
                'data' => 1,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'categorieLibelle'
            ])
            // On ajoute le champ "images" dans le formulaire
            // Il n'est pas lié à la base de données (mapped à false)
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
