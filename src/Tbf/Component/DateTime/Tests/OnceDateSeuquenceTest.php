<?php
namespace Tbf\Component\DateTime\Tests;

use Tbf\Component\DateTime\DateSequence\OnceDateSequence;

class OnceDateSeuquenceTest extends DateTimeTestCase{
    function testForEach(){
        $test_time = \DateTime::createFromFormat('Y-m-d H:i:s','2000-01-01 01:01:01');
        $obj = new OnceDateSequence($test_time);
        $count = 0;
        foreach($obj as $time){
            $count +=1;
            $this->assertEquals($test_time,$time);
        }
        $this->assertEquals(1,$count);
    }
    function testCurrent(){
        $test_time = \DateTime::createFromFormat('Y-m-d H:i:s','2000-01-01 01:01:01');
        $obj = new OnceDateSequence($test_time);
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals($test_time,$obj->current());
        $this->assertEquals(0,$obj->key());
        $obj->next();
        $this->assertEquals(false,$obj->valid());
        $obj->next();
        $this->assertEquals(false,$obj->valid());
    }
}