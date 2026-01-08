<?php

namespace App\Form;

use App\Entity\Regime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomRegime', TextType::class, [
                'label' => 'Nom du régime',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Régime méditerranéen'
                ],
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (jours)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : 30'
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de régime',
                'choices' => [
                    'Perte de poids'       => 'Perte de poids',
                    'Prise de masse'       => 'Prise de masse',
                    'Santé & Bien-être'    => 'Santé & Bien-être',
                ],
                'placeholder' => 'Choisir un type',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (fichier)',
                'mapped' => false, // ne mappe pas directement à l’entité
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpeg, png ou webp)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Regime::class,
        ]);
    }
}
