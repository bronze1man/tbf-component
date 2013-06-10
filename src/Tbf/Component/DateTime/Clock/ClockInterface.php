<?php
namespace Tbf\Component\DateTime\Clock;

interface ClockInterface {
    /**
     * @return \DateTime
     */
    function now();
}