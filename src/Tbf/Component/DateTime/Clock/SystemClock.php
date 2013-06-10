<?php
namespace Tbf\Component\DateTime\Clock;
class SystemClock implements ClockInterface{
    function now(){
        return new \DateTime();
    }
}