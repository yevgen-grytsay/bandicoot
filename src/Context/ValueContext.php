<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\PropertyAccess;

/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 02.10.15
 */
class ValueContext implements Context
{
    /**
     * @var string
     */
    protected $accessor;
    /**
     * @var PropertyAccess
     */
    private $propertyAccess;

    /**
     * ValueContext constructor.
     *
     * @param string                                                          $accessor
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess $propertyAccess
     */
    public function __construct($accessor, PropertyAccess $propertyAccess)
    {
        $this->accessor = $accessor;
        $this->propertyAccess = $propertyAccess;
    }

    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($value, \SplStack $stack)
    {
        return $this->propertyAccess->getValue($value, $this->accessor);
    }
}