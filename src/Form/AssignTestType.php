<?php
namespace App\Form;

use App\Entity\User;
use App\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('employee', ChoiceType::class, [
            'choices' => $options['employees'],
            'choice_label' => function ($employee) {
                return $employee->getNomUser() . ' ' . $employee->getPrenomUser(); 
            },
            'label' => 'Employé',
            'placeholder' => 'Sélectionner un employé',
        ])
        ->add('tests', ChoiceType::class, [
            'choices' => $options['tests'],
            'choice_label' => 'titre', 
            'label' => 'Tests à assigner',
            'multiple' => true,
            'expanded' => false,
        ]) ;   }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'employees' => [],
            'tests' => [],
        ]);
    }
}
