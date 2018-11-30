<?php

namespace App\Form;

use App\Entity\InformationsPaiements;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class InformationsPaiementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('numero',TextType::class,[
                'label'=>'Numero de la carte'
            ])
            ->add('date',DateType::class,[
                'widget' => 'choice',
                'format' => 'yyyy-MM-dd',
                'label'=> "date d'expiration de la carte"
            ])
            ->add('cryptogramme',TextType::class,[
                'label'=> 'Inserer les 3 chiffres au dos de la carte'
            ])
            ->add('nom',TextType::class,[
                'label'=> 'Votre nom'
            ])
            ->add('prenom',TextType::class,[
                'label'=> 'Votre prénom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InformationsPaiements::class,
        ]);
    }
}
