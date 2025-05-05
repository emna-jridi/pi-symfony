<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CongeTypeadmin extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Type_conge', ChoiceType::class, [
                'choices' => [
                    'Congé Annuel' => 'Congé Annuel',
                    'Congé Maladie' => 'Congé Maladie',
                    'Congé Maternité' => 'Congé Maternité',
                    'Congé Paternité' => 'Congé Paternité',
                    'Congé Sans Solde' => 'Congé Sans Solde',
                ],
                'placeholder' => 'Choisissez un type de congé',
            ])
            ->add('Date_debut', null, [
                'widget' => 'single_text',
                'data' => new \DateTime('today'),
            ])
            ->add('Status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Accepté' => 'Accepté',
                    'Refusé' => 'Refusé',
                ],
                'placeholder' => 'Choisissez un statut',
            ])
            ->add('Date_fin', null, [
                'widget' => 'single_text',
                'data' => new \DateTime('+1 day'),
            ])
            ->add('Date_fin', null, [
                'widget' => 'single_text',
                'data' => new \DateTime('+1 day'),
            ])

            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNomUser() . ' ' . $user->getPrenomUser();
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
