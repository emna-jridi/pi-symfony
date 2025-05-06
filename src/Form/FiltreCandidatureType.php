<?php

namespace App\Form;

use App\Entity\Candidature;
use App\Entity\Offreemploi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltreCandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('offre', EntityType::class, [
            'class' => Offreemploi::class,
            'choice_label' => 'titre', // ou un autre champ que tu veux afficher
            'required' => false,
            'placeholder' => 'Toutes les offres',
            'label' => 'Offre d\'emploi',
        ])
        ->add('filtrer', SubmitType::class, [
            'label' => 'Filtrer',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'method' => 'GET',
        'csrf_protection' => false,
    ]);
}
}
