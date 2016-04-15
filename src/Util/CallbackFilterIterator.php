<?php
/**
 * CallbackFilterIterator for PHP 5.3
 * @link http://php.net/manual/en/class.callbackfilteriterator.php
 *
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 15.04.16
 */
namespace YevgenGrytsay\Bandicoot\Util;


class CallbackFilterIterator extends \FilterIterator
{
    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @param \Iterator $iterator
     * @param callable $callback
     * @throws \InvalidArgumentException
     */
    public function __construct(\Iterator $iterator, $callback)
    {
        CallbackFilterIterator::assertCallable($callback);
        $this->callback = $callback;
        parent::__construct($iterator);
    }

    /**
     * {{@inheritdoc}}
     */
    public function accept()
    {
        return call_user_func($this->callback, $this->current(), $this->key(), $this->getInnerIterator());
    }

    /**
     * @param $callback
     * @throws \InvalidArgumentException
     */
    static protected function assertCallable($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(
                sprintf('Argument "callback" must be of type callable. Given type "%s".', gettype($callback)));
        }
    }
}