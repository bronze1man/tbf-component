<?php
namespace Tbf\Component\DateTime\DateSequence;
interface DateSequenceInterface{
    /**
     * @param \DateTime
     * @return DateSequenceInterface
     */
    function setTimeAfter(\DateTime $time);
    /**
     * Return the current element
     * @return mixed Can return any type.
     */
    public function current();

    /**
     * Move forward to next element
     * @return void Any returned value is ignored.
     */
    public function next();

    /**
     * Return the key of the current element
     * @return mixed scalar on success, or null on failure.
     */
    public function key();

    /**
     * Checks if current position is valid
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid();

    /**
     * Rewind the Iterator to the first element
     * @return void Any returned value is ignored.
     */
    public function rewind();
}