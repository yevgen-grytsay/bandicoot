<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date: 13.04.16
 */

namespace YevgenGrytsay\Bandicoot\Context;


use YevgenGrytsay\Bandicoot\Context;

class PostProcessDecorator implements Context
{
    /**
     * @var Context
     */
    private $innerContext;
    /**
     * @var
     */
    private $fnc;

    /**
     * ContextDecorator constructor.
     * @param Context $innerContext
     * @param callable $fnc
     * @throws \InvalidArgumentException
     */
    public function __construct(Context $innerContext, $fnc)
    {
        if (!is_callable($fnc)) {
            throw new \InvalidArgumentException(
                sprintf('Argument "fnc" must be of type "callable". "%s" given.', gettype($fnc))
            );
        }
        $this->innerContext = $innerContext;
        $this->fnc = $fnc;
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
        $result = $this->innerContext->run($value, $stack);
        
        return call_user_func($this->fnc, $result);
    }
}