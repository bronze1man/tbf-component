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
    function next($steps = 1){
        $this->position+=1;
    }

    /**
     * 将序列向前拨,使其在某个时间之后,
     * 如果在中途,序列变为无效,则保持无效状态,(拨不到那个时间)
     * @param \DateTime $time
     * @return DateSequenceInterface
     */
    function setTimeAfter(\DateTime $time){
        while(true){
            if (!$this->valid()){
                return $this;
            }
            $this_interval = $time->diff($this->current());
            //输入时间在当前时间之后
            if ($this_interval->invert===0){
                return $this;
            }
            $this->next();
        }
        return $this;
    }
}