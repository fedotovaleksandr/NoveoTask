<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Constrains;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email',EmailType::class,[
            'constraints'=>[
                new Constrains\Length([
                    'max'=>256,
                    'maxMessage'=>'Mail max lenght only 255 symbols'
                ])
            ]

        ])
            ->add('lastName',TextType::class,[
                'required' => 1,
                'constraints'=>[
                new Constrains\Length([
                    'max'=>256,
                    'maxMessage'=>'Mail max lenght only 255 symbols'
                ])
            ]])
            ->add('firstName',TextType::class,[
                'required' => 1,
                'constraints'=>[
                    new Constrains\Length([
                        'max'=>256,
                        'maxMessage'=>'Mail max lenght only 255 symbols'
                    ])
                ]])
            ->add('firstName',TextType::class,[
                'required' => 1,
                'constraints'=>[
                    new Constrains\Length([
                        'max'=>256,
                        'maxMessage'=>'Mail max lenght only 255 symbols'
                    ])
                ]])
            ->add('state',CheckboxType::class,[
                'constraints'=>[
                    ]
                ])
            ->add('creationDate',DateTimeType::class,[
                'widget' => 'single_text',
                'format' => DateTimeType::HTML5_FORMAT

            ])
            ->add('groups',EntityType::class,[
                'class' => 'ApiBundle:UserGroup'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>User::class
        ]);
    }

    public function getName()
    {
        return 'api_bundle_user_type';
    }
}
