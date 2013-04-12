<?php
namespace Tbf\Component\Io\Tests\Buffer;
use Tbf\Component\Io\Buffer\ArrayBuffer;
use Tbf\Component\Io\Tests\TestCase;

class ArrayBufferTest extends TestCase{
    function testArrayBuffer(){
        $buffer = new ArrayBuffer();
        $buffer->writeOne(array(
            'a'=>1,
            'b'=>2
        ));
        $buffer->writeOne(array(
            'a'=>2,
            'b'=>3
        ));
        
        $data = $buffer->readOne();
        $this->assertEquals(array(
            'a'=>1,
            'b'=>2
        ),$data);
        
        $data = $buffer->readOne();
        $this->assertEquals(array(
            'a'=>2,
            'b'=>3
        ),$data);
        
        $data = $buffer->readOne();
        $this->assertEquals(null,$data);
        
        $data = $buffer->readOne();
        $this->assertEquals(null,$data);
    }
}