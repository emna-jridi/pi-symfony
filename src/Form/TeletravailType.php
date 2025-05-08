<?php
namespace App\Form;

use App\Entity\Teletravail;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TeletravailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('DateDemandeTT', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => 'readonly', // Rendre le champ readonly
                ],
            ])
            ->add('DateDebutTT', null, [
                'widget' => 'single_text',
            ])
            ->add('DateFinTT', null, [
                'widget' => 'single_text',
            ])

            ->add('RaisonTT');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teletravail::class,
        ]);
    }
}
