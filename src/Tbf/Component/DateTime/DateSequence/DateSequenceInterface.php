<?php
namespace Tbf\Component\DateTime\DateSequence;
interface DateSequenceInterface extends \Iterator{
    /**
     * @param \DateTime
     * @return DateSequenceInterface
     */
    function setTimeAfter(\DateTime $time);
}