<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Niveau;
use App\Entity\Representant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('ville')
            ->add('adresse')
            ->add('telephone')
            ->add('password')
            ->add('imageFile', FileType::class, array(
                 
                 'required'=>false
            ))

            ->add('date_naissance')
            ->add('genre', ChoiceType::class, array(
                'choices'  => array(
                    'M' => 'M',
                    'F' => 'F',
                    
                ),
            ))
            ->add('niveau', EntityType::class, array(
                'class' => Niveau::class,
                'choice_label' => 'libelle',
                ))
            ->add('representant', EntityType::class, array(
                    'class' => Representant::class,
                    'choice_label' => 'nom',
                    ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
