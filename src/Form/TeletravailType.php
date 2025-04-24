<?php

namespace App\Form;

use App\Entity\Teletravail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeletravailType extends AbstractType
{// Dans le fichier TeletravailType.php
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('IdEmploye')
        ->add('DateDemandeTT', null, [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control',
                'readonly' => 'readonly', // Rendre le champ readonly
                'style' => 'display:none;', // Masquer le champ dans le formulaire
            ],
        ])
        ->add('DateDebutTT', null, [
            'widget' => 'single_text',
        ])
        ->add('DateFinTT', null, [
            'widget' => 'single_text',
        ])
        ->add('StatutTT', null, [
            'data' => 'Traitement', // Définir la valeur par défaut
            'attr' => [
                'style' => 'display:none;', // Masquer le champ dans le formulaire
            ],
        ])
        ->add('RaisonTT');
}

}
