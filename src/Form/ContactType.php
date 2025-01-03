<?php

namespace App\Form;

use App\DTO\ContactMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'empty_data' => ''
            ])
            ->add('email',EmailType::class,[
                'empty_data'=> ''
            ])
            ->add('message',TextareaType::class,[
                'empty_data'=>  ''
            ])
            ->add('save',SubmitType::class,
            ['label'=>'Emvoyer'])
            ->add('service', ChoiceType::class,[
                'choices'=>[
                    'Conta'=>'conta@demo.fr',
                    'Commerce'=>'marketing@demo.fr',
                    'Support'=>'suport@demo.fr',
                ],
            ])
        ;
     }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMail::class,
            
        ]);
    }
}
