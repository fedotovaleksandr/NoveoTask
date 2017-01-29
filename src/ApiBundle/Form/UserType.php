<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\User;
use ApiBundle\Entity\UserGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Constrains;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'constraints' => [
                new Constrains\Length([
                    'max' => 256,
                    'maxMessage' => 'Mail max lenght only 255 symbols'
                ])
            ]

        ])
            ->add('lastName', TextType::class, [
                'required' => 1,
                'constraints' => [
                    new Constrains\Length([
                        'max' => 256,
                        'maxMessage' => 'Mail max lenght only 255 symbols'
                    ])
                ]])
            ->add('firstName', TextType::class, [
                'required' => 1,
                'constraints' => [
                    new Constrains\Length([
                        'max' => 256,
                        'maxMessage' => 'Mail max lenght only 255 symbols'
                    ])
                ]])
            ->add('firstName', TextType::class, [
                'required' => 1,
                'constraints' => [
                    new Constrains\Length([
                        'max' => 256,
                        'maxMessage' => 'Mail max lenght only 255 symbols'
                    ])
                ]])
            ->add('state', BooleanType::class, [
                'required' => 0,
            ])
            ->add('creationDate', DateTimeType::class, [
                'invalid_message' => "Format date " . DateTimeType::HTML5_FORMAT,
                'required' => false,
                'widget' => 'single_text',
                'format' => DateTimeType::HTML5_FORMAT,
            ])
            ->add('groups', EntityType::class, [
                'class'=>UserGroup::class,
                'invalid_message'=>"values must be integer or Some of groups not found"
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'api_bundle_user_type';
    }
}
