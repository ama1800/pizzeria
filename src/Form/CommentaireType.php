<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Votre Commentaire',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('note', ChoiceType::class, [
                'choices' => range(0, 5),
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'Accepter les terms d\'utilisation.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'accepter les terms'
                    ])
                ],
            ])
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
