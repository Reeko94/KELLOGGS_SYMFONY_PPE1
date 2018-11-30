<?php

namespace App\Form;

use App\Entity\InformationsLivraisons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationsLivraisonsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('complement')
            ->add('rue')
            ->add('codepostal')
            ->add('ville')
            ->add('pays')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InformationsLivraisons::class,
        ]);
    }
}
