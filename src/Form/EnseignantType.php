<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Niveau;
use App\Entity\Matiere;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('adresse')
            ->add('ville')
            ->add('date_naissance')
            ->add('genre', ChoiceType::class, array(
                'choices'  => array(
                    'M' => 'M',
                    'F' => 'F',
            ),
            ))
            ->add('telephone')
            ->add('password')
            ->add('matiere')
            ->add('niveau')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
        ]);
    }
     
}
