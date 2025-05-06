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
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionTechniqueType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('question', TextType::class, [
            'label' => 'Question',
            'constraints' => [
                // Your existing constraints
            ],
        ])
        ->add('options', CollectionType::class, [
            'entry_type' => TextType::class,
            'entry_options' => [
                'label' => false,
            ],
            'label' => 'Options de réponse',
            'allow_add' => false,
            'allow_delete' => false,
        ])
        ->add('reponseCorrecte', ChoiceType::class, [
            'label' => 'Réponse correcte',
            'choices' => [
                'Option 1' => 1,
                'Option 2' => 2,
                'Option 3' => 3,
                'Option 4' => 4,
            ],
            'constraints' => [
                new Range([
                    'min' => 1,
                    'max' => 4,
                    'notInRangeMessage' => 'La réponse correcte doit être entre 1 et 4',
                ]),
            ],
        ])
        ->add('categorie', ChoiceType::class, [
            'label' => 'Catégorie',
            'choices' => [
                'PHP' => 1,
                'JavaScript' => 2,
                'HTML/CSS' => 3,
                'SQL' => 4,
                'Symfony' => 5,
                'Autre' => 6,
            ],
        ])
        ->add('difficulte', ChoiceType::class, [
            'label' => 'Difficulté',
            'choices' => [
                'Facile' => 1,
                'Moyen' => 2,
                'Difficile' => 3,
                'Expert' => 4,
            ],
        ])
        // Add the score field here
        ->add('score', IntegerType::class, [
            'label' => 'Points pour cette question',
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => 'Veuillez spécifier le nombre de points pour cette question']),
                new Range([
                    'min' => 1,
                    'max' => 10,
                    'notInRangeMessage' => 'Le score doit être entre 1 et 10 points',
                ]),
            ],
            'attr' => [
                'min' => 1,
                'max' => 10,
                'placeholder' => 'Nombre de points'
            ],
            'help' => 'Attribuez entre 1 et 10 points pour cette question selon son importance',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionTechnique::class,
        ]);
    }
}
