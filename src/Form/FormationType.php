<?php
namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FormationType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomFormation', TextType::class, [
                'label' => 'Nom de la formation',
                'attr' => ['class' => 'form-control']
            ])
            ->add('themeFormation', ChoiceType::class, [
                'label' => 'Thème',
                'choices' => [
                    'Développement' => 'Développement',
                    'Commercial' => 'Commercial',
                    'Marketing' => 'Marketing',
                    'Design' => 'Design',
                    'Ressources Humaines' => 'Ressources Humaines',
                    'Gestion de projet' => 'Gestion de projet',
                    'Finance' => 'Finance',
                ],
                'placeholder' => 'Sélectionner un thème',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control']
            ])
            ->add('lienFormation', UrlType::class, [
                'label' => 'Lien de la formation',
                'attr' => ['class' => 'form-control']
            ])
            ->add('niveauDifficulte', ChoiceType::class, [
                'label' => 'Niveau de difficulté',
                'choices' => [
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Avancé' => 'Avancé',
                ],
                'placeholder' => 'Choisir un niveau',
                'attr' => ['class' => 'form-control']
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en jours)',
                'attr' => ['class' => 'form-control']
            ])
            // ->add('imageFile', FileType::class, [
            //     'label' => 'Image de la formation (JPG, PNG)',
            //     'mapped' => true, // Maps to the imageFile property
            //     'required' => false, // Optional upload
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '5M',
            //             'mimeTypes' => ['image/jpeg', 'image/png'],
            //             'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG ou PNG).',
            //         ])
            //     ],
            // ])
            ->add('date', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
            'validation_groups' => ['Default'],
        ]);
    }
}