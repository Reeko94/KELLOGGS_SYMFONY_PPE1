<?php

namespace App\Form;

use App\Entity\Fabricants;
use App\Entity\TypeNutrition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FabricantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'nom de la marque'
            ])
            ->add('logo',FileType::class, [
                'label' => 'Logo de la marque',
                'mapped' => false,
            ])
            ->add('TypeNutrition', EntityType::class, [
                'class' => TypeNutrition::class,
                'choice_label' => 'libelle',
                'label' => 'Type de nutrition de la marque'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fabricants::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
