<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModifyuserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => true,
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 6)),
                ),
                'first_options'  => ['label'=>' ','attr' => array('placeholder' => 'Passowrd', 'class'=> 'form-control required')],
                'second_options' => ['label'=>' ','attr' => array('placeholder' => 'Confirm Password', 'class'=> 'form-control required')]
            ))
            ->add('image',FileType::class, array(
                    'multiple'    => false,
                    'attr' => array(

                        'accept' => 'image/*',
                    )
                )
            )
            ->add('bio')
            ->add("submit",SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
