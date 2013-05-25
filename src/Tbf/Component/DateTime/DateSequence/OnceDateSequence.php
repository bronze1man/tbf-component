<?php
namespace Tbf\Component\DateTime\DateSequence;


/**
 * 一次时间序列
 */
class OnceDateSequence extends AbstractDateSequence{
    protected $time;
    function __construct(\DateTime $time){
        $this->time = $time;
        $this->rewind();
    }
    function valid(){
        if ($this->position===0){
            return true;
        }else{
            return false;
        }
    }
    function current(){
        return $this->time;
    }
}