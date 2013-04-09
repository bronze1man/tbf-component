<?php
namespace Tbf\Component\Io\Tests\Buffer;
use Tbf\Component\Io\Buffer\StringBuffer;
use Tbf\Component\Io\Tests\TestCase;

class StringBufferTest extends TestCase{
    function testStringBuffer(){
        $string = new StringBuffer();
        $string->write(str_repeat('1234',1000));
        
        $data = $string->read();
        $this->assertEquals(str_repeat('1234',1000),$data);
        
        $data = $string->read();
        $this->assertEquals(null,$data);
        
        $data = $string->read();
        $this->assertEquals(null,$data);
    }
}