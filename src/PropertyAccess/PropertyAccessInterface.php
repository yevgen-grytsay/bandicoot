<?php
/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 05.10.15
 */

namespace YevgenGrytsay\Bandicoot\PropertyAccess;


interface PropertyAccessInterface
{
    /**
     * @param $objectOrArray
     * @param $propertyPath
     *
     * @return mixed
     */
    public function getValue($objectOrArray, $propertyPath);
}