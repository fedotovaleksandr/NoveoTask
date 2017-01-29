<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 3:20
 */

namespace ApiBundle\Form\DataTransformer;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityDataTransformer implements DataTransformerInterface
{
    private $em;
    private $entityName;
    private $fieldName;
    /**
     * @var bool
     */
    private $multiple;

    public function __construct(EntityManager $em, $entityName, $fieldName,$multiple = false)
    {
        $this->em = $em;
        $this->entityName = $entityName;
        $this->fieldName = $fieldName;
        $this->multiple = $multiple;
    }

    public function transform($value)
    {
        $path = "get".ucwords($this->fieldName);
        if (is_array($value)){
            return array_map(function ($value) use ($path){return ['id'=>$value->$path()];},$value);
        }
        return [];
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }
        if ($value instanceof  ArrayCollection){
            return $value;
        }
        $rep = $this->em->getRepository($this->entityName);
        if (!$this->multiple){
            $data = $rep->findOneBy([$this->fieldName => $value]);
        } else {
            $data = $rep->findBy([$this->fieldName => $value]);
        }
         if (count($value) != count($data)){
             throw new TransformationFailedException('Some entities does not exist!');
         }
        return new ArrayCollection($data);
    }
}