<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 12.10.15
 * Time: 21:36
 */

namespace YevgenGrytsay\Bandicoot\PropertyAccess;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class SymfonyAdapter implements PropertyAccessInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * SymfonyPropertyAccessAdapter constructor.
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @param $objectOrArray
     * @param $propertyPath
     *
     * @return mixed
     */
    public function getValue($objectOrArray, $propertyPath)
    {
        return $this->accessor->getValue($objectOrArray, $propertyPath);
    }
}