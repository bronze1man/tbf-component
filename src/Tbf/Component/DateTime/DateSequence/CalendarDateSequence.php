<?php
namespace Tbf\Component\DateTime\DateSequence;
/**
 * 由某些特定的日期生成的时间序列
 * FIXME 完成它!!! 目前对这个高端货,没有需求,使用IntervalDateSequence即可
 * 1.从某个时间开始的每天06:00
 * 2.从某个时间开始的每月1号06:00
 * 输入的时间设置,每一项要么是EVERY,要么是一个确定的数据
 * time_config struct{
 *   second
 *   minute
 *   hour
 *   day
 *   month
 *   year
 * }
 * 3.第一个时间为不包括起始时间在内的一个有效时间
 */
class CalendarDateSequence extends AbstractDateSequence{
    const EVERY = -1; //每一个可能的时间
    protected $start_time;
    protected $time_config;
    function __construct($time_config,$start_time=null){
        if ($start_time===null){
            $start_time = new \DateTime('now');
        }
        $this->start_time = $start_time;
        $this->time_config = $time_config;
        $this->rewind();
    }
    function current(){

    }
    function next(){

    }
    function valid(){

    }
    function rewind(){
        parent::rewind();
    }
    //Y-m-d H:i:s
    static protected $table = array(
        'second'=>'s',
        'minute'=>'i',
        'hour'=>'H',
        'day'=>'d',
        'month'=>'m',
        'year'=>'Y'
    );
    /**
     * 获取在某个时间之后的一个有效时间,
     * 不包括这个输入时间,
     * 不修改输入的datetime对象
     * 返回一个新的DateTime实例
     */
    protected function getOneAfterTime(\DateTime $time){
        $time_array = array();
        foreach(self::$table as $name=>$format){
            $time_array[$name] = $time->format($format);
        }
    }
}