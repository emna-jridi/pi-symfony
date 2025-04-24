<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class BackOfficeUserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUser', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('prenomUser', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('emailUser', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephoneUser', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control']
            ])
            ->add('adresseUser', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control']
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe actuel',
                        'groups' => ['password_change']
                    ]),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nouveau mot de passe',
                        'groups' => ['password_change']
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                        'groups' => ['password_change']
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre',
                        'groups' => ['password_change']
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmer le nouveau mot de passe',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez confirmer votre nouveau mot de passe',
                        'groups' => ['password_change']
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => function ($form) {
                $groups = ['Default'];
                
                // Récupérer les données du formulaire
                $currentPassword = $form->get('currentPassword')->getData();
                $newPassword = $form->get('newPassword')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();
                
                // Si l'un des champs de mot de passe est rempli, ajouter le groupe password_change
                if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
                    $groups[] = 'password_change';
                }
                
                return $groups;
            },
        ]);
    }
} 