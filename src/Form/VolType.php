<?php

namespace App\Form;

use App\Entity\Aeroport;
use App\Entity\Avion;
use App\Entity\Compagnie;
use App\Entity\Vol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('heurDepart', null, [
                'widget' => 'single_text',
            ])
            ->add('heurArrive', null, [
                'widget' => 'single_text',
            ])
            ->add('compagnie', EntityType::class, [
                'class' => Compagnie::class,
                'choice_label' => 'nom',
            ])
            ->add('avion', EntityType::class, [
                'class' => Avion::class,
                'choice_label' => 'id',
            ])
            ->add('aeroport', EntityType::class, [
                'class' => Aeroport::class,
                'choice_label' => 'nom',
            ])
            ->add('depart')
            ->add('destination')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vol::class,
        ]);
    }
}
