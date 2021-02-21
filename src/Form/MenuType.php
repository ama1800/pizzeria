<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {

                foreach ($event->getData()->getProduitsOnMenu() as $produit) {
                    $produit->setMenu($event->getData());
                }
            }
        );
        $builder
            ->add('libelle', TextType::class)
            // ->add('prix', NumberType::class, [
            //     'scale' => 2, // spécifie nombres de chiffre apres  La virgule.
            // ])
            ->add('disponible', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'selectpicker',],
                'choices'  => [
                    'OUI' =>  '1',
                    'NON' =>  '0',
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
