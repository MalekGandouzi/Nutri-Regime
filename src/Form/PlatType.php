<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\Regime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomPlat', TextType::class, [
                'label' => 'Nom du plat',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : Salade César'
                ],
            ])
            ->add('cout', MoneyType::class, [
                'label' => 'Coût',
                'currency' => 'TND',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('nbrCalories', IntegerType::class, [
                'label' => 'Nombre de calories',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex : 450',
                ],
            ])
            ->add('ingredients', TextareaType::class, [
                'label' => 'Ingrédients',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Ex : Poulet, salade, sauce César...'
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du plat (fichier)',
                'mapped' => false,
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
            ->add('regime', EntityType::class, [
                'class' => Regime::class,
                'choice_label' => 'nomRegime',
                'label' => 'Régime associé',
                'placeholder' => 'Choisir un régime',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
