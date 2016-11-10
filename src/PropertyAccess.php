<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 05.10.15
 */

namespace YevgenGrytsay\Bandicoot;


interface PropertyAccess
{
    /**
     * @param $objectOrArray
     * @param $propertyPath
     *
     * @return mixed
     * @throws \Exception
     */
    public function getValue($objectOrArray, $propertyPath);
}