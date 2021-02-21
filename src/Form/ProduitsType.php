<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\produitsOnMenu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produitsOnMenu', CollectionType::class, [
                'label' => false,
                // chaque entry dans le tableau deviendera un champ de produit
                'entry_type' => EntityType::class,
                // ces options sont passés à chaque "produit" type
                'entry_options' => [
                    'label' => 'choisir Produit:',
                    'class' => produitsOnMenu::class
                ],
                //true permet l'ajout et suppression des produits
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
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
            'data_class' => Menu::class,
        ]);
    }
}
