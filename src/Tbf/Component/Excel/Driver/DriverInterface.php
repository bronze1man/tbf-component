<?php
namespace Tbf\Component\Excel\Driver;
interface DriverInterface{
    /**
     * @return WriterInterface
     */
    function getWriter();
    /**
     * @return ReaderInterface
     */
    function getReader();
}