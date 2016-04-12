<?php
/**
 * @author: yevgen
 */

namespace YevgenGrytsay\Bandicoot\Tests\Context;

use YevgenGrytsay\Bandicoot\Context\ValueSelfContext;

class ValueSelfContextTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldReturnValueUnmodified()
    {
        $ctx = new ValueSelfContext();

        $result = $ctx->run('sample value', new \SplStack());

        $this->assertEquals('sample value', $result);
    }
}
