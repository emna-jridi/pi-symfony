<?php

// src/Form/ContratType.php
namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Service;
use App\Enum\ModePaiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDebutContrat', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début du contrat',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('DateFinContrat', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin du contrat',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('StatusContrat', ChoiceType::class, [
                'label' => 'Statut du contrat',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                ],
                'expanded' => true,
            
                'multiple' => false, 
                'attr' => ['class' => 'form-control'],
            ])
            ->add('MontantContrat', NumberType::class, [
                'label' => 'Montant du contrat',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir le montant du contrat'],
            ])
            
            ->add('NomClient', TextType::class, [
                'label' => 'Nom du client',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir le nom de client'],
            ])
            ->add('EmailClient', EmailType::class, [
                'label' => 'Email du client',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir Email de client'],
            ])
            ->add('telephoneClient', TextType::class, [
                'label' => 'Téléphone du client',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir le numéro de téléphone de client'],
                
               
            ])
            ->add('modePaiement', ChoiceType::class, [
                'choices' => ModePaiement::cases(),
                'choice_label' => function($choice) {
                    return $choice->value;
                },
                'placeholder' => 'Choisir mode de paiement',
                'label' => 'Mode de paiement',
                'attr' => ['class' => 'form-control'],
            ])
            
   
            ->add('contratServices', EntityType::class, [
                'class' => Service::class, 
                'choice_label' => 'NomService',
                'label' => 'Services associés',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-check-input'],
              'mapped' => false, 
              'constraints' => [
                new Assert\Count(
                    min : 1,
                    minMessage : 'Veuillez sélectionner au moins un service pour le contrat.'
                ),
            ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
