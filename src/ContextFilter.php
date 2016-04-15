<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 15.04.16
 */

namespace YevgenGrytsay\Bandicoot;


interface ContextFilter
{
    public function accept($item);
}