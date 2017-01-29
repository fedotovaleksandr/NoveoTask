<?php

namespace ApiBundle\Form;


use ApiBundle\Form\DataTransformer\BooleanDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BooleanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new BooleanDataTransformer());
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getName()
    {
        return 'api_bundle_boolean_type';
    }
}
