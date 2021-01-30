<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Commentaire;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => function ($user){
            //         return $user->getPrenom();
            //     }
            // ])
            ->add('contenu', TextType::class, [
                'label' => 'Votre Commentaire',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('note', NumberType::class, [
                'label' => 'Votre Note de 0 à 5.',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('rgpd', CheckboxType::class)
            ->add('parentId', HiddenType::class, [
                'mapped' => false
            ])
            ->add('Envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
