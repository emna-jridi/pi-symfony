<?php
namespace App\Form;

use App\Entity\ContratService;
use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'NomService',  // The field to display in the dropdown
                'expanded' => false,  // Set to false to make it a dropdown (instead of checkboxes)
                'multiple' => false,   // Allow multiple selections
                'placeholder' => 'Choisissez un ou plusieurs services', // Placeholder text
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContratService::class,
        ]);
    }
}
