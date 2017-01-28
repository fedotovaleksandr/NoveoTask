<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\UserGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Constrains;

class UserGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'required' => 1,
                'constraints'=>[
                    new Constrains\Length([
                        'max'=>256,
                        'maxMessage'=>'Mail max lenght only 255 symbols'
                    ])
                ]])
            ->add('users',EntityType::class,[
                'class' => 'ApiBundle:User'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>UserGroup::class
        ]);
    }

    public function getName()
    {
        return 'api_bundle_user_group_type';
    }
}