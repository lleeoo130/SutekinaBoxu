<?php

namespace App\Form;

use App\Entity\Box;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Box\'s name',
                'attr'  => [ 'placeholder' => 'The name of our box']
            ])
            ->add('budget', IntegerType::class, [
                'label' => 'Box\'s budget',
                'attr'  => ['placeholder' => 'Money']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Box'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Box::class,
        ]);
    }
}
