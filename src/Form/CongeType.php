<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CongeType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Type_conge', ChoiceType::class, [
                'choices' => [
                    'Congé Annuel' => 'Congé Annuel',
                    'Congé Maladie' => 'Congé Maladie',
                    'Congé Maternité' => 'Congé Maternité',
                    'Congé Paternité' => 'Congé Paternité',
                    'Congé Sans Solde' => 'Congé Sans Solde',
                ],
                'placeholder' => 'Choisissez un type de congé',
                'data' => 'Congé Annuel',
                'required' => true,
            ])
            ->add('Date_debut', null, [
                'widget' => 'single_text',
                'data' => new \DateTime('today'),
            ])
            ->add('Status', null, [
                'data' => 'En attente',
                'disabled' => true,
            ])
            ->add('Date_fin', null, [
                'widget' => 'single_text',
                'data' => new \DateTime('+1 day'),
            ]);
            
        // Le champ id_user sera géré dans le contrôleur
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
