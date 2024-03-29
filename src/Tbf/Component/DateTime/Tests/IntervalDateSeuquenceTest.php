<?php
namespace Tbf\Component\DateTime\Tests;

use Tbf\Component\DateTime\DateSequence\IntervalDateSequence;
use Tbf\Component\DateTime\DateSequence\OnceDateSequence;

class IntervalDateSeuquenceTest extends DateTimeTestCase{
    function testDay(){
        $start_time = \DateTime::createFromFormat('Y-m-d H:i:s','2000-01-01 01:01:01');
        $interval_time = \DateInterval::createFromDateString('+1 day');
        $obj = new IntervalDateSequence($interval_time,$start_time);
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2000-01-02 01:01:01'),$obj->current());
        $this->assertEquals(0,$obj->key());
        $obj->next();
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2000-01-03 01:01:01'),$obj->current());
        $this->assertEquals(1,$obj->key());
        $obj->next();
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2000-01-04 01:01:01'),$obj->current());
        $this->assertEquals(2,$obj->key());
        for($i=0;$i<10;$i++){
            $obj->next();
        }
        $this->assertEquals(true,$obj->valid());
    }
    function testMonth(){
        $start_time = \DateTime::createFromFormat('Y-m-d H:i:s','2000-01-01 01:01:01');
        $interval_time = \DateInterval::createFromDateString('+1 month');
        $obj = new IntervalDateSequence($interval_time,$start_time);
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2000-02-01 01:01:01'),$obj->current());
        $this->assertEquals(0,$obj->key());
        $obj->next();
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2000-03-01 01:01:01'),$obj->current());
        $this->assertEquals(1,$obj->key());
        $obj = unserialize(serialize($obj));
        $obj->next();
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2000-04-01 01:01:01'),$obj->current());
        $this->assertEquals(2,$obj->key());

        $obj->setTimeAfter(\DateTime::createFromFormat('Y-m-d H:i:s','2001-01-01 01:01:01'));
        $this->assertEquals(true,$obj->valid());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2001-01-01 01:01:01'),$obj->current());

        $obj->setTimeAfter(\DateTime::createFromFormat('Y-m-d H:i:s','2002-01-01 01:01:02'));
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2002-02-01 01:01:01'),$obj->current());

        $obj->setTimeAfter(\DateTime::createFromFormat('Y-m-d H:i:s','2003-01-01 01:01:00'));
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2003-01-01 01:01:01'),$obj->current());
    }
    function testfastSetTimeAfter(){
        $start_time = \DateTime::createFromFormat('Y-m-d H:i:s','2000-01-01 01:01:01');
        $interval_time = \DateInterval::createFromDateString('+1 second');
        $obj = new IntervalDateSequence($interval_time,$start_time);
        $obj->setTimeAfter(\DateTime::createFromFormat('Y-m-d H:i:s','2001-01-01 01:01:01'));
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','2001-01-01 01:01:01'),$obj->current());
        $obj->setTimeAfter(\DateTime::createFromFormat('Y-m-d H:i:s','4001-01-01 01:01:01'));
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s','4001-01-01 01:01:01'),$obj->current());
    }
}