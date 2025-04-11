<?php

namespace App\Form;

use App\Entity\Offreemploi;
use App\Enum\Experiencerequise;
use App\Enum\NiveauEtudes;
use App\Enum\Niveaulangues;
use App\Enum\Statut;
use App\Enum\Typecontrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreemploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'offre',
                'attr' => [
                    'placeholder' => 'Entrez le titre de l\'offre',
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description détaillée',
                'attr' => [
                    'rows' => 5,
                    'class' => 'form-control',
                    'placeholder' => 'Décrivez en détail le poste...'
                ]
            ])
            ->add('experiencerequise', ChoiceType::class, [
                'label' => 'Expérience requise',
                'choices' => Experiencerequise::cases(),
                'choice_label' => function($choice) {
                    return $choice->value;  // Utilise value au lieu de name pour afficher la version lisible
                },
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
                'required' => true
            ])
            ->add('niveauEtudes', ChoiceType::class, [
                'label' => 'Niveau d\'études requis',
                'choices' => NiveauEtudes::cases(),
                'choice_label' => function($choice) {
                    return $choice->value;
                },
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
                'required' => true
            ])
            ->add('competences', TextareaType::class, [
                'label' => 'Compétences requises',
                'attr' => [
                    'rows' => 3,
                    'class' => 'form-control',
                    'placeholder' => 'Listez les compétences nécessaires...'
                ]
            ])
            ->add('typecontrat', ChoiceType::class, [
                'label' => 'Type de contrat',
                'choices' => Typecontrat::cases(),
                'choice_label' => function($choice) {
                    return $choice->value;
                },
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
                'required' => true
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ville ou région'
                ]
            ])
            ->add('niveaulangues', ChoiceType::class, [
                'label' => 'Niveau de langue requis',
                'choices' => Niveaulangues::cases(),
                'choice_label' => function($choice) {
                    return $choice->value;
                },
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
                'required' => true
            ])
            ->add('dateCreation', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('dateExpiration', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
           /*  ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => Statut::cases(),
                'choice_label' => fn($choice) => $choice->value,
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
                'required' => true
            ]) */
     /*   ->add('statut') */
 /* ->add('candidaturesrecues')   */

            ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offreemploi::class,
        ]);
    }
}