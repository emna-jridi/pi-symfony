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

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('NomService', TextType::class, [
                'label' => 'Nom du service',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir le nom de service'],
            ])
            ->add('DescriptionService', TextareaType::class, [
                'label' => 'Description du service',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir la description de service'],
            ])
            ->add('TypeService', TextType::class, [
                'label' => 'Type de service',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir le type de service'],
            ])
            ->add('DateDebutService', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début du service',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('DateFinService', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('StatusService', ChoiceType::class, [
                'label' => 'Statut du service',
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                ],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
