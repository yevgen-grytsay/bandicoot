<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\ArrayHelper;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ValueContext implements ContextInterface
{
    /**
     * @var string
     */
    protected $accessor;

    /**
     * ValueContext constructor.
     *
     * @param string $accessor
     */
    public function __construct($accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        return ArrayHelper::getValue($value, $this->accessor);
    }
}