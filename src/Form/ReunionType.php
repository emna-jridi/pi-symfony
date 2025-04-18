<?php

namespace App\Form;

use App\Entity\Reunion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReunionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('date', null, [
                'widget' => 'single_text',
                'data' => new \DateTime('today'),
            ])
            ->add('Type', ChoiceType::class, [
                'choices' => [
                    'En ligne' => 'En ligne',
                    'presentiel' => 'presentiel',
                ],
                'placeholder' => 'Choisissez un Type',
            ])
            ->add('description')
            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNomUser() . ' ' . $user->getPrenomUser();
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reunion::class,
            'csrf_protection' => false,
        ]);
    }
}
