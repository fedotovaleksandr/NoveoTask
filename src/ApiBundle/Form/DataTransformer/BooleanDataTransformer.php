<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 3:21
 */

namespace ApiBundle\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;

class BooleanDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return null;
    }

    public function reverseTransform($value)
    {
        if ($value === "false" || $value === "0" || $value === "" || $value === 0) {
            return false;
        }

        return true;
    }
}