<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 21/11/2018
 * Time: 14:03
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'     => 'Email',
                'attr'      => [ 'placeholder' => 'Email.']
            ])
            ->add('password', PasswordType::class, [
                'label'     => 'Password',
                'attr'      => [ 'placeholder' => 'Password.' ]
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Register'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => null
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_login';
    }

}