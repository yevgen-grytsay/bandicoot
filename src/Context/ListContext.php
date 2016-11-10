<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 02.10.15
 */
class ListContext implements Context
{
    /**
     * @var Context
     */
    protected $dataSource;

    /**
     * ListContext constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\Context                 $dataSource
     */
    public function __construct(Context $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     * @throws \Exception
     */
    public function run($value, \SplStack $stack)
    {
        $result = array();
        foreach ($this->dataSource->run($value, $stack) as $key => $item) {
            $result[$key] = $item;
        }

        return $result;
    }
}