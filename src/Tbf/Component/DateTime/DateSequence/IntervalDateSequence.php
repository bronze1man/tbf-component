<?php
namespace Tbf\Component\DateTime\DateSequence;

/**
 * 间隔时间序列
 * 1.从某时刻开始,间隔一段时间后发生,再间隔一段时间后发生
 * 2.这个开始时刻不会发生
 * 3.时间序列不会停止
 */
class IntervalDateSequence extends AbstractDateSequence{
    protected $interval_time;
    protected $start_time;
    protected $next_time;
    function __construct(\DateInterval $interval_time,\DateTime $start_time=null){
        $this->interval_time = $interval_time;
        if ($start_time===null){
            $start_time = new \DateTime('now');
        }
        $this->start_time = $start_time;
        $this->rewind();
    }
    function current(){
        return $this->next_time;
    }
    function valid(){
        return true;
    }
    function next(){
        parent::next();
        $this->next_time->add($this->interval_time);
    }
    function rewind(){
        parent::rewind();
        $start_time = clone $this->start_time;
        $this->next_time = $start_time->add($this->interval_time);
    }
}