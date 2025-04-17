<?php
// src/Form/QuestionTechniqueType.php
namespace App\Form;

use App\Entity\QuestionTechnique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionTechniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextareaType::class, [
                'label' => 'Question',
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('options', CollectionType::class, [
                'label' => 'Options de réponse',
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => ['class' => 'form-control mb-2']
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('reponseCorrecte', ChoiceType::class, [
                'label' => 'Réponse correcte',
                'choices' => [
                    'Option 1' => 0,
                    'Option 2' => 1,
                    'Option 3' => 2,
                    'Option 4' => 3,
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Programmation' => 1,
                    'Base de données' => 2,
                    'Architecture logicielle' => 3,
                    'Symfony' => 4,
                    'PHP' => 5,
                    'HTML/CSS' => 6,
                    'JavaScript' => 7,
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('difficulte', ChoiceType::class, [
                'label' => 'Niveau de difficulté',
                'choices' => [
                    'Débutant' => 1,
                    'Intermédiaire' => 2,
                    'Avancé' => 3,
                    'Expert' => 4,
                ],
                'attr' => ['class' => 'form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionTechnique::class,
        ]);
    }
}
