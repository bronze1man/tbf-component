<?php
namespace Tbf\Component\DateTime\DateSequence;
/**
 * 帮助子类处理好position问题...
 */
abstract class AbstractDateSequence implements DateSequenceInterface{
    protected $position;
    function key(){
        return $this->position;
    }
    function rewind(){
        $this->position = 0;
    }
    function next(){
        $this->position++;
    }

}