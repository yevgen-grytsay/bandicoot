<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 12.04.16
 * Time: 20:43
 */

namespace YevgenGrytsay\Bandicoot;


class StackSearch
{
    /**
     * @var \SplStack
     */
    private $stack;

    /**
     * StackSearch constructor.
     * @param \SplStack $stack
     */
    public function __construct(\SplStack $stack)
    {
        $this->stack = $stack;
    }

    public function closest($name)
    {
        $result = null;
        $prev = null;
        foreach ($this->stack as $item) {
            if ($item === $name) {
                $result = $prev;
                break;
            }
            $prev = $item;
        }

        return $result;
    }
}