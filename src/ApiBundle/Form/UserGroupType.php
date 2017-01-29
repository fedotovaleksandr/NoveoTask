<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\User;
use ApiBundle\Entity\UserGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                        'maxMessage'=>'Name max lenght only 255 symbols'
                    ])
                ]])
            ->add('users', EntityType::class, [
                'class'=>User::class,
                'invalid_message'=>"values must be integer or Some of users not found"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>UserGroup::class,
            'csrf_protection'=>false
        ]);
    }

    public function getName()
    {
        return 'api_bundle_user_group_type';
    }
}
