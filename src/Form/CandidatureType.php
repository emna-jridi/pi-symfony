<?php

namespace App\Form;

use App\Entity\Candidature;
use App\Entity\Offreemploi;
use App\Enum\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           /*  ->add('dateCandidature', null, [
                'widget' => 'single_text',
            ]) */
          /*    ->add('statut', ChoiceType::class, [
                'label' => 'Statut de la candidature',
                'choices' => Statut::cases(),
                'choice_label' => fn($choice) => $choice->value,
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
                'required' => true,
            ])  */
            ->add('cvUrl')
            ->add('lettreMotivation')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone')
            // ->add('candidat_id')
          /*   ->add('offre', EntityType::class, [
                'class' => Offreemploi::class,
                'choice_label' => 'titre',
            ])
        ; */
        ->add('offre', EntityType::class, [
            'class' => Offreemploi::class,
            'choice_label' => 'titre', // Affiche le titre de l'offre
            'placeholder' => 'Choisir une offre', // Ajoute une option vide pour forcer l'utilisateur à choisir une offre
            'required' => true, // Si vous voulez que le champ soit obligatoire
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
