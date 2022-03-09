<?php

namespace App\Form;

use App\Entity\inscriptionT;
use App\Entity\Tournoi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class InscriptionTType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_name')
            ->add('email')
            ->add('etat')
            ->add('Rank',ChoiceType::class,[
                'choices'  => [
            'bronze' => 'bronze',
            'silver' => 'silver',
            'gold' => 'gold',
            'platinum' => 'platinum',
            'diamond' => 'diamond',
            'Master' => 'Master',
            'grand' => 'grand' ]
            ])
            ->add('tournoi')
->add("add",SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => inscriptionT::class,
        ]);
    }
}
