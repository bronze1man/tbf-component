<?php
namespace Tbf\Component\Io\Tests\Buffer;
use Tbf\Component\Io\Buffer\MapBuffer;
use Tbf\Component\Io\Tests\TestCase;

class MapBufferTest extends TestCase{
    function testMapBuffer(){
        $map = new MapBuffer();
        $map->writeOne(array(
            'a'=>1,
            'b'=>2
        ));
        $map->writeOne(array(
            'a'=>2,
            'b'=>3
        ));
        
        $data = $map->readOne();
        $this->assertEquals(array(
            'a'=>1,
            'b'=>2
        ),$data);
        
        $data = $map->readOne();
        $this->assertEquals(array(
            'a'=>2,
            'b'=>3
        ),$data);
        
        $data = $map->readOne();
        $this->assertEquals(null,$data);
        
        $data = $map->readOne();
        $this->assertEquals(null,$data);
    }
}