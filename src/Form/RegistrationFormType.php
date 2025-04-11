<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUser', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ\s-]+$/',
                        'message' => 'Le nom ne peut contenir que des lettres, espaces et tirets',
                    ]),
                ],
            ])
            ->add('prenomUser', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom est obligatoire',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ\s-]+$/',
                        'message' => 'Le prénom ne peut contenir que des lettres, espaces et tirets',
                    ]),
                ],
            ])
            ->add('dateNaissanceUser', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de naissance est obligatoire',
                    ]),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de naissance doit être antérieure à aujourd\'hui',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => '-100 years',
                        'message' => 'La date de naissance n\'est pas valide',
                    ]),
                ],
            ])
            ->add('adresseUser', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'adresse est obligatoire',
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('telephoneUser', NumberType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le numéro de téléphone est obligatoire',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{8}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 10 chiffres',
                    ]),
                ],
            ])
            ->add('emailUser', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email est obligatoire',
                    ]),
                    new Email([
                        'message' => 'L\'email "{{ value }}" n\'est pas un email valide',
                    ]),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Responsable RH' => 'ResponsableRH',
                    'Employé' => 'Employe',
                    'Candidat' => 'Candidat'
                ],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le rôle est obligatoire',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
} 