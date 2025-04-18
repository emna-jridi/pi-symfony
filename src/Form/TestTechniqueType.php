<?php
namespace App\Form;

use App\Entity\QuestionTechnique;
use App\Entity\TestTechnique;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestTechniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du test',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 4]
            ])
            ->add('dureeMinutes', IntegerType::class, [
                'label' => 'Durée (en minutes)',
                'attr' => ['class' => 'form-control', 'min' => 5]
            ])
            ->add('questions', EntityType::class, [
                'class' => QuestionTechnique::class,
                'choice_label' => function (QuestionTechnique $question) {
                    return sprintf('[%s] %s', $this->getCategorieLabel($question->getCategorie()), substr($question->getQuestion(), 0, 50) . '...');
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('q')
                        ->orderBy('q.categorie', 'ASC')
                        ->addOrderBy('q.difficulte', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'question-selector'],
            ])
        ;
    }

    private function getCategorieLabel(int $categorie): string
    {
        $categories = [
            1 => 'Programmation',
            2 => 'Base de données',
            3 => 'Architecture',
            4 => 'Symfony',
            5 => 'PHP',
            6 => 'HTML/CSS',
            7 => 'JavaScript',
        ];
        
        return $categories[$categorie] ?? 'Autre';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TestTechnique::class,
        ]);
    }
}