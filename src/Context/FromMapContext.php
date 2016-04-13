<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 11.04.16
 */

namespace YevgenGrytsay\Bandicoot\Context;


use YevgenGrytsay\Bandicoot\PropertyAccess\ConstantPropertyAccess;

class FromMapContext implements Context
{
    /**
     * @var ConstantPropertyAccess|array
     */
    private $source;
    /**
     * @var ConstantPropertyAccess
     */
    private $key;
    /**
     * @var ConstantPropertyAccess
     */
    private $value;

    /**
     * FromMapContext constructor.
     * @param array|\ArrayAccess $source
     * @param ConstantPropertyAccess $keyName
     * @param ConstantPropertyAccess|null $valueName
     * @throws \InvalidArgumentException
     */
    public function __construct($source, ConstantPropertyAccess $keyName, ConstantPropertyAccess $valueName = null)
    {
        if (!is_array($source) && !$source instanceof \ArrayAccess) {
            throw new \InvalidArgumentException('Source must be an array or an instance of \ArrayAccess');
        }
        $this->source = $source;
        $this->key = $keyName;
        $this->value = $valueName;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        $key = $this->key->getValue($value);
        if ($this->value) {
            return $this->value->getValue($this->source[$key]);
        } else {
            return $this->source[$key];
        }
    }
}