<?php

namespace App\Form;

use App\Enum\Typecontrat;
use App\Enum\NiveauEtudes;
use App\Entity\Offreemploi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltreOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('typeContrat', EnumType::class, [
            'class' => Typecontrat::class,
            'required' => false,
            'placeholder' => 'Tous les types de contrat',
            'label' => 'Type de contrat'
        ])
        ->add('niveauEtudes', EnumType::class, [
            'class' => NiveauEtudes::class,
            'required' => false,
            'placeholder' => 'Tous les niveaux d\'études',
            'label' => 'Niveau d\'études'
        ])
        ->add('filtrer', SubmitType::class, [
            'label' => 'Filtrer',
            'attr' => ['class' => 'btn btn-primary']
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
