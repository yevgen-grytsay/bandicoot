<?php
/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 05.10.15
 */

namespace YevgenGrytsay\Bandicoot\Context;


interface ContextResolverInterface
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getContext($name);
}