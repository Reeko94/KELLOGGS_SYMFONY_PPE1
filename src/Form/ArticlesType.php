<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Fabricants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('disponibilite')
            ->add('prix')
            ->add('fabricant',EntityType::class,[
                'class' => Fabricants::class,
                'choice_label' => 'libelle',
                'label' => 'Marque de l\'article'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
