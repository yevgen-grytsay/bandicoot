<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class UnwindArrayDelegatingContext extends UnwindArrayContext
{
    /**
     * @var ContextInterface
     */
    protected $delegate;

    /**
     * @param ContextInterface $delegate
     */
    public function __construct(ContextInterface $delegate)
    {
        parent::__construct(null);
        $this->delegate = $delegate;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function _iterator($value)
    {
        return $this->delegate->run($value);
    }
}