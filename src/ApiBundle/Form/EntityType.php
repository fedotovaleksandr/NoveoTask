<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 3:25
 */

namespace ApiBundle\Form;


use ApiBundle\Form\DataTransformer\EntityDataTransformer;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'field' => 'id',
            'class' => null,
            'multiple' => true,
            'by_reference' =>true,
        ]);

        $resolver->setRequired([
            'class',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EntityDataTransformer(
            $this->em, $options['class'], $options['field'],$options['multiple']
        ));
    }

    public function getName()
    {
        return 'api_bundle_entity_type';
    }

    public function getParent()
    {
        return \Symfony\Bridge\Doctrine\Form\Type\EntityType::class;
    }
}