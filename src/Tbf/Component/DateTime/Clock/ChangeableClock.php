<?php
namespace Tbf\Component\DateTime\Clock;
use Tbf\Component\DateTime\DateSequence\DateSequenceInterface;
use Tbf\Component\DateTime\DateTimeException;

class ChangeableClock implements ClockInterface{
    protected $type;
    protected $fix_time;
    protected $offset;
    /** @var \Tbf\Component\DateTime\DateSequence\DateSequenceInterface  */
    protected $sequence;
    function now(){
        switch($this->type){
            case 'fix':
                return $this->nowOnFixTime();
            case 'offset':
                return $this->nowOnOffsetTime();
            case 'sequence':
                return $this->nowOnSequence();
            default:
                return new \DateTime();
        }
    }

    /**
     * 一直返回一个固定的时间
     * @param \DateTime $time
     */
    function setFixTime(\DateTime $time){
        $this->type = 'fix';
        $this->fix_time = $time;
    }

    /**
     * 返回的时间和当前的时间有固定偏移
     * @param \DateInterval $offset
     */
    function setOffsetTime(\DateInterval $offset){
        $this->type = 'offset';
        $this->offset = $offset;
    }

    /**
     * 按照某个序列返回时间
     */
    function setSequence(DateSequenceInterface $ds){
        $this->type = 'sequence';
        $this->sequence = $ds;
    }
    protected function nowOnFixTime(){
        return $this->fix_time;
    }
    protected function nowOnOffsetTime(){
        $now = new \DateTime();
        $now->add($this->offset);
        return $now;
    }
    protected function nowOnSequence(){
        if (!$this->sequence->valid()){
            throw new DateTimeException('invalid value of ChangableClock');
        }
        $ret = $this->sequence->current();
        $this->sequence->next();
        return $ret;
    }
}