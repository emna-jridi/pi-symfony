<?php
// src/Form/TestAssignmentType.php
namespace App\Form;

use App\Entity\TestAssignment;
use App\Entity\TestTechnique;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestAssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('test', EntityType::class, [
                'class' => TestTechnique::class,
                'choice_label' => 'titre',
                'label' => 'Select Test',
                'placeholder' => 'Choose a test to assign',
                'required' => true,
            ])
            ->add('assignedTo', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNomUser() . ' ' . $user->getPrenomUser() . ' (' . $user->getEmailUser() . ')';
                },
                'label' => 'Assign To',
                'placeholder' => 'Choose a user',
                'required' => true,
            ])
            ->add('userType', ChoiceType::class, [
                'choices' => [
                    'Candidate' => 'candidate',
                    'Employee' => 'employee',
                ],
                'label' => 'User Type',
                'required' => true,
            ])
            ->add('dueDate', DateTimeType::class, [
                'label' => 'Due Date',
                'required' => false,
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TestAssignment::class,
        ]);
    }
}