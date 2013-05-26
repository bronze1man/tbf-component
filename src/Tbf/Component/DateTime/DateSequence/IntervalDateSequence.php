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
    function next($steps=1){
        parent::next($steps);
        if ($steps===1){
            $this->next_time->add($this->interval_time);
            return;
        }
        $this->next_time->add($this->mulitDateInterval($this->interval_time,$steps));
        return;
    }
    function rewind(){
        parent::rewind();
        $start_time = clone $this->start_time;
        $this->next_time = $start_time->add($this->interval_time);
    }
    function setTimeAfter(\DateTime $time){
        for($i=0;$i<100;$i++){
            if ($time<=$this->current()){
                return $this;
            }
            $this->next();
        }
        //输入时间在当前时间之后
        if ($time<=$this->current()){
            return $this;
        }
        $this->fastSetTimeAfter($time);
        return $this;
    }
    //在对数级时间内找到在某个时间之后
    protected function fastSetTimeAfter(\DateTime $time){
        $steps = 1;
        while(true){
            if ($time<= $this->addDateInterval(
                    $this->next_time,
                    $this->mulitDateInterval($this->interval_time,$steps))
                ){
                if ($steps!=1){
                    $steps = floor($steps / 2);
                    continue;
                }else{
                    $this->setTimeAfter($time);
                    return;
                }
            }else{
                $this->next($steps);
                $steps = $steps * 2;
                continue;
            }
        }
    }
    protected function mulitDateInterval(\DateInterval $input_interval,$steps){
        $interval_time = unserialize(serialize($input_interval)); // there is some bug of clone $input_interval ...
        foreach(array('y','m','d','h','i','s','days') as $key){
            if (!empty($interval_time->{$key})){
                $interval_time->{$key} *= $steps;
            }
        }
        return $interval_time;
    }
    protected function addDateInterval(\DateTime $time,\DateInterval $interval){
        $output_time = clone $time;
        $output_time->add($interval);
        return $output_time;
    }
}