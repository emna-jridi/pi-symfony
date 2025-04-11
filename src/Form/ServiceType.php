<?php
namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('NomService', TextType::class, [
                'label' => 'Nom du service',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom du service est obligatoire.'])
                ]
            ])
            ->add('DescriptionService', TextareaType::class, [
                'label' => 'Description du service',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description du service est obligatoire.'])
                ]
            ])
            ->add('TypeService', TextType::class, [
                'label' => 'Type de service',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le type de service est obligatoire.'])
                ]
            ])
            ->add('DateDebutService', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début du service',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de début est requise.']),
                    new Assert\LessThanOrEqual([
                        'propertyPath' => 'parent.all[DateFinService].data',
                        'message' => 'La date de début ne peut pas être après la date de fin.',
                    ]),
                ],
            ])
            ->add('DateFinService', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de fin est obligatoire.']),
                    new Assert\GreaterThanOrEqual([
                        'propertyPath' => 'parent.all[DateDebutService].data',
                        'message' => 'La date de fin ne peut pas être avant la date de début.'
                    ])
                ]
            ])
            ->add('StatusService', ChoiceType::class, [
                'label' => 'Statut du service',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                ],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le statut du service est obligatoire.'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
