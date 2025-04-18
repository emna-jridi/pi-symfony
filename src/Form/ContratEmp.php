<?php

namespace App\Form;

use App\Entity\ContratEmploye;
use App\Entity\User;
use App\Enum\Typecontrat;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ContratEmp extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $isEdit = $options['is_edit'] ?? false;
       

        if (!$isEdit) {
            $builder->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNomUser() . ' ' . $user->getPrenomUser();
                },
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('u')
                        ->leftJoin('App\Entity\ContratEmploye', 'c', 'WITH', 'c.user = u')
                        ->where('u.role = :role')
                        ->andWhere('c.idContratEmp IS NULL')
                        ->setParameter('role', 'employe')
                        ->orderBy('u.nomUser', 'ASC');
                },
                'label' => 'Employé concerné',
                'placeholder' => 'Choisir un employé disponible',
            ]);
        }


        $builder
        ->add('typeContrat', ChoiceType::class, [
            'label' => 'Type de contrat',
            'choices' => Typecontrat::cases(),
            'choice_label' => function($choice) {
                        return $choice->value;
                    },
            'choice_value' => fn(?Typecontrat $choice) => $choice?->value,
            'placeholder' => 'Choisir type de contrat',
            ])
            
        ->add('DateDebutContrat', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début du contrat',
                'attr' => ['class' => 'form-control'],
                
            ])
            ->add('DateFinContrat', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin du contrat',
                'required' => false,
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
            ->add('Salaire', NumberType::class, [
                'label' => 'Salaire',
                'attr' => ['class' => 'form-control',
            'placeholder' => 'Saisir le salaire du l\'employe'],
                
            ]);
            
          
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContratEmploye::class,
            'is_edit' => false, 
        ]);
    }
}
