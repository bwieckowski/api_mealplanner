<?php

namespace App\Form;

use App\Entity\PersonDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PersonDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sex', TextType::class)
            ->add('calory', NumberType::class)
            ->add('protein', NumberType::class)
            ->add('carbon', NumberType::class)
            ->add('fat', NumberType::class)
            ->add('weight', NumberType::class)
            ->add('height', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PersonDetails::class,
            'csrf_protection' => false
        ]);
    }
}
