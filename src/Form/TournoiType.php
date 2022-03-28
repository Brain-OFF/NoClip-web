<?php

namespace App\Form;

use App\Entity\Tournoi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('Date', DateTimeType::class, ['date_widget'=>'single_text','time_widget'=>'single_text'])

            ->add('cathegorie',ChoiceType::class,[
                'choices'  => [
                    'RPG' => 'RPG',
                    'MMORPG' => 'MMORPG',
                    'MOBA' => 'MOBA',
                    'RTS' =>'RTS',
                        'Battle Royale' => 'Battle Royale',
                    'Beat Them All ' =>'Beat Them All',
                    'survival Horror'=>'survival Horror'
                ],
            ])
            ->add('Discription')
            ->add("add",SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}
