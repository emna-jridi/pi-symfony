<?php

namespace App\Form;

use App\Entity\ReservationSalle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ReservationSalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('IdEmploye')
            ->add('IdSalle')
            ->add('DateReservation', null, [
                'widget' => 'single_text',
            ])
            ->add('DureeReservation', IntegerType::class, [
                'label' => 'Durée (en minutes)', // ou heures si tu préfères
            ])
            ->add('StatutReservation', HiddenType::class, [
                'data' => 'en attente',  // Valeur par défaut
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationSalle::class,
        ]);
    }
}
