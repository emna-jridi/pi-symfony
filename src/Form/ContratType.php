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
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de début est requise.']),
                    new Assert\LessThanOrEqual([
                        'propertyPath' => 'parent.all[DateFinContrat].data',
                        'message' => 'La date de début ne peut pas être après la date de fin.',
                    ]),
                ],
            ])
            ->add('DateFinContrat', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin du contrat',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de fin est requise.']),
                ],
            ])
            ->add('StatusContrat', ChoiceType::class, [
                'label' => 'Statut du contrat',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                ],
                'expanded' => true,  // Affiche les choix sous forme de boutons radio
                'multiple' => false,  // Un seul choix possible
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le statut est requis.']),
                ],
            ])
            ->add('MontantContrat', NumberType::class, [
                'label' => 'Montant du contrat',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le montant est requis.']),
                    new Assert\Type([
                        'type' => 'numeric',
                        'message' => 'Le montant doit être un nombre.',
                    ]),
                    new Assert\Positive([
                        'message' => 'Le montant doit être supérieur à 0.',
                    ]),
                ],
            ])
            
            ->add('NomClient', TextType::class, [
                'label' => 'Nom du client',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom du client est requis.']),
                ],
            ])
            ->add('EmailClient', EmailType::class, [
                'label' => 'Email du client',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est requis.']),
                    new Assert\Email(['message' => 'Veuillez entrer une adresse email valide.']),
                ],
            ])
            ->add('telephoneClient', TextType::class, [
                'label' => 'Téléphone du client',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le numéro de téléphone est requis.']),
                    new Assert\Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 8 chiffres.',
                    ]),
                ],
            ])
            ->add('modePaiement', ChoiceType::class, [
                'choices' => [
                    'VIREMENT BANCAIRE' => ModePaiement::VIREMENT_BANCAIRE->value,
                    'CHEQUE' => ModePaiement::CHEQUE->value,
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Mode de paiement',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mode de paiement est requis.']),
                ],
            ])
            ->add('contratServices', EntityType::class, [
                'class' => Service::class, 
                'choice_label' => 'NomService',
                'label' => 'Services associés',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-check-input'],
                'mapped' => false,  
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
